<?php

namespace App\Http\Controllers\Admin_api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Excel;
use Illuminate\Http\Request;
use App\Model\Instructor;
use App\Model\Event;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\EventController;
use App\Http\Resources\InstructorResource;
use App\Http\Resources\EventResourceRoyalty;
use Auth;
use App\Exports\RoyaltiesExport;
use App\Exports\RoyaltiesExportInstructorsList;
use Storage;
use DateTime;

class RoyaltiesController extends Controller
{

    /**
     * Get countdown
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Instructor::class, Auth::user());

        try {

            $instructors = Instructor::tableSort($request);

            $instructors = $this->filters($instructors, $request);

            $instructors = $instructors->has('elearningEventsForRoyalties')->whereStatus(1)->paginate($request->per_page ?? 50);
            return InstructorResource::collection($instructors);


        } catch (Exception $e) {
            Log::error("Failed to get tickers. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($model, $request)
    {
        $model->lookForOriginal($request->filter);



        if ($request->events !== null) {

            $ids = [];
            foreach($request->events as $event){

                if(gettype($event) != 'array'){
                    $event = json_decode($event, true);
                }


                $ids[] = $event['id'];
            }

            $model->whereIn("event_id", $ids);
        }

        return $model;
    }

    public function calculateIncomeByPercentHours($eventData)
    {
        $finalIncomeForInstructor = 0;
        if(!isset($eventData['instructor_percent']) || $eventData['instructor_percent'] < 0){
            return $finalIncomeForInstructor;
        }

        $percentOfSplitting = 0.15;

        // Total Income Event
        $totalEventIncome = 0;
        $totalEventIncome = $eventData['total_income'];


        // Income for spliting
        $incomeForSpliting = $totalEventIncome * $percentOfSplitting;
        $finalIncomeForInstructor = $incomeForSpliting * ($eventData['instructor_percent'] / 100);

        return $finalIncomeForInstructor;
    }

    public function exportInstructorList()
    {
        $year = new DateTime();

        $request = new \Illuminate\Http\Request();

        $request->replace([
            'transaction_from' => $year->setDate($year->format('Y'), 1, 1)->format('Y-m-d'),
            'transaction_to' => date("Y-m-d")
        ]);



        try {

            $instructors = Instructor::has('elearningEventsForRoyalties')->whereStatus(1)->get();

            $filename = 'Royalties_Export.xlsx';

            $path = Storage::disk('royalties')->path($filename);

            Excel::store(new RoyaltiesExportInstructorsList($instructors), $filename, 'royalties');
            return Excel::download(new RoyaltiesExportInstructorsList($instructors), $filename);



        }catch (Exception $e) {
            Log::error("Failed to get countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function export(int $id, Request $request)
    {

        try {

            //$events = Event::tableSort($request);

            if($id != 0){
                $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title','elearningEventsForRoyalties.lessons','elearningEventsForRoyalties.delivery')->whereStatus(1)->where('id',$id)->get();

            }else{
                $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title','elearningEventsForRoyalties.lessons','elearningEventsForRoyalties.delivery')->whereStatus(1)->get();

            }

            foreach($instructor as $key => $instr){

                $instructor[$key]['events'] = $instr->elearningEventsForRoyalties()->tableSort($request);
                $instructor[$key]['events'] = $this->filters($instructor[$key]['events'], $request);
                $instructor[$key]['events'] = $instructor[$key]['events']->get();


                $data = $this->getInstructorEventData($instr, $instructor[$key]['events'], $request);




                foreach($instructor[$key]['events'] as $key2 => $event){
                    //dd($this->calculateIncomeByPercentHours($data['events'][$event->id]));
                    $instructor[$key]['events'][$key2]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    $instructor[$key]['events'][$key2]['total_event_minutes'] = $data['events'][$event->id]['total_event_minutes'];
                    $instructor[$key]['events'][$key2]['total_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'];
                    $instructor[$key]['events'][$key2]['percent'] = $data['events'][$event->id]['instructor_percent'];
                }

            }

            if($id == 0){
                $filename = 'Royalties_Export.xlsx';
            }else{
                $filename = $instructor[0]->title.'_'.$instructor[0]->subtitle.'_Royalties_Export.xlsx';
            }


            $path = Storage::disk('royalties')->path($filename);

            Excel::store(new RoyaltiesExport($instructor), $filename, 'royalties');
            return Excel::download(new RoyaltiesExport($instructor), $filename);



        }catch (Exception $e) {
            Log::error("Failed to get countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Get page
     *
     * @return PageResource
     */
    public function show(int $id, Request $request)
    {
        try {
            //$events = Event::tableSort($request);

            $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title','elearningEventsForRoyalties.lessons','elearningEventsForRoyalties.delivery')->whereStatus(1)->where('id',$id)->get();

            $preResponseData = [];
            $i = 0;

            foreach($instructor as $key => $instr){

                $instructor[$key]['events'] = $instr->elearningEventsForRoyalties()->tableSort($request);

                $instructor[$key]['events'] = $this->filters($instructor[$key]['events'], $request);
                $instructor[$key]['events'] = $instructor[$key]['events']->get();


                $data = $this->getInstructorEventData($instr, $instructor[$key]['events'], $request);



                foreach($instructor[$key]['events'] as $key2 => $event){
                    //dd($this->calculateIncomeByPercentHours($data['events'][$event->id]));
                    //$instructor[$key]['events'][$key2]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);

                    $preResponseData[$i]['instructor'] = $instr['title'].' '.$instr['subtitle'];
                    $preResponseData[$i]['title'] = $event['title'];
                    $preResponseData[$i]['id'] = $instr['id'];
                    $preResponseData[$i]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    $preResponseData[$i]['total_event_minutes'] = $data['events'][$event->id]['total_event_minutes'];
                    $preResponseData[$i]['total_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'];
                    $preResponseData[$i]['percent'] = $data['events'][$event->id]['instructor_percent'];

                    $i++;
                }

            }


            if($request->sort != null){

                if(explode('|',$request->sort)[1] == 'desc'){
                    return EventResourceRoyalty::collection(collect($preResponseData)->sortByDesc(explode('|',$request->sort)[0]));
                }
                else{
                    return EventResourceRoyalty::collection(collect($preResponseData)->sortBy(explode('|',$request->sort)[0]));
                }


            }

            return EventResourceRoyalty::collection(collect($preResponseData));



        } catch (Exception $e) {
            Log::error("Failed to get countdown. " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function getEventData($events, $request)
    {
        $data = [];

        $incomes = [];

        $filterForTransaction = null;

        if($request->transaction_from != null){

            $filterForTransaction['created_at']['from'] = $request->transaction_from;
        }
        if($request->transaction_to != null){
            $filterForTransaction['created_at']['to'] = $request->transaction_to;
        }

        $filterForTransaction['calculateSubscription'] = false;

        foreach($events as $key => $event){

            $data['events'][$event->id] = $event->getTotalHours() * 60;
            $data['incomes'][$event->id] = (new EventController)->event_statistics($event->id, true, $filterForTransaction)->getOriginalContent()['data']['income'];
        }

        return $data;
    }

    public function getInstructorEventData($instructor, $events,$request)
    {
        $data = [];
        if(isset($events)){
            $responseDataEvent = $this->getEventData($events, $request);



            if(count($events) > 0){
                $data['events'] = [];

                $total = 0;
                foreach($events as $key => $event){

                    $data['events'][$event->id] = [];
                    //$data['events'][$event->id]['lessons'] = [];

                    $sum1 = 0;
                    $data['events'][$event->id]['total_income'] = $responseDataEvent['incomes'][$event->id]['total'];
                    $data['events'][$event->id]['total_event_minutes'] = $responseDataEvent['events'][$event->id];
                    $data['events'][$event->id]['total_lessons_instructor_minutes'] = 0;


                    foreach($event['lessons'] as $lesson){
                        $sum = 0;
                        if($lesson['vimeo_duration'] != "" && $lesson['vimeo_duration'] != 0 ){

                            if($lesson->pivot->instructor_id == $instructor->id){
                                 $sum = $sum + getSumLessonSecond($lesson);
                            }
                            $data['events'][$event->id]['total_lessons_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'] + $sum;
                        }




                    }


                    $data['events'][$event->id]['instructor_percent'] = $data['events'][$event->id]['total_lessons_instructor_minutes'] / $data['events'][$event->id]['total_event_minutes'] * 100;

                }

            }
        }



        return $data;
    }

    public function widgetsByInstructor(int $id, Request $request){
        return [
            [
                "TOTAL ROYALTIES",
                $data = [
                    'sum' => $this->getCacheIncome($request)
                ],
                'Total royalties for all instructors'
            ],


        ];
    }

    public function widgets(Request $request)
    {
        return [
            [
                "TOTAL ROYALTIES",
                $data = [
                    'sum' => $this->getCacheIncome($request)
                ],
                'Total royalties for all instructors'
            ],


        ];
    }

    public function getCacheIncome($request){
        try {
                    $income = Instructor::has('elearningEventsForRoyalties')->whereStatus(1)->sum('cache_income');
                    //$pages = $this->filters($request, $pages);
                    return $income;
                } catch (Exception $e) {
                    Log::warning("(pages widget) Failed to get pages count. " . $e->getMessage());
                    return "0";
                }
    }

    // public function PagesCount($request)
    // {
    //     try {
    //         $pages = Page::withoutGlobalScopes();
    //         //$pages = $this->filters($request, $pages);
    //         return $pages->count();
    //     } catch (Exception $e) {
    //         Log::warning("(pages widget) Failed to get pages count. " . $e->getMessage());
    //         return "0";
    //     }
    // }

    // public function publishedPagesCount($request)
    // {
    //     try {
    //         $pages = Page::withoutGlobalScopes()->wherePublished(true);
    //         //$pages = $this->filters($request, $pages);
    //         return $pages->count();
    //     } catch (Exception $e) {
    //         Log::warning("(pages widget) Failed to get published pages count. " . $e->getMessage());
    //         return "0";
    //     }
    // }

}

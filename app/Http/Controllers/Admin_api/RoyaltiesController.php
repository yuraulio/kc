<?php

namespace App\Http\Controllers\Admin_api;

use App\Exports\RoyaltiesExport;
use App\Exports\RoyaltiesExportInstructorsList;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransactionController;
use App\Http\Resources\EventResourceRoyalty;
use App\Http\Resources\InstructorResource;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;

class RoyaltiesController extends Controller
{
    public function parseDataFormCache($request)
    {
        $status = false;

        $request_transasction_from = $request->transaction_from != null ? Carbon::createFromFormat('Y-m-d', explode('T', $request->transaction_from)[0])->addDays()->format('Y-m-d') : null;
        $request_transasction_to = $request->transaction_to != null ? Carbon::createFromFormat('Y-m-d', explode('T', $request->transaction_to)[0])->format('Y-m-d') : null;

        $firstDayOfYear = new DateTime();
        $firstDayOfYear->setDate($firstDayOfYear->format('Y'), 1, 1);
        $firstDayOfYear = $firstDayOfYear->format('Y-m-d');
        $currentDateOfYear = date('Y-m-d');

        if (($currentDateOfYear == $request_transasction_to || $request_transasction_to == null) && $request_transasction_from == $firstDayOfYear) {
            $status = true;
        }

        return $status;
    }

    /**
     * Get countdown.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Instructor::class, Auth::user());

        if ($request->sort != null && str_contains($request->sort, 'income')) {
            $request->sort = str_replace('income', 'cache_income', $request->sort);
        }

        try {
            $instructors = Instructor::tableSort($request);

            $instructors = $this->filters($instructors, $request);

            if ($this->parseDataFormCache($request)) {
                $instructors = $instructors->has('elearningEventsForRoyalties')->whereStatus(1)->get();

                return InstructorResource::collection($instructors);
            } else {
                $instructor = $instructors->has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title', 'elearningEventsForRoyalties.lessons', 'elearningEventsForRoyalties.delivery')->whereStatus(1)->get();

                foreach ($instructor as $key => $instr) {
                    $instructor[$key]['events'] = $instr->elearningEventsForRoyalties();

                    $instructor[$key]['events'] = $instructor[$key]['events']->get();

                    $data = $this->getInstructorEventData($instr, $instructor[$key]['events'], $request);

                    $instructor[$key]['cache_income'] = 0.0;

                    foreach ($instructor[$key]['events'] as $key2 => $event) {
                        $instructor[$key]['cache_income'] = $instructor[$key]['cache_income'] + $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    }
                }

                if ($request->sort != null && str_contains($request->sort, 'income')) {
                    $request->sort = str_replace('income', 'cache_income', $request->sort);
                }

                if ($request->sort != null && str_contains($request->sort, 'income')) {
                    $column = explode('|', $request->sort);

                    if ($column[0] == 'income') {
                        $column = 'cache_income';
                    }

                    if (explode('|', $request->sort)[1] == 'desc') {
                        return InstructorResource::collection(collect($instructor)->sortByDesc($column));
                    } else {
                        return InstructorResource::collection(collect($instructor)->sortBy($column));
                    }
                }

                return InstructorResource::collection($instructor);
            }
        } catch (Exception $e) {
            Log::error('Failed to get tickers. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function filters($model, $request)
    {
        $model->lookForOriginal($request->filter);

        if ($request->events !== null) {
            $ids = [];
            foreach ($request->events as $event) {
                if (gettype($event) != 'array') {
                    $event = json_decode($event, true);
                }

                $ids[] = $event['id'];
            }

            $model->whereIn('event_id', $ids);
        }

        return $model;
    }

    public function calculateIncomeByPercentHours($eventData)
    {
        $finalIncomeForInstructor = 0;
        if (!isset($eventData['instructor_percent']) || $eventData['instructor_percent'] < 0) {
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

    public function exportInstructorList(Request $request)
    {
        try {
            if ($this->parseDataFormCache($request)) {
                $instructors = Instructor::has('elearningEventsForRoyalties')->whereStatus(1)->get();
            } else {
                $instructors = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title', 'elearningEventsForRoyalties.lessons', 'elearningEventsForRoyalties.delivery')->whereStatus(1)->get();

                foreach ($instructors as $key => $instr) {
                    $instructors[$key]['events'] = $instr->elearningEventsForRoyalties()->tableSort($request);

                    $instructors[$key]['events'] = $this->filters($instructors[$key]['events'], $request);
                    $instructors[$key]['events'] = $instructors[$key]['events']->get();

                    $data = $this->getInstructorEventData($instr, $instructors[$key]['events'], $request);

                    $instructors[$key]['cache_income'] = 0.0;
                    $instructors[$key]['title'] = $instr['title'];
                    $instructors[$key]['subtitle'] = $instr['subtitle'];
                    $instructors[$key]['header'] = $instr['header'];
                    $instructors[$key]['company'] = $instr['company'];

                    foreach ($instructors[$key]['events'] as $key2 => $event) {
                        $instructors[$key]['cache_income'] = $instructors[$key]['cache_income'] + $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    }
                }
            }

            $filename = 'Royalties_Export.xlsx';

            $path = Storage::disk('royalties')->path($filename);

            Excel::store(new RoyaltiesExportInstructorsList($instructors), $filename, 'royalties');

            return Excel::download(new RoyaltiesExportInstructorsList($instructors), $filename);
        } catch (Exception $e) {
            Log::error('Failed to get countdown. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function export(int $id, Request $request)
    {
        try {
            //$events = Event::tableSort($request);

            if ($id != 0) {
                $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title', 'elearningEventsForRoyalties.lessons', 'elearningEventsForRoyalties.delivery')->whereStatus(1)->where('id', $id)->get();
            } else {
                $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title', 'elearningEventsForRoyalties.lessons', 'elearningEventsForRoyalties.delivery')->whereStatus(1)->get();
            }

            foreach ($instructor as $key => $instr) {
                $instructor[$key]['events'] = $instr->elearningEventsForRoyalties()->tableSort($request);
                $instructor[$key]['events'] = $this->filters($instructor[$key]['events'], $request);
                $instructor[$key]['events'] = $instructor[$key]['events']->get();

                $data = $this->getInstructorEventData($instr, $instructor[$key]['events'], $request);

                foreach ($instructor[$key]['events'] as $key2 => $event) {
                    //dd($this->calculateIncomeByPercentHours($data['events'][$event->id]));
                    $instructor[$key]['events'][$key2]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    $instructor[$key]['events'][$key2]['total_event_minutes'] = $data['events'][$event->id]['total_event_minutes'];
                    $instructor[$key]['events'][$key2]['total_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'];
                    $instructor[$key]['events'][$key2]['percent'] = $data['events'][$event->id]['instructor_percent'];
                }
            }

            if ($id == 0) {
                $filename = 'Royalties_Export.xlsx';
            } else {
                $filename = $instructor[0]->title . '_' . $instructor[0]->subtitle . '_Royalties_Export.xlsx';
            }

            $path = Storage::disk('royalties')->path($filename);

            Excel::store(new RoyaltiesExport($instructor), $filename, 'royalties');

            return Excel::download(new RoyaltiesExport($instructor), $filename);
        } catch (Exception $e) {
            Log::error('Failed to get countdown. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Get page.
     *
     * @return PageResource
     */
    public function show(int $id, Request $request)
    {
        try {
            //$events = Event::tableSort($request);

            $instructor = Instructor::has('elearningEventsForRoyalties')->with('elearningEventsForRoyalties:id,title', 'elearningEventsForRoyalties.lessons', 'elearningEventsForRoyalties.delivery')->whereStatus(1)->where('id', $id)->get();

            $preResponseData = [];
            $i = 0;

            foreach ($instructor as $key => $instr) {
                $instructor[$key]['events'] = $instr->elearningEventsForRoyalties()->tableSort($request);

                $instructor[$key]['events'] = $this->filters($instructor[$key]['events'], $request);
                $instructor[$key]['events'] = $instructor[$key]['events']->get();

                $data = $this->getInstructorEventData($instr, $instructor[$key]['events'], $request);

                foreach ($instructor[$key]['events'] as $key2 => $event) {
                    //dd($this->calculateIncomeByPercentHours($data['events'][$event->id]));
                    //$instructor[$key]['events'][$key2]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);

                    $preResponseData[$i]['instructor'] = $instr['title'] . ' ' . $instr['subtitle'];
                    $preResponseData[$i]['title'] = $event['title'];
                    $preResponseData[$i]['id'] = $instr['id'];
                    $preResponseData[$i]['income'] = $this->calculateIncomeByPercentHours($data['events'][$event->id]);
                    $preResponseData[$i]['total_event_minutes'] = $data['events'][$event->id]['total_event_minutes'];
                    $preResponseData[$i]['total_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'];
                    $preResponseData[$i]['percent'] = $data['events'][$event->id]['instructor_percent'];

                    $i++;
                }
            }

            if ($request->sort != null) {
                if (explode('|', $request->sort)[1] == 'desc') {
                    return EventResourceRoyalty::collection(collect($preResponseData)->sortByDesc(explode('|', $request->sort)[0]));
                } else {
                    return EventResourceRoyalty::collection(collect($preResponseData)->sortBy(explode('|', $request->sort)[0]));
                }
            }

            return EventResourceRoyalty::collection(collect($preResponseData));
        } catch (Exception $e) {
            Log::error('Failed to get countdown. ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getEventData($events, $request)
    {
        $data = [];

        $incomes = [];

        $filterForTransaction = null;

        if ($request->transaction_from != null) {
            $filterForTransaction['created_at']['from'] = $request->transaction_from;
        }
        if ($request->transaction_to != null) {
            $filterForTransaction['created_at']['to'] = $request->transaction_to;
        }

        $filterForTransaction['calculateSubscription'] = false;

        foreach ($events as $key => $event) {
            $data['events'][$event->id] = $event->getTotalHours() * 60;
            $data['incomes'][$event->id] = $this->participants($event->id, $request);
        }

        return $data;
    }

    private function participants($eventId, $request)
    {
        if (isset(Auth::user()->role)) {
            $userRole = Auth::user()->role->pluck('id')->toArray();
        } else {
            $userRole = [];
        }
        $amount = 0;

        if (count(explode('T', $request->transaction_from)) > 1) {
            $transaction_from = isset($request->transaction_from) ? explode('T', $request->transaction_from)[0] : null;

            if ($transaction_from) {
                $transaction_from = Carbon::createFromFormat('Y-m-d', $transaction_from)->startOfDay()->format('Y-m-d');
            }
        } else {
            $transaction_from = $request->transaction_from;
        }

        if (count(explode('T', $request->transaction_to)) > 1) {
            $transaction_to = isset($request->transaction_to) ? explode('T', $request->transaction_to)[0] : null;

            if ($transaction_to) {
                $transaction_to = Carbon::parse($transaction_to, 'UTC')->endOfDay()->format('Y-m-d');
            }
        } else {
            $transaction_to = $request->transaction_to;
        }

        //$userRole = Auth::user()->role->pluck('id')->toArray();
        $transactions = [];
        if ($transaction_from && $transaction_to) {
            $from = $transaction_from;
            $to = $transaction_to;

            $transactions = Transaction::with('subscription', 'event', 'user')
                ->whereHas('event', function ($q) use ($eventId) {
                    $q->where('id', $eventId);
                })
                ->whereBetween('created_at', [$from, $to])
                ->where('status', 1)
                ->get();
        } elseif ($transaction_from && !$transaction_to) {
            $start_date = $transaction_from;

            $transactions = Transaction::with('subscription', 'event')
                ->whereHas('event', function ($q) use ($eventId) {
                    $q->where('id', $eventId);
                })
                ->whereDate('created_at', '>=', $start_date)
                ->where('status', 1)
                ->get();
        } elseif (!$transaction_from && $transaction_to) {
            $end_date = $transaction_to;

            $transactions = Transaction::with('subscription', 'event')
                ->whereHas('event', function ($q) use ($eventId) {
                    $q->where('id', $eventId);
                })
                ->whereDate('created_at', '<=', $end_date)
                ->where('status', 1)
                ->get();
        }

        foreach ($transactions as $key => $transaction) {
            if (!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()) {
                if (in_array(9, $userRole) && ($category !== 46)) {
                    continue;
                }

                $amount1 = $transaction['amount'];

                $amount = $amount + $amount1;
            }
        }
        // if($eventId == 2304){
        //    dd($amount);
        // }

        return $amount;
    }

    public function getInstructorEventData($instructor, $events, $request)
    {
        $data = [];
        if (isset($events)) {
            $responseDataEvent = $this->getEventData($events, $request);

            if (count($events) > 0) {
                $data['events'] = [];

                $total = 0;
                foreach ($events as $key => $event) {
                    $data['events'][$event->id] = [];
                    //$data['events'][$event->id]['lessons'] = [];

                    $sum1 = 0;
                    $data['events'][$event->id]['total_income'] = $responseDataEvent['incomes'][$event->id];
                    $data['events'][$event->id]['total_event_minutes'] = $responseDataEvent['events'][$event->id];
                    $data['events'][$event->id]['total_lessons_instructor_minutes'] = 0.0;

                    ///dd($data['events'][$event->id]['total_event_minutes']);

                    //$arr = [];

                    foreach ($event['lessons'] as $lesson) {
                        $sum = 0;
                        if ($lesson['vimeo_duration'] != '' && $lesson['vimeo_duration'] != 0) {
                            if ($lesson->pivot->instructor_id == $instructor->id) {
                                // if($lesson->topic()->first()){
                                //     $arr[$lesson->topic()->first()->title]['lessons'][$lesson->id] = $lesson->title . ' -- '.CarbonInterval::seconds(getSumLessonSecond($lesson))->cascade()->forHumans();;

                                // }

                                $sum = $sum + (getSumLessonSecond($lesson));
                            }
                            $data['events'][$event->id]['total_lessons_instructor_minutes'] = $data['events'][$event->id]['total_lessons_instructor_minutes'] + $sum;
                        }
                    }

                    // if($event->id == 2304){
                    //     dd($arr);
                    // }

                    $data['events'][$event->id]['instructor_percent'] = $data['events'][$event->id]['total_lessons_instructor_minutes'] / $data['events'][$event->id]['total_event_minutes'] * 100;
                }
            }
        }

        return $data;
    }

    public function getCacheIncome($request)
    {
        try {
            $income = Instructor::has('elearningEventsForRoyalties')->whereStatus(1)->sum('cache_income');

            //$pages = $this->filters($request, $pages);
            return [$income];
        } catch (Exception $e) {
            Log::warning('(pages widget) Failed to get pages count. ' . $e->getMessage());

            return '0';
        }
    }

    public function widgets(Request $request)
    {
        return [
            [
                'TOTAL ROYALTIES',
                $this->getCacheIncome($request),
                'Total royalties for all instructors',
            ],

        ];
    }
}

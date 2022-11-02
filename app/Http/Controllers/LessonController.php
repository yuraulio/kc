<?php

namespace App\Http\Controllers;

use App\Model\Lesson;
use App\Model\Event;
use App\Model\Topic;
use App\Model\Type;
use App\Model\User;
use App\Model\Instructor;
use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Requests\LessonRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Vimeo\Vimeo;
use App\Jobs\UpdateStatisticJson;
use App\Exports\LessonsNoVimeoLinkExport;
use Excel;
use App\Jobs\FixOrder;
use App\Jobs\LessonUpdate;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    /*public function index(Lesson $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $data['topics'] = Topic::with('lessonsPage')->get();
        dd($data['topics'][0]);
        $categories = Category::with('topics')->get()->groupBy('name')->toArray();

        return view('lesson.index', ['topics' => $data['topics'], 'user' => $user, 'categories' => $categories]);
    }*/
   
    public function index(Lesson $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $data['lessons'] = $model->with('category','topic', 'type')->get();
        

        $categories = Category::with('topics')->get()->groupBy('name')->toArray();

        return view('lesson.index', ['lessons' => $data['lessons'], 'user' => $user, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $topics)
    {
        $user = Auth::user();

        return view('lesson.create', ['user' => $user, 'topics' => $topics->with('category')->get(['id', 'title']), 'types' => Type::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lesson $model)
    {
        $this->validate($request, [
            'category' => 'required',
            'topic_id' => 'required',
        ]);
        //dd($request->all());

        $arr = array();

        if(!empty($request->links)){
            foreach($request->links as $key => $link){
                $arr1[$key] = ['name'=> $request->names[$key], 'link'=>$link];
            }
            //dd($links);
        }else{
            $arr1 = [];
        }
        $links = json_encode($arr1);

        if($request->status == 'on'){
            $status = 1;
        }else{
            $status = 0;
        }

        if($request->bold == 'on')
        {
            $bold = 1;
        }else
        {
            $bold = 0;
        }

        $request->request->add(['status' => $status, 'links' => $links,'bold'=>$bold]);

        $lesson = $model->create($request->all());

        $vimeoVideo = explode("/",$lesson->vimeo_video);
        $duration = 0;       

        $client = new Vimeo(env('client_id'), env('client_secret'), env('vimeo_token'));
        $response = $client->request("/videos/". end($vimeoVideo) . "/?password=".env('video_password'), array(), 'GET');

        if($response['status'] === 200){
            $duration = $response['body']['duration'];
            $lesson->vimeo_duration = $this->formatDuration($duration);
            $lesson->save();
        }

        if($request->topic_id != null){
            
            dispatch(new LessonUpdate($request->all(),$model));
        }

        if($request->type_id != null){
            $lesson->type()->detach();
            $type = Type::find($request->type_id);

            $lesson->type()->attach([$request->type_id]);
        }


        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Lesson $lesson)
    {
        
        $lesson = $lesson->with('topic', 'category','type')->find($lesson['id']);
        $topics = Topic::with('category')->get();
        $selectedCategory = $request->get('selectedCategory') ?: $request->get('selectedCategory');
        

        $types = Type::all();
 
        return view('lesson.edit', compact('lesson', 'topics', 'types', 'selectedCategory'));
    }

    public function save_instructor(Request $request)
    {
        $start = null;
        $end = null;
        $date = null;
        $topic = Topic::find($request->topic_id);

        if($request->date != null){
            $date = date('Y-m-d', strtotime($request->date));

            $date1 = date('Y-m-d', strtotime($request->date));
            
        }else{
            $date1 = date('Y-m-d', strtotime($request->date));;
        }

        if($request->start != null){
            $start = date('Y-m-d H:i:s', strtotime($date1." ".$request->start));
            $start_response = date('H:i:s', strtotime($date1." ".$request->start));
        }else{
            $start_response = null;
        }

        if($request->end != null){

            if($request->start > $request->end){
                $date1 = strtotime($date1);
                $date1 = date('Y-m-d', strtotime('+1 day', $date1));
                //dd($date1);
            }

            $end = date('Y-m-d H:i:s', strtotime($date1." ".$request->end));
            $end_response = date('H:i:s', strtotime($date1." ".$request->end));
            $end_response;
        }else{
            $end_response = null;
        }


        $duration = null;
        //if($start_response && $end_response){
        if($start && $end){
            
            //$startHour = date_create($start_response);
            //$endHour = date_create($end_response);
            $startHour = date_create($start);
            $endHour = date_create($end);

            $durationH = date_diff($endHour, $startHour);;
            if($durationH->h > 0){
                $duration .=  $durationH->h.'h';
            }

            if($durationH->i > 0){
                $duration .= ' ' .  $durationH->i.'m';
            }
            $duration = trim($duration);
        }
        
        $location_url = $request->location_url;
        if($location_url){
            $location_url = str_replace('https://', '', $location_url);
            $location_url = str_replace('http://', '', $location_url);
            $location_url = 'https://'.$location_url;
        }
        
        $topic->event_topic()->wherePivot('lesson_id', '=', $request->lesson_id)->wherePivot('event_id', '=', $request->event_id)->updateExistingPivot($request->topic_id,[
            //'priority' => $request->priority,
            'date' => $date,
            'room' => $request->room,
            'location_url' => $location_url,
            'duration' => $duration,
            'instructor_id' => $request->instructor_id,
            'time_starts' => $start,
            'time_ends' => $end
        ], false);

        //dd(date_format($start,"H:i:sa"));

        $data['instructor'] = Instructor::with('medias')->find($request->instructor_id);
        $data['lesson_id'] = $request->lesson_id;
        $data['date1'] = $date;
        $data['start'] = $start_response;
        $data['end'] = $end_response;
        $data['room'] = $request->room;
        $data['location_url'] = $location_url;

        dispatch((new UpdateStatisticJson($request->event_id))->delay(now()->addSeconds(3)));

        echo json_encode($data);

    }

    public function edit_instructor(Request $request)
    {
        $event = Event::with('type')->find($request->event_id);

        $lesson = $event->topic_edit_instructor()->wherePivot('lesson_id', $request->lesson_id)->first();
        $instructors = Instructor::where('status', 1)->with('medias')->get();

        $data['lesson'] = $lesson;
        $data['instructors'] = $instructors;
        $data['event'] = $event;
        $data['topic_id'] = $request->topic_id;
        $data['lesson_id'] = $request->lesson_id;
        $data['isInclassCourse'] = $event->is_inclass_course();
       
        echo json_encode($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    

    public function update(Request $request, Lesson $lesson)
    {

        $this->validate($request, [
            'category' => 'required',
            //'topic_id' => 'required',
        ]);

        $arr = array();

        if(!empty($request->links)){
            foreach($request->links as $key => $link){

                $link = str_replace('https://', '', $link);
                $link = str_replace('http://', '', $link);
                //$link = str_replace('www.', '', $link);
                //$link = str_replace('www', '', $link);

                $correct_link = strpos($link, 'https://');

                if(!$correct_link){
                    $link = 'https://'.$link;
                }
                $arr1[$key] = ['name'=> $request->names[$key], 'link'=>$link];
            }
        }else{
            $arr1 = [];
        }
        $links = json_encode($arr1);

        if($request->status == 'on')
        {
            $status = 1;
        }else
        {
            $status = 0;
        }

        if($request->bold == 'on')
        {
            $bold = 1;
        }else
        {
            $bold = 0;
        }

        $request->request->add(['status' => $status, 'links' => $links,'bold'=>$bold]);

        $lesson_id = $lesson['id'];
        $lesson->update($request->all());

        $vimeoVideo = explode("/",$lesson->vimeo_video);
        $duration = 0;       

        $client = new Vimeo(env('client_id'), env('client_secret'), env('vimeo_token'));
        $response = $client->request("/videos/". end($vimeoVideo) . "/?password=".env('video_password'), array(), 'GET');

        if($response['status'] === 200){
            $duration = $response['body']['duration'];
            $lesson->vimeo_duration = $this->formatDuration($duration);
            $lesson->save();
        }
        if($request->topic_id != null){
            

            dispatch(new LessonUpdate($request->all(),$lesson));


        }else {


            $category = Category::find($request->category);

            $allEvents = $category->events;
            foreach($allEvents as $event)
            {
                           
                $event->allLessons()->detach($lesson['id']);              
                $lesson->topic()->wherePivot('category_id',$request->category)->detach();
    
            }
        }

        $lesson->type()->sync([$request->type_id]);


        return back()->withStatus(__('Lesson successfully updated.'));
        //return redirect()->route('lessons.index')->withStatus(__('Lesson successfully updated.'));
    }


    private function formatDuration($duration){
        $duration = gmdate("H:i:s", $duration);
        $duration = explode(":",$duration);

        $finalFormat = '';

        if($duration[0]!="00"){
            $finalFormat = $finalFormat . $duration[0]."h ";
        }
        if($duration[1]!="00"){
            $finalFormat =  $finalFormat . $duration[1]."m ";
        }

        if($duration[2]!="00"){
            $finalFormat = $finalFormat . $duration[2]."s";
        }
        return trim($finalFormat);
    }


    public function destroy(Request $request, Lesson $lesson)
    {

        $categories = $request->categories;
        foreach( (array) $request->lessons as $key => $lesson){

            if(!isset($categories[$key])){
                continue;
            }

            $category = Category::find($categories[$key]);
            $category->lessons()->detach($lesson);

            foreach($category->events as $event){
                $event->allLessons()->detach($lesson);
            }

            $lesson = Lesson::find($lesson);
            if(count($lesson->topic) == 0){
                $lesson->delete();
            }
            

        }

       

        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully deleted.'));
    }


    public function remove_lesson(Request $request)
    {
       
        $topic = Topic::find($request->topic_id);
        $topic->event_topic()->wherePivot('lesson_id', '=', $request->lesson_id)->wherePivot('event_id', '=', $request->event_id)->detach($request->topic_id);

        dispatch((new UpdateStatisticJson($request->event_id))->delay(now()->addSeconds(3)));

        echo json_encode($request->all());
    }

    public function moveMultipleLessonToTopic(Request $request){
        
        //dd($request->all());
        $validatorArray['lessons'] = 'required';
        $validatorArray['category'] = 'required';
        $validatorArray['fromTopic'] = 'required';
        $validatorArray['toTopic'] = 'required';
       

        $validator = Validator::make($request->all(), $validatorArray);
        
        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'message' => '',
            ];

        }

        $lessons = $request->lessons;
        $category = $request->category;
        $fromTopic = $request->fromTopic;
        $toTopic = $request->toTopic;
       
        $category = Category::find($category);
        $newOrder = [];
        if($category){

            foreach($lessons as $lesson){
                //dd($fromTopic);
                //dd($category->lessons()->wherePivot('topic_id',450)->get());
                //$category->lessons()->wherePivot('topic_id',$fromTopic)->detach();
                //$category->topic()->attach($toTopic, ['lesson_id' => $lesson]);
                
                $allEvents = $category->events;

                foreach($allEvents as $event)
                {
                        
                    $allLessons = $event->allLessons->groupBy('id');
                    $priorityLesson = $event->allLessons()->wherePivot('topic_id',$toTopic)->orderBy('priority')->get();
                    
                    $date = '';
                    $time_starts = '';
                    $time_ends = '';
                    $duration = '';
                    $room = '';
                    $instructor_id = '';
                    //$priority = isset($allLessons->last()[0]['pivot']['priority']) ? $allLessons->last()[0]['pivot']['priority'] + 1 :( count($allLessons)+1);
                    
                    $priority = isset($priorityLesson->last()['pivot']['priority']) ? $priorityLesson->last()['pivot']['priority'] + 1 :count($priorityLesson)+1 ;
                    
                    if(isset($allLessons[$lesson][0])){
                        
                        $date = $allLessons[$lesson][0]['pivot']['date'];
                        $time_starts = $allLessons[$lesson][0]['pivot']['time_starts'];
                        $time_ends = $allLessons[$lesson][0]['pivot']['time_ends'];
                        $duration = $allLessons[$lesson][0]['pivot']['duration'];
                        $room = $allLessons[$lesson][0]['pivot']['room'];
                        $instructor_id = $allLessons[$lesson][0]['pivot']['instructor_id'];
                        //$priority = $priority;
                    }

                    $event->allLessons()->detach($lesson);
                    
                    $event->changeOrder($priority);
                
                    /*foreach($event->allLessons()->wherePivot('priority','>=',$priority)->get() as  $pLesson){
                        $newPriorityLesson = $pLesson->pivot->priority + 1;
                        $pLesson->pivot->priority = $newPriorityLesson;
                        $pLesson->pivot->save();
                        $newOrder[$category->id.'-'.$fromTopic.'-'.$pLesson->id] = $newPriorityLesson;
                        
                    }*/

                    
                    $event->topic()->attach($toTopic,['lesson_id' => $lesson,'date'=>$date,'time_starts'=>$time_starts,
                        'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority]);
                        //}

                    $event->fixOrder();
                    /*$newPriorityLesson = 1;
                    foreach($event->allLessons()->orderBy('priority')->get() as  $pLesson){
            
                        $pLesson->pivot->priority = $newPriorityLesson;
                        $pLesson->pivot->save();
                        $newPriorityLesson = $pLesson->pivot->priority + 1;
                    }*/


    

                }



                //$allLessons = $event->allLessons->groupBy('id');
                $priorityLessonCat = $category->lessons()->wherePivot('topic_id',$toTopic)->orderBy('priority')->get();
                $priorityCat = isset($priorityLessonCat->last()['pivot']['priority']) ? $priorityLessonCat->last()['pivot']['priority'] + 1 :count($priorityLessonCat)+1 ;
                $category->lessons()->wherePivot('topic_id',$fromTopic)->wherePivot('lesson_id',$lesson)->detach();
                    //$category->lessons()->wherePivot('topic_id',$fromTopic)->wherePivot('lesson_id',$lesson)->detach();

                //dd($priorityCat);
                /*foreach($category->lessons()->wherePivot('priority','>=',$priorityCat)->get() as  $pLesson){
                    $newPriorityLesson = $pLesson->pivot->priority + 1;
                    $pLesson->pivot->priority = $newPriorityLesson;
                    $pLesson->pivot->save();  
                }*/
                $category->changeOrder($priorityCat);
                $category->topic()->attach($toTopic, ['lesson_id' => $lesson,'priority'=>$priorityCat]);
                
                $newOrder = $category->fixOrder($fromTopic);
            
                /*$newPriorityLesson = 1;
                foreach($category->lessons()->orderBy('priority')->get() as  $pLesson){
                    $pLesson->pivot->priority = $newPriorityLesson;
                    $pLesson->pivot->save();  
                    $newOrder[$category->id.'-'.$fromTopic.'-'.$pLesson->id] = $newPriorityLesson;
                    $newPriorityLesson = $pLesson->pivot->priority + 1;
                }*/
                
                


            }

            return response()->json([
                'success' => true,
                'newOrder' => $newOrder,
            ]);

        }
        return response()->json([
            'success' => false,
        ]);
        

    }

    public function orderLesson(Request $request){
        //dd($request->all());

        $validatorArray['order'] = 'required';
        $validatorArray['category'] = 'required';
        $validatorArray['topic'] = 'required';       

        $validator = Validator::make($request->all(), $validatorArray);
        
        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'message' => '',
            ];

        }

        $category = Category::find($request->category);

        $lessons = [];
        foreach($request->order as $key => $order){
            $lessons[] = explode('-',$key)[2];
        }

        foreach($category->events as $event){
            //dd($event->lessons()->wherePivotIn('lesson_id',$lessons)->get());
            
            foreach($event->lessons()->wherePivot('topic_id',$request->topic)->wherePivotIn('lesson_id',$lessons)->get() as $lesson){
                $index = $category->id . '-' . $lesson->pivot->topic_id . '-' . $lesson->pivot->lesson_id;

                if(!isset($request->order[$index])){
                    continue;
                }
                
                $lesson->pivot->priority = $request->order[$index];
                $lesson->pivot->save();

            }
            //dispatch(new FixOrder($event,''));
        }
        

        $newOrder = [];
        foreach($category->lessons()->wherePivot('topic_id',$request->topic)->wherePivotIn('lesson_id',$lessons)->get() as $lesson){
            
            $index = $lesson->pivot->category_id . '-' . $lesson->pivot->topic_id . '-' . $lesson->pivot->lesson_id;
           
            if(!isset($request->order[$index])){
                continue;
            }
           
            $lesson->pivot->priority = $request->order[$index];
            $lesson->pivot->save();

        }
        //$category = Category::find($request->category);
        //dispatch(new FixOrder($category,''));

        return [
            'success' => true,
            'newOrder' => $request->order,
            'message' => 'Order has changed',
        ];

    }

    public function extractElearningLessonsWithNoVimeoLink(){
        
        $lessons = Lesson::whereHas('event', function($event){

            return $event->whereHas('delivery', function($delivery){
                return $delivery->where('deliveries.id', 143);
            });

        })->whereIn('vimeo_video',['',null])->get();


        //Excel::store(new LessonsNoVimeoLinkExport($lessons), 'LessonsNoVimeoLinkExport.xlsx', 'export');
        //return Excel::download(new LessonsNoVimeoLinkExport($lessons), 'LessonsNoVimeoLinkExport.xlsx');

        return view('lesson.lessons_with_no_vimeo_link', ['lessons' => $lessons]);


    }

}

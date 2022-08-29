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
            
            foreach($request->topic_id as $topic){
                $topic = Topic::with('category')->find($topic);
               
                foreach($topic->category as $cat){
                    
                    if($cat->id != $request->category){
                        continue;
                    }
                    //$lesson->topic()->wherePivot('category_id',$request->category)->detach();
                    //$cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);

                    $allEvents = $cat->events;
                    foreach($allEvents as $event)
                    {
                        $allLessons = $event->allLessons->groupBy('id');

                        $date = '';
                        $time_starts = '';
                        $time_ends = '';
                        $duration = '';
                        $room = '';
                        $instructor_id = '';
                        $priority = count($allLessons)+1;

                        if(isset($allLessons[$lesson['id']][0])){

                            $date = $allLessons[$lesson['id']][0]['pivot']['date'];
                            $time_starts = $allLessons[$lesson['id']][0]['pivot']['time_starts'];
                            $time_ends = $allLessons[$lesson['id']][0]['pivot']['time_ends'];
                            $duration = $allLessons[$lesson['id']][0]['pivot']['duration'];
                            $room = $allLessons[$lesson['id']][0]['pivot']['room'];
                            $instructor_id = $allLessons[$lesson['id']][0]['pivot']['instructor_id'];
                            
                        }

                        //if(!in_array($lesson['id'],$allLessons)){
                        
                        $event->allLessons()->detach($lesson['id']);
                        $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'date'=>$date,'time_starts'=>$time_starts,
                            'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority]);
                        //}

                        $lesson->topic()->wherePivot('category_id',$request->category)->detach();
                        $cat->topic()->attach($topic, ['lesson_id' => $lesson->id,'priority'=>$priority]);
                        

                    }
                }

            }
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
        // //dd($topics);
        // $new_topics = [];

        // foreach($topics as $topic)
        // {
        //     //dd($topic);
        //     if($topic->category[0]['id'] != $lesson->category[0]['id']){
        //         array_push($new_topics, $topic);
        //     }
        // }

        // $topics = $new_topics;
        // dd($topics);

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
        }else{
            $end_response = null;
        }


        $duration = null;
        if($start_response && $end_response){

            $startHour = date_create($start_response);
            $endHour = date_create($end_response);

            $durationH = date_diff($endHour, $startHour);;

            if($durationH->h > 0){
                $duration .=  $durationH->h.'h';
            }

            if($durationH->i > 0){
                $duration .= ' ' .  $durationH->i.'m';
            }

            $duration = trim($duration);
        }


        $topic->event_topic()->wherePivot('lesson_id', '=', $request->lesson_id)->wherePivot('event_id', '=', $request->event_id)->updateExistingPivot($request->topic_id,[
            //'priority' => $request->priority,
            'date' => $date,
            'room' => $request->room,
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
    /*public function update(Request $request, Lesson $lesson)
    {
        $arr = array();

        if(!empty($request->links)){
            foreach($request->links as $key => $link){

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

        //dd($request->all());
        if($request->topic_id != null){
            $lesson->topic()->detach();
            foreach($request->topic_id as $topic)
            {
                $topic = Topic::with('category')->find($topic);
                dd($topic);
                foreach($topic->category as $cat){
                    $cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);

                    $allEvents = $cat->events;
                    foreach($allEvents as $event)
                    {
                        $allLessons = $event->allLessons->pluck('id')->toArray();

                        if(!in_array($lesson['id'],$allLessons)){
                            $priority = count($allLessons)+1;
                            //$event->topic()->sync([['topic_id'=>$topic['id'],'lesson_id' => $lesson['id'],'priority' => $priority]]);
                            $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'priority' => $priority]);
                        }
                        
    
                    }

                 
                       

                }
                

                
            }
        }

        $lesson->type()->sync([$request->type_id]);


        return back()->withStatus(__('Lesson successfully updated.'));
        //return redirect()->route('lessons.index')->withStatus(__('Lesson successfully updated.'));
    }*/

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
            //$lesson->topic()->detach();
            foreach($request->topic_id as $topic)
            {
                $topic = Topic::with('category')->find($topic);
               
                foreach($topic->category as $cat){
                    
                    //dd($cat->id . ' => ' .$request->category);
                    if($cat->id != $request->category){
                        
                        continue;
                    }
                    //$lesson->topic()->wherePivot('category_id',$request->category)->detach();
                    //$cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);
                    $allEvents = $cat->events;
                    foreach($allEvents as $event)
                    {
                        
                        $allLessons = $event->allLessons->groupBy('id');
                        
                        $date = '';
                        $time_starts = '';
                        $time_ends = '';
                        $duration = '';
                        $room = '';
                        $instructor_id = '';
                        $priority = count($allLessons)+1;

                        if(isset($allLessons[$lesson['id']][0])){
                            
                            $date = $allLessons[$lesson['id']][0]['pivot']['date'];
                            $time_starts = $allLessons[$lesson['id']][0]['pivot']['time_starts'];
                            $time_ends = $allLessons[$lesson['id']][0]['pivot']['time_ends'];
                            $duration = $allLessons[$lesson['id']][0]['pivot']['duration'];
                            $room = $allLessons[$lesson['id']][0]['pivot']['room'];
                            $instructor_id = $allLessons[$lesson['id']][0]['pivot']['instructor_id'];
                            $priority = $allLessons[$lesson['id']][0]['pivot']['priority'];
                        }

                        //if(!in_array($lesson['id'],$allLessons)){
                        
                        
                        //$lesson->topic()->attach($topic->id);
                
                        $event->allLessons()->detach($lesson['id']);
                        $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'date'=>$date,'time_starts'=>$time_starts,
                            'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority]);
                        //}
                        
                        $lesson->topic()->wherePivot('category_id',$request->category)->detach();
                        $cat->topic()->attach($topic, ['lesson_id' => $lesson->id,'priority'=>$priority]);
    
                    }

                }
                    
            }

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    /*public function destroy(Lesson $lesson)
    {

        if(count($lesson->topic) > 1){

            $catgegoriesAssignded = '';
           
            foreach($lesson->topic as $key => $topic){
            
                $catgegoriesAssignded .= $lesson->category[$key]['name'] . ' => ' . $topic->title . '<br> ';

               
            }

            return redirect()->route('lessons.index')->withErrors(__('Lesson cannot be delete because is attached to more than one topics.<br>'. $catgegoriesAssignded));
        }
        $lesson->deletee();

        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully deleted.'));
    }*/

    /*public function destroy(Request $request, Lesson $lesson)
    {

        $error = false;
        $categoriesAssignded = '';
        foreach($request->lessons as $lesson){
            $lesson = Lesson::find($lesson);

            if(count($lesson->topic) > 1){
                $error = true;
                $categoriesAssignded .= 'Lesson <strong>'. $lesson->title .'</strong> cannot be delete because is attached to more than one topics.<br>';
               
                foreach($lesson->topic as $key => $topic){
                
                    $categoriesAssignded .= $lesson->category[$key]['name'] . ' => ' . $topic->title . '<br> ';
    
                   
                }
                $categoriesAssignded .= '<br>';
                //return redirect()->route('lessons.index')->withErrors(__('Lesson cannot be delete because is attached to more than one topics.<br>'. $catgegoriesAssignded));
            }else{
                $lesson->deletee();

            }

        }

        if($error){
            return redirect()->route('lessons.index')->withErrors($categoriesAssignded);
        }

        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully deleted.'));
    }*/


    public function destroy(Request $request, Lesson $lesson)
    {

        $categories = $request->categories;
        foreach( (array) $request->lessons as $key => $lesson){

            if(!isset($categories[$key])){
                continue;
            }

            $category = Category::find($categories[$key]);
            //$category->lessons()->where('id',$lesson)->detach();
            $category->lessons()->detach($lesson);

            foreach($category->events as $event){

                //dd($event->allLessons->where('id',$lesson)->first());
                $event->allLessons()->detach($lesson);
                //$event->allLessons()->where('id',$lesson)->detach();
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
                    
                    $date = '';
                    $time_starts = '';
                    $time_ends = '';
                    $duration = '';
                    $room = '';
                    $instructor_id = '';
                    $priority = isset($allLessons->last()[0]['pivot']['priority']) ? $allLessons->last()[0]['pivot']['priority'] + 1 :( count($allLessons)+1);
                   
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
                    $event->topic()->attach($toTopic,['lesson_id' => $lesson,'date'=>$date,'time_starts'=>$time_starts,
                        'time_ends'=>$time_ends, 'duration' => $duration, 'room' => $room, 'instructor_id' => $instructor_id, 'priority' => $priority]);
                        //}

                    $category->lessons()->wherePivot('topic_id',$fromTopic)->wherePivot('lesson_id',$lesson)->detach();
                    $category->topic()->attach($toTopic, ['lesson_id' => $lesson,'priority'=>$priority]);
                    $newOrder[$category->id.'-'.$fromTopic.'-'.$lesson] = $priority;
                }

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

    /*public function orderLesson(Request $request, Event $event){
        //dd($request->all());
        foreach($event->lessons as $lesson){
            $lesson->pivot->priority = $request->all()[$lesson['id']];
            $lesson->pivot->save();

        }

    }*/

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

            foreach($event->lessons()->wherePivotIn('lesson_id',$lessons)->get() as $lesson){
                $index = $category->id . '-' . $lesson->pivot->topic_id . '-' . $lesson->pivot->lesson_id;

                if(!isset($request->order[$index])){
                    continue;
                }
                
                $lesson->pivot->priority = $request->order[$index];
                $lesson->pivot->save();

            }
        }
        $newOrder = [];
        foreach($category->lessons()->wherePivotIn('lesson_id',$lessons)->get() as $lesson){
            $index = $lesson->pivot->category_id . '-' . $lesson->pivot->topic_id . '-' . $lesson->pivot->lesson_id;
           
            if(!isset($request->order[$index])){
                continue;
            }
            
            $lesson->pivot->priority = $request->order[$index];
            $lesson->pivot->save();

        }

        return [
            'success' => true,
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

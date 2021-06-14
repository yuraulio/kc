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

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lesson $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        return view('lesson.index', ['lessons' => $model->with('topic', 'type')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $topics)
    {
        $user = Auth::user();

        return view('lesson.create', ['user' => $user, 'topics' => $topics->get(['id', 'title']), 'types' => Type::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonRequest $request, Lesson $model)
    {
        if($request->status == 'on')
        {
            $status = 1;
        }else
        {
            $status = 0;
        }

        $request->request->add(['status' => $status]);

        $lesson = $model->create($request->all());

        if($request->topic_id != null){
            foreach($request->topic_id as $topic){

                //assign on Topic
                $topic = Topic::find($topic);
                $cat_id = $topic->with('category')->first()->category[0]->id;
                $cat = Category::with('events')->find($cat_id);

                $cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);

                //assign on AllEvents
                $allEvents = $cat->events;
                foreach($allEvents as $event)
                {
                    $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id']]);
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
    public function edit(Lesson $lesson)
    {
        $lesson = $lesson->with('topic', 'category','type')->find($lesson['id']);
        $topics = Topic::with('category')->get();
        $new_topics = [];

        foreach($topics as $topic)
        {
            //dd($topic);
            if($topic->category[0]['id'] != $lesson->category[0]['id']){
                array_push($new_topics, $topic);
            }
        }

        $topics = $new_topics;


        $types = Type::all();


        return view('lesson.edit', compact('lesson', 'topics', 'types'));
    }

    public function save_instructor(Request $request)
    {
        $start = null;
        $end = null;
        $date = null;
        $topic = Topic::find($request->topic_id);

        if($request->date != null){
            $date = date('Y-m-d H:i:s', strtotime($request->date));
            //$date1 = date('Y-m-d', strtotime($request->date));
        }

        if($request->start != null){
            dd($request->start);
            $start = date('Y-m-d H:i:s', strtotime($date1." ".$request->start));
            //$start_response = date('H:i:s', strtotime($date1." ".$request->start));
        }

        if($request->end != null){
            $end = date('Y-m-d H:i:s', strtotime($date1." ".$request->end));
            //$end_response = date('H:i:s', strtotime($date1." ".$request->end));
        }




        $topic->event_topic()->wherePivot('lesson_id', '=', $request->lesson_id)->wherePivot('event_id', '=', $request->event_id)->updateExistingPivot($request->topic_id,[
            'priority' => $request->priority,
            'date' => $date,
            'room' => $request->room,
            'duration' => $request->duration,
            'instructor_id' => $request->instructor_id,
            'time_starts' => $start,
            'time_ends' => $end
        ], false);

        //dd(date_format($start,"H:i:sa"));

        $data['instructor'] = Instructor::find($request->instructor_id);
        $data['lesson_id'] = $request->lesson_id;
        //$data['date1'] = $date1;
        //$data['start'] = $start_response;
        //$data['end'] = $end_response;
        $data['duration'] = $request->duration;
        $data['room'] = $request->room;

        echo json_encode($data);

    }

    public function edit_instructor(Request $request)
    {
        $event = Event::with('topic', 'type')->find($request->event_id);

        $lesson = $event->topic()->wherePivot('event_id', '=', $request->event_id)->wherePivot('topic_id', '=', $request->topic_id)->wherePivot('lesson_id', '=', $request->lesson_id)->get();
        $instructors = Instructor::where('status', 1)->get();

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
        if($request->status == 'on')
        {
            $status = 1;
        }else
        {
            $status = 0;
        }

        $request->request->add(['status' => $status]);

        $lesson_id = $lesson['id'];
        $lesson->update($request->all());


        if($request->topic_id != null){
            $lesson->topic()->detach();
            foreach($request->topic_id as $topic)
            {
                $topic = Topic::with('category')->find($topic);
                $cat = $topic->category[0];

                $cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);
            }
        }


        if($request->type_id != null){
            $lesson->type()->detach();
            $type = Type::find($request->type_id);

            $lesson->type()->attach([$request->type_id]);
        }


        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        //dd($lesson);
        if (!$lesson->topic->isEmpty()) {
            return redirect()->route('lessons.index')->withErrors(__('This lesson has items attached and can\'t be deleted.'));
        }

        $lesson->delete();

        return redirect()->route('lessons.index')->withStatus(__('Lesson successfully deleted.'));
    }

    public function remove_lesson(Request $request)
    {
        $topic = Topic::find($request->topic_id);
        $topic->event_topic()->wherePivot('lesson_id', '=', $request->lesson_id)->wherePivot('event_id', '=', $request->event_id)->detach($request->topic_id);

        echo json_encode($request->all());
    }

    public function orderLesson(Request $request, Event $event){
        //dd($request->all());
        foreach($event->lessons as $lesson){
            $lesson->pivot->priority = $request->all()[$lesson['id']];
            $lesson->pivot->save();

        }

    }

}

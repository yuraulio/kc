<?php

namespace App\Http\Controllers;

use App\Model\Lesson;
use App\Model\Topic;
use App\Model\Type;
use App\Model\Event;
use App\Model\Instructor;
use App\User;
use App\Category;
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
        $lesson = $model->create($request->all());

        if($request->topic_id != null){
            foreach($request->topic_id as $topic){
                $topic = Topic::find($topic);
                $cat_id = $topic->with('category')->first()->category[0]->id;
                $cat = Category::find($cat_id);

                $cat->topic()->attach($topic, ['lesson_id' => $lesson->id]);
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

    public function edit_instructor(Request $request)
    {
        $event = Event::with('topic')->find($request->event_id);

        $lesson = $event->topic()->wherePivot('event_id', '=', $request->event_id)->wherePivot('topic_id', '=', $request->topic_id)->wherePivot('lesson_id', '=', $request->lesson_id)->get();

        $instructors = Instructor::all();

        $data['lesson'] = $lesson;
        $data['instructors'] = $instructors;

        echo json_encode($data);
        //dd($find);
        //dd('from edit instructor');
        //return view('lesson.edit_instructor_modal');
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
        //dd($request->all());
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
}

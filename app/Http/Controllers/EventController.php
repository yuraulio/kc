<?php

namespace App\Http\Controllers;

use App\Model\Event;
use App\Model\Type;
use App\Model\Topic;
use App\Model\Instructor;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        return view('event.index', ['events' => $model->with('category', 'type')->get(), 'user' => $user]);
    }

    public function assign(Request $request)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();
        $event = Event::with('type', 'category')->find($request->id);

        $topics = [];
        foreach($event->category as $category){
            foreach($category->topics as $topic){
                $topics[$topic->id] = $topic;
            }
        }

        $instructors = Instructor::all();
        //dd($instructors);
        return view('event.assign', ['user' => $user, 'event' => $event, 'topics' => $topics, 'instructors' => $instructors]);
    }

    public function fetchTopics(Request $request)
    {
        $topics = [];
        foreach($request->topics_ids as $key => $topic)
        {
            //dd($topic);
            $topic1 = Topic::with('lessons', 'event_topic')->find($topic['value']);
            //dd($topic1);
            array_push($topics, $topic1);
        }

        echo json_encode($topics);
    }

    public function assign_store(Request $request, $event_id)
    {
        $lessons = json_decode($request->all_lessons);
        $event = Event::find($event_id);
        foreach($lessons as $lesson){

            $lesson = (array)$lesson;

            $lesson_id = explode("-",$lesson['name']);

            $instructor = $lesson['lesson_id'];

            //dd($event);

            if($event->topic()->wherePivot('lesson_id',$lesson_id[1])->first() == null){
                $event->topic()->attach($lesson['topic_id'],['lesson_id'=> $lesson_id[1], 'instructor_id' => $instructor]);
            }else{

                $event->topic()->wherePivot('lesson_id',$lesson_id[1])->updateExistingPivot($lesson['topic_id'], ['instructor_id' => $instructor], false);
            }

        }

        return redirect()->route('events.index')->withStatus(__('Event successfully assign.'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('city.create', ['user' => $user, 'events' => Events::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, Event $model)
    {
        $event = $model->create($request->all());

        if($request->category_id != null){
            $category = Category::find($request->category_id);

            $event->category()->attach([$category->id]);
        }


        if($request->type_id != null){
            $type = Type::find($request->type_id);

            $event->type()->attach([$request->type_id]);
        }


        return redirect()->route('events.index')->withStatus(__('Event successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $id = $event['id'];
        $event = $event->with('category')->find($id);

        $categories = Category::all();
        //$event = $event->with('category')->first();
        $types = Type::all();

        return view('event.edit', compact('event', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->all());
        $event->category()->sync([$request->category_id]);

        if($request->type_id != null){
            $event->type()->detach();
            $type = Type::find($request->type_id);

            $event->type()->attach([$request->type_id]);
        }

        return redirect()->route('events.index')->withStatus(__('Event successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if (!$event->category->isEmpty()) {
            return redirect()->route('events.index')->withErrors(__('This event has items attached and can\'t be deleted.'));
        }

        $event->delete();

        return redirect()->route('events.index')->withStatus(__('Event successfully deleted.'));
    }
}

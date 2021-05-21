<?php

namespace App\Http\Controllers;

use App\Model\Event;
use App\Model\Type;
use App\Model\Topic;
use App\Model\Ticket;
use App\Model\Instructor;
use App\Model\Category;
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

    public function assign_ticket(Request $request)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();
        $event = Event::with('type', 'category')->find($request->id);

        $tickets = Ticket::all();

        //dd($instructors);
        return view('event.assign_ticket', ['user' => $user, 'event' => $event, 'tickets' => $tickets]);
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

    public function assign_ticket_store(Request $request, $event_id)
    {
        $event = Event::find($event_id);


        $event->ticket()->attach($request->ticket_id);

        return redirect()->route('events.index')->withStatus(__('Ticket successfully assign.'));
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

    public function assign_store(Request $request)
    {

        $event = Event::with('type')->find($request->event_id);

        //dd($event);

        $allLessons = Topic::with('lessons')->find($request->topic_id);

        foreach($allLessons->lessons as $lesson)
        {
            $find = $event->topic()->wherePivot('event_id', '=', $request->event_id)->wherePivot('topic_id', '=', $request->topic_id)->wherePivot('lesson_id', '=', $lesson['id'])->get();
            //dd($find);
            if(count($find) == 0 && $request->status1 == true)
            {
                $event->topic()->attach($request->topic_id,['lesson_id' => $lesson['id']]);
            }else{
                $topicLesson_for_detach = $event->topic()->detach($request->topic_id);
                break;
            }



        }

        $data['request'] = $request->all();
        $data['lesson'] = $allLessons;
        $data['event'] = $event;
        echo json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $categories = Category::all();
        $types = Type::all();

        return view('event.create', ['user' => $user, 'events' => Event::all(), 'categories' => $categories, 'types' => $types]);
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
        $user = Auth::user();
        $id = $event['id'];
        $event = $event->with('category', 'summary', 'benefits', 'ticket', 'city', 'venues', 'topic')->find($id);
        $categories = Category::all();
        $types = Type::all();

        $allTopicsByCategory = Category::with('topics')->find($event->category[0]->id);

        //$allTopicsByCategory1 = $allTopicsByCategory->topic()->with('lessons')->get()->groupBy('id');

        $allTopicsByCategory1 = $event->topic()->with('lessons')->get()->groupBy('id');
        //dd($allTopicsByCategory1);



        return view('event.edit', compact('event', 'categories', 'types', 'user', 'allTopicsByCategory', 'allTopicsByCategory1'));
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

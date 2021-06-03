<?php

namespace App\Http\Controllers;

use App\Model\Event;
use App\Model\Type;
use App\Model\Topic;
use App\Model\Ticket;
use App\Model\Instructor;
use App\Model\Category;
use App\Model\Partner;
use App\Model\PaymentMethod;
use App\Model\Delivery;
use App\Model\Categories_Faqs;
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

    // public function assign(Request $request)
    // {
    //     $this->authorize('manage-users', User::class);
    //     $user = Auth::user();
    //     $event = Event::with('type', 'category')->find($request->id);

    //     $topics = [];
    //     foreach($event->category as $category){
    //         foreach($category->topics as $topic){
    //             $topics[$topic->id] = $topic;
    //         }
    //     }

    //     $instructors = Instructor::all();
    //     //dd($instructors);
    //     return view('event.assign', ['user' => $user, 'event' => $event, 'topics' => $topics, 'instructors' => $instructors]);
    // }

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

        $event = Event::with('delivery')->find($request->event_id);
        $data['isInclassCourse'] = $event->is_inclass_course();


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

    public function assignPaymentMethod(Request $request, Event $event)
    {
        $event->paymentMethod()->detach();
        $event->paymentMethod()->attach($request->payment_method);

        return response()->json([
            'message' => 'Payment Method Changed'
        ]);

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
        $delivery = Delivery::all();

        return view('event.create', ['user' => $user, 'events' => Event::all(), 'categories' => $categories, 'types' => $types, 'delivery' =>$delivery]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, Event $model)
    {
        $request->request->add(['release_date_files' => date('Y-m-d H:i:s', strtotime($request->release_date_files))]);

        $event = $model->create($request->all());

        $event->createSlug($request->slug);
        $event->createMetas($request->all());

        if($request->category_id != null){
            $category = Category::find($request->category_id);

            $event->category()->attach([$category->id]);
        }


        if($request->type_id != null){
            $type = Type::find($request->type_id);

            $event->type()->attach([$request->type_id]);
        }

        if($request->delivery_id != null){
            $delivery = Delivery::find($request->delivery_id);

            $event->delivery()->attach([$request->delivery_id]);
        }

        return redirect()->route('events.edit',$event->id)->withStatus(__('Event successfully created.'));
        //return redirect()->route('events.index')->withStatus(__('Event successfully created.'));
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
        $event = $event->with('delivery','category', 'summary', 'benefits', 'ticket', 'city', 'venues', 'topic',/*'categoryFaqs'*/, 'lessons', 'instructors', 'users', 'partners', 'sections','paymentMethod','slugable','metable','faqs')->find($id);

        $categories = Category::all();
        $types = Type::all();
        $partners = Partner::all();

        //dd($event->category->first());
        if($event->category->first() != null){
            $allTopicsByCategory = Category::with('topics')->find($event->category->first()->id);
        }else{
            $allTopicsByCategory = Category::with('topics')->first();
        }

        $allTopicsByCategory1 = $event['lessons']->unique()->groupBy('topic_id');
        $instructors = $event['instructors']->groupBy('lesson_id');
        $topics = $event['topic']->unique()->groupBy('topic_id');
       // dd($event['topic']->groupBy('id'));
        //dd($allTopicsByCategory);
        $data['event'] = $event;
        //dd($event);
        $data['categories'] = $categories;
        $data['types'] = $types;
        $data['user'] = $user;
        $data['allTopicsByCategory'] = $allTopicsByCategory;
        $data['lessons'] = $allTopicsByCategory1;
        $data['instructors'] = $instructors;
        $data['topics'] = $topics;

        $data['slug'] = $event['slugable'];
        $data['metas'] = $event['metable'];

        $data['methods'] = PaymentMethod::where('status',1)->get();
        $data['delivery'] = Delivery::all();
        $data['isInclassCourse'] = $event->is_inclass_course();
        //dd($data['topics']);

        //dd($event['categoryFaqs']);
        return view('event.edit', $data);
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
        $request->request->add(['release_date_files' => date('Y-m-d H:i:s', strtotime($request->release_date_files))]);


        $event->update($request->all());

        $updated_event = Event::find($event['id']);
        Event::where('id',$updated_event['id'])->update(['release_date_files' => $timestamp]);
        $event->category()->sync([$request->category_id]);

        if($request->type_id != null){
            $event->type()->detach();
            $type = Type::find($request->type_id);

            $event->type()->attach([$request->type_id]);
        }

        if($request->delivery_id != null){
            $event->delivery()->detach();
            $delivery = Delivery::find($request->delivery_id);

            $event->delivery()->attach([$request->delivery_id]);
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

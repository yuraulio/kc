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
use App\Model\Media;
use App\Model\CategoriesFaqs;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

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
        $event = Event::find($request->event_id);

        $allLessons = Topic::with('lessonsCategory')->find($request->topic_id);

        foreach($allLessons->lessonsCategory as $lesson)
        {
            $find = $event->topic()->wherePivot('event_id', '=', $request->event_id)->wherePivot('topic_id', '=', $request->topic_id)->wherePivot('lesson_id', '=', $lesson['id'])->get();
            if(count($find) == 0 && $request->status1 == true)
            {
                $event->topic()->attach($request->topic_id,['lesson_id' => $lesson['id']]);
                $this->assignEventStatistic($event, $lesson);
            }else{
                $topicLesson_for_detach = $event->topic()->detach($request->topic_id);
                break;
            }

        }
        die();

        $data['request'] = $request->all();
        $data['lesson'] = $allLessons;
        $data['event'] = $event;

        echo json_encode($data);
    }

    public function assignEventStatistic($event, $lesson){
        //dd($lesson);
        $vimeo_id = str_replace("https://vimeo.com/", "", $lesson['vimeo_video']);
        //539259765
        //dd($vimeo_id);
        $allStatistic = $event->statistic()->wherePivot('user_id', 1359)->get();
        //dd($allStatistic);
        $found = false;

        foreach($allStatistic as $statistic){
            $videos = json_decode($statistic->pivot['videos'], true);
            dd($videos);
            $notes = json_decode($statistic->pivot['notes'], true);


            foreach($videos as $videoKey => $video){
                //dd($videoKey);
                if($videoKey == $vimeo_id){
                    $found = true;
                    //dd($videoKey);
                }else{
                    $found = false;
                }

            }

        }
        if($found){
            var_dump($vimeo_id);

            $videos[$vimeo_id] = ['seen' => 0, 'stop_time'=> 0, 'percentMinutes'=> 0, 'lesson_id'=> $lesson['id']];


            $notes[$vimeo_id] = '';

            //dd($notes);

            $event->statistic()->updateExistingPivot($event['id'], [
                'videos' => json_encode($videos),
                'notes' => json_encode($notes)
            ]);
        }

        //$event->statistic()->wherePivot('user_id', 1359)->get();

    }

    public function assignPaymentMethod(Request $request, Event $event)
    {

        if(count($event->users) > 0){
            return response()->json([
                'success' => false,
                'message' => 'Payment Method Cannot Changed'
            ]);
        }

        $event->paymentMethod()->detach();
        $event->paymentMethod()->attach($request->payment_method);

        return response()->json([
            'success' => true,
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
        $instructors = Instructor::with('medias')->where('status', 1)->get()->groupBy('id');

        return view('event.create', ['user' => $user, 'events' => Event::all(), 'categories' => $categories, 'types' => $types, 'delivery' =>$delivery, 'instructors' => $instructors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request, Event $model)
    {
        if($request->published == 'on')
        {
            $published = 1;
        }else
        {
            $published = 0;
        }

        $request->request->add(['published' => $published,'release_date_files' => date('Y-m-d H:i:s', strtotime($request->release_date_files))]);
        $event = $model->create($request->all());

        /*if($event && $request->image_upload){
            $event->createMedia($request->image_upload);
        }*/
        $event->createMedia();
        if($request->syllabus){
            $event->syllabus()->attach(['instructor_id' => $request->syllabus]);
        }
        //dd($request->all());

        $event->createSlug($request->slug);
        $event->createMetas($request->all());

        if($request->category_id != null){
            $category = Category::with('topics')->find($request->category_id);

            $event->category()->attach([$category->id]);

            //assign all topics with lesson

            foreach($category->topics as $topic){
               //dd($topic);
                //$lessons = Topic::with('lessons')->find($topic['id']);
                $lessons = $topic->lessonsCategory;

                foreach($lessons as $lesson){
                    $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id']]);
                }
            }

        }


        if($request->type_id != null){
            //dd($request->type_id);
            $event->type()->sync($request->type_id);
        }

        if($request->delivery != null){
            $event->delivery()->attach($request->delivery);

        }

        $priority = 0;
        foreach($event->category->first()->faqs as $faq){
            $event->faqs()->attach($faq,['priority'=> $priority]);
            $priority += 1;
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

        //$faq = Faq::find(16);
        //dd($faq->category);

        $user = Auth::user();
        $id = $event['id'];
        $event = $event->with('delivery','category', 'summary1', 'benefits', 'ticket', 'city', 'venues', 'topic', 'lessons', 'instructors', 'users', 'partners', 'sections','paymentMethod','slugable','metable', 'medias')->find($id);
        //dd($event['topic']);
        //dd($event->summary1);
        //dd($event->medias->details);
        $categories = Category::all();
        $types = Type::all();
        $partners = Partner::all();

        //dd($event->category->first());
        if($event->category->first() != null){
            $allTopicsByCategory = Category::with('topics')->find($event->category->first()->id);
        }else{
            $allTopicsByCategory = Category::with('topics')->first();
        }
        //dd($allTopicsByCategory);


        //dd($event['lessons']->unique()->groupBy('topic_id'));
        //$allTopicsByCategory1 = $event['lessons']->unique()->groupBy('topic_id');
        $allTopicsByCategory1 = $event['lessons']->groupBy('topic_id');
        //dd($allTopicsByCategory1);
        $data['instructors1'] = Instructor::with('medias')->get()->groupBy('id');
        $instructors = $event['instructors']->groupBy('lesson_id');
        //dd($instructors);
        $topics = $event['topic']->unique()->groupBy('topic_id');
        $unassigned = [];
        //dd($allTopicsByCategory->topics);
        //dd($allTopicsByCategory1);

        foreach($allTopicsByCategory->topics as $key => $allTopics){
            //dd($allTopics);

            $found = false;
            foreach($allTopicsByCategory1 as $key1 => $assig){
                //dd($assig);
                if($allTopics['id'] == $key1){
                    $found = true;
                }
            }
            if(!$found){
                $unassigned[$allTopics['id']] = $allTopics;
                $unassigned[$allTopics['id']]['lessons'] = Topic::with('lessonsCategory')->find($allTopics['id'])->lessonsCategory;
            }
        }
        //dd($unassigned);
       // dd($event['topic']->groupBy('id'));
        //dd($allTopicsByCategory);
        $data['unassigned'] = $unassigned;
        //dd($data['unassigned']);
        $data['event'] = $event;
        //dd($event);
        $data['categories'] = $categories;
        $data['types'] = $types;
        $data['user'] = $user;
        $data['allTopicsByCategory'] = $allTopicsByCategory;
        $data['lessons'] = $allTopicsByCategory1;
        //dd($allTopicsByCategory1);
        $data['instructors'] = $instructors;
        $data['topics'] = $topics;

        $data['slug'] = $event['slugable'];
        $data['metas'] = $event['metable'];

        $data['methods'] = PaymentMethod::where('status',1)->get();
        //dd($data['methods']);
        $data['delivery'] = Delivery::all();
        $data['isInclassCourse'] = $event->is_inclass_course();
        $data['eventFaqs'] = $event->faqs->pluck('id')->toArray();
        $data['eventUsers'] = $event->users->toArray();

        //dd($data['topics']);

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

        if($request->published == 'on')
        {
            $published = 1;
        }else
        {
            $published = 0;
        }

        //dd($request->all());
        $request->request->add(['published' => $published,'release_date_files' => date('Y-m-d H:i:s', strtotime($request->release_date_files))]);
        $ev = $event->update($request->all());

        /*if($request->image_upload != null && $ev){
            $event->updateMedia($request->image_upload);
        }*/

        if($request->syllabus){
            $event->syllabus()->sync($request->syllabus);
        }

        $event->category()->sync([$request->category_id]);

        $event->type()->sync($request->type_id);


        if($request->delivery != null){
            $event->delivery()->detach();

            $event->delivery()->attach($request->delivery);
        }

        return back()->withStatus(__('Event successfully updated.'));
        //return redirect()->route('events.edit',$event->id)->withStatus(__('Event successfully created.'));
        //return redirect()->route('events.index')->withStatus(__('Event successfully updated.'));
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

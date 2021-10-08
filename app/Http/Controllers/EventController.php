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
use App\Model\User;
use App\Model\CategoriesFaqs;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Coupon;

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

        $data['live_courses'] = count(Event::where('published', 1)->where('status', [0,2])->get());
        $data['completed_courses'] = count(Event::where('published', 1)->where('status', '3')->get());
        $data['total_courses'] = count(Event::all());

        return view('event.index', ['events' => $model->with('category', 'type')->orderBy('published', 'asc')->get(), 'user' => $user, 'data' => $data]);
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
        //dd($request->topic_id);
        $event = Event::find($request->event_id);
        //dd($event);

        $allLessons = Topic::with('lessonsCategory')->find($request->topic_id);
        //dd($allLessons);

        foreach($allLessons->lessonsCategory as $key => $lesson)
        {
            //var_dump($lesson['id']);
            $find = $event->topic()->wherePivot('topic_id', $request->topic_id)->wherePivot('lesson_id', $lesson['id'])->first();
            
            if($find == null && $request->status1 == '0')
            {
                $a = $event->topic()->attach($request->topic_id,['lesson_id' => $lesson['id']]);

            }else{
                $topicLesson_for_detach = $event->topic()->detach($request->topic_id);
            }

        }
        if($request->status1 == '1'){
            $status1 = '0';
        }else{
            $status1 = '1';
        }
        //dd($request->status1);
        $data['request']['status1'] = $status1;
        $data['request']['topic_id'] = $request->topic_id;
        $data['request']['event_id'] = $request->event_id;

        $data['lesson'] = $allLessons;
        $data['event'] = $event;

        echo json_encode($data);
    }

    public function assignCoupon(Request $request, Event $event, Coupon $coupon){

        if(!$request->status){
            $event->coupons()->detach($coupon->id);
            $event->coupons()->attach($coupon->id);
        }else{
            $event->coupons()->detach($coupon->id);
        }
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
            $published_at = date("Y-m-d");
        }else
        {
            $published = 0;
            $published_at = null;
        }

        $launchDate = date('Y-m-d',strtotime($request->launch_date));

        $request->request->add(['published' => $published, 'published_at' => $published_at, 'release_date_files' => date('Y-m-d', strtotime($request->release_date_files)),'launch_date'=>$launchDate]);
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
                    $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'priority'=>$lesson->pivot->priority]);
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
        //$event = $event->with('coupons','delivery','category', 'summary1', 'benefits', 'ticket', 'city', 'venues', 'topic', 'lessons', 'instructors', 'users', 'partners', 'sections','paymentMethod','slugable','metable', 'medias', 'sectionVideos');
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
        //dd($allTopicsByCategory1[185]);
        $data['instructors1'] = Instructor::with('medias')->where('status', 1)->get()->groupBy('id');
        $instructors = $event['instructors']->groupBy('lesson_id');
        //dd($instructors);
        $topics = $event['topic']->unique()->groupBy('topic_id');
        $unassigned = [];
        //dd($allTopicsByCategory->topics[1]);
        //dd($allTopicsByCategory1);

        foreach($allTopicsByCategory->topics as $key => $allTopics){

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
        $data['coupons'] = Coupon::all();

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
            $published_at = !$event->published_at ? date("Y-m-d") : $event->published_at;
        }else
        {
            $published = 0;
            $published_at = $event->published_at;
        }

        //dd($request->all());
       //dd($request->release_date_files);
       
        $launchDate = date('Y-m-d',strtotime($request->launch_date));

        $request->request->add(['published' => $published, 'published_at' => $published_at, 'release_date_files' => date('Y-m-d', strtotime($request->release_date_files)),'launch_date'=>$launchDate]);
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

        if($request->video != null){
            $event->video()->attach($request->video);
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

    public function fetchAllEvents(){
        $data['events'] = Event::with('coupons')->select('id', 'title')->get()->groupby('id')->toArray();
        //dd($data['events']);

        return $data['events'];

    }

    public function elearning_infos_user_table(Request $request){
        $ids = [];
        $event = Event::where('title', $request->event)->first();
        $num = $request->page;
        if($num != 0){
            $str = $num.'0';
            $num = (int)$str;
        }

        $count = 0;

        foreach(array_slice($request->ids, $num) as $key => $item){
            $user = User::find($item);
            //$exam = $event->exam_result()->get();


            //dd($event->video_seen($user));
            $ids[$count]['id'] = $user['id'];
            $ids[$count]['video_seen'] = $event->video_seen($user);
            if($count == 9){
                break;
            }
            $count++;

        }

        echo json_encode($ids);


    }

    public function cloneEvent(Request $request, Event $event){

        $newEvent = $event->replicate();
        

        $newEvent->published = false;
        $newEvent->title = $newEvent->title . ' - clone';
        $newEvent->release_date_files = null;
        $newEvent->published_at = null;
        $newEvent->launch_date = null;
        $newEvent->enroll = false;
        $newEvent->push();

        $newEvent->createMedia();
        $newEvent->createSlug($newEvent->title);
        //$event->createMetas($request->all());
        //dd($event->lessons);
        $event->load('category','faqs','sectionVideos','type','summary1','delivery','ticket','city','sections','venues','syllabus','benefits');

        foreach ($event->getRelations() as $relationName => $values){
            if($relationName == 'summary1' || $relationName == 'benefits' || $relationName == 'sections'){
                $newValues = [];
                foreach($values as $value){

    
                    $valuee = $value->replicate();
                    $valuee->push();

                    if($value->medias){
                        $valuee->medias()->delete();
                        //$valuee->createMedia();
                        
                        $medias = $value->medias->replicate();
                        $medias->push();
                        //dd($medias);
                        $valuee->medias()->save($medias);
                    }
                    

                    $newValues[] = $valuee; 

                }
                
                $newValues = collect($newValues);
                $newEvent->{$relationName}()->detach();

                foreach($newValues as $value){
                    $newEvent->{$relationName}()->attach($value);
                }

            }else{
                $newEvent->{$relationName}()->sync($values);
            }
 
        }


        foreach($event->lessons as $lesson){
            if(!$lesson->pivot){
                continue;
            }

            $newEvent->lessons()->attach($lesson->pivot->lesson_id,['topic_id'=>$lesson->pivot->topic_id, 'date'=>$lesson->pivot->date,
                'time_starts'=>$lesson->pivot->time_starts,'time_ends'=>$lesson->pivot->time_ends, 'duration' => $lesson->pivot->duration, 
                'room' => $lesson->pivot->room,'instructor_id' => $lesson->pivot->instructor_id, 'priority' => $lesson->pivot->priority]);
        }

        
        //$newEvent->lessons()->save($event->lessons());

        $newEvent->createMetas();
        $newEvent->metable->meta_title = $event->metable ? $event->metable->meta_title : '';
        $newEvent->metable->meta_keywords = $event->metable ? $event->metable->meta_keywords : '';
        $newEvent->metable->meta_description = $event->metable ? $event->metable->meta_description : '';
        $newEvent->metable->save();

        return redirect()->route('events.edit',$newEvent->id)->withStatus(__('Event successfully cloned.'));
    }
}

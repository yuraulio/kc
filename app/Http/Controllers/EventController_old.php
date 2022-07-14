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
use App\Model\Section;
use App\Model\City;
use App\Jobs\SendMaiWaitingList;
use Artisan;
use Storage;
use App\Model\Dropbox;
use App\Model\EventInfo;
use App\Jobs\EnrollStudentsToElearningEvents;

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

        return view('event.index', ['events' => $model->with('category', 'type','delivery')->orderBy('published', 'asc')->get(), 'user' => $user, 'data' => $data]);
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

        /*if(count($event->users) > 0){
            return response()->json([
                'success' => false,
                'message' => 'Payment Method Cannot Changed'
            ]);
        }*/

        $event->paymentMethod()->detach();
        $event->paymentMethod()->attach($request->payment_method);

        $info = $event->event_info()->first();

        if($info){
            $info->update([
                'course_payment_method' => 'paid'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment Method Changed'
        ]);

    }

    public function removePaymentMethod(Request $request, Event $event)
    {

        /*if(count($event->users) > 0){
            return response()->json([
                'success' => false,
                'message' => 'Payment Method Cannot Changed'
            ]);
        }*/


        if(count($event->paymentMethod()->get()) != 0){
            $event->paymentMethod()->detach();

            $info = $event->event_info()->first();

            if($info){
                $info->update([
                    'course_payment_method' => 'free'
                ]);
            }



            return response()->json([
                'success' => true,
                'message' => 'Payment Method Removed'
            ]);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Nothing to remove'
            ]);
        }



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
        $cities = City::all();
        $partners = Partner::all();

        return view('event.create', ['user' => $user, 'events' => Event::all(), 'categories' => $categories, 'types' => $types, 'delivery' =>$delivery,
                                        'instructors' => $instructors, 'cities' => $cities,'partners'=>$partners]);
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

        $launchDate = $request->launch_date ? date('Y-m-d',strtotime($request->launch_date)) : $published_at;

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

        $event->createSlug($request->slug ? $request->slug : $request->title);
        $event->createMetas($request->all());


        if($request->category_id != null){
            $category = Category::with('topics')->find($request->category_id);

            $event->category()->attach([$category->id]);

            //assign all topics with lesson

            foreach($category->topics as $topic){
               //dd($topic);
                //$lessons = Topic::with('lessons')->find($topic['id']);
                //$lessons = $topic->lessonsCategory;
                $lessons = $topic->lessonsCategory()->wherePivot('category_id',$category->id)->get();

                foreach($lessons as $lesson){

                    $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'priority'=>$lesson->pivot->priority]);

                }
            }

        }

        $event->city()->sync([$request->city_id]);

        $event->partners()->detach();
        foreach((array) $request->partner_id as $partner_id){
            $event->partners()->attach($partner_id);
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

        $data['sumOfStudents'] = count($event->users);
        $data['totalRevenue'] = $event->transactions->sum('amount');

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
        $topics = $event['allTopics']->unique()->groupBy('topic_id');
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
                //$unassigned[$allTopics['id']]['lessons'] =Topic::with('lessonsCategory')->find($allTopics['id'])->lessonsCategory()->wherePivot('category_id',219)->get();


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
        $data['cities'] = City::all();
        $data['partners'] = Partner::all();
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
        $data['eventUsers'] = $event->users;//$event->users->toArray();
        $data['eventWaitingUsers'] = $event->waitingList()->with('user')->get();
        $data['coupons'] = Coupon::all();
        $data['activeMembers'] = 0;
        $data['sections'] = $event->sections->groupBy('section');

        $today = strtotime(date('Y-m-d'));
        if(!$data['isInclassCourse']){

            foreach($data['eventUsers'] as $activeUser){
                if(!$activeUser['pivot']['expiration'] || $today <= strtotime($activeUser['pivot']['expiration'])){
                    $data['activeMembers'] += 1;
                }
            }


        }

        $data['folders'] = [];
        $li = Storage::disk('dropbox');
        if($li) {

            $folders = $li->listContents();

            foreach ($folders as $key => $row) {

                if($row['type'] == 'dir') :
                    $data['folders'][$row['basename']] = $row['basename'];
                endif;
            }

            $data['already_assign'] = $event->dropbox;

        }


        //dd($data['topics']);

        return view('event.edit', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit_new(Event $event)
    {

        //$faq = Faq::find(16);
        //dd($faq->category);

        $data['sumOfStudents'] = count($event->users);
        $data['totalRevenue'] = $event->transactions->sum('amount');

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
        $topics = $event['allTopics']->unique()->groupBy('topic_id');
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
                //$unassigned[$allTopics['id']]['lessons'] =Topic::with('lessonsCategory')->find($allTopics['id'])->lessonsCategory()->wherePivot('category_id',219)->get();


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
        $data['cities'] = City::all();
        $data['partners'] = Partner::all();
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
        $data['delivery'] = Delivery::all();
        $data['isInclassCourse'] = $event->is_inclass_course();
        $data['eventFaqs'] = $event->faqs->pluck('id')->toArray();
        $data['eventUsers'] = $event->users;//$event->users->toArray();
        $data['eventWaitingUsers'] = $event->waitingList()->with('user')->get();
        $data['coupons'] = Coupon::all();
        $data['activeMembers'] = 0;
        $data['sections'] = $event->sections->groupBy('section');
        $data['info'] = !empty($event->event_info()) ? $event->event_info() : null;


        //dd($data['info']);

        //if elearning course (id = 143)
        $elearning_events = Delivery::with('event:id,title')->where('id',143)->whereHas('event', function ($query) {
            return $query->where('published', true);
        })->first()->toArray()['event'];


        $data['elearning_events'] = $elearning_events;


        $today = strtotime(date('Y-m-d'));
        if(!$data['isInclassCourse']){

            foreach($data['eventUsers'] as $activeUser){
                if(!$activeUser['pivot']['expiration'] || $today <= strtotime($activeUser['pivot']['expiration'])){
                    $data['activeMembers'] += 1;
                }
            }


        }

        $data['folders'] = [];
        $li = Storage::disk('dropbox');
        if($li) {

            $folders = $li->listContents();

            foreach ($folders as $key => $row) {

                if($row['type'] == 'dir') :
                    $data['folders'][$row['basename']] = $row['basename'];
                endif;
            }

            $data['already_assign'] = $event->dropbox;

        }


        //dd($data['topics']);
        return view('event.edit_new', $data);
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

       $launchDate = $request->launch_date ? date('Y-m-d',strtotime($request->launch_date)) : $published_at;

        $request->request->add(['published' => $published, 'published_at' => $published_at,
            'release_date_files' => date('Y-m-d', strtotime($request->release_date_files)),
            'launch_date'=>$launchDate,'title'=>$request->eventTitle]);
        $ev = $event->update($request->all());

        /*if($request->image_upload != null && $ev){
            $event->updateMedia($request->image_upload);
        }*/

        if($request->syllabus){
            $event->syllabus()->sync($request->syllabus);
        }

        $event->category()->sync([$request->category_id]);
        $event->city()->sync([$request->city_id]);


        $event->partners()->detach();
        foreach((array) $request->partner_id as $partner_id){
            $event->partners()->attach($partner_id);
        }

        if($request->folder_name != null){
            $exist_dropbox = Dropbox::where('folder_name', $request->folder_name)->first();
            if($exist_dropbox){
                $event->dropbox()->sync([$exist_dropbox->id]);
            }

        }

        if($request->category_id != $request->oldCategory){
            $category = Category::with('topics')->find($request->category_id);


            if($category){

                $event->topic()->detach();
                //assign all topics with lesson

                foreach($category->topics as $topic){
                   //dd($topic);
                    //$lessons = Topic::with('lessons')->find($topic['id']);
                    //$lessons = $topic->lessonsCategory;
                    $lessons = $topic->lessonsCategory()->wherePivot('category_id',$category->id)->get();

                    foreach($lessons as $lesson){
                        $event->topic()->attach($topic['id'],['lesson_id' => $lesson['id'],'priority'=>$lesson->pivot->priority]);
                    }
                }
            }

        }


        $event->type()->sync($request->type_id);


        if($request->delivery != null){
            $event->delivery()->detach();

            $event->delivery()->attach($request->delivery);
        }

        if($request->video != null){
            $event->video()->attach($request->video);
        }
        foreach((array) $request->sections as $key => $sectionn){

            if( $section = Section::find($sectionn['id']) ){
                $section->tab_title = $sectionn['tab_title'];
                $section->title = $sectionn['title'];
                $section->visible = (isset($sectionn['visible']) && $sectionn['visible'] == 'on') ? true : false;
                $section->save();
            }else{

                $section = new Section;

                $section->section = $key;
                $section->tab_title = $sectionn['tab_title'];
                $section->title = $sectionn['title'];
                $section->visible = (isset($sectionn['visible']) && $sectionn['visible'] == 'on') ? true : false;
                $section->save();

                $event->sections()->save($section);

            }


        }

        if($event->status == 0 && $request->old_status == 5){
            //SendMaiWaitingList::dispatchAfterResponse($event->id);
            dispatch((new SendMaiWaitingList($event->id))->delay(now()->addSeconds(3)));

        }

        return back()->withStatus(__('Event successfully updated.'));
        //return redirect()->route('events.edit',$event->id)->withStatus(__('Event successfully created.'));
        //return redirect()->route('events.index')->withStatus(__('Event successfully updated.'));
    }

    public function calculateTotalHours($id)
    {
        $event = Event::find($id);

        $totalHours = $event->getTotalHours();




        return response()->json([
            'success' => true,
            'message' => 'Calculate successfully total hours for this event!',
            'data'  => $totalHours
        ]);

    }





    public function prepareInfo($requestData, $status, $deliveryId, $partner, $syllabus, $cityId, $event)
    {
        $data = [];

        //$delivery = Delivery::find($delivery)['name'];
        $city = City::find($cityId);

        $data['course_status'] = $status;
        $data['course_delivery'] = $deliveryId;
        $data['course_hours_text'] = $requestData['hours']['text'];
        $data['course_hours_hour'] = $requestData['hours']['hour'];

        $data['course_partner'] = $partner;
        $data['course_manager'] = ($syllabus != null) ? true : false;


        // Delivery Inclass City
        if($event->is_inclass_course()){
            $data['course_inclass_absences'] = $requestData['delivery']['inclass']['absences'];
            $data['course_inclass_city'] = ($city) ? $city->name : null;
            $data['course_inclass_city_icon'] = json_encode($requestData['delivery']['inclass']['city']['icon']);
        }else if($event->is_elearning_course()){
            $visible_loaded_data = isset($requestData['delivery']['elearning']['visible']) ? $requestData['delivery']['elearning']['visible'] : null;
            $data['course_elearning_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));
            $data['course_elearning_icon'] = $requestData['delivery']['elearning']['icon'] != null ?  json_encode($requestData['delivery']['elearning']['icon']) : null;
            $data['course_elearning_expiration'] = (isset($requestData['delivery']['elearning']['expiration']) && $requestData['delivery']['elearning']['expiration'] != null) ? $requestData['delivery']['elearning']['expiration'] : null;
            $data['course_elearning_text'] = (isset($requestData['delivery']['elearning']['text']) && $requestData['delivery']['elearning']['text'] != null) ? $requestData['delivery']['elearning']['text'] : null;
        }


        /////////////

        // Course
        if(isset($requestData['hours']['visible'])){

            $visible_loaded_data = $requestData['hours']['visible'];
            $data['course_hours_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

        }else{
            $data['course_hours_visible'] = json_encode($this->prepareVisibleData());
        }


        $data['course_hours_icon'] = json_encode($requestData['hours']['icon']);
        /////////////////


        // Language
        $data['course_language'] = $requestData['language']['text'];
        if(isset($requestData['language']['visible'])){

            $visible_loaded_data = $requestData['language']['visible'];
            $data['course_language_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

        }else{
            $data['course_language_visible'] = json_encode($this->prepareVisibleData());
        }

        $data['course_language_icon'] = json_encode($requestData['language']['icon']);
        ///////////////

        // Partner

        $data['course_partner_icon'] = json_encode($requestData['partner']['icon']);



        //////////////////////////

        if(isset($requestData['delivery']['inclass'])){
            $dates = [];
            $days = [];
            $times = [];


            // Dates
            if(isset($requestData['delivery']['inclass']['dates'])){
                $dates['text'] = $requestData['delivery']['inclass']['dates']['text'];

                if(isset($requestData['delivery']['inclass']['dates']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['dates']['visible'];
                    $dates['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $dates['visible'] = $this->prepareVisibleData();
                }

                $dates['icon'] = $requestData['delivery']['inclass']['dates']['icon'];


            }
            $data['course_inclass_dates'] = json_encode($dates);

            // Days
            if(isset($requestData['delivery']['inclass']['day'])){
                $days['text'] = $requestData['delivery']['inclass']['day']['text'];

                if(isset($requestData['delivery']['inclass']['day']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['day']['visible'];
                    $days['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $days['visible'] = $this->prepareVisibleData();
                }

                $days['icon'] = $requestData['delivery']['inclass']['day']['icon'];
            }
            $data['course_inclass_days'] = json_encode($days);

            // Times
            if(isset($requestData['delivery']['inclass']['times'])){
                $times['text'] = $requestData['delivery']['inclass']['times']['text'];

                if(isset($requestData['delivery']['inclass']['times']['visible'])){
                    $visible_loaded_data = $requestData['delivery']['inclass']['times']['visible'];
                    $times['visible'] = $this->prepareVisibleData($visible_loaded_data);
                }else{
                    $times['visible'] = $this->prepareVisibleData();
                }


                $times['icon'] = $requestData['delivery']['inclass']['times']['icon'];
            }
            $data['course_inclass_times'] = json_encode($times);
        }

        // Manager

        $data['course_manager_icon'] = json_encode($requestData['manager']['icon']);

        //////////////////////////


        // Free E-learning
        if(isset($requestData['free_courses']['list'])){
            $data['course_elearning_access'] = json_encode($requestData['free_courses']['list']);
        }else{
            $data['course_elearning_access'] = null;
        }

        $data['course_elearning_access_icon'] = json_encode($requestData['free_courses']['icon']);

        // Payment

        if(isset($requestData['payment'])){
            if(isset($requestData['payment']['paid'])){
                $data['course_payment_method'] = 'paid';
            }else{
                $data['course_payment_method'] = 'free';
            }
        }else{
            $data['course_payment_method'] = 'free';
        }

        if(isset($requestData['payment'])){
            $data['course_payment_icon'] = json_encode($requestData['payment']['icon']);
        }



        // Award
        if(isset($requestData['awards'])){

            $data['course_awards'] = true;
            $data['course_awards_text'] = $requestData['awards']['text'];

        }else{
            $data['course_awards'] = false;
            $data['course_awards_text'] = null;
        }

        $data['course_awards_icon'] = json_encode($requestData['awards']['icon']);



        // Certificate
        if(isset($requestData['certificate'])){
            $data['course_certification_name_success'] = $requestData['certificate']['success_text'];
            $data['course_certification_name_failure'] = $requestData['certificate']['failure_text'];
            $data['course_certification_type'] = $requestData['certificate']['type'];

            if(isset($requestData['certificate']['visible'])){

                $visible_loaded_data = $requestData['certificate']['visible'];
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

            }else{
                $data['course_certificate_visible'] = json_encode($this->prepareVisibleData());
            }

            //dd($requestData['certificate']);

            $data['course_certificate_icon'] = json_encode($requestData['certificate']['icon']);
        }



        // Students
        if(isset($requestData['students'])){
            $data['course_students_number'] = $requestData['students']['count_start'];
            $data['course_students_text'] = $requestData['students']['text'];

            if(isset($requestData['students']['visible'])){

                $visible_loaded_data = $requestData['students']['visible'];
                $data['course_students_visible'] = json_encode($this->prepareVisibleData($visible_loaded_data));

            }else{
                $data['course_students_visible'] = json_encode($this->prepareVisibleData());
            }

            $data['course_students_icon'] = json_encode($requestData['students']['icon']);
        }


        return $data;

    }

    public function prepareVisibleData($data = false)
    {
        $visible_returned_data = ['landing' => 0, 'home' => 0, 'list' => 0, 'invoice' => 0, 'emails' => 0];

        if(!$data){
            return $visible_returned_data;
        }

        foreach($data as $key => $item){
            if(in_array($item,$data)){
                $visible_returned_data[$key] = 1;
            }
        }

        return $visible_returned_data;
    }
    public function updateEventInfo($event_info, $event_id)
    {
        //dd($event_info);
        $event = Event::find($event_id);

        //dd($event->paymentMethod);

        $info = $event->event_info();


        if($info == null || $info == '[]'){
            $infos = new EventInfo();
            $infos->event_id = $event->id;
        }else{
            $infos = EventInfo::where('event_id', $event_id)->first();
        }


        $infos->course_status = $event_info['course_status'];

        $infos->course_hours = $event_info['course_hours_hour'];
        $infos->course_hours_text = $event_info['course_hours_text'];
        $infos->course_hours_visible = $event_info['course_hours_visible'];
        $infos->course_hours_icon = $event_info['course_hours_icon'];

        $infos->course_language = $event_info['course_language'];
        $infos->course_language_visible = $event_info['course_language_visible'];
        $infos->course_language_icon = $event_info['course_language_icon'];

        $infos->course_partner = $event_info['course_partner'];
        $infos->course_partner_icon = $event_info['course_partner_icon'];

        $infos->course_manager = $event_info['course_manager'];
        $infos->course_manager_icon = $event_info['course_manager_icon'];

        $infos->course_delivery = $event_info['course_delivery'];


        if($event->is_inclass_course()){
            $infos->course_inclass_absences = $event_info['course_inclass_absences'];

            $infos->course_inclass_city = $event_info['course_inclass_city'];
            $infos->course_inclass_city_icon = $event_info['course_inclass_city_icon'];
            $infos->course_inclass_dates = $event_info['course_inclass_dates'];
            $infos->course_inclass_times = $event_info['course_inclass_times'];
            $infos->course_inclass_days = $event_info['course_inclass_days'];
        }else if($event->is_elearning_course()){
            $infos->course_elearning_visible = $event_info['course_elearning_visible'];
            $infos->course_elearning_icon = $event_info['course_elearning_icon'];
            $infos->course_elearning_expiration = $event_info['course_elearning_expiration'];
            $infos->course_elearning_text = $event_info['course_elearning_text'];
        }


        if($event->paymentMethod()->first()){
            $infos->course_payment_method = (isset($event->paymentMethod) && count($event->paymentMethod) != 0) ? 'paid' : 'free';
            $infos->course_payment_icon = $event_info['course_payment_icon'];
        }


        $infos->course_awards = (isset($event_info['course_awards_text']) && $event_info['course_awards_text'] != "") ? true : false;
        $infos->course_awards_text = $event_info['course_awards_text'];
        $infos->course_awards_icon = $event_info['course_awards_icon'];

        $infos->course_certification_name_success = $event_info['course_certification_name_success'];
        $infos->course_certification_name_failure = $event_info['course_certification_name_failure'];
        $infos->course_certification_type = $event_info['course_certification_type'];
        $infos->course_certification_visible = $event_info['course_certificate_visible'];
        $infos->course_certification_icon = $event_info['course_certificate_icon'];

        $infos->course_students_number = $event_info['course_students_number'];
        $infos->course_students_text = $event_info['course_students_text'];
        $infos->course_students_visible = $event_info['course_students_visible'];
        $infos->course_students_icon = $event_info['course_students_icon'];

        $infos->course_elearning_access = $event_info['course_elearning_access'];
        $infos->course_elearning_access_icon = $event_info['course_elearning_access_icon'];

        if($info == null || $info == '[]'){
            $infos->save();
        }else{
            $infos->update();
        }

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
        $event->load('category','faqs','sectionVideos','type','summary1','delivery','ticket','city','sections','venues','syllabus','benefits','paymentMethod','dropbox');

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

        foreach($newEvent->ticket as $ticket){
            $ticket->pivot->active = false;
            $ticket->pivot->save();
        }

        return redirect()->route('events.edit',$newEvent->id)->withStatus(__('Event successfully cloned.'));
    }


    public function deleteExplainerVideo(Request $request, $eventId, $explainerVideo){

        $event = Event::find($eventId);

        $detach = $event->sectionVideos()->where('id',$explainerVideo)->detach();

        if($detach){
            return response()->json([
                'success' => true,
                'message' => 'Removed successfully',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Removed failed'
            ]);
        }


    }

}

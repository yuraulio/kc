<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Model\Media;
use App\Model\Activation;
use App\Model\Event;
use App\Model\Role;
use App\Model\Instructor;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Traits\MediaTrait;
use App\Model\Invoice;
use Laravel\Cashier\Subscription;
use App\Model\CookiesSMS;
use App\Model\Plan;
use App\Model\CartCache;
use App\Model\ExamResult;
use App\Model\OauthAccessToken;
use App\Model\Transaction;

class User extends Authenticatable
{

    use Notifiable, HasApiTokens, MediaTrait, Billable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'company',
        'firstname',
        'lastname',
        'birthday',
        'username',
        'mobile',
        'telephone',
        'address',
        'address_num',
        'postcode',
        'city',
        'job_title',
        'partner_id',
        'kc_id',
        'student_type_id',
        'afm',
        'nationality',
        'comments',
        'consent',
        'terms',
        'receipt_details',
        'invoice_details',
        'oexams',
        'country_code',
        'stripe_ids',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function scopeSearchUsers($query, $search_term)
    {

        $query->where(function ($query) use ($search_term) {
            $search_term_str = '%'.implode("%", explode(" ", $search_term)).'%';
            $query->where('email', 'like', $search_term_str)
                ->orWhere('firstname', 'like', $search_term_str)
                ->orWhere('lastname', 'like', $search_term_str);
        });



        return $query->select('id','firstname','lastname','email')->get();
    }


   /**
     * Get the role of the user
     *
     * @return \App\Model\Role
     */
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'instructors_user')->with('event');
    }

    public function statusAccount()
    {
        return $this->hasOne(Activation::class);
    }

    /**
     * Get the path to the profile picture
     *
     * @return string
     */
    public function profilePicture()
    {
        if ($this->picture) {
            return "/{$this->picture}";
        }

         return 'http://i.pravatar.cc/200';
    }

    /**
     * Check if the user has admin role
     *
     * @return boolean
     */
    public function isAdmin()
    {

        /*dd($this->role);
        //dd($this->id);
        $role = DB::table('role_users')->where('user_id', $this->id)->first();
        //dd($role->role_id);
        $role = Role::where('id', $role->role_id)->first();
        return $role['id'] == 1;*/

        return $this->role->where('id',1)->first() ? true : false;
    }

    public function isAdministrator()
    {

        /*dd($this->role);
        //dd($this->id);
        $role = DB::table('role_users')->where('user_id', $this->id)->first();
        //dd($role->role_id);
        $role = Role::where('id', $role->role_id)->first();
        return $role['id'] == 1;*/

        return $this->role->where('id',2)->first() ? true : false;
    }

    /**
     * Check if the user has creator role
     *
     * @return boolean
     */
    public function isCreator()
    {
        return $this->role_id == 2;
    }

    /**
     * Check if the user has user role
     *
     * @return boolean
     */
    public function isMember()
    {
        return $this->role_id == 3;
    }

    /**
     * Get the user's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'expiration','comment','payment_method')->with('summary1','category','slugable')->wherePivot('paid',true);
    }


    public function events_for_user_list()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('event_id','paid', 'expiration','comment','payment_method');
    }

    public function subscriptionEvents()
    {
        return $this->belongsToMany(Event::class, 'subscription_user_event')->withPivot('expiration','event_id','payment_method');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket')->select('tickets.*','event_id','ticket_id')->withPivot('ticket_id','event_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public function statistic()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')->withPivot('id','videos','lastVideoSeen', 'notes', 'event_id');
    }

    public function statisticGroupByEvent()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')->select('user_id','event_id')->withPivot('id','videos','lastVideoSeen', 'notes', 'event_id');
    }

    public function transactions(){
        return $this->morphToMany(Transaction::class,'transactionable');
    }

/*
 public function transactionss(){
        return $this->morphToMany(Transaction::class,'transactionable')->doesntHave('subscription')->with('subscription','event','event.delivery')->orderBy('created_at','desc');
    }
*/

    /*public function examAccess($successPer = 0.8, $event){
        $seenPercent =  $this->videosSeenPercent($event);
        $studentsEx = [1353,1866,1753,1882,1913,1923];

        if(in_array($this->user_id, $studentsEx)){
            return true;
        }

        //$event = EventStudent::where('student_id',$this->user_id)->where('event_id',$this->event_id)->first()->created_at;
        $event = $this->events->where('id',$event)->first();
        if(!$event->created_at || $event->comment == 'enroll' || $event->view_tpl == 'elearning_free'){
             return false;
        }


        return $seenPercent >=  $successPer;

    }

    public function videosSeen($event){
        if(!($videos = $this->statistic->where('id',$event)->first())){
            return 0;
        }

        $videos = json_decode($videos->pivot->videos,true);
        $sumSeen = 0;

        if(count($videos) == 0 ){
            return 0;
        }

        foreach($videos as $video){
            if($video['seen']){
                $sumSeen += 1;
            }

        }

        return $sumSeen .' / '. count($videos);
    }

    public function videosSeenPercent($event){

        if(!($videos = $this->statistic->where('id',$event)->first())){
            return 0;
        }

        $videos = json_decode($videos->pivot->videos,true);
        $sumSeen = 0;

        if(count($videos) == 0 ){
            return 0;
        }

        foreach($videos as $video){
            if($video['seen']){
                $sumSeen += 1;
            }

        }

        return round ($sumSeen / count($videos),2) * 100 ;

    }*/


    public function invoices(){
        return $this->morphToMany(Invoice::class, 'invoiceable');
        //return $this->morphToMany(Invoice::class, 'invoiceable')->whereHasMorph('event',[Event::class]);
    }

    /*public function subscriptions(){
        return $this->belongsToMany(Subscription::class,'subscription_user_event');
    }*/

    public function eventSubscriptions(){
        return $this->belongsToMany(Subscription::class,'subscription_user_event')->with('event');
    }

    public function cookiesSMS(){
        return $this->hasMany(CookiesSMS::class);
    }

    public function checkUserSubscriptionByEvent(){

        $plans = Plan::has('events')->get();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        if(count($plans) == 0){
            return [false,[]];
        }

        foreach($plans as $key => $plan){

            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(),$nonEventPlans);
            if(!key_exists($key,$categoryPlans)){
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan->categories()->pluck('category_id')->toArray(),$categoryPlans[$key]);

            foreach($plan->noEvents as $event){
                $category = $event->categories->where('parent_id',45)->first()->id;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach($this->events as $event){

            $categoryIndex = false;
            $categoryIndexDelete = 0;
            //dd($event);
            $events[] = $event->pivot->event_id;
            //dd($event);
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $eventCategories[] = $category;

            $categoryEvents[$category][] = $event->pivot->event_id;

            foreach($categoryPlans as $key => $categoryPlan){
                $categoryIndexDelete += 1;
                if(!in_array($category,$categoryPlan)){
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if(count($categoryPlans[$key]) == 0){
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }

        }

        foreach($this->subscriptionEvents as $event){

            $categoryIndex = false;
            $categoryIndexDelete = 0;
            //dd($event);
            $events[] = $event->pivot->event_id;
            //dd($event);
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $eventCategories[] = $category;

            $categoryEvents[$category][] = $event->pivot->event_id;

            foreach($categoryPlans as $key => $categoryPlan){
                $categoryIndexDelete += 1;
                if(!in_array($category,$categoryPlan)){
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if(count($categoryPlans[$key]) == 0){
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }

        }

        if(count(array_diff($events, $nonEventPlans)) == 0){
            return [false,[]];
        }

        foreach($nonEventsCategory as $key => $plan){
            $planIndex = 0;
            foreach($plan as $category => $event){



                if(key_exists($category,$categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0){
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }



            if($planIndex == count($categoryPlans[$key])){
                unset($plans[$key]);
            }


        }

         /************
         * NEWW
         *************/
        foreach($plans as $key1 => $plan){
            $index = 0;

            foreach($plan->categories as $key => $planCat){
                if(!in_array($planCat->id, $eventCategories)){
                    $index += 1;
                }
            }

            if($index === count($plan->categories)){

                unset($plans[$key1]);
            }

        }

        if(count($plans) == 0){
            return [false,[]];
        }
        /************
         * NEWW
         *************/

        foreach($plans as $plan){
            $eventPlans = array_merge($plan->events()->pluck('event_id')->toArray(),$eventPlans);
        }


        $eventPlans = array_diff($eventPlans,$events);
        $eventPlans = array_diff($eventPlans,$this->subscriptionEvents->pluck('event_id')->toArray());

        return [true,$eventPlans];

    }

    public function checkUserSubscriptionByEventId($eventId){

        $plans = Plan::has('events')->get();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        if(count($plans) == 0){
            return false;
        }

        foreach($plans as $key => $plan){

            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(),$nonEventPlans);
            if(!key_exists($key,$categoryPlans)){
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan->categories()->pluck('category_id')->toArray(),$categoryPlans[$key]);

            foreach($plan->noEvents as $event){
                $category = $event->category->first() ? $event->category->first()->id : -1;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach($this->events as $event){

            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;

            $category = $event->category->first() ? $event->category->first()->id : -1;

            $categoryEvents[$category][] = $event->pivot->event_id;
            $eventCategories[] = $category;

            foreach($categoryPlans as $key => $categoryPlan){

                $categoryIndexDelete += 1;
                if(!in_array($category,$categoryPlan)){

                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if(count($categoryPlans[$key]) == 0){
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }

        }

        if(count(array_diff($events, $nonEventPlans)) == 0){
            return false;
        }

        foreach($nonEventsCategory as $key => $plan){
            $planIndex = 0;
            foreach($plan as $category => $event){



                if(key_exists($category,$categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0){
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }



            if($planIndex == count($categoryPlans[$key])){
                unset($plans[$key]);
            }

        }

         /************
         * NEWW
         *************/
        foreach($plans as $key => $plan){
            $index = 0;

            foreach($plan->categories as $key2 => $planCat){
                if(!in_array($planCat->id, $eventCategories)){
                    $index += 1;
                }
            }

            if($index === count($plan->categories)){
                unset($plans[$key]);
            }

        }
        if(count($plans) == 0){
            return false;
        }
        /************
         * NEWW
         *************/

        foreach($plans as $plan){
            $eventPlans = array_merge($plan->events()->pluck('event_id')->toArray(),$eventPlans);
        }

        return in_array($eventId,$eventPlans);

    }


    public function checkUserPlans($plans){

        //$plans = Plan::all();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        if(count($plans) == 0){
            return [];
        }

        foreach($plans as $key => $plan){

            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(),$nonEventPlans);
            if(!key_exists($key,$categoryPlans)){
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan->categories()->pluck('category_id')->toArray(),$categoryPlans[$key]);

            foreach($plan->noEvents as $event){
                $category = $event->category->first() ? $event->category->first()->id : -1;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach($this->events as $event){

            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $categoryEvents[$category][] = $event->pivot->event_id;
            $eventCategories[] = $category;

            foreach($categoryPlans as $key => $categoryPlan){

                $categoryIndexDelete += 1;
                if(!in_array($category,$categoryPlan)){

                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if(count($categoryPlans[$key]) == 0){
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }

        }

        if(count(array_diff($events, $nonEventPlans)) == 0){
            return [];
        }

        foreach($nonEventsCategory as $key => $plan){
            $planIndex = 0;
            foreach($plan as $category => $event){



                if(key_exists($category,$categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0){
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }



            if($planIndex == count($categoryPlans[$key])){
                unset($plans[$key]);
            }

        }

         /************
         * NEWW
         *************/
        foreach($plans as $key => $plan){
            $index = 0;

            foreach($plan->categories as $key2 => $planCat){
                if(!in_array($planCat->id, $eventCategories)){
                    $index += 1;
                }
            }

            if($index === count($plan->categories)){
                unset($plans[$key]);
            }

        }
        if(count($plans) == 0){
            return [];
        }
        /************
         * NEWW
         *************/

        foreach($plans as $plan){
            $eventPlans = array_merge($plan->events()->pluck('event_id')->toArray(),$eventPlans);
        }

        return $plans;

    }

    public function checkUserPlansById($plans,$planId){

        $plans = $this->checkUserPlans($plans);
        $eventPlans = [];

        foreach($plans as $plan){
            $eventPlans = array_merge($plan->events()->pluck('plan_id')->toArray(),$eventPlans);
        }

        return in_array($planId,array_unique($eventPlans));

    }

    public function cart(){
        return $this->hasOne(CartCache::class);
    }

    public function updateUserStatistic($event,$statistics){

        $tab = $event['title'];
        $tab = str_replace(' ','_',$tab);
        $tab = str_replace('-','',$tab);
        $tab = str_replace('&','',$tab);
        $tab = str_replace('_','',$tab);


        $statistic = $statistics;

        $videos = [];
        $notes = [];
        $lastVideoSeen = '';
        $firstTime = true;
        if(isset($statistic['videos']) && $statistic['videos'] != ''){
            $notes = json_decode($statistic['notes'], true);
            $videos = json_decode($statistic['videos'], true);
            $lastVideoSeen = $statistic['lastVideoSeen'];
            $firstTime = false;
        }
        $countVideos = $videos ? count($videos) + 1 : 1;
        $oldVideos = [];
        $change = 0;
        foreach($event->topicsLessonsInstructors()['topics'] as $key => $topic){

            foreach($topic['lessons'] as $key1 => $lesson){
                // if(isset($lesson) && $lesson['vimeo_video'] != null){
                    //dd($lesson);

                    $vimeo_id = str_replace('https://vimeo.com/', '', $lesson['vimeo_video']);
                    $oldVideos[] = $vimeo_id;
                    if($firstTime){
                        $firstTime = false;
                        $lastVideoSeen = $vimeo_id;
                    }
                    if(!isset($videos[$vimeo_id])){
                       $change+=1;
                       $videos[$vimeo_id] = ['seen' => 0, 'tab' =>$tab.$vimeo_id, 'lesson' => $lesson['id'], 'stop_time' => 0,
                                               'percentMinutes' => 0];
                       $notes[$vimeo_id] = '';
                    }
                    $countVideos += 1;
            }
            //$countVideos += 1;
        }

        if(!in_array($lastVideoSeen,$oldVideos) && isset($oldVideos[0])){
            $lastVideoSeen =$oldVideos[0];
        }

        foreach($videos as $key => $videoId){
            if(!in_array($key,$oldVideos)){
                unset($videos[$key]);
            }
        }

        if(!$this->statistic()->wherePivot('event_id', $event['id'])->first()){
            $this->statistic()->attach($event['id'],['videos'=>json_encode($videos), 'notes' => json_encode($notes), 'lastVideoSeen' => $lastVideoSeen,
                                        'created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        }else{
            $this->statistic()->wherePivot('event_id', $event['id'])->updateExistingPivot($event['id'],['videos' => json_encode($videos),
                                            'notes' => json_encode($notes),'lastVideoSeen' => $lastVideoSeen], false);

        }

        return $this->statistic()->wherePivot('event_id', $event['id'])->first();

    }

    public function hasExamResults($exam){
        return $this->hasMany(ExamResult::class)->where('exam_id',$exam)->first();
    }

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    // TODO setup role & permission correctly
    public function canManageBinshopsBlogPosts()
    {
        return $this->role->whereIn('name', ['Super Administrator', 'Administrator', 'Author'])->isNotEmpty();
    }

    public function getNameAttribute()
    {
        return "$this->firstname $this->lastname";
    }
}

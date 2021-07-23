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
        //dd('asd');
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
        //dd($this->id);
        $role = DB::table('role_users')->where('user_id', $this->id)->first();
        //dd($role->role_id);
        $role = Role::where('id', $role->role_id)->first();
        return $role['id'] == 1;
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
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'expiration')->with('summary1','category','slugable');
    }

    public function subscriptionEvents()
    {
        return $this->belongsToMany(Event::class, 'subscription_user_event')->withPivot('expiration','event_id');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket')->withPivot('ticket_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public function statistic()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')->withPivot('id','videos','lastVideoSeen', 'notes', 'event_id');
    }

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
            $category = $event->category->first() ? $event->category->first()->first()->id : -1;

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
            $category = $event->category->first() ? $event->category->first()->first()->id : -1;

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
        foreach($plans as $plan){
            $index = 0;
            
            foreach($plan->categories as $key => $planCat){
                if(!in_array($planCat->id, $eventCategories)){
                    $index += 1;
                }
            }
            
            if($index === count($plan->categories)){

                unset($plans[$key]);
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
        //dd($eventPlans);
       
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
                $category = $event->categories->where('parent_id',45)->first()->id;
                $nonEventsCategory[$key][$category][] = $event->id;   
            }   
        }
        
        foreach($this->events as $event){

            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;
            $category = $event->event->categories->where('parent_id',45)->first()->id;
           
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
                $category = $event->categories->where('parent_id',45)->first()->id;
                $nonEventsCategory[$key][$category][] = $event->id;   
            }   
        }
        
        foreach($this->events as $event){

            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;
            $category = $event->event->categories->where('parent_id',45)->first() ? $event->event->categories->where('parent_id',45)->first()->id : -1;
           
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

}

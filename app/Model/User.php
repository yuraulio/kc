<?php

namespace App\Model;

use App\CMSFile;
use App\Enums\RoleEnum;
use App\Enums\WorkExperience;
use App\Model\Admin\Comment;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Searchable;
use App\Services\QueryString\Traits\Sortable;
use App\Traits\MediaTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Passport\HasApiTokens;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, MediaTrait, Billable, SoftDeletes, Filterable, Sortable, Searchable, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'email',
        'password',
        'company',
        'company_url',
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
        'country',
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
        'stripe_id',
        'social_links',
        'biography',
        'is_employee',
        'is_freelancer',
        'will_work_remote',
        'will_work_in_person',
        'work_experience',
        'is_public_profile_enabled',
        'notes',
        'profile_status',
        'account_status',
        'facebook_id',
        'facebook_access_token',
        'facebook_page_token',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'social_links'              => 'json',
        'is_employee'               => 'boolean',
        'is_freelancer'             => 'boolean',
        'will_work_remote'          => 'boolean',
        'will_work_in_person'       => 'boolean',
        'work_experience'           => WorkExperience::class,
        'is_public_profile_enabled' => 'boolean',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['firstname', 'lastname'])
            ->saveSlugsTo('slug');
    }

    public function scopeSearchUsers($query, $search_term)
    {
        $query->where(function ($query) use ($search_term) {
            $search_term_str = '%' . implode('%', explode(' ', $search_term)) . '%';
            $query->where('email', 'like', $search_term_str)
                ->orWhere('firstname', 'like', $search_term_str)
                ->orWhere('lastname', 'like', $search_term_str);
        });

        return $query->select('id', 'firstname', 'lastname', 'email')->get();
    }

    public function scopeRole(Builder $query, RoleEnum $role): Builder
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('roles.id', $role);
        });
    }

    /**
     * Get the role of the user.
     *
     * @return \App\Model\Role
     * @deprecated Use the roles() method.
     */
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'instructors_user')->with('event');
    }

    public function statusAccount(): HasOne
    {
        return $this->hasOne(Activation::class);
    }

    public function profileStatus(): HasOne
    {
        return $this->hasOne(ProfileStatus::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function careerPaths()
    {
        return $this->belongsToMany(Career::class, 'career_path_user', 'user_id', 'career_path_id')->withTimestamps();
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, CityUser::class);
    }

    /**
     * Get the path to the profile picture.
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
     * Check if the user has admin role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->whereIn('id', [1, 2])->first() ? true : false;
    }

    /**
     * Check if the user has creator role.
     *
     * @return bool
     */
    public function isCreator()
    {
        return $this->role_id == 2;
    }

    /**
     * Check if the user has user role.
     *
     * @return bool
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

    /**
     * Get the user's image.
     */
    public function profile_image()
    {
        return $this->hasOne(CMSFile::class, 'id', 'profile_image_id');
    }

    /**
     * Get the user's image.
     */
    public function profile_image_original()
    {
        if ($this->profile_image && $this->profile_image->parent) {
            return $this->profile_image->parent;
        }
    }

    public function instructorEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor', 'user_id', 'event_id');
    }

    public function allEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'expiration', 'comment', 'payment_method');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'expiration', 'comment', 'payment_method')
            ->with([
                'summary1',
                'category',
                'slugable',
                'dropbox',
                'lessons',
                'topic',
                'instructors',
                'certificates' => function ($certificate) {
                    //dd($certificate->first());
                    $user = $this->id;

                    return $certificate->where('show_certificate', true)->whereHas('user', function ($query) use ($user) {
                        $query->where('id', $user);
                    });
                },
            ])->wherePivot('paid', true);
    }

    public function eventList(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->withPivot('paid', 'expiration', 'payment_method');
    }

    public function eventsUnPaid()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'comment')
            ->wherePivot('paid', false);
    }

    public function events_for_user_list()
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->with('summary1', 'category', 'slugable', 'dropbox')
            ->withPivot('event_id', 'paid', 'expiration', 'comment', 'payment_method');
    }

    public function events_for_user_list1()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('event_id', 'paid', 'expiration', 'expiration_email', 'comment');
    }

    public function events_for_user_list1_expired()
    {
        return $this->belongsToMany(Event::class, 'event_user')
            ->has('plans')
            ->with('plans')
            ->wherePivot('event_user.expiration', '!=', '')
            ->wherePivot('event_user.expiration', '<=', date('Y-m-d H:s:i'))
            ->withPivot('event_id', 'paid', 'expiration', 'expiration_email');
    }

    public function subscriptionEvents()
    {
        return $this->belongsToMany(Event::class, 'subscription_user_event')->withPivot('expiration', 'event_id', 'payment_method', 'subscription_id');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket')->select('tickets.*', 'event_id', 'ticket_id')->withPivot('ticket_id', 'event_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public function statistic()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')->withPivot('id', 'videos', 'user_id', 'lastVideoSeen', 'notes', 'event_id', 'created_at', 'total_seen', 'total_duration');
    }

    public function statisticGroupByEvent()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')
            ->select('user_id', 'event_id')
            ->withPivot('id', 'videos', 'lastVideoSeen', 'notes', 'event_id', 'created_at', 'total_seen', 'total_duration');
    }

    public function transactions()
    {
        return $this->morphToMany(Transaction::class, 'transactionable');
    }

    public function invoices()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable');
    }

    public function eventSubscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_user_event')->with('event')->withPivot('expiration');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function cookiesSMS()
    {
        return $this->hasMany(CookiesSMS::class);
    }

    public function checkUserSubscriptionByEvent($events = null)
    {
        $eventsData = $events ? $events : $this->events;

        $plans = Plan::has('events')->with('events', 'categories', 'noEvents.categories')->get();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        $subscriptionEvents = $this->subscriptionEvents;

        if (count($plans) == 0) {
            return [false, []];
        }

        foreach ($plans as $key => $plan) {
            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(), $nonEventPlans);
            if (!key_exists($key, $categoryPlans)) {
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan['categories']->pluck('category_id')->toArray(), $categoryPlans[$key]);

            foreach ($plan->noEvents as $event) {
                $category = $event->categories->where('parent_id', 45)->first()->id;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach ($eventsData as $event) {
            $categoryIndex = false;
            $categoryIndexDelete = 0;
            //dd($event);
            $events[] = $event->pivot->event_id;
            //dd($event);
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $eventCategories[] = $category;

            $categoryEvents[$category][] = $event->pivot->event_id;

            foreach ($categoryPlans as $key => $categoryPlan) {
                $categoryIndexDelete += 1;
                if (!in_array($category, $categoryPlan)) {
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if (count($categoryPlans[$key]) == 0) {
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }
        }

        foreach ($subscriptionEvents as $event) {
            $categoryIndex = false;
            $categoryIndexDelete = 0;
            //dd($event);
            $events[] = $event->pivot->event_id;
            //dd($event);
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $eventCategories[] = $category;

            $categoryEvents[$category][] = $event->pivot->event_id;

            foreach ($categoryPlans as $key => $categoryPlan) {
                $categoryIndexDelete += 1;
                if (!in_array($category, $categoryPlan)) {
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if (count($categoryPlans[$key]) == 0) {
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }
        }

        if (count(array_diff($events, $nonEventPlans)) == 0) {
            return [false, []];
        }

        foreach ($nonEventsCategory as $key => $plan) {
            $planIndex = 0;
            foreach ($plan as $category => $event) {
                if (key_exists($category, $categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0) {
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }

            if ($planIndex == count($categoryPlans[$key])) {
                unset($plans[$key]);
            }
        }

        /************
         * NEWW
         *************/
        foreach ($plans as $key1 => $plan) {
            $index = 0;

            foreach ($plan['categories'] as $key => $planCat) {
                if (!in_array($planCat->id, $eventCategories)) {
                    $index += 1;
                }
            }

            if ($index === count($plan['categories'])) {
                unset($plans[$key1]);
            }
        }

        if (count($plans) == 0) {
            return [false, []];
        }
        /************
         * NEWW
         *************/

        foreach ($plans as $plan) {
            $eventPlans = array_merge($plan['events']->pluck('event_id')->toArray(), $eventPlans);
        }

        $eventPlans = array_diff($eventPlans, $events);
        $eventPlans = array_diff($eventPlans, $subscriptionEvents->pluck('event_id')->toArray());

        return [true, $eventPlans];
    }

    public function checkUserSubscriptionByEventId($eventId)
    {
        $plans = Plan::has('events')->get();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        if (count($plans) == 0) {
            return false;
        }

        foreach ($plans as $key => $plan) {
            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(), $nonEventPlans);
            if (!key_exists($key, $categoryPlans)) {
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan->categories()->pluck('category_id')->toArray(), $categoryPlans[$key]);

            foreach ($plan->noEvents as $event) {
                $category = $event->category->first() ? $event->category->first()->id : -1;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach ($this->events as $event) {
            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;

            $category = $event->category->first() ? $event->category->first()->id : -1;

            $categoryEvents[$category][] = $event->pivot->event_id;
            $eventCategories[] = $category;

            foreach ($categoryPlans as $key => $categoryPlan) {
                $categoryIndexDelete += 1;
                if (!in_array($category, $categoryPlan)) {
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if (count($categoryPlans[$key]) == 0) {
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }
        }

        if (count(array_diff($events, $nonEventPlans)) == 0) {
            return false;
        }

        foreach ($nonEventsCategory as $key => $plan) {
            $planIndex = 0;
            foreach ($plan as $category => $event) {
                if (key_exists($category, $categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0) {
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }

            if ($planIndex == count($categoryPlans[$key])) {
                unset($plans[$key]);
            }
        }

        /************
         * NEWW
         *************/
        foreach ($plans as $key => $plan) {
            $index = 0;

            foreach ($plan->categories as $key2 => $planCat) {
                if (!in_array($planCat->id, $eventCategories)) {
                    $index += 1;
                }
            }

            if ($index === count($plan->categories)) {
                unset($plans[$key]);
            }
        }
        if (count($plans) == 0) {
            return false;
        }
        /************
         * NEWW
         *************/

        foreach ($plans as $plan) {
            $eventPlans = array_merge($plan->events()->pluck('event_id')->toArray(), $eventPlans);
        }

        return in_array($eventId, $eventPlans);
    }

    public function checkUserPlans($plans)
    {
        //$plans = Plan::all();
        $eventPlans = [];
        $categoryPlans = [];
        $nonEventPlans = [];
        $nonEventsCategory = [];
        $categories = [];
        $categoryEvents = [];
        $events = [];
        $eventCategories = [];

        if (count($plans) == 0) {
            return [];
        }

        foreach ($plans as $key => $plan) {
            $nonEventPlans = array_merge($plan->noEvents()->pluck('event_id')->toArray(), $nonEventPlans);
            if (!key_exists($key, $categoryPlans)) {
                $categoryPlans[$key] = [];
            }
            $categoryPlans[$key] = array_merge($plan->categories()->pluck('category_id')->toArray(), $categoryPlans[$key]);

            foreach ($plan->noEvents as $event) {
                $category = $event->category->first() ? $event->category->first()->id : -1;
                $nonEventsCategory[$key][$category][] = $event->id;
            }
        }

        foreach ($this->events as $event) {
            $categoryIndex = 0;
            $categoryIndexDelete = 0;
            $events[] = $event->pivot->event_id;
            $category = $event->category->first() ? $event->category->first()->id : -1;

            $categoryEvents[$category][] = $event->pivot->event_id;
            $eventCategories[] = $category;

            foreach ($categoryPlans as $key => $categoryPlan) {
                $categoryIndexDelete += 1;
                if (!in_array($category, $categoryPlan)) {
                    $key1 = array_search($category, $categoryPlan);
                    unset($categoryPlan[$key1]);
                    $categoryIndex += 1;
                }
            }

            if (count($categoryPlans[$key]) == 0) {
                unset($plans[$key]);
                unset($nonEventsCategory[$key]);
            }
        }

        if (count(array_diff($events, $nonEventPlans)) == 0) {
            return [];
        }

        foreach ($nonEventsCategory as $key => $plan) {
            $planIndex = 0;
            foreach ($plan as $category => $event) {
                if (key_exists($category, $categoryEvents) && count(array_diff($categoryEvents[$category], $event)) == 0) {
                    $planIndex += 1;

                    unset($plans[$key]);
                }
            }

            if ($planIndex == count($categoryPlans[$key])) {
                unset($plans[$key]);
            }
        }

        /************
         * NEWW
         *************/
        foreach ($plans as $key => $plan) {
            $index = 0;

            foreach ($plan->categories as $key2 => $planCat) {
                if (!in_array($planCat->id, $eventCategories)) {
                    $index += 1;
                }
            }

            if ($index === count($plan->categories)) {
                unset($plans[$key]);
            }
        }
        if (count($plans) == 0) {
            return [];
        }
        /************
         * NEWW
         *************/

        foreach ($plans as $plan) {
            $eventPlans = array_merge($plan->events()->pluck('event_id')->toArray(), $eventPlans);
        }

        return $plans;
    }

    public function checkUserPlansById($plans, $planId)
    {
        $plans = $this->checkUserPlans($plans);
        $eventPlans = [];

        foreach ($plans as $plan) {
            $eventPlans = array_merge($plan->events()->pluck('plan_id')->toArray(), $eventPlans);
        }

        return in_array($planId, array_unique($eventPlans));
    }

    public function cart()
    {
        return $this->hasOne(CartCache::class);
    }

    public function getStatistsicsUpdate($event, $statistics, $eventTopics = null)
    {
        if (!$eventTopics) {
            $eventTopics = $event->topicsLessonsInstructors()['topics'];
        }

        $newStatistics = [];

        $tab = $event['title'];
        $tab = str_replace(' ', '_', $tab);
        $tab = str_replace('-', '', $tab);
        $tab = str_replace('&', '', $tab);
        $tab = str_replace('_', '', $tab);
        $tab = str_replace(',', '', $tab);
        $tab = str_replace(':', '', $tab);
        $tab = str_replace(')', '', $tab);
        $tab = str_replace('(', '', $tab);

        $statistic = $statistics;

        $videos = [];
        $notes = [];
        $lastVideoSeen = '';
        $firstTime = true;
        $createdAt = Carbon::now();
        $updatedAt = Carbon::now();

        $is_new = 1;

        if (isset($statistic['videos']) && $statistic['videos'] != '') {
            $notes = json_decode($statistic['notes'], true);
            $videos = json_decode($statistic['videos'], true);
            $lastVideoSeen = $statistic['lastVideoSeen'];
            $createdAt = $statistic['created_at'];
            $firstTime = false;
        }
        $countVideos = $videos ? count($videos) + 1 : 1;
        $oldVideos = [];
        $change = 0;
        foreach ($eventTopics as $key => $topic) {
            foreach ($topic['lessons'] as $key1 => $lesson) {
                // if(isset($lesson) && $lesson['vimeo_video'] != null){

                $vimeo_id = str_replace('https://vimeo.com/', '', $lesson['vimeo_video']);
                $oldVideos[] = $vimeo_id;
                if ($firstTime) {
                    $firstTime = false;
                    $lastVideoSeen = $vimeo_id;
                    $is_new = 0;
                }
                if (!isset($videos[$vimeo_id])) {
                    $change += 1;
                    $videos[$vimeo_id] = [
                        'seen'                => 0,
                        'tab'                 => $tab . $vimeo_id,
                        'lesson_id'           => $lesson['id'],
                        'stop_time'           => 0,
                        'total_seen'          => 0,
                        'percentMinutes'      => 0,
                        'total_duration'      => getLessonDurationToSec($lesson['vimeo_duration']),
                        'is_new'              => $is_new,
                        'send_automate_email' => isset($videos[$vimeo_id]['send_automate_email']) ? $videos[$vimeo_id]['send_automate_email'] : 0,
                    ];
                    $notes[$vimeo_id] = '';
                }

                if (!isset($videos[$vimeo_id]['tab'])) {
                    $stopTimee = isset($videos[$vimeo_id]['stop_time']) ? $videos[$vimeo_id]['stop_time'] : 0;
                    $totalSeenn = isset($videos[$vimeo_id]['total_seen']) ? $videos[$vimeo_id]['total_seen'] : 0;
                    $percentMinutess = isset($videos[$vimeo_id]['percentMinutes']) ? $videos[$vimeo_id]['percentMinutes'] : 0;
                    $seenn = isset($videos[$vimeo_id]['seen']) ? $videos[$vimeo_id]['seen'] : 0;
                    $isNeww = isset($videos[$vimeo_id]['is_new']) ? $videos[$vimeo_id]['is_new'] : 0;

                    $videos[$vimeo_id] = [
                        'seen'                => $seenn,
                        'tab'                 => $tab . $vimeo_id,
                        'lesson_id'           => $lesson['id'],
                        'stop_time'           => $stopTimee,
                        'total_seen'          => $totalSeenn,
                        'percentMinutes'      => $percentMinutess,
                        'total_duration'      => getLessonDurationToSec($lesson['vimeo_duration']),
                        'is_new'              => $isNeww,
                        'send_automate_email' => isset($videos[$vimeo_id]['send_automate_email']) ? $videos[$vimeo_id]['send_automate_email'] : 0,
                    ];

                    $notes[$vimeo_id] = isset($notes[$vimeo_id]) ? $notes[$vimeo_id] : '';
                }

                if (!isset($notes[$vimeo_id])) {
                    $notes[$vimeo_id] = '';
                }

                $countVideos += 1;
            }
            //$countVideos += 1;
        }

        if (!in_array($lastVideoSeen, $oldVideos) && isset($oldVideos[0])) {
            $lastVideoSeen = $oldVideos[0];
        }

        foreach ($videos as $key => $videoId) {
            if (!in_array($key, $oldVideos)) {
                unset($videos[$key]);
            }
        }

        $newStatistics = [
            'videos'     => json_encode($videos), 'notes' => json_encode($notes), 'lastVideoSeen' => $lastVideoSeen,
            'created_at' => $createdAt, 'updated_at' => $updatedAt, 'event_id' => $event['id'], 'user_id' => $this->id,
        ];

        return $newStatistics;
    }

    public function updateUserStatistic($event, $statistics, $eventTopics = null)
    {
        if (!$this->statistic()->wherePivot('event_id', $event['id'])->first()) {
            $this->statistic()->attach($event['id'], $this->getStatistsicsUpdate($event, $statistics, $eventTopics));
        } else {
            $this->statistic()->wherePivot('event_id', $event['id'])->updateExistingPivot($event['id'], $this->getStatistsicsUpdate($event, $statistics, $eventTopics), false);
        }

        return $this->statistic()->wherePivot('event_id', $event['id'])->first();
    }

    public function hasExamResults()
    {
        return $this->hasMany(ExamResult::class)->get()->groupBy('exam_id');
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function AauthAcessToken()
    {
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

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function getAbsencesByEvent(Event $event)
    {
        $eventId = $event->id;
        $absences = $this->absences()->whereEventId($eventId);
        $event->getTotalHours();
        if (empty($absences->get())) {
            return [];
        }

        $userMinutes = $absences->sum('minutes');
        $eventMinutes = $event->getTotalHours() > 0 ? $event->getTotalHours() : 1; //$absences->sum('total_minutes');
        $userMinutesAbsences = $eventMinutes - $userMinutes;
        $eventLimitAbsence = $event->absences_limit;

        $absencesByDate = [];

        foreach ($absences->get()->groupBy('date') as $key => $absence) {
            $userM = 0;
            $eventM = 0;

            foreach ($absence as $ab) {
                $userM += $ab->minutes;
                $eventM += $ab->total_minutes;
            }

            $absencesByDate[$key] = ['id' => $ab->id, 'user_minutes' => $userM, 'event_minutes' => $eventM];
        }

        $userAbsencesPercent = ($userMinutesAbsences / $eventMinutes) * 100;

        $class = '';

        if ($userAbsencesPercent >= $event->absences_limit) {
            $class = 'dangerous-absences';
        } elseif ($event->absences_limit - $userAbsencesPercent <= 2) {
            $class = 'warning-absences';
        }

        $data['absences_by_date'] = $absencesByDate;
        $data['total_user_minutes'] = $userMinutes;
        $data['total_event_minutes'] = $eventMinutes;
        $data['user_minutes_absences'] = $userMinutesAbsences;
        $data['user_absences_percent'] = $userAbsencesPercent;
        $data['event_id'] = $eventId;
        $data['class'] = $class;

        return $data;
    }

    public function usersComments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function waitingList()
    {
        return $this->hasMany(WaitingList::class);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('logging.channels.slack.url');
    }

    public function getAccessToken()
    {
        $token = $this->tokens()->where('revoked', false)->where('expires_at', '>', Carbon::now())->first();

        if ($token) {
            return $token->getPersonalAccessTokenResult()->accessToken;
        }

        return $this->createToken('auth_token')->accessToken;
    }

    public function shoppingCarts(): HasMany
    {
        return $this->hasMany(ShoppingCart::class, 'identifier');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'entity', 'taggables');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }

    public function canRetakeExam($examId)
    {
        $exam = Exam::find($examId);
        $examResult = ExamResult::where(['exam_id' => $examId, 'user_id' => $this->id])->orderBy('id', 'desc')->first();
        $data = $examResult->getResults($this->id);

        if ($data['success']) {
            $daysPassed = Carbon::now()->diffInDays($examResult->end_time);

            return $daysPassed >= $exam->repeat_exam_in;
        } else {
            $daysPassed = Carbon::now()->diffInDays($examResult->end_time);

            return $daysPassed >= $exam->repeat_exam_in_failure;
        }
    }

    public function getAssociatedRegistrations(): array
    {
        $data = [];

        foreach ($this->events_for_user_list as $event) {
            foreach ($event->transactionsByUser($this->id)->get() as $tran) {
                if (isset($tran['status_history'][0]['installments'])) {
                    $data[$tran->id]['amount'] = $tran['status_history'][0]['installments'];
                } else {
                    $data[$tran->id]['amount'] = null;
                }

                $data[$tran->id]['users'] = $tran->user()->where('id', '!=', $this->id)->get();
            }
        }

        return $data;
    }
}

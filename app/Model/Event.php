<?php

namespace App\Model;

use App\Audience;
use App\Events\EmailSent;
use App\Library\PageVariables;
use App\Model\Admin\Countdown;
use App\Notifications\CertificateAvaillable;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Sortable;
use App\Traits\BenefitTrait;
use App\Traits\MediaTrait;
use App\Traits\MetasTrait;
use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @property int                       $id
 * @property EventInfo                 $eventInfo
 * @property Collection<Coupon>        $coupons
 * @property Collection<City>          $city
 * @property Collection<Career>        $career
 * @property Collection<Skill>         $skills
 * @property Collection<Partner>       $partners
 * @property Collection<Audience>      $audiences
 * @property Collection<Event>         $relatedCourses
 * @property Collection<Tag>           $tags
 * @property Collection<Delivery>      $deliveries
 * @property Collection<Certificate>   $certificates
 * @property Metas                     $metable
 * @property Carbon                    $created_at
 * @property string                    $title
 * @property string|null               $admin_title
 * @property string|null               $fb_group
 * @property int                       $absences_limit
 * @property float                     $absences_start_hours
 * @property int|null                  $access_duration
 * @property Carbon|null               $files_access_till
 * @property DynamicAds|null           $dynamicAds
 * @property Collection<PaymentMethod> $paymentMethod
 * @property Collection<PaymentOption> $paymentOptions
 * @property Collection<Exam>          $exam
 * @property Collection<Event>         $bonusCourse
 * @property Collection<Dropbox>       $dropbox
 */
class Event extends Model
{
    use HasFactory,
        SlugTrait,
        MetasTrait,
        BenefitTrait,
        MediaTrait,
        SearchFilter,
        PaginateTable,
        Filterable,
        Sortable {
            Filterable::scopeFilter insteadof PaginateTable;
        }

    const STATUS_OPEN = 0;
    const STATUS_CLOSE = 1;
    const STATUS_SOLDOUT = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_MY_ACCOUNT_ONLY = 4;
    const STATUS_WAITING = 5;

    protected $table = 'events';

    protected $fillable = [
        'priority',
        'status',
        'published',
        'is_promoted',
        'release_date_files',
        'expiration',
        'title',
        'admin_title',
        'video_url',
        'htmlTitle',
        'subtitle',
        'header',
        'summary',
        'body',
        'hours',
        'absences_limit',
        'absences_start_hours',
        'access_duration',
        'files_access_till',
        'enroll',
        'index',
        'feed',
        'certificate_title',
        'fb_group',
        'evaluate_topics',
        'evaluate_instructors',
        'fb_testimonial',
        'author_id',
        'creator_id',
        'view_tpl',
        'view_counter',
        'published_at',
        'launch_date',
        'xml_title',
        'xml_description',
        'xml_short_description',
    ];

    protected $casts = [
        'files_access_till' => 'date',
    ];

    public function schemadata()
    {
        $schema = Cache::remember('schemadata-event-' . $this->id, 10, function () {
            $dynamicPageData = [
                'event' => $this,
                'info'  => $this->event_info(),
            ];
            $schema =
                [
                    '@context'                     => 'https://schema.org/',
                    '@type'                        => 'Course',
                    'name'                         => PageVariables::parseText($this->title, null, $dynamicPageData),
                    'description'                  => PageVariables::parseText($this->summary, null, $dynamicPageData),
                    'provider'                     => [
                        '@type' => 'Organization',
                        'name'  => 'Know Crunch',
                        'url'   => 'https://knowcrunch.com/',
                    ],
                    'author'                       => [
                        '@type' => 'Person',
                        'name'  => 'Tolis Aivalis',
                    ],
                    'inLanguage'                   => 'Greek/Ellinika',
                    'offers'                       => [
                        [
                            '@type'         => 'Offer',
                            'category'      => 'Free',
                            'priceCurrency' => 'EUR',
                            'price'         => 0,
                        ],
                    ],
                    'educationalCredentialAwarded' => 'EQF 5+ level',
                ];
            if (isset($this->mediable)) {
                $schema['image'] = [
                    cdn(get_image($this->mediable, 'event-card')),
                ];
            }
            if ($this->relationLoaded('slugable') && isset($this->slugable->slug)) {
                $schema['@id'] = $this->slugable->slug;
            }
            if ($this->relationLoaded('ticket') && isset($this->ticket[0]->price)) {
                $schema['offers'] = [
                    [
                        '@type'         => 'Offer',
                        'category'      => 'Paid',
                        'priceCurrency' => 'EUR',
                        'price'         => $this->ticket[0]->price,
                    ],
                ];
            }
            if (isset($this->published_at)) {
                $schema['datePublished'] = Carbon::create($this->published_at)->format('Y-m-d');
            }
            if ($this->relationLoaded('eventInfo') && isset($this->eventInfo->course_delivery)) {
                // dd($this->topic[0]->lessons);
                $instructors = [];
                if ($this->relationLoaded('instructors') && isset($this->instructors)) {
                    foreach ($this->instructors as $instr) {
                        $instructor = [
                            '@type'       => 'Person',
                            'name'        => $instr->title . ' ' . $instr->subtitle,
                            'description' => $instr->header,
                        ];
                        if (isset($instr->mediable) && $instr->mediable->count() > 0) {
                            $instructor['image'] = config('app.url') . $instr->mediable->path . '/' . $instr->mediable->original_name;
                        }
                        // Check if already added
                        $alreadyInserted = false;
                        foreach ($instructors as $in) {
                            if ($in['image'] == $instructor['image']) {
                                $alreadyInserted = true;
                            }
                        }
                        if (!$alreadyInserted) {
                            $instructors[] = $instructor;
                        }
                    }
                }
                $courseWorkload = 'PT0H';
                if (isset($this->course_hours)) {
                    $courseWorkload = 'PT' . $this->course_hours . 'H';
                }
                switch ($this->eventInfo->course_delivery) {
                    case 139: // Classroom training
                        $schema['hasCourseInstance'] = [
                            [
                                // Blended, instructor-led course meeting 3 hours per day in July.
                                '@type'          => 'CourseInstance',
                                'courseMode'     => 'onsite',
                                'location'       => $this->eventInfo->course_inclass_city ?? '',
                                'instructor'     => $instructors,
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 143: // Video e-learning
                        $schema['hasCourseInstance'] = [
                            [
                                // Online self-paced course that takes 2 days to complete.
                                '@type'          => 'CourseInstance',
                                'courseMode'     => 'online',
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 215: // Zoom training
                        $schema['hasCourseInstance'] = [
                            [
                                // Online self-paced course that takes 2 days to complete.
                                '@type'          => 'CourseInstance',
                                'courseMode'     => 'online',
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 216: // Corporate training
                        $schema['hasCourseInstance'] = [
                            [
                                // Blended, instructor-led course meeting 3 hours per day in July.
                                '@type'          => 'CourseInstance',
                                'courseMode'     => 'onsite',
                                'location'       => $this->course_inclass_city ?? '',
                                'instructor'     => $instructors,
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                }
            }

            return $schema;
        });

        // dd($this);
        return $schema;
    }

    public function toSearchableArray(): array
    {
        return $this->toArray();
    }

    public function category(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')
            ->with('faqs', 'testimonials', 'dropbox');
    }

    public function dynamicAds(): HasOne
    {
        return $this->hasOne(DynamicAds::class);
    }

    public function paymentOptions(): BelongsToMany
    {
        return $this->belongsToMany(PaymentOption::class, 'event_payment_options', 'event_id', 'option_id')
            ->withPivot(['active', 'installments_allowed', 'monthly_installments_limit']);
    }

    public function faqs(): MorphToMany
    {
        return $this->morphToMany(Faq::class, 'faqable')
            ->with('category')
            ->withPivot('priority')
            ->orderBy('faqables.priority');
    }

    public function sectionVideos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'event_video', 'event_id', 'video_id');
    }

    public function bonusCourse(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_bonuses', 'event_id', 'bonus_id')
            ->withPivot('exams_required', 'access_period');
    }

    public function exam(): MorphToMany
    {
        return $this->morphToMany(Exam::class, 'examable')
            ->withPivot(['exam_accessibility_type', 'exam_accessibility_value', 'exam_repeat_delay', 'whole_amount_should_be_paid']);
    }

    public function audiences(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class, 'event_audiences', 'event_id', 'audience_id');
    }

    public function relatedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'related_courses', 'event_id', 'related_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'entity', 'taggables');
    }

    public function type(): MorphToMany
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function topic(): BelongsToMany
    {
        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
                ->select('topics.*', 'topic_id', 'instructor_id')
                ->where('instructor_id', '!=', null)
                ->where('instructor_id', '!=', 0)
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')
                ->with('lessons.instructor')
                ->orderBy('event_topic_lesson_instructor.priority');
        } else {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
                ->select('topics.*', 'topic_id', 'instructor_id')
                ->where('instructor_id', '!=', null)
                ->where('instructor_id', '!=', 0)
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')
                ->with('lessons.instructor')
                ->orderBy('event_topic_lesson_instructor.time_starts', 'asc');
        }
    }

    public function topic_with_no_instructor(): BelongsToMany
    {
        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
                ->select('topics.*', 'topic_id', 'instructor_id')
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')
                ->with('lessons.instructor')
                ->orderBy('event_topic_lesson_instructor.priority', 'asc');
        } else {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
                ->select('topics.*', 'topic_id', 'instructor_id')
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')
                ->with('lessons.instructor')
                ->orderBy('event_topic_lesson_instructor.time_starts');
        }
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, EventTopic::class);
    }

    //forEventEdit
    public function allTopics(): BelongsToMany
    {
        $query = $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')
            ->select('topics.*', 'topic_id', 'instructor_id');

        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            $query->orderBy('event_topic_lesson_instructor.priority');
        } else {
            $query->orderBy('event_topic_lesson_instructor.time_starts');
        }

        return $query
            ->groupBy('topic_id');
    }

    public function topic_edit_instructor(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
            ->select('topics.*', 'topic_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail');
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'event_coupons');
    }

    public function allLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
            ->select('lessons.*')
            ->withPivot('id', 'event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail');
    }

    public function lessons(): BelongsToMany
    {
        if (!$this->is_inclass_course()) {
            return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
                ->where('status', true)
                ->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id')
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail')
                ->orderBy('event_topic_lesson_instructor.priority')
                ->with('type'); //priority
        } else {
            return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
                ->where('status', true)
                ->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id')
                ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail')
                ->orderBy('event_topic_lesson_instructor.time_starts')
                ->with('type'); //priority
        }
    }

    public function finishClassDuration()
    {
        $lastTimestamp = '2000-01-01 00:00';
        foreach ($this->lessons as $lesson) {
            if ($lesson->pivot->time_ends > $lastTimestamp) {
                $lastTimestamp = $lesson->pivot->time_ends;
            }
        }

        return $lastTimestamp;
    }

    public function lessonsForApp(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
            ->where('status', true)
            ->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id', 'event_topic_lesson_instructor.priority', 'event_topic_lesson_instructor.time_starts')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'location_url', 'priority', 'automate_mail', 'send_automate_mail')
            ->with('type');
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_events')
            ->where('published', true);
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'event_topic_lesson_instructor')
            ->with('mediable')
            ->select('instructors.*', 'lesson_id', 'instructor_id', 'event_id')
            ->withPivot('lesson_id', 'instructor_id')
            ->orderBy('subtitle')
            ->with('slugable')
            ->groupBy('instructor_id');
    }

    public function userInstructors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_topic_lesson_instructor', 'user_id');
    }

    public function summary(): BelongsToMany
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id');
    }

    public function summary1(): BelongsToMany
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id')
            ->orderBy('priority')
            ->with('mediable');
    }

    public function is_inclass_course()
    {
        $eventInfo = $this->event_info();

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] != 143) {
            return true;
        }

        return false;
    }

    public function is_elearning_course()
    {
        $eventInfo = $this->event_info();

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            return true;
        }

        return false;
    }

    public function delivery(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'event_delivery', 'event_id', 'delivery_id');
    }

    public function scopeWithDelivery($query, $deliveryId)
    {
        return $query->whereHas('delivery', function ($query) use ($deliveryId) {
            $query->where('delivery_id', $deliveryId);
        });
    }

    public function scopeWithDeliveries($query, $deliveryIds)
    {
        return $query->whereHas('delivery', function ($query) use ($deliveryIds) {
            $query->whereIn('delivery_id', $deliveryIds);
        });
    }

    public function ticket(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, EventTicket::class)
            ->select('tickets.*', 'event_tickets.ticket_id', 'event_tickets.price')
            ->withPivot('id', 'priority', 'ticket_id', 'price', 'options', 'quantity', 'features', 'active', 'public_title', 'seats_visible')
            ->orderBy('pivot_priority');
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket');
    }

    public function city(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'event_city')->with('slugable');
    }

    public function career(): BelongsToMany
    {
        return $this->belongsToMany(Career::class, 'event_career_paths', 'event_id', 'career_path_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'event_skills', 'event_id', 'skill_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'sectionTitles_event', 'event_id', 'section_title_id');
    }

    public function venues(): BelongsToMany
    {
        return $this->belongsToMany(Venue::class, 'event_venue');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('expiration', 'expiration_email', 'payment_method', 'comment', 'paid');
    }

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_creators', 'event_id', 'user_id');
    }

    public function users_with_transactions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->select('event_user.user_id as id', 'firstname', 'lastname', 'email', 'mobile')
            ->with('transactions')
            ->withPivot('expiration', 'payment_method', 'paid', 'comment');
    }

    public function usersPaid(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('expiration', 'payment_method', 'paid')
            ->wherePivot('paid', true);
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'event_partner')->with('mediable');
    }

    public function syllabus(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class, 'event_syllabus_manager', 'event_id', 'instructor_id')
            ->with('mediable', 'slugable');
    }

    public function paymentMethod(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'paymentmethod_event');
    }

    public function dropbox(): MorphToMany
    {
        return $this->morphToMany(Dropbox::class, 'dropboxcacheable')->withPivot('selectedFolders');
    }

    public function medias(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    private function calcTopics($topicEvent, $lessons, $event_id = null, $cache = false)
    {
        $topics = [];
        if ($this->status == self::STATUS_WAITING) {
            $topicEvent = $topicEvent ? $topicEvent->unique()->groupBy('topic_id') : $this->topic_with_no_instructor->unique()->groupBy('topic_id');
        } else {
            $topicEvent = $topicEvent ? $topicEvent->unique()->groupBy('topic_id') : $this->topic->unique()->groupBy('topic_id');
        }
        if ($cache) {
            foreach ($topicEvent as $topic_key => $topic) {
                if (isset($topic[0]->lessons)) {
                    foreach ($topic[0]->lessons as $lesson_key => $lesson) {
                        $lesson->pivot->instructor_id = $lesson->instructor[0]->id ?? 0;
                        unset($lesson->instructor);
                        unset($lesson->type);
                        $topicEvent[$topic_key][0]->lessons[$lesson_key] = $lesson;
                    }
                }
            }
            $topicEvent = $topicEvent->toArray();
            $topicEvent = array_map(function ($topic) {
                return array_map(function ($t) {
                    return (object) $t;
                }, $topic);
            }, $topicEvent);
            $topicEvent = array_values($topicEvent);
        }

        foreach ($topicEvent as $key => $topic) {
            foreach ($topic as $t) {
                if (!$t->status) {
                    continue;
                }

                if (!isset($lessons[$t->id])) {
                    continue;
                }
                $lessonsArray = $lessons[$t->id]->toArray();
                foreach ($lessonsArray as $key => $lesson) {
                    // ean einai se waiting list to event na mhn to kanei unset
                    if (!$lesson['instructor_id'] && $this->status != 5) {
                        unset($lessonsArray[$key]);
                    }
                    // ean einai se waiting list to event na mhn to kanei unset
                    if ($this->is_elearning_course() && $lesson['vimeo_video'] == '' && $this->status != 5) {
                        unset($lessonsArray[$key]);
                    }
                }
                if (count($lessonsArray) > 0) {
                    $topics[$t->title]['lessons'] = $lessonsArray;
                }
            }
        }

        return $topics;
    }

    public function topicsLessonsInstructors($videos = null, $topicEvent = null, $lessons = null, $instructors = null, $event_id = null)
    {
        // If there is no videos, topics, and instructors, it means the call of this function was topicsLessonsInstructors(), so, we can cache this request because we are not in the private area.
        $emptyParameters = false;
        if ($videos == null && $topicEvent == null && $lessons == null && $instructors == null) {
            $emptyParameters = true;
        }

        $videos = json_decode($videos, true);

        $topics = [];
        $topicsSeen = [];
        $lessons = $lessons ? $lessons->groupBy('topic_id') : $this->lessons->groupBy('topic_id');
        $sum1 = 0;
        //162

        // sum topic duration
        foreach ($lessons as $key => $lesson1) {
            $sum1 = 0;
            $topicsSeen[$key] = 0;
            $lessons_with_video_links = 0;
            foreach ($lesson1 as $key1 => $lesson) {
                $sum = 0;

                if ($lesson['vimeo_duration'] != null && $lesson['vimeo_duration'] != '0') {
                    $vimeo_duration = explode(' ', $lesson['vimeo_duration']);
                    $hour = 0;
                    $min = 0;
                    $sec = 0;

                    if (count($vimeo_duration) == 3) {
                        $string_hour = $vimeo_duration[0];
                        $string_hour = intval(explode('h', $string_hour)[0]);
                        $hour = $string_hour * 3600;

                        $string_min = $vimeo_duration[1];
                        $string_min = intval(explode('m', $string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[2];
                        $string_sec = intval(explode('s', $string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $hour + $min + $sec;
                    } elseif (count($vimeo_duration) == 2) {
                        $string_min = $vimeo_duration[0];
                        $string_min = intval(explode('m', $string_min)[0]);
                        $min = $string_min * 60;

                        $string_sec = $vimeo_duration[1];
                        $string_sec = intval(explode('s', $string_sec)[0]);
                        $sec = $string_sec;

                        $sum = $min + $sec;
                    } elseif (count($vimeo_duration) == 1) {
                        //dd($vimeo_duration);
                        $a = strpos($vimeo_duration[0], 's');
                        //dd($a);
                        if ($a === false) {
                            $sum = 0;
                            if (strpos($vimeo_duration[0], 'm')) {
                                $string_min = $vimeo_duration[0];
                                $string_min = intval(explode('m', $string_min)[0]);
                                $min = $string_min * 60;
                                $sum = $min;
                            }
                        } elseif ($a !== false) {
                            $string_sec = intval(explode('s', $vimeo_duration[0])[0]);
                            $sec = $string_sec;
                            $sum = $sec;
                        }
                    }
                }

                $vimeoVideo = explode('/', $lesson->vimeo_video);
                $vimeoVideo = end($vimeoVideo);

                if (isset($videos[$vimeoVideo]) && (int) $videos[$vimeoVideo]['seen'] == 1) {
                    $topicsSeen[$key]++;
                }

                $sum1 = $sum1 + $sum;
                $data['keys'][$key] = $sum1;

                // Count all the lessons that have video link assigned
                if ($lesson->vimeo_video != '') {
                    $lessons_with_video_links++;
                }
            }
            $topicsSeen[$key] = $topicsSeen[$key] >= $lessons_with_video_links;
        }

        $instructors = $instructors ? $instructors->unique()->groupBy('instructor_id')->toArray() : $this->instructors->unique()->groupBy('instructor_id')->toArray();

        // Ean einai sto status = waiting tote fere ta topics xwris na exoyn instructor
        if ($emptyParameters) {
            $topics = Cache::remember('topics-event-status-' . $this->id, 60 * 60 * 24, function () use ($topicEvent, $lessons, $event_id) {
                return $this->calcTopics($topicEvent, $lessons, $event_id, true);
            });
        } else {
            $topics = $this->calcTopics($topicEvent, $lessons, $event_id, false);
        }

        $data['topics'] = $topics;
        $data['instructors'] = $instructors;
        foreach ($data['topics'] as $key => $topics) {
            //if(!isset($topics['lessons'][0]['topic_id'])){
            if (!isset($topics['lessons'])) {
                continue;
            }

            if (!$topics = reset($topics['lessons'])) {
                continue;
            }

            if (!isset($topics['topic_id'])) {
                continue;
            }

            //$topic_id = $topics['lessons'][0]['topic_id'];
            $topic_id = $topics['topic_id'];
            //if(isset($topics['lessons'][0])){
            //    $topic_id = $topics['lessons'][0]['topic_id'];
            //}else{
            //    $topic_id = $topics['lessons'][1]['topic_id'];
            //}

            $data['topics'][$key]['topic_duration'] = $data['keys'][$topic_id] ?? 0;
            $data['topics'][$key]['topic_seen'] = $topicsSeen[$topic_id] ?? 0;
            $data['topics'][$key]['priority'] = $topics['pivot']['priority'];
        }

        return $data;
    }

    public function getFaqs()
    {
        $faqs = [];
        foreach ($this->faqs->toArray() as $faq) {
            if (!isset($faq['category']['0'])) {
                continue;
            }

            $faqs[$faq['category']['0']['name']][] = ['question' => $faq['title'], 'answer' => $faq['answer']];
        }

        // dd($faqs);
        return $faqs;
    }

    /*public function getFaqsByCategoryEvent(){

        $faqs = [];

        foreach($this->getFaqs() as $key => $faq){
            if(key_exists($key,$faqs)){
                continue;
            }
            $faqs[$key] = [];
        }

        if($this->category->first()){
            $categoryFaqs = $this->category->first()->faqs;

            foreach($categoryFaqs as $faq){

                foreach($faq->category as $categoryFaq){
                    $faqs[$categoryFaq['name']][] = array('id' => $faq['id'], 'question' =>  $faq['title']);
                }
            }
        }

        //pt

        $new_faqs = [];

        foreach($faqs as $key => $val){
           $arr = unique_multidim_array($val, 'id');
           $new_faqs[$key] = $arr;
        }

        //dd($new_faqs);
        return $new_faqs;

        //pt


        //return $faqs;
    }*/

    public function getFaqsByType()
    {
        $type = $this->is_elearning_course() ? 'elearning' : 'in_class';
        $type = ['both', $type];

        return Faq::whereIn('type', $type)->with('category')->get();
    }

    public function getFaqsByCategoryEvent()
    {
        $faqs = [];

        foreach ($this->getFaqs() as $key => $faq) {
            if (key_exists($key, $faqs)) {
                continue;
            }
            $faqs[$key] = [];
        }

        $categoryFaqs = $this->getFaqsByType();
        foreach ($categoryFaqs as $faq) {
            foreach ($faq->category as $categoryFaq) {
                $faqs[$categoryFaq['name']][] = ['id' => $faq['id'], 'question' => $faq['title']];
            }
        }

        //pt

        $new_faqs = [];

        foreach ($faqs as $key => $val) {
            $arr = unique_multidim_array($val, 'id');
            $new_faqs[$key] = $arr;
        }

        //dd($new_faqs);
        return $new_faqs;

        //pt

        //return $faqs;
    }

    public function statistic(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_statistics')
            ->withPivot('videos', 'lastVideoSeen', 'notes', 'event_id', 'user_id', 'last_seen');
    }

    public function waitingList(): HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    public function examAccess($user, $accessMonths = 2, $checkForCetification = true)
    {
        if ($accessMonths < 1) {
            // accessMonths var is video progress for this condition
            $accessMonths = 80;
            $periodAfterHasCourse = $this->progress($user);
        } else {
            $periodAfterHasCourse = $this->period($user);
        }

        //$studentsEx = [1353,1866,1753,1882,1913,1923,1359];
        $studentsEx = [1353, 1866, 1753, 1882, 1913, 1923, 5514, 7460, 5200, 7852];

        if (in_array($user->id, $studentsEx)) {
            return true;
        }

        $event = $this;

        if (!$event->created_at || $event->pivot->comment == 'enroll||0' || (strpos($event->pivot->comment, 'enroll from') !== false && explode('||', $event->pivot->comment)[1] == 0)) {
            return false;
        } elseif ($event->pivot->comment == 'enroll||1' || (strpos($event->pivot->comment, 'enroll from') !== false && explode('||', $event->pivot->comment)[1] == 1)) {
            $accessMonths = 80;
            $periodAfterHasCourse = $this->progress($user);
        }

        $certification = $checkForCetification && count($this->certificatesByUser($user->id)) > 0;

        return $periodAfterHasCourse >= $accessMonths && !$certification;
    }

    public function allInstallmentsPayed()
    {
        // Check if it's free
        $ticket_ids = EventUserTicket::where('user_id', Auth::id())->where('event_id', $this->id)->get()->pluck('ticket_id')->toArray();
        if (in_array(Ticket::FREE, $ticket_ids)) {
            return true;
        }
        // Check if this user payed all the installments
        $transaction_ids = Transactionable::where('transactionable_id', Auth::id())->where('transactionable_type', 'App\\Model\\User')->get()->pluck('transaction_id');
        $transaction_ids = Transactionable::whereIn('transaction_id', $transaction_ids)->where('transactionable_id', $this->id)->where('transactionable_type', 'App\\Model\\Event')->pluck('transaction_id');
        $invoiceable_ids = Invoiceable::whereIn('invoiceable_id', $transaction_ids)->where('invoiceable_type', 'App\\Model\\Transaction')->pluck('invoice_id');
        $invoices = Invoice::whereIn('id', $invoiceable_ids)->orderBy('id', 'DESC')->first();
        if ($invoices) {
            if ($invoices->instalments_remaining <= 0) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function period($user)
    {
        $transaction1 = null;
        $transactionAll = $this->transactionsByUserNew($user['id'])->where('status', 1)->get();

        if ($transactionAll == null) {
            return 0;
        } else {
            // fetch order by first payment date
            $transactions = $transactionAll->reverse();

            //parse first transaction has status = completed payment date
            foreach ($transactions as $transaction) {
                if ($transaction['status'] == self::STATUS_CLOSE) {
                    $transaction1 = $transaction;
                    break;
                }
            }
        }

        if ($transaction1 == null) {
            return 0;
        }

        $transactionDate = Carbon::parse($transaction1['created_at']);
        $nowDate = Carbon::now();
        $months = $transactionDate->diffInMonths($nowDate);

        return $months;
    }

    public function progress($user, $videos = false): float|int
    {
        if ($videos == 'no_videos') {
            return 0;
        }

        if (!$videos && !$videos = $user->statistic()->wherePivot('event_id', $this['id'])->first()) {
            $eventUser = EventUser::where('event_id', $this->id)
                ->where('user_id', $user->id)
                ->where('expiration', '!=', null)
                ->first();

            $subscriptionEventUser = SubscriptionEventUser::where('event_id', $this->id)
                ->where('user_id', $user->id)
                ->where('expiration', '!=', null)
                ->first();

            if ($eventUser || $subscriptionEventUser) {
                $fullCourseDuration = Carbon::parse($this->launch_date)
                    ->diffInDays(Carbon::parse($eventUser->expiration ?? $subscriptionEventUser->expiration));

                $currentDays = Carbon::parse($this->launch_date)
                    ->diffInDays(Carbon::now());

                if ($currentDays > $fullCourseDuration) {
                    return 100;
                }

                return $fullCourseDuration > 0 ? $currentDays / $fullCourseDuration * 100 : 0;
            }

            return 0;
        }

        $videos = $videos->pivot['videos'];

        $totalDuration = 0;
        $seenTime = 0;

        if ($videos != '') {
            $videos = json_decode($videos, true);
            foreach ($videos as $video) {
                if (!isset($video['total_duration'])) {
                    continue;
                }

                $seen = (float) $video['total_seen'];
                $seen = $seen > (float) $video['total_duration'] ? (float) $video['total_duration'] : $seen;

                if ((int) $video['seen'] == 1) {
                    $seen = (float) $video['total_duration'];
                }

                $seenTime += $seen;
                $totalDuration += (float) $video['total_duration'];
            }
        }

        return $totalDuration > 0 ? $seenTime / $totalDuration * 100 : 0;
    }

    public function video_seen($user, $videos = false): int|string
    {
        if ($videos === 'no_videos') {
            return 0;
        }

        if (!$videos && !$videos = $user->statistic()->wherePivot('event_id', $this['id'])->first()) {
            return '0 of ' . $this->lessons()->count();
        }

        $videos = json_decode($videos->pivot['videos'], true);

        if ($videos) {
            $sum = 0;

            foreach ($videos as $video) {
                if ($video['seen'] == 1) {
                    $sum++;
                }
            }

            return $sum . ' of ' . count($videos);
        }

        return '0 of ' . $this->lessons()->count();
    }

    public function invoices(): MorphToMany
    {
        return $this->morphToMany(Invoice::class, 'invoiceable', 'invoiceables');
    }

    public function email_trigger_logs(): MorphToMany
    {
        return $this->morphToMany(EmailTrigger::class, 'email_triggerables');
    }

    public function transactions(): MorphToMany
    {
        return $this->morphToMany(Transaction::class, 'transactionable')->with('user.ticket', 'isSubscription');
    }

    public function invoicesByUser($user)
    {
        return $this->invoices()->doesntHave('subscription')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->withPivot('invoiceable_id', 'invoiceable_type');
    }

    public function transactionsByUserNew($user)
    {
        return $this->transactions()->with('invoice')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->latest();
    }

    public function subscriptionInvoicesByUser($user)
    {
        return $this->invoices()->has('subscription')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->withPivot('invoiceable_id', 'invoiceable_type');
    }

    public function subscriptionΤransactionsByUser($user)
    {
        return $this->transactions()->has('subscription')->with('invoice', 'user')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        });
    }

    public function transactionsByUser($user)
    {
        return $this->transactions()->doesntHave('subscription')->with('invoice', 'user')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        });
    }

    public function getExams(): array
    {
        $curr_date_time = date('Y-m-d G:i:00');
        $curr_date = date('Y-m-d');
        $examsArray = [];
        $exams = $this->exam->where('status', true);

        foreach ($exams as $exam) {
            if ($exam->publish_time > $curr_date_time) {
                $exam->exstatus = 0;
                $exam->islive = 0;
                $exam->isupcom = 1;
            } elseif ($exam->publish_time < $curr_date && !$this->is_elearning_course()) {
                $exam->exstatus = 0;
                $exam->islive = 0;
                $exam->isupcom = 0;
            } else {
                $exam->exstatus = 0;
                $exam->islive = 1;
                $exam->isupcom = 0;
            }

            $examsArray[] = $exam;
        }

        return $examsArray;
    }

    public function certificates(): MorphToMany
    {
        return $this->morphToMany(Certificate::class, 'certificatable');
    }

    public function certificatesByUser($user)
    {
        return $this->certificates()->where('show_certificate', true)->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->withPivot('certificatable_id', 'certificatable_type')->get();
    }

    public function userHasCertificate($user)
    {
        return $this->certificates()->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        })->withPivot('certificatable_id', 'certificatable_type')->get();
    }

    public function certification(User $user, $successPer = 0.9)
    {
        $certification = count($this->certificatesByUser($user->id)) > 0;
        $infos = $this->event_info();

        if ($this->examAccess($user, $successPer) && !$certification) {
            Log::channel('daily')
                ->info('[user_id: ' . $user->id . ', event_id: ' . $this->id . '] User has enough progress of the course to generate certificate. Trying to generate certificate.');

            $cert = new Certificate;
            $cert->success = true;
            $cert->create_date = strtotime(date('Y-m-d'));
            $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
            $cert->certification_date = date('F') . ' ' . date('Y');

            $cert->firstname = $user->firstname;
            $cert->lastname = $user->lastname;
            $cert->credential = get_certifation_crendetial();
            $cert->certificate_title = isset($infos['certificate']['type']) && $infos['certificate']['type'] ? $infos['certificate']['type'] : $this->title;

            $createDate = strtotime(date('Y-m-d'));
            $cert->create_date = $createDate;

            $cert->template = 'new_kc_certificate';
            $cert->show_certificate = true;

            $cert->save();

            $cert->event()->attach($this->id);
            $cert->user()->attach($user->id);

            Log::channel('daily')
                ->info('[user_id: ' . $user->id . ', event_id: ' . $this->id . '] Certificate created. Trying to send email.');

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $this->title;
            $data['eventId'] = $this->id;
            $data['fbGroup'] = $this->fb_group;
            $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' you certification is ready';
            $data['template'] = 'emails.user.certificate';
            $data['certUrl'] = trim(url('/') . '/mycertificate/' . base64_encode($user->email . '--' . $cert->id));
            $user->notify(new CertificateAvaillable($data, $user));

            Log::channel('daily')
                ->info('[user_id: ' . $user->id . ', event_id: ' . $this->id . '] The email about a new certificate is sent.');

            event(new EmailSent($user->email, 'CertificateAvaillable'));
        }
    }

    public function getTotalHours(): float|int
    {
        $hours = 0;

        if ($this->is_inclass_course()) {
            foreach ($this->lessons as $lesson) {
                $timeStarts = strtotime($lesson->pivot->time_starts);
                $timeEnds = strtotime($lesson->pivot->time_ends);

                if ($timeStarts && $timeEnds) {
                    $hours += ($timeEnds - $timeStarts) / 60;
                }
            }
        } else {
            $lessons = $this->lessons->groupBy('topic_id');
            $totalVimeoSeconds = $this->getSumLessonHours($lessons);

            $hours = $totalVimeoSeconds;
        }

        return $hours;
    }

    public function getXmlDescriptionAttribute($value)
    {
        $value = strip_tags($value);
        $value = html_entity_decode($value);

        return $value;
    }

    public function eventInfo(): HasOne
    {
        return $this->hasOne(EventInfo::class);
    }

    public function event_info()
    {
        $infos = $this->eventInfo;
        $data = [];

        if ($infos != null) {
            $data['status'] = $infos['course_status'];

            $data['hours']['hour'] = $infos['course_hours'];
            $data['hours']['text'] = $infos['course_hours_text'];
            $data['hours']['title'] = $infos['course_hours_title'];
            $data['hours']['icon'] = $infos['course_hours_icon'] != null ? $infos['course_hours_icon'] : null;
            $data['hours']['visible'] = $infos['course_hours_visible'] != null ? $infos['course_hours_visible'] : null;

            $data['language']['text'] = $infos['course_language'];
            $data['language']['title'] = $infos['course_language_title'];
            $data['language']['icon'] = $infos['course_language_icon'] != null ? $infos['course_language_icon'] : null;
            $data['language']['visible'] = $infos['course_language_visible'] != null ? $infos['course_language_visible'] : null;

            $data['delivery'] = $infos['course_delivery'];
            $data['delivery_icon'] = $infos['course_delivery_icon'] != null ? $infos['course_delivery_icon'] : null;
            $data['delivery_info']['text'] = $infos['course_delivery_text'];
            $data['delivery_info']['title'] = $infos['course_delivery_title'];
            $data['delivery_info']['visible'] = $infos['course_delivery_visible'] != null ? $infos['course_delivery_visible'] : null;

            if ($data['delivery'] == 139) {
                //dd($infos['course_inclass_city']);
                $data['inclass']['absences'] = $infos['course_inclass_absences'];
                $data['inclass']['city']['text'] = $infos['course_inclass_city'];
                $data['inclass']['city']['icon'] = $infos['course_inclass_city_icon'];

                $data['inclass']['dates'] = ($infos['course_inclass_dates'] != null && $infos['course_inclass_dates'] != '[]') ? $infos['course_inclass_dates'] : null;
                $data['inclass']['days'] = ($infos['course_inclass_days'] != null && $infos['course_inclass_days'] != '[]') ? $infos['course_inclass_days'] : null;
                $data['inclass']['times'] = ($infos['course_inclass_times'] != null && $infos['course_inclass_times'] != '[]') ? $infos['course_inclass_times'] : null;

                $data['inclass']['elearning_access'] = ($infos['course_elearning_access'] != null) ? json_decode($infos['course_elearning_access'], true) : null;
                $data['inclass']['elearning_access_icon'] = ($infos['course_elearning_access_icon'] != null) ? $infos['course_elearning_access_icon'] : null;
                $data['inclass']['elearning_exam'] = ($infos['course_elearning_exam']) ? true : false;
            } elseif ($data['delivery'] == 143) {
                $data['elearning']['visible'] = $infos['course_elearning_visible'] != null ? $infos['course_elearning_visible'] : null;
                $data['elearning']['icon'] = $infos['course_elearning_icon'] != null ? $infos['course_elearning_icon'] : null;
                $data['elearning']['expiration'] = $infos['course_elearning_expiration'] != null ? $infos['course_elearning_expiration'] : null;
                $data['elearning']['text'] = $infos['course_elearning_text'] != null ? $infos['course_elearning_text'] : null;
                $data['elearning']['title'] = $infos['course_elearning_expiration_title'] != null ? $infos['course_elearning_expiration_title'] : null;

                $data['elearning']['exam']['visible'] = $infos['course_elearning_exam_visible'] != null ? $infos['course_elearning_exam_visible'] : null;
                $data['elearning']['exam']['icon'] = $infos['course_elearning_exam_icon'] != null ? $infos['course_elearning_exam_icon'] : null;
                $data['elearning']['exam']['text'] = $infos['course_elearning_exam_text'] != null ? $infos['course_elearning_exam_text'] : null;
                $data['elearning']['exam']['title'] = $infos['course_elearning_exam_title'] != null ? $infos['course_elearning_exam_title'] : null;

                $data['elearning']['exam']['activate_months'] = $infos['course_elearning_exam_activate_months'] != null ? json_decode($infos['course_elearning_exam_activate_months'], true) : null;
            } elseif ($data['delivery'] == 215) {
                $data['inclass']['absences'] = $infos['course_inclass_absences'];
                $data['inclass']['dates'] = ($infos['course_inclass_dates'] != null && $infos['course_inclass_dates'] != '[]') ? $infos['course_inclass_dates'] : null;
                $data['inclass']['days'] = ($infos['course_inclass_days'] != null && $infos['course_inclass_days'] != '[]') ? $infos['course_inclass_days'] : null;
                $data['inclass']['times'] = ($infos['course_inclass_times'] != null && $infos['course_inclass_times'] != '[]') ? $infos['course_inclass_times'] : null;

                $data['inclass']['elearning_access'] = ($infos['course_elearning_access'] != null) ? json_decode($infos['course_elearning_access'], true) : null;
                $data['inclass']['elearning_access_icon'] = ($infos['course_elearning_access_icon'] != null) ? $infos['course_elearning_access_icon'] : null;
                $data['inclass']['elearning_exam'] = ($infos['course_elearning_exam']) ? true : false;
            }

            $data['awards']['text'] = $infos['course_awards_text'];
            $data['awards']['icon'] = $infos['course_awards_icon'] != null ? $infos['course_awards_icon'] : null;

            $data['payment_method'] = $infos['course_payment_method'];
            $data['payment_icon'] = $infos['course_payment_icon'] != null ? $infos['course_payment_icon'] : null;
            $data['payment_installments'] = $infos['course_payment_installments'];

            $data['partner']['status'] = $infos['course_partner'];
            $data['partner']['text'] = $infos['course_partner_text'];
            $data['partner']['icon'] = $infos['course_partner_icon'] != null ? $infos['course_partner_icon'] : null;
            $data['partner']['visible'] = $infos['course_partner_visible'] != null ? $infos['course_partner_visible'] : null;

            $data['manager']['status'] = $infos['course_manager'];
            $data['manager']['icon'] = $infos['course_manager_icon'] != null ? $infos['course_manager_icon'] : null;

            //$data['certificate']['event_title'] = $infos['course_certification_event_title'];
            $data['certificate']['messages']['completion'] = $infos['course_certification_completion'];
            $data['certificate']['messages']['success'] = $infos['course_certification_name_success'];
            //$data['certificate']['messages']['failure'] = $infos['course_certification_name_failure'];
            $data['certificate']['type'] = $infos['course_certification_type'];
            $data['certificate']['title'] = $infos['course_certification_title'];
            $data['certificate']['text'] = $infos['course_certification_text'];
            //$data['certificate']['attendance_title'] = $infos['course_certification_attendance_title'];
            $data['certificate']['visible'] = $infos['course_certification_visible'] != null ? $infos['course_certification_visible'] : null;
            $data['certificate']['icon'] = $infos['course_certification_icon'] != null ? $infos['course_certification_icon'] : null;
            $data['certificate']['has_certificate'] = $infos['has_certificate'];
            $data['certificate']['has_certificate_exam'] = $infos['has_certificate_exam'];

            $data['students']['number'] = (int) $infos['course_students_number'];
            $data['students']['text'] = $infos['course_students_text'];
            $data['students']['title'] = $infos['course_students_title'];
            $data['students']['visible'] = $infos['course_students_visible'] != null ? $infos['course_students_visible'] : null;
            $data['students']['icon'] = $infos['course_students_icon'] != null ? $infos['course_students_icon'] : null;
            $data['bonus_access_expiration'] = $infos['bonus_access_expiration'] ?? null;
        }

        return $data;
    }

    public function isFree(): bool
    {
        return $this->eventInfo?->course_payment_method == 'free';
    }

    public function hasCertificate(): bool
    {
        return (bool) $this->eventInfo?->has_certificate;
    }

    public function hasCertificateExam(): bool
    {
        return (bool) $this->eventInfo?->has_certificate_exam;
    }

    // Return seconds
    public function getSumLessonHours($lessons)
    {
        $total = 0;
        foreach ($lessons as $key => $lesson1) {
            $sum1 = 0;

            foreach ($lesson1 as $key1 => $lesson) {
                $sum = 0;

                $sum1 = $sum1 + getSumLessonSecond($lesson);
            }

            $total = $total + $sum1;
        }

        return $total / 60;
    }

    public function changeOrder($from = 0)
    {
        $or = [];

        foreach ($this->allLessons()->wherePivot('priority', '>=', $from)->get() as $pLesson) {
            $newPriorityLesson = $pLesson->pivot->priority + 1;
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();

            $or[$pLesson->pivot->lesson_id] = [
                'topic_id'           => $pLesson->pivot->topic_id,
                'lesson_id'          => $pLesson->pivot->lesson_id,
                'event_id'           => $pLesson->pivot->event_id,
                'instructor_id'      => $pLesson->pivot->instructor_id,
                'date'               => $pLesson->pivot->date,
                'time_starts'        => $pLesson->pivot->time_starts,
                'time_ends'          => $pLesson->pivot->time_ends,
                'duration'           => $pLesson->pivot->duration,
                'room'               => $pLesson->pivot->room,
                'location_url'       => $pLesson->pivot->location_url,
                'automate_mail'      => $pLesson->pivot->automate_mail,
                'send_automate_mail' => $pLesson->pivot->send_automate_mail,
                'priority'           => $newPriorityLesson,
            ];
        }

        $this->allLessons()->detach(array_keys($or));
        $this->allLessons()->attach($or);
    }

    public function fixOrder()
    {
        $newPriorityLesson = 1;
        $or = [];
        foreach ($this->allLessons()->orderBy('priority')->get() as $pLesson) {
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();

            $or[$pLesson->pivot->lesson_id] = [
                'topic_id'           => $pLesson->pivot->topic_id,
                'lesson_id'          => $pLesson->pivot->lesson_id,
                'event_id'           => $pLesson->pivot->event_id,
                'instructor_id'      => $pLesson->pivot->instructor_id,
                'date'               => $pLesson->pivot->date,
                'time_starts'        => $pLesson->pivot->time_starts,
                'time_ends'          => $pLesson->pivot->time_ends,
                'duration'           => $pLesson->pivot->duration,
                'room'               => $pLesson->pivot->room,
                'location_url'       => $pLesson->pivot->location_url,
                'automate_mail'      => $pLesson->pivot->automate_mail,
                'send_automate_mail' => $pLesson->pivot->send_automate_mail,
                'priority'           => $newPriorityLesson,
            ];
            $newPriorityLesson += 1;
        }

        $this->allLessons()->detach(array_keys($or));
        $this->allLessons()->attach($or);
    }

    public function getAccessInMonths()
    {
        $eventInfo = $this->eventInfo;
        $accessMonths = isset($eventInfo['course_elearning_expiration']) ? $eventInfo['course_elearning_expiration'] : 0;

        return $accessMonths;
    }

    public function countdown(): BelongsToMany
    {
        return $this->belongsToMany(Countdown::class, 'cms_countdown_event');
    }

    public function resetCache()
    {
        Cache::forget('topics-event-status-' . $this->id);
    }

    public function deliveries(): BelongsToMany
    {
        return $this->belongsToMany(Delivery::class, 'course_deliveries', 'event_id', 'delivery_id');
    }
}

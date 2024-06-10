<?php

namespace App\Model;

use App\Events\EmailSent;
use App\Library\PageVariables;
use App\Model\Admin\Countdown;
use App\Notifications\CertificateAvaillable;
use App\Services\QueryString\Traits\Filterable;
use App\Services\QueryString\Traits\Sortable;
use App\Traits\BenefitTrait;
use App\Traits\Invoices;
use App\Traits\MediaTrait;
use App\Traits\MetasTrait;
use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

    public function toSearchableArray()
    {
        return $this->toArray();
    }

    protected $fillable = [
        'published', 'published_at', 'release_date_files', 'expiration', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'hours', 'author_id', 'creator_id', 'view_tpl', 'view_counter',
        'launch_date', 'certificate_title', 'fb_group', 'evaluate_topics', 'evaluate_instructors', 'fb_testimonial', 'absences_limit', 'xml_title', 'xml_description', 'xml_short_description', 'index', 'feed',
    ];

    public function schemadata()
    {
        $schema = Cache::remember('schemadata-event-' . $this->id, 10, function () {
            $dynamicPageData = [
                'event' => $this,
                'info' => $this->event_info(),
            ];
            $schema =
            [
                '@context' => 'https://schema.org/',
                '@type' => 'Course',
                'name' => PageVariables::parseText($this->title, null, $dynamicPageData),
                'description' => PageVariables::parseText($this->summary, null, $dynamicPageData),
                'provider' => [
                    '@type' => 'Organization',
                    'name' => 'Know Crunch',
                    'url' => 'https://knowcrunch.com/',
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => 'Tolis Aivalis',
                ],
                'inLanguage' => 'Greek/Ellinika',
                'offers' => [
                    [
                        '@type' => 'Offer',
                        'category' => 'Free',
                        'priceCurrency' => 'EUR',
                        'price' => 0,
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
                        '@type' => 'Offer',
                        'category' => 'Paid',
                        'priceCurrency' => 'EUR',
                        'price' => $this->ticket[0]->price,
                    ],
                ];
            }
            if (isset($this->published_at)) {
                $schema['datePublished'] = Carbon::create($this->published_at)->format('Y-m-d');
            }
            if ($this->relationLoaded('event_info1') && isset($this->event_info1->course_delivery)) {
                // dd($this->topic[0]->lessons);
                $instructors = [];
                if ($this->relationLoaded('instructors') && isset($this->instructors)) {
                    foreach ($this->instructors as $instr) {
                        $instructor = [
                            '@type' => 'Person',
                            'name' => $instr->title . ' ' . $instr->subtitle,
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
                switch($this->event_info1->course_delivery) {
                    case 139: // Classroom training
                        $schema['hasCourseInstance'] = [
                            [
                                // Blended, instructor-led course meeting 3 hours per day in July.
                                '@type' => 'CourseInstance',
                                'courseMode' => 'onsite',
                                'location' => $this->event_info1->course_inclass_city ?? '',
                                'instructor' => $instructors,
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 143: // Video e-learning
                        $schema['hasCourseInstance'] = [
                            [
                                // Online self-paced course that takes 2 days to complete.
                                '@type' => 'CourseInstance',
                                'courseMode' => 'online',
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 215: // Zoom training
                        $schema['hasCourseInstance'] = [
                            [
                                // Online self-paced course that takes 2 days to complete.
                                '@type' => 'CourseInstance',
                                'courseMode' => 'online',
                                'courseWorkload' => $courseWorkload,
                            ],
                        ];
                        break;
                    case 216: // Corporate training
                        $schema['hasCourseInstance'] = [
                            [
                                // Blended, instructor-led course meeting 3 hours per day in July.
                                '@type' => 'CourseInstance',
                                'courseMode' => 'onsite',
                                'location' => $this->course_inclass_city ?? '',
                                'instructor' => $instructors,
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

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable')->with('faqs', 'testimonials', 'dropbox');
    }

    public function faqs()
    {
        return $this->morphToMany(Faq::class, 'faqable')->with('category')->withPivot('priority')->orderBy('faqables.priority', 'asc');
    }

    public function sectionVideos()
    {
        return $this->belongsToMany(Video::class, 'event_video', 'event_id', 'video_id');
    }

    public function exam()
    {
        return $this->morphToMany(Exam::class, 'examable');
    }

    public function exam_result()
    {
        return $this->belongsToMany(ExamResult::class, 'exam_results', 'user_id', 'exam_id')->withPivot('total_score');
    }

    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function topic()
    {
        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')->where('instructor_id', '!=', null)->where('instructor_id', '!=', 0)
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.priority', 'asc');
        } else {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')->where('instructor_id', '!=', null)->where('instructor_id', '!=', 0)
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.time_starts', 'asc');
        }
    }

    public function topic_with_no_instructor()
    {
        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.priority', 'asc');
        } else {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.time_starts', 'asc');
        }
    }

    //forEventEdit
    public function allTopics()
    {
        if ($this->delivery->first() && $this->delivery->first()->id == 143) {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.priority', 'asc');
        } else {
            return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'automate_mail', 'send_automate_mail')->with('lessons.instructor')->with('lessons.instructor')->orderBy('event_topic_lesson_instructor.time_starts', 'asc');
        }
    }

    public function topic_edit_instructor()
    {
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*', 'topic_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'event_coupons');
    }

    public function allLessons()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->select('lessons.*')->withPivot('id', 'event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail');
    }

    public function lessons()
    {
        if (!$this->is_inclass_course()) {
            return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->where('status', true)->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail')->orderBy('event_topic_lesson_instructor.priority', 'asc')->with('type'); //priority
        } else {
            return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->where('status', true)->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority', 'location_url', 'automate_mail', 'send_automate_mail')->orderBy('event_topic_lesson_instructor.time_starts', 'asc')->with('type'); //priority
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

    public function lessonsForApp()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->where('status', true)
            ->select('lessons.*', 'topic_id', 'event_id', 'lesson_id', 'instructor_id', 'event_topic_lesson_instructor.priority', 'event_topic_lesson_instructor.time_starts')
            ->withPivot('event_id', 'topic_id', 'lesson_id', 'instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'location_url', 'priority', 'automate_mail', 'send_automate_mail')->with('type');
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_events')->where('published', true);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'event_topic_lesson_instructor')->with('mediable')->select('instructors.*', 'lesson_id', 'instructor_id', 'event_id')
            ->withPivot('lesson_id', 'instructor_id')->orderBy('subtitle', 'asc')->with('slugable');
    }

    public function summary1()
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id')->orderBy('priority')->with('mediable');
    }

    public function is_inclass_course()
    {
        $eventInfo = $this->event_info();
        /*if(isset($eventInfo['delivery']) && $eventInfo['delivery'] == 139){
            return true;
        }*/

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] != 143) {
            return true;
        }

        return false;

        //return $this->view_tpl !== 'elearning_event' && $this->view_tpl !== 'elearning_free';

        //if($this->delivery->first() && $this->delivery->first()->id == 139){
        //    return true;
        //}else{
        //    return false;
        //}
    }

    public function is_elearning_course()
    {
        $eventInfo = $this->event_info();

        if (isset($eventInfo['delivery']) && $eventInfo['delivery'] == 143) {
            return true;
        }

        return false;

        //return $this->view_tpl == 'elearning_event' || $this->view_tpl == 'elearning_free';
        // if($this->delivery->first() && $this->delivery->first()->id == 143){
        //     return true;
        // }else{
        //     return false;
        // }
    }

    public function delivery()
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

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets')
            ->select('tickets.*', 'event_tickets.ticket_id', 'event_tickets.price')
            ->withPivot('id', 'priority', 'ticket_id', 'price', 'options', 'quantity', 'features', 'active', 'public_title', 'seats_visible')
            ->orderBy('pivot_priority');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket');
    }

    public function city()
    {
        return $this->belongsToMany(City::class, 'event_city')->with('slugable');
    }

    public function career()
    {
        return $this->morphedByMany(Career::class, 'careerpathable', 'careerpathables', 'event_id', 'careerpathable_id')
            ->withPivot('careerpath_type');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'sectionTitles_event', 'event_id', 'section_title_id');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'event_venue');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')->withPivot('expiration', 'expiration_email', 'payment_method', 'comment', 'paid');
    }

    public function users_with_transactions()
    {
        return $this->belongsToMany(User::class, 'event_user')->select('event_user.user_id as id', 'firstname', 'lastname', 'email', 'mobile')->with('transactions')->withPivot('expiration', 'payment_method', 'paid', 'comment');
    }

    public function usersPaid()
    {
        return $this->belongsToMany(User::class, 'event_user')->withPivot('expiration', 'payment_method', 'paid')->wherePivot('paid', true);
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'event_partner')->with('mediable');
    }

    public function syllabus()
    {
        return $this->belongsToMany(Instructor::class, 'event_syllabus_manager')->with('mediable', 'slugable');
    }

    public function paymentMethod()
    {
        return $this->belongsToMany(PaymentMethod::class, 'paymentmethod_event');
    }

    public function dropbox()
    {
        return $this->morphToMany(Dropbox::class, 'dropboxcacheable')->withPivot('selectedFolders');
    }

    public function medias()
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
                    //if(($this->view_tpl=='elearning_event' || $this->view_tpl == 'elearnig_free') && $lesson['vimeo_video'] ==''){
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

            $faqs[$faq['category']['0']['name']][] = ['question' =>$faq['title'], 'answer' => $faq['answer']];
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
                $faqs[$categoryFaq['name']][] = ['id' => $faq['id'], 'question' =>  $faq['title']];
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

    public function statistic()
    {
        return $this->belongsToMany(Event::class, 'event_statistics')->withPivot('videos', 'lastVideoSeen', 'notes', 'event_id', 'user_id');
    }

    public function waitingList()
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
        $studentsEx = [1353, 1866, 1753, 1882, 1913, 1923, 5514, 7460];

        if (in_array($user->id, $studentsEx)) {
            return true;
        }

        //$event = EventStudent::where('student_id',$this->user_id)->where('event_id',$this->event_id)->first()->created_at;
        $event = $this;
        //if(!$event->created_at || $event->pivot->comment == 'enroll' /*|| $event->view_tpl == 'elearning_free'*/){

        if (!$event->created_at || $event->pivot->comment == 'enroll||0' || (strpos($event->pivot->comment, 'enroll from') !== false && explode('||', $event->pivot->comment)[1] == 0)) {
            return false;
        } elseif ($event->pivot->comment == 'enroll||1' || (strpos($event->pivot->comment, 'enroll from') !== false && explode('||', $event->pivot->comment)[1] == 1)) {
            //$periodAfterHasCourse = $accessMonths;
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

    /*public function progress($user)
    {
        //dd($user->statistic()->wherePivot('event_id',$this['id'])->first()->pivot['videos']);

        if(!$videos = $user->statistic()->wherePivot('event_id',$this['id'])->first()){
            return 0 * 100;
        }

        $videos = $videos->pivot['videos'];


        //dd($videos);
        $sum = 0;
        if($videos != ''){
            $videos = json_decode($videos, true);
            foreach($videos as $video){
                if($video['seen'] == 1 || $video['seen'] == '1'){
                    $sum++;
                }

            }
            return count($videos) > 0 ? ($sum/count($videos)) * 100 : 0;
        }

        return 0 * 100;
    }*/

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

    public function progress($user, $videos = false)
    {
        if ($videos == 'no_videos') {
            return 0;
        }

        if (!$videos && !$videos = $user->statistic()->wherePivot('event_id', $this['id'])->first()) {
            return 0;
        }

        $videos = $videos->pivot['videos'];
        $totalDuration = 0;

        //dd($videos);
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

    public function video_seen($user, $videos = false)
    {
        if ($videos == 'no_videos') {
            return 0;
        }

        if (!$videos && !$videos = $user->statistic()->wherePivot('event_id', $this['id'])->first()) {
            return '0 of ' . count($this->lessons);
        }

        $videos = $videos->pivot['videos'];
        $videos = json_decode($videos, true);
        //dd($videos);
        //dd($user->statistic()->wherePivot('event_id',$this['id'])->first());
        if ($videos) {
            //$videos = json_decode($videos, true);
            //dd($videos);
            $sum = 0;
            foreach ($videos as $video) {
                if ($video['seen'] == 1 || $video['seen'] == '1') {
                    $sum++;
                }
            }

            return $sum . ' of ' . count($videos);
        }

        return'0 of ' . count($this->lessons);
        //return '0 of 0';
    }

    public function invoices()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable', 'invoiceables');
    }

    public function transactions()
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

        /*return $this->transactions()->whereHas('user', function ($query) use($user) {
            $query->where('id', $user);
        });*/
    }

    public function transactionsByUser($user)
    {
        return $this->transactions()->doesntHave('subscription')->with('invoice', 'user')->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user);
        });

        /*return $this->transactions()->whereHas('user', function ($query) use($user) {
            $query->where('id', $user);
        });*/
    }

    public function getExams()
    {
        $curr_date_time = date('Y-m-d G:i:00');
        $curr_date = date('Y-m-d');
        $examsArray = [];
        $exams = $this->exam->where('status', true);
        //return $examsArray;
        foreach ($exams as $exam) {
            if ($exam->publish_time > $curr_date_time) {
                $exam->exstatus = 0;
                $exam->islive = 0;
                $exam->isupcom = 1;
            //}else if($exam->publish_time <  $curr_date && $this->view_tpl != 'elearning_event'){
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

    public function certificates()
    {
        return $this->morphToMany(Certificate::class, 'certificatable');
    }

    public function certificatesByUser($user)
    {
        /*return $this->certificates()->whereHas('user', function ($query) use($user) {
            $query->where('id', $user);
        })->withPivot('certificatable_id','certificatable_type')->get();*/

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

            $cert->event()->save($this);
            $cert->user()->save($user);

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $this->title;
            $data['fbGroup'] = $this->fb_group;
            $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' you certification is ready';
            $data['template'] = 'emails.user.certificate';
            $data['certUrl'] = trim(url('/') . '/mycertificate/' . base64_encode($user->email . '--' . $cert->id));
            $user->notify(new CertificateAvaillable($data));
            event(new EmailSent($user->email, 'CertificateAvaillable'));
        }
    }

    /*public function getTotalHours(){

        $hours = 0;
        //In class
        if($this->is_inclass_course()){
            $timeStarts = false;
            $timeEnds = false;

            foreach($this->lessons as $lesson){
                $timeStarts = false;
                $timeEnds = false;

                $timeStarts = (int) date('H', strtotime($lesson->pivot->time_starts));
                $timeEnds = (int) date('H', strtotime($lesson->pivot->time_ends));
                if($timeStarts && $timeEnds){
                    $hours += ($timeEnds - $timeStarts) * 60;
                }

            }
        }else{
            // E-learning

            // Return sec

            $lessons = $this->lessons->groupBy('topic_id');
            //dd($this->lessons);
            $totalVimeoSeconds = $this->getSumLessonHours($lessons);
            $hours = $totalVimeoSeconds;
        }

        return $hours;
    }*/

    public function getTotalHours()
    {
        $hours = 0;
        //In class
        if ($this->is_inclass_course()) {
            $timeStarts = false;
            $timeEnds = false;

            foreach ($this->lessons as $lesson) {
                $timeStarts = false;
                $timeEnds = false;

                $timeStarts = strtotime($lesson->pivot->time_starts);
                $timeEnds = strtotime($lesson->pivot->time_ends);
                if ($timeStarts && $timeEnds) {
                    $hours += ($timeEnds - $timeStarts) / 60;
                }
            }
        } else {
            // E-learning

            // Return sec

            $lessons = $this->lessons->groupBy('topic_id');
            //dd($this->lessons);
            $totalVimeoSeconds = $this->getSumLessonHours($lessons);
            $hours = $totalVimeoSeconds;
        }

        //dd($lesson->pivot->time_starts);
        return $hours;
    }

    public function getXmlDescriptionAttribute($value)
    {
        $value = strip_tags($value);
        $value = html_entity_decode($value);

        return $value;
    }

    public function event_info1()
    {
        return $this->hasOne(EventInfo::class);
    }

    public function event_info()
    {
        //return $this->hasOne(EventInfo::class);

        $infos = $this->event_info1; //$this->hasOne(EventInfo::class)->first();
        $data = [];

        if ($infos != null) {
            $data['status'] = $infos['course_status'];

            $data['hours']['hour'] = $infos['course_hours'];
            $data['hours']['text'] = $infos['course_hours_text'];
            $data['hours']['title'] = $infos['course_hours_title'];
            $data['hours']['icon'] = $infos['course_hours_icon'] != null ? json_decode($infos['course_hours_icon'], true) : null;
            $data['hours']['visible'] = $infos['course_hours_visible'] != null ? json_decode($infos['course_hours_visible'], true) : null;

            $data['language']['text'] = $infos['course_language'];
            $data['language']['title'] = $infos['course_language_title'];
            $data['language']['icon'] = $infos['course_language_icon'] != null ? json_decode($infos['course_language_icon'], true) : null;
            $data['language']['visible'] = $infos['course_language_visible'] != null ? json_decode($infos['course_language_visible'], true) : null;

            $data['delivery'] = $infos['course_delivery'];
            $data['delivery_icon'] = $infos['course_delivery_icon'] != null ? json_decode($infos['course_delivery_icon'], true) : null;
            $data['delivery_info']['text'] = $infos['course_delivery_text'];
            $data['delivery_info']['title'] = $infos['course_delivery_title'];
            $data['delivery_info']['visible'] = $infos['course_delivery_visible'] != null ? json_decode($infos['course_delivery_visible'], true) : null;

            if ($data['delivery'] == 139) {
                //dd($infos['course_inclass_city']);
                $data['inclass']['absences'] = $infos['course_inclass_absences'];
                $data['inclass']['city']['text'] = $infos['course_inclass_city'];
                $data['inclass']['city']['icon'] = json_decode($infos['course_inclass_city_icon'], true);

                $data['inclass']['dates'] = ($infos['course_inclass_dates'] != null && $infos['course_inclass_dates'] != '[]') ? json_decode($infos['course_inclass_dates'], true) : null;
                $data['inclass']['days'] = ($infos['course_inclass_days'] != null && $infos['course_inclass_days'] != '[]') ? json_decode($infos['course_inclass_days'], true) : null;
                $data['inclass']['times'] = ($infos['course_inclass_times'] != null && $infos['course_inclass_times'] != '[]') ? json_decode($infos['course_inclass_times'], true) : null;

                $data['inclass']['elearning_access'] = ($infos['course_elearning_access'] != null) ? json_decode($infos['course_elearning_access'], true) : null;
                $data['inclass']['elearning_access_icon'] = ($infos['course_elearning_access_icon'] != null) ? json_decode($infos['course_elearning_access_icon'], true) : null;
                $data['inclass']['elearning_exam'] = ($infos['course_elearning_exam']) ? true : false;
            } elseif ($data['delivery'] == 143) {
                $data['elearning']['visible'] = $infos['course_elearning_visible'] != null ? json_decode($infos['course_elearning_visible'], true) : null;
                $data['elearning']['icon'] = $infos['course_elearning_icon'] != null ? json_decode($infos['course_elearning_icon'], true) : null;
                $data['elearning']['expiration'] = $infos['course_elearning_expiration'] != null ? $infos['course_elearning_expiration'] : null;
                $data['elearning']['text'] = $infos['course_elearning_text'] != null ? $infos['course_elearning_text'] : null;
                $data['elearning']['title'] = $infos['course_elearning_expiration_title'] != null ? $infos['course_elearning_expiration_title'] : null;

                $data['elearning']['exam']['visible'] = $infos['course_elearning_exam_visible'] != null ? json_decode($infos['course_elearning_exam_visible'], true) : null;
                $data['elearning']['exam']['icon'] = $infos['course_elearning_exam_icon'] != null ? json_decode($infos['course_elearning_exam_icon'], true) : null;
                $data['elearning']['exam']['text'] = $infos['course_elearning_exam_text'] != null ? $infos['course_elearning_exam_text'] : null;
                $data['elearning']['exam']['title'] = $infos['course_elearning_exam_title'] != null ? $infos['course_elearning_exam_title'] : null;

                $data['elearning']['exam']['activate_months'] = $infos['course_elearning_exam_activate_months'] != null ? json_decode($infos['course_elearning_exam_activate_months'], true) : null;
            } elseif ($data['delivery'] == 215) {
                $data['inclass']['absences'] = $infos['course_inclass_absences'];
                $data['inclass']['dates'] = ($infos['course_inclass_dates'] != null && $infos['course_inclass_dates'] != '[]') ? json_decode($infos['course_inclass_dates'], true) : null;
                $data['inclass']['days'] = ($infos['course_inclass_days'] != null && $infos['course_inclass_days'] != '[]') ? json_decode($infos['course_inclass_days'], true) : null;
                $data['inclass']['times'] = ($infos['course_inclass_times'] != null && $infos['course_inclass_times'] != '[]') ? json_decode($infos['course_inclass_times'], true) : null;

                $data['inclass']['elearning_access'] = ($infos['course_elearning_access'] != null) ? json_decode($infos['course_elearning_access'], true) : null;
                $data['inclass']['elearning_access_icon'] = ($infos['course_elearning_access_icon'] != null) ? json_decode($infos['course_elearning_access_icon'], true) : null;
                $data['inclass']['elearning_exam'] = ($infos['course_elearning_exam']) ? true : false;
            }

            $data['awards']['text'] = $infos['course_awards_text'];
            $data['awards']['icon'] = $infos['course_awards_icon'] != null ? json_decode($infos['course_awards_icon'], true) : null;

            $data['payment_method'] = $infos['course_payment_method'];
            $data['payment_icon'] = $infos['course_payment_icon'] != null ? json_decode($infos['course_payment_icon'], true) : null;
            $data['payment_installments'] = $infos['course_payment_installments'];

            $data['partner']['status'] = $infos['course_partner'];
            $data['partner']['text'] = $infos['course_partner_text'];
            $data['partner']['icon'] = $infos['course_partner_icon'] != null ? json_decode($infos['course_partner_icon'], true) : null;
            $data['partner']['visible'] = $infos['course_partner_visible'] != null ? json_decode($infos['course_partner_visible'], true) : null;

            $data['manager']['status'] = $infos['course_manager'];
            $data['manager']['icon'] = $infos['course_manager_icon'] != null ? json_decode($infos['course_manager_icon'], true) : null;

            //$data['certificate']['event_title'] = $infos['course_certification_event_title'];
            $data['certificate']['messages']['completion'] = $infos['course_certification_completion'];
            $data['certificate']['messages']['success'] = $infos['course_certification_name_success'];
            //$data['certificate']['messages']['failure'] = $infos['course_certification_name_failure'];
            $data['certificate']['type'] = $infos['course_certification_type'];
            $data['certificate']['title'] = $infos['course_certification_title'];
            $data['certificate']['text'] = $infos['course_certification_text'];
            //$data['certificate']['attendance_title'] = $infos['course_certification_attendance_title'];
            $data['certificate']['visible'] = $infos['course_certification_visible'] != null ? json_decode($infos['course_certification_visible'], true) : null;
            $data['certificate']['icon'] = $infos['course_certification_icon'] != null ? json_decode($infos['course_certification_icon'], true) : null;
            $data['certificate']['has_certificate'] = $infos['has_certificate'];
            $data['certificate']['has_certificate_exam'] = $infos['has_certificate_exam'];

            $data['students']['number'] = (int) $infos['course_students_number'];
            $data['students']['text'] = $infos['course_students_text'];
            $data['students']['title'] = $infos['course_students_title'];
            $data['students']['visible'] = $infos['course_students_visible'] != null ? json_decode($infos['course_students_visible'], true) : null;
            $data['students']['icon'] = $infos['course_students_icon'] != null ? json_decode($infos['course_students_icon'], true) : null;
        }

        return $data;
    }

    public function isFree()
    {
        $free = true;
        $infos = $this->event_info();

        if (isset($infos['payment_method']) && $infos['payment_method'] != 'free') {
            $free = false;
        }

        return $free;
    }

    public function hasCertificate()
    {
        $hasCertificate = false;
        $infos = $this->event_info();

        if (isset($infos['certificate']['has_certificate']) && $infos['certificate']['has_certificate']) {
            $hasCertificate = true;
        }

        return $hasCertificate;
    }

    public function hasCertificateExam()
    {
        $hasCertificateExam = false;
        $infos = $this->event_info();

        if (isset($infos['certificate']['has_certificate_exam']) && $infos['certificate']['has_certificate_exam']) {
            $hasCertificateExam = true;
        }

        return $hasCertificateExam;
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

        foreach ($this->allLessons()->wherePivot('priority', '>=', $from)->get() as  $pLesson) {
            $newPriorityLesson = $pLesson->pivot->priority + 1;
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();

            $or[$pLesson->pivot->lesson_id] = [
                'topic_id'=>$pLesson->pivot->topic_id,
                'lesson_id'=>$pLesson->pivot->lesson_id,
                'event_id' => $pLesson->pivot->event_id,
                'instructor_id' => $pLesson->pivot->instructor_id,
                'date' => $pLesson->pivot->date,
                'time_starts' => $pLesson->pivot->time_starts,
                'time_ends' => $pLesson->pivot->time_ends,
                'duration' => $pLesson->pivot->duration,
                'room' => $pLesson->pivot->room,
                'location_url'=> $pLesson->pivot->location_url,
                'automate_mail'=>$pLesson->pivot->automate_mail,
                'send_automate_mail'=>$pLesson->pivot->send_automate_mail,
                'priority' => $newPriorityLesson,
            ];
        }

        $this->allLessons()->detach(array_keys($or));
        $this->allLessons()->attach($or);
    }

    public function fixOrder()
    {
        $newPriorityLesson = 1;
        $or = [];
        foreach ($this->allLessons()->orderBy('priority')->get() as  $pLesson) {
            //$pLesson->pivot->priority = $newPriorityLesson;
            //$pLesson->pivot->save();

            $or[$pLesson->pivot->lesson_id] = [
                'topic_id'=>$pLesson->pivot->topic_id,
                'lesson_id'=>$pLesson->pivot->lesson_id,
                'event_id' => $pLesson->pivot->event_id,
                'instructor_id' => $pLesson->pivot->instructor_id,
                'date' => $pLesson->pivot->date,
                'time_starts' => $pLesson->pivot->time_starts,
                'time_ends' => $pLesson->pivot->time_ends,
                'duration' => $pLesson->pivot->duration,
                'room' => $pLesson->pivot->room,
                'location_url'=> $pLesson->pivot->location_url,
                'automate_mail'=>$pLesson->pivot->automate_mail,
                'send_automate_mail'=>$pLesson->pivot->send_automate_mail,
                'priority' => $newPriorityLesson,
            ];
            $newPriorityLesson += 1;
        }

        $this->allLessons()->detach(array_keys($or));
        $this->allLessons()->attach($or);
    }

    public function getAccessInMonths()
    {
        $eventInfo = $this->event_info1;
        $accessMonths = isset($eventInfo['course_elearning_expiration']) ? $eventInfo['course_elearning_expiration'] : 0;

        return $accessMonths;
    }

    public function countdown()
    {
        return $this->belongsToMany(Countdown::class, 'cms_countdown_event');
    }

    public function resetCache()
    {
        Cache::forget('topics-event-status-' . $this->id);
    }
}

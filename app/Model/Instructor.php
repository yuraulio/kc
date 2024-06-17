<?php

namespace App\Model;

use App\Model\Media;
use App\Model\Topic;
use App\Model\User;
use App\Traits\MediaTrait;
use App\Traits\MetasTrait;
use App\Traits\PaginateTable;
use App\Traits\SearchFilter;
use App\Traits\SlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Instructor extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use MediaTrait;
    use Notifiable;
    use SearchFilter;
    use PaginateTable;

    protected $table = 'instructors';

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
    }

    protected $fillable = [
        'priority',
        'status',
        'comment_status',
        'title',
        'short_title',
        'subtitle',
        'header',
        'summary',
        'mobile',
        'body',
        'ext_url',
        'social_media',
        'author_id',
        'creator_id',
        'company',
        'cache_income',
    ];

    protected $casts = [
        'social_media' => 'array',
    ];

    public function lesson()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor');
    }

    public function elearningEvents()
    {
        $now = date('Y-m-d');

        //return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('summary1', 'category', 'slugable','dropbox')->wherePivot('instructor_id',$this->user()->first()->id)->wherePivot('time_starts','>=',$now)->orWhere('time_starts',null)->whereIn('status',[0,2,3])->where('published',true)->with('slugable','category','city')->distinct();
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('summary1', 'category', 'slugable', 'dropbox')->whereIn('status', [0, 2, 3, 4])->where('published', true)->with('slugable', 'category', 'city')->distinct();
    }

    public function elearningEventsForRoyalties()
    {
        $now = date('Y-m-d');

        //return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('summary1', 'category', 'slugable','dropbox')->wherePivot('instructor_id',$this->user()->first()->id)->wherePivot('time_starts','>=',$now)->orWhere('time_starts',null)->whereIn('status',[0,2,3])->where('published',true)->with('slugable','category','city')->distinct();
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->whereIn('status', [0, 2, 3, 4])->where('published', true)
                ->whereHas('event_info1', function ($q) {
                    $q->where('course_payment_method', '!=', 'free');
                })
                ->whereHas('event_info1', function ($q) {
                    $q->where('course_delivery', 143);
                })->distinct();
    }

    public function event()
    {
        $now = date('Y-m-d');

        //return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('summary1', 'category', 'slugable','dropbox')->wherePivot('instructor_id',$this->user()->first()->id)->wherePivot('time_starts','>=',$now)->orWhere('time_starts',null)->whereIn('status',[0,2,3])->where('published',true)->with('slugable','category','city')->distinct();
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('summary1', 'category', 'slugable', 'dropbox')->wherePivot('time_starts', '>=', $now)->orWhere('time_starts', null)->whereIn('status', [0, 2, 3, 4])->where('published', true)->with('slugable', 'category', 'city')->distinct();
    }

    public function eventInstructorPage()
    {
        $now = date('Y-m-d');

        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->wherePivot('time_starts', '>=', $now)->orWhere('time_starts', null)->whereIn('status', [0, 2, 3])->where('published', true)->with('slugable', 'category', 'city')->distinct();
    }

    public function testimonials()
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable');
    }

    public function medias()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'instructors_user');
    }
}

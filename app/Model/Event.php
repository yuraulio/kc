<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Ticket;
use App\Model\Category;
use App\Model\Type;
use App\Model\City;
use App\Model\Summary;
use App\Model\Delivery;
use App\Model\Section;
use App\Model\Benefit;
use App\Model\Venue;
use App\Model\User;
use App\Model\Partner;
use App\Model\Lesson;
use App\Model\Instructor;
use App\Traits\SlugTrait;
use App\Traits\MetasTrait;
use App\Traits\BenefitTrait;

class Event extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use BenefitTrait;

    protected $table = 'events';

    protected $fillable = [
        'priority', 'published', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'hours','author_id', 'creator_id', 'view_tpl', 'view_counter'
    ];

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function topic()
    {
        
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->select('topics.*','topic_id')
            ->withPivot('event_id','topic_id','lesson_id','instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority');
    }

    public function lessons()
    {
        
        return $this->belongsToMany(Lesson::class,'event_topic_lesson_instructor')->where('status',true)->select('lessons.*','topic_id','event_id', 'lesson_id','instructor_id')
        ->withPivot('event_id','topic_id','lesson_id','instructor_id', 'date', 'time_starts', 'time_ends', 'duration', 'room', 'priority');
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class,'event_topic_lesson_instructor')->where('status',true)->select('instructors.*','lesson_id')->withPivot('lesson_id');
    }


    public function summary()
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id' );
    }

    public function is_inclass_course()
    {   
        
        if($this->delivery->first() && $this->delivery->first()->id == 2){
            return true;
        }else{
            return false;
        }
    }

    public function faqs()
    {
        return $this->belongsToMany(Faq::class, 'events_faqevent', 'event_id', 'events_faqevent' );
    }

    public function delivery()
    {
        return $this->belongsToMany(Delivery::class, 'event_delivery', 'event_id', 'delivery_id');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets')->withPivot('id','priority', 'price', 'options', 'quantity', 'features');
    }

    public function city()
    {
        return $this->belongsToMany(City::class, 'event_city');
    }

    public function career()
    {
        return $this->belongsToMany(Career::class, 'career_event');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'sectiontitles_event', 'event_id', 'section_title_id');
    }

    public function benefits()
    {
        return $this->morphToMany(Benefit::class, 'benefitable');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'event_venue');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'event_partner');
    }

    public function paymentMethod()
    {
        return $this->belongsToMany(PaymentMethod::class, 'paymentmethod_event');
    }

    public function topicsLessonsInstructors(){
        $topics = [];
        //dd($this->topic->unique()->groupBy('topic_id'));
        //dd($this->lessons->groupBy('topic_id'));
        //dd($this->instructors->groupBy('lesson_id'));
        $lessons = $this->lessons->groupBy('topic_id');
        foreach($this->topic->groupBy('topic_id') as $key => $topic){
            //dd($topic);
            dd($lessons[$key]);

        }

    }

}

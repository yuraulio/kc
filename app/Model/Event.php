<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Event;
use App\Model\Ticket;
use App\Category;
use App\Model\Type;
use App\Model\City;
use App\Model\Summary;
use App\Model\Section;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'priority', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'hours','author_id', 'creator_id', 'view_tpl', 'view_counter'
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
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor');
    }


    public function user()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }

    public function summary()
    {
        return $this->belongsToMany(Summary::class, 'events_summaryevent', 'event_id', 'summary_event_id' );
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_tickets');
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
        return $this->belongsToMany(Section::class, 'sectiontitles_event');
    }

}

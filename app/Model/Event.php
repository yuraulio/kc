<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Category;
use App\Model\Type;
use App\Model\City;
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

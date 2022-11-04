<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Category;
use App\Model\Lesson;
use App\Model\Event;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topics';

    protected $fillable = [
        'priority', 'status', 'comment_status', 'title', 'short_title', 'subtitle', 'header', 'summary', 'body', 'author_id', 'creator_id', 'email_template'
    ];

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function topic()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function category1()
    {
        return $this->belongsToMany(Category::class, 'categories_topics_lesson','category_id', 'topic_id');
    }

    public function lessonsCategory()
    {
        return $this->belongsToMany(Lesson::class, 'categories_topics_lesson')->withPivot('category_id','priority');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->withPivot('event_topic_lesson_instructor.priority')->with('type')->orderBy('event_topic_lesson_instructor.priority','asc');
    }

    public function event_topic()
    {
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->withPivot('event_id','lesson_id','instructor_id','date','priority','time_starts','time_ends','duration','room');
    }

    public function event_lesson()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->select('lessons.*','event_id')->where('status',true)->withPivot('event_id','lesson_id','instructor_id','date','priority','time_starts','time_ends','duration','room')->with('instructor');
    }

}

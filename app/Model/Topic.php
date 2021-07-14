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
        'priority', 'status', 'comment_status', 'title', 'short_title', 'subtitle', 'header', 'summary', 'body', 'author_id', 'creator_id'
    ];

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function topic()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function lessonsCategory()
    {
        return $this->belongsToMany(Lesson::class, 'categories_topics_lesson');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->withPivot('event_topic_lesson_instructor.priority')->with('type')->orderBy('event_topic_lesson_instructor.priority','asc');
    }

    public function event_topic()
    {
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')->withPivot('event_id','lesson_id','instructor_id');
    }

    public function event_lesson()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')->select('event_id')->where('status',true)->withPivot('event_id','lesson_id','instructor_id','topic_id')->with('instructor');
    }

}

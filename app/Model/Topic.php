<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Category;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topics';

    protected $fillable = [
        'priority', 'status', 'comment_status', 'title', 'short_title', 'subtitle', 'header', 'summary', 'body', 'author_id', 'creator_id'
    ];

    // public function event()
    // {
    //     return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor', 'topic_id','event_id' );
    // }

    // public function category()
    // {
    //     return $this->belongsToMany(Category::class, 'categories_topics_lesson');
    // }

    ///////////////////////////////////////NEW

    public function category()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    // public function lessons()
    // {
    //     return $this->belongsToMany(category_topic_lesson::class, 'categoryable');
    // }

    

}

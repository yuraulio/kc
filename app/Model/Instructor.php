<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Testimonial;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructors';

    protected $fillable = [
        'priority', 'status', 'comment_status', 'title', 'short_title', 'subtitle', 'header', 'summary', 'body', 'ext_url', 'author_id', 'creator_id'
    ];

    public function lesson()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor');
    }

    public function testimonials()
    {
        return $this->morphToMany(Testimonial::class, 'testimoniable');
    }
}

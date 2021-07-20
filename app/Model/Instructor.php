<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Media;
use App\Model\User;
use App\Traits\SlugTrait;
use App\Traits\MetasTrait;
use App\Traits\MediaTrait;

class Instructor extends Model
{
    use HasFactory;
    use SlugTrait;
    use MetasTrait;
    use MediaTrait;

    protected $table = 'instructors';

    protected $fillable = [
        'priority', 'status', 'comment_status', 'title', 'short_title', 'subtitle', 'header', 'summary', 'body', 'ext_url', 'social_media','author_id', 'creator_id'
    ];

    public function lesson()
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor');
    }

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor')->with('slugable');
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

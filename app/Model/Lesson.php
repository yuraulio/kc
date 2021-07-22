<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Type;
use App\Model\Slug;
use App\Model\Category;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'priority', 'status', 'links' ,'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'vimeo_video', 'vimeo_duration','author_id', 'creator_id'
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'categories_topics_lesson');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'categories_topics_lesson');
    }



    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'event_topic_lesson_instructor')->select('instructors.*')->with('medias','slugable');

    }

    public function get_instructor($id)
    {
        $instructor = Instructor::find($id);
        return $instructor;
    }
}

<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Slug;
use App\Model\Topic;
use App\Model\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'priority', 'status', 'links', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'vimeo_video', 'vimeo_duration', 'author_id', 'creator_id', 'bold',
    ];

    protected $casts = [
        // TODO: Implement a JSON cast for the links attribute so that we can remove any json encoding and decoding we're doing throughout the app
        // 'links' => 'json',
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class, 'categories_topics_lesson')->withPivot('category_id', 'lesson_id', 'topic_id', 'priority')->orderBy('priority', 'asc');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class, 'categories_topics_lesson')->withPivot('priority')->orderBy('priority', 'asc');
    }

    public function type()
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'event_topic_lesson_instructor')
            ->select('instructors.*')
            ->distinct('instructors.id')
            ->with('medias', 'slugable');
    }

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_topic_lesson_instructor');
    }

    public function get_instructor($id)
    {
        $instructor = Instructor::find($id);

        return $instructor;
    }

    public function deletee()
    {
        $this->event()->detach();
        $this->topic()->detach();
        $this->delete();
    }
}

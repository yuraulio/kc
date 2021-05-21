<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Topic;
use App\Model\Type;
use App\Model\Category;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'priority', 'status', 'title', 'htmlTitle', 'subtitle', 'header', 'summary', 'body', 'vimeo_video', 'vimeo_duration','author_id', 'creator_id'
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
}

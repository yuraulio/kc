<?php

namespace App\Model;

use App\Model\Category;
use App\Model\Event;
use App\Model\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;

class Topic extends Model
{
    use HasFactory;
    protected $table = 'topics';

    protected $fillable = [
        'priority',
        'status',
        'comment_status',
        'title',
        'short_title',
        'subtitle',
        'header',
        'summary',
        'body',
        'author_id',
        'creator_id',
        'email_template',
    ];

    public function category(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable')->withPivot('priority');
    }

    public function topic(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function category1(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_topics_lesson', 'category_id', 'topic_id');
    }

    public function lessonsCategory(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'categories_topics_lesson')
            ->withPivot('category_id', 'priority');
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
            ->withPivot('event_topic_lesson_instructor.priority')
            ->with('type')
            ->orderBy('event_topic_lesson_instructor.priority')
            ->groupBy('id');
    }

    public function event_topic(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'event_topic_lesson_instructor')
            ->withPivot('event_id', 'lesson_id', 'instructor_id', 'date', 'priority', 'time_starts', 'time_ends', 'duration', 'room');
    }

    public function event_lesson(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'event_topic_lesson_instructor')
            ->select('lessons.*', 'event_id')
            ->where('status', true)
            ->withPivot('event_id', 'lesson_id', 'instructor_id', 'date', 'priority', 'time_starts', 'time_ends', 'duration', 'room')
            ->with('instructor')
            ->orderBy('event_topic_lesson_instructor.priority')
            ->groupBy('id');
    }

    public function scopeForCategory(Builder $query, int $categoryId): Builder
    {
        return $query->whereHas('category', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        });
    }
}

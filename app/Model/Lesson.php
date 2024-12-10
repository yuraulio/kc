<?php

namespace App\Model;

use App\Services\QueryString\Parameter\SearchParameter;
use App\Services\QueryString\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Lesson extends Model
{
    use HasFactory, Filterable;

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

    public function email_trigger_logs(): MorphToMany
    {
        return $this->morphToMany(EmailTrigger::class, 'email_triggerables');
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

    public function scopeSearch(Builder $builder, SearchParameter $search): Builder
    {
        return $builder->where(function ($query) use ($search) {
            $searchableFields = [
                'title',
                'htmlTitle',
                'subtitle',
                'header',
            ];

            foreach ($searchableFields as $searchableField) {
                $query->orWhere($searchableField, 'LIKE', $search->getTerm());
            }
        });
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, TopicLesson::class);
    }
}

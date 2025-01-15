<?php

namespace App\Services\Lesson;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Dto\Api\v1\Lesson\LessonDto;
use App\Model\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LessonService implements ILessonService
{
    public function filterQuery(array $data): Builder
    {
        return Lesson::query()->with('category')
            ->withCount(['event', 'topic'])
            ->when(array_key_exists('delivery', $data), function ($query) use ($data) {
                $query->whereHas('event', function ($query) use ($data) {
                    $query->whereHas('deliveries', function ($query) use ($data) {
                        $query->where('deliveries.id', $data['delivery']);
                    });
                });
            })->when(array_key_exists('course', $data), function ($query) use ($data) {
                $query->whereHas('event', function ($query) use ($data) {
                    $query->where('events.id', $data['course']);
                });
            })->when(array_key_exists('topic', $data), function ($query) use ($data) {
                $query->whereHas('topic', function ($query) use ($data) {
                    $query->where('topics.id', $data['topic']);
                });
            })->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('users.title', 'like', '%' . $data['query'] . '%');
                });
            })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc');
    }

    public function create(LessonDto $dto)
    {
        $lesson = Lesson::create($dto->getData());
        $lesson->category()->associate($dto->getCategoryId());
        $lesson->save();

        $courses = $dto->getCourses();
        if (is_array($courses)) {
            $lesson->event()->sync($courses);
        }

        return $lesson;
    }

    public function update(Lesson $lesson, LessonDto $dto): Lesson
    {
        $lesson->update($dto->getData());

        $lesson->category()->associate($dto->getCategoryId());
        $lesson->save();

        $courses = $dto->getCourses();
        if (is_array($courses)) {
            $lesson->event()->sync($courses);
        }

        return $lesson;
    }
}

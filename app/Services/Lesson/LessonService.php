<?php

namespace App\Services\Lesson;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Dto\Api\v1\Lesson\LessonDto;
use App\Model\Lesson;

class LessonService implements ILessonService
{
    public function create(LessonDto $dto)
    {
        $lesson = Lesson::create($dto->getData());

        $lesson->category()->sync($dto->getCategories());

        $courses = $dto->getCourses();
        if (is_array($courses)) {
            $lesson->event()->sync($courses);
        }

        return $lesson;
    }

    public function update(Lesson $lesson, LessonDto $dto): Lesson
    {
        $lesson->update($dto->getData());

        $lesson->category()->sync($dto->getCategories());

        $courses = $dto->getCourses();
        if (is_array($courses)) {
            $lesson->event()->sync($courses);
        }

        return $lesson;
    }
}

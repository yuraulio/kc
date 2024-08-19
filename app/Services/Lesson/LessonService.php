<?php

namespace App\Services\Lesson;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Dto\Api\v1\Lesson\LessonDto;
use App\Model\Lesson;

class LessonService implements ILessonService
{
    public function create(LessonDto $dto)
    {
        return Lesson::create($dto->getData());
    }

    public function update(Lesson $lesson, LessonDto $dto): Lesson
    {
        $lesson->update($dto->getData());

        return $lesson;
    }
}

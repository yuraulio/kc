<?php

namespace App\Contracts\Api\v1\Lesson;

use App\Dto\Api\v1\Lesson\LessonDto;
use App\Model\Lesson;

interface ILessonService
{
    public function create(LessonDto $dto);

    public function update(Lesson $lesson, LessonDto $dto): Lesson;
}

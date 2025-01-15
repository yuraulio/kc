<?php

namespace App\Contracts\Api\v1\Lesson;

use App\Dto\Api\v1\Lesson\LessonDto;
use App\Model\Lesson;
use Illuminate\Database\Eloquent\Builder;

interface ILessonService
{
    public function filterQuery(array $data): Builder;

    public function create(LessonDto $dto);

    public function update(Lesson $lesson, LessonDto $dto): Lesson;
}

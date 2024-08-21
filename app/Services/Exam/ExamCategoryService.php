<?php

namespace App\Services\Exam;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Dto\Api\v1\ExamCategory\LessonDto;
use App\Model\ExamCategory;
use Illuminate\Http\Request;

class ExamCategoryService
{
    public function create(Request $request)
    {
        return ExamCategory::create(['title' => $request->title, 'description' => $request->description]);
    }

    public function delete(ExamCategory $examCategory)
    {
        return $examCategory->delete();
    }
}

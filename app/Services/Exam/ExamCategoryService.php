<?php

namespace App\Services\Exam;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Model\ExamCategory;
use Illuminate\Http\Request;

class ExamCategoryService
{
    public function createOrUpdate(Request $request)
    {
        return ExamCategory::updateOrCreate(
            ['id'=>$request->id],
            ['title' => $request->title, 'description' => $request->description]
        );
    }

    public function delete(ExamCategory $examCategory)
    {
        return $examCategory->delete();
    }
}

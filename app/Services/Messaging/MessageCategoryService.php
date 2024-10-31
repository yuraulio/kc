<?php

namespace App\Services\Messaging;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Model\MessageCategory;
use Illuminate\Http\Request;

class MessageCategoryService
{
    public function createOrUpdate(Request $request)
    {
        return MessageCategory::updateOrCreate(
            ['id'=>$request->id],
            ['title' => $request->title, 'description' => $request->description, 'published' => $request->published]
        );
    }

    public function delete(MessageCategory $messageCategory)
    {
        return $messageCategory->delete();
    }
}

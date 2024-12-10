<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Lesson;
use App\Model\Topic;
use App\Model\TopicLesson;
use Illuminate\Support\Facades\DB;

class TopicService
{
    public function attachLesson(Topic $topic, Lesson $lesson): bool
    {
        $priority = $topic->lessonList()->orderBy('priority', 'desc')->first()->priority + 1;
        $topic->lessonList()->sync([$lesson, 'priority' => $priority]);

        return true;
    }

    public function changePriority(Topic $topic, Lesson $lesson, int $newPriority): bool
    {
        $item = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->where('lessons_id', $lesson->id)
            ->first();

        $oldPriority = $item->priority;

        if ($oldPriority === $newPriority) {
            return true;
        }

        if ($newPriority > $oldPriority) {
            TopicLesson::where('order', '>', $oldPriority)
                ->where('order', '<=', $newPriority)
                ->update([
                    'order' => DB::raw('order - 1'),
                ]);
        } else {
            TopicLesson::where('order', '<', $oldPriority)
                ->where('order', '>=', $newPriority)
                ->update([
                    'order' => DB::raw('order + 1'),
                ]);
        }

        $item->update(['order' => $newPriority]);

        return true;
    }
}

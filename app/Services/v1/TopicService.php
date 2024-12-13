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
        $existsAlready = $item = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->where('lesson_id', $lesson->id)
            ->exists();

        if ($existsAlready) {
            return true;
        }

        $priority = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->orderBy('priority', 'desc')
            ->first()
            ->priority ?? 0;

        $topic->lessonList()->syncWithoutDetaching([$lesson->id => ['priority' => $priority + 1]]);

        return true;
    }

    public function changePriority(Topic $topic, Lesson $lesson, int $newPriority): bool
    {
        $item = TopicLesson::query()
            ->where('topic_id', $topic->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        $oldPriority = $item->priority;

        if (!$item || $oldPriority === $newPriority) {
            return true;
        }

        if ($newPriority > $oldPriority) {
            TopicLesson::where('priority', '>', $oldPriority)
                ->where('priority', '<=', $newPriority)
                ->where('topic_id', $topic->id)
                ->update([
                    'priority' => DB::raw('priority - 1'),
                ]);
        } else {
            TopicLesson::where('priority', '<', $oldPriority)
                ->where('priority', '>=', $newPriority)
                ->where('topic_id', $topic->id)
                ->update([
                    'priority' => DB::raw('priority + 1'),
                ]);
        }

        $item->update(['priority' => $newPriority]);

        return true;
    }
}

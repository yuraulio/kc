<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Model\Event;
use App\Model\EventTopic;
use App\Model\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function store(array $data): Event
    {
        $event = new Event($data);
        $event->save();

        if (array_key_exists('media_id', $data)) {
            $event->medias()->create([$data['media_id'] ?? null]);
        }

        $event->createSlug(array_key_exists('slug', $data) ? $data['slug'] : $data['title']);

        if (array_key_exists('topic_id', $data)) {
            $event->topic()->sync([$data['topic_id']] ?? null);
        }
        if (array_key_exists('audience_id', $data)) {
            $event->audiences()->sync([$data['audience_id']] ?? null);
        }
        if (array_key_exists('delivery_id', $data)) {
            $event->delivery()->sync([$data['delivery_id']] ?? null);
        }
        $event->eventInfo()
            ->create(
                [
                    'course_payment_method' => $data['course_payment_method'],
                    'language_id'           => $data['language_id'] ?? null,
                ]
            );
        $event->summary()->createMany([
            [
                'section' => 'duration',
            ],
            [
                'section' => 'date',
            ],
            [
                'section' => 'duration',
            ], [
                'section' => 'duration',
            ], [
                'section' => 'duration',
            ], [
                'section' => 'duration',
            ], [
                'section' => 'duration',
            ],
        ]);

        event(
            new ActivityEvent(
                Auth::user(),
                ActivityEventEnum::CourseAdded->value,
                '',
                Auth::user(),
                $event
            ),
        );

        $event->load($this->getRelations());

        return $event;
    }

    public function update(Event $event, array $data): Event
    {
        $event->fill($data);
        $event->save();

        if (array_key_exists('topic_id', $data)) {
            //TODO should be removed after a complete transition to the new relations logic
            $event->topic()->sync([$data['topic_id']] ?? null);

            $priority = $event->topics()->orderBy('priority', 'desc')->first()->priority + 1;
            $event->topics()->sync([$data['topic_id'], 'priority' => $priority] ?? null);
        }
        if (array_key_exists('audience_id', $data)) {
            $event->audiences()->sync([$data['audience_id']] ?? null);
        }
        if (array_key_exists('delivery_id', $data)) {
            $event->delivery()->sync([$data['delivery_id']] ?? null);
        }

        $eventInfo = $event->eventInfo;
        $eventInfo->fill($data);
        $eventInfo->save();

        $event->load($this->getRelations());

        return $event;
    }

    public function getRelations(): array
    {
        return [
            'delivery',
            'audiences',
        ];
    }

    public function changePriority(Event $event, Topic $topic, int $newPriority): bool
    {
        $item = EventTopic::query()
            ->where('topic_id', $topic->id)
            ->where('event_id', $event->id)
            ->first();

        $oldPriority = $item->priority;

        if (!$item || $oldPriority === $newPriority) {
            return true;
        }

        if ($newPriority > $oldPriority) {
            EventTopic::where('priority', '>', $oldPriority)
                ->where('priority', '<=', $newPriority)
                ->where('event_id', $event->id)
                ->update([
                    'priority' => DB::raw('priority - 1'),
                ]);
        } else {
            EventTopic::where('priority', '<', $oldPriority)
                ->where('priority', '>=', $newPriority)
                ->where('event_id', $event->id)
                ->update([
                    'priority' => DB::raw('priority + 1'),
                ]);
        }

        $item->update(['priority' => $newPriority]);

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Model\Event;
use Illuminate\Support\Facades\Auth;

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
            $event->topic()->sync([$data['topic_id']] ?? null);
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
}

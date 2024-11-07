<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventDuplicationService
{
    /**
     * Default values of some fields which should be reset for an event duplicate.
     */
    private const DEFAULTS = [
        'published' => false,
        'release_date_files' => null,
        'published_at' => null,
        'index' => false,
        'feed' => false,
        'launch_date' => null,
        'enroll' => false,
    ];

    /**
     * Fields values of need to be marked as a copy.
     */
    private const MARK_FIELDS = [
        'title',
        'htmlTitle',
        'xml_title',
        'xml_description',
        'xml_short_description',
    ];

    /**
     * The string used to mark copies of text values.
     */
    private const COPY_MARK = ' (COPY)';

    /**
     * Creates a duplicate of an event.
     *
     * @param  Event  $event
     * @return bool
     */
    public function duplicate(Event $event): bool
    {
        try {
            DB::beginTransaction();

            $duplicate = $event->replicate();

            $this->setDefaults($duplicate);
            $this->markFieldsCopied($duplicate);
            $duplicate->push();

            $duplicate->createMedia();
            $duplicate->createSlug($duplicate->title);

            $event->load(
                'category',
                'faqs',
                'sectionVideos',
                'type',
                'delivery',
                'ticket',
                'city',
                'sections',
                'venues',
                'syllabus',
                'paymentMethod',
                'dropbox',
                'eventInfo'
            );

            $this->duplicateMedias($event, $duplicate);
            $this->duplicateRelations($event, $duplicate);
            $this->attachLessons($event, $duplicate);
            $this->duplicateMetas($event, $duplicate);
            $this->inactivateTickets($duplicate);

            DB::commit();

            return true;
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to duplicate event: {message}', ['message' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Marks some text fields to be distinguishable form original.
     *
     * @param  Event  $event
     * @param  string  $mark
     * @return void
     */
    protected function markFieldsCopied(Event $event, string $mark = self::COPY_MARK): void
    {
        foreach (self::MARK_FIELDS as $field_name) {
            if (!empty($event->{$field_name})) {
                $event->{$field_name} .= $mark;
            }
        }
    }

    /**
     * Sets default values for duplicated event.
     *
     * @param  Event  $event
     * @return void
     */
    protected function setDefaults(Event $event): void
    {
        foreach (self::DEFAULTS as $field_name => $value) {
            $event->{$field_name} = $value;
        }
    }

    /**
     * Handles event medias duplication.
     *
     * @param  Event  $event
     * @param  Event  $duplicate
     * @return void
     */
    protected function duplicateMedias(Event $event, Event $duplicate): void
    {
        if ($event->medias && $event->medias->mediable_type === 'App\Model\Event') {
            $new_media = $event->medias->replicate();
            $new_media->mediable_id = $duplicate->id;
            $new_media->save();
        }
    }

    /**
     * Handles event relations duplication.
     *
     * @param  Event  $event
     * @param  Event  $duplicate
     * @return void
     */
    protected function duplicateRelations(Event $event, Event $duplicate): void
    {
        foreach ($event->getRelations() as $relationName => $relationValues) {
            if ($relationName === 'summary1' || $relationName === 'benefits' || $relationName === 'sections') {
                $newValues = [];
                foreach ($relationValues as $relationValue) {
                    $value = $relationValue->replicate();
                    $value->push();
                    $newValues[] = $value;
                }
                $newValues = collect($newValues);
                $duplicate->{$relationName}()->detach();

                foreach ($newValues as $newValue) {
                    $duplicate->{$relationName}()->attach($newValue);
                }
            } elseif ($relationName === 'eventInfo') {
                $value = $relationValues->replicate();
                $value->course_elearning_access = null;
                $value->push();
                $duplicate->{$relationName}()->save($value);
            } elseif ($relationName !== 'medias') {
                $duplicate->{$relationName}()->sync($relationValues);
            }
        }
    }

    /**
     * Attaches lessons to a duplicate event.
     *
     * @param  Event  $event
     * @param  Event  $duplicate
     * @return void
     */
    protected function attachLessons(Event $event, Event $duplicate): void
    {
        foreach ($event->lessons as $lesson) {
            if (!$lesson->pivot) {
                continue;
            }

            $duplicate->lessons()->attach(
                $lesson->pivot->lesson_id,
                [
                    'topic_id'=>$lesson->pivot->topic_id,
                    'date'=>$lesson->pivot->date,
                    'time_starts'=>$lesson->pivot->time_starts,
                    'time_ends'=>$lesson->pivot->time_ends,
                    'duration' => $lesson->pivot->duration,
                    'room' => $lesson->pivot->room,
                    'instructor_id' => $lesson->pivot->instructor_id,
                    'priority' => $lesson->pivot->priority,
                    'automate_mail'=>$lesson->pivot->automate_mail,
                ]
            );
        }
    }

    /**
     * Duplicates event metas.
     *
     * @param  Event  $event
     * @param  Event  $duplicate
     * @return void
     */
    protected function duplicateMetas(Event $event, Event $duplicate): void
    {
        $duplicate->createMetas();
        $duplicate->metable->meta_title = $event->metable ? $event->metable->meta_title . self::COPY_MARK : '';
        $duplicate->metable->meta_description = $event->metable ? $event->metable->meta_description . self::COPY_MARK : '';
        $duplicate->metable->save();
    }

    /**
     * @param  Event  $event
     * @return void
     */
    protected function inactivateTickets(Event $event): void
    {
        foreach ($event->ticket as $ticket) {
            $ticket->pivot->active = false;
            $ticket->pivot->save();
        }
    }
}

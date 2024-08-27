<?php

namespace App\Services\Event;

use App\Model\Event;
use App\Model\Topic;
use Barryvdh\DomPDF\PDF;

class EventSyllabusService
{
    private const VIEW_PATH = 'theme.event.syllabus_print';

    private const PAGE_SIZE = 'A4';

    private const PAGE_ORIENTATION = 'landscape';

    private PDF $pdfGenerator;

    public function __construct(PDF $pdf)
    {
        $this->pdfGenerator = $pdf;
    }

    public function getSyllabusFileForEvent(Event $event): PDF
    {
        $data['content'] = $event;

        $data['eventtopics'] = $event->topicsLessonsInstructors()['topics'];
        if (!$event->is_inclass_course()) {
            array_multisort(array_column($data['eventtopics'], 'priority'), SORT_ASC, $data['eventtopics']);
        }

        $data['desc'] = [];
        foreach ($data['eventtopics'] as $key => $topic) {
            $topic = Topic::where('title', $key)->first();

            if ($topic) {
                $data['desc'][$key] = $topic->summary;
            }
        }

        $data['instructors'] = $event->topicsLessonsInstructors()['instructors'];

        return $this->pdfGenerator
            ->loadView(self::VIEW_PATH, $data)
            ->setPaper(self::PAGE_SIZE, self::PAGE_ORIENTATION);
    }
}

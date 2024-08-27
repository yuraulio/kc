<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use App\Services\Event\EventSyllabusService;
use Illuminate\Http\Response;

class EventDownloadSyllabusController extends ApiBaseController
{
    public function __invoke(Event $event, EventSyllabusService $eventSyllabusService): Response
    {
        return $eventSyllabusService->getSyllabusFileForEvent($event)
            ->download(sprintf('%s.pdf', $event->title));
    }
}

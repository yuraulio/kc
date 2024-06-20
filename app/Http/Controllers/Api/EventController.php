<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends ApiBaseController
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Event::query(), $request);

        $events = $query->paginate((int) $request->query->get('per_page', 50))
            ->appends($request->query->all());

        return new JsonResponse($events);
    }

    /**
     * Display the event.
     *
     * @param  Request  $request
     * @param  Event  $event
     *
     * @return JsonResponse
     */
    public function show(Request $request, Event $event): JsonResponse
    {
        $event = $this->applyRequestParametersToModel($event, $request);

        return new JsonResponse($event);
    }
}

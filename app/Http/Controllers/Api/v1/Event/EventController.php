<?php

namespace App\Http\Controllers\Api\v1\Event;

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

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
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
        return new JsonResponse(
            $this->applyRequestParametersToModel($event, $request)
        );
    }
}

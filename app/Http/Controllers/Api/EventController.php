<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($request, Event::query());

        $events = $query->paginate((int) $request->query->get('per_page', 50))
            ->appends($request->query->all());

        return new JsonResponse($events);
    }

    /**
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        // TODO: discuss which relations are needed to load
        return new JsonResponse($event->load([
            'category',
            'event_info1',
//            'lessons',
//            'instructors',
            'delivery',
            'slugable',
            'metable',
//            'faqs',
            'coupons',
            'sections',
        ]));
    }
}

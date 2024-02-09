<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Event;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = Event::where('published', true)
            ->where('status', 0)
            ->get();

        return new JsonResponse($events);
    }

    /**
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        return new JsonResponse($event);
    }
}

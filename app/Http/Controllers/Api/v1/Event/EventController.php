<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\v1\Event\UpdateEventRequest;
use App\Http\Resources\Api\v1\Event\Settings\CourseSettingsResource;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * Display the event.
     */
    public function show(Request $request, Event $event, IEventSettingsService $settingsService): JsonResponse
    {
        $event = $this->applyRequestParametersToModel($event, $request);
        $event->settings = CourseSettingsResource::make($settingsService->getEventSettings($event));

        return new JsonResponse($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event, IEventSettingsService $settingsService): JsonResponse
    {
        // update other fields
        // ...
        // update settings tab
        $settingsService->updateSettings($event, $request->toDto());

        return new JsonResponse([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        return new JsonResponse([]);
    }
}

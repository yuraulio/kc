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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function certificatePreview(string $template)
    {
        $templates = [
            'certificate',
            'certificate_facebook_marketing',
            'kc_attendance',
            'kc_attendance_2022a',
            'kc_attendance_2022b',
            'kc_deree_attendance',
            'kc_deree_attendance_2022',
            'kc_deree_diploma',
            'kc_deree_diploma_2022',
            'kc_diploma',
            'kc_diploma_2022a',
            'kc_diploma_2022b',
            'new_kc_certificate',
            'new_kc_deree_certificate',
        ];

        $certificate = [
            'success' => true,
            'meta_title' => '#META TITLE#',
            'firstname' => '#FIRSTNAME#',
            'lastname' => '#LASTNAME#',
            'certification_title' => '#CERTIFICATION TITLE#',
            'certification_date' => '#DATE#',
            'expiration_date' => '#EXP. DATE#',
            'credential' => '#CREDENTIAL#',
        ];

        if (in_array($template, $templates)) {
            return view("admin.certificates.{$template}", compact('certificate'));
        }

        throw new NotFoundHttpException('Certificate template not found');
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Messaging\WebNotificationResource;
use App\Model\WebNotification;
use App\Services\Messaging\WebNotificationService;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebNotificationController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(WebNotification::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WebNotificationService $webNotificationService): WebNotificationResource
    {
        return WebNotificationResource::make(
            $webNotificationService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, WebNotification $webNotification): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($webNotification, $request)
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, WebNotification $webNotification, WebNotificationService $webNotificationService): Bool
    {
        return $webNotificationService->updateNotification($webNotification, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebNotification $webNotification, WebNotificationService $webNotificationService): Response
    {
        $webNotificationService->delete($webNotification);

        return response()->noContent();
    }

    public function triggerWebNotification(Request $request, WebNotificationService $webNotificationService)
    {
        $notification = $webNotificationService->findTriggerByScreen($request);
        if($notification) {
            return ['data'=>$notification];
        }        
        return response()->json(['data' => null, 'message' => 'No notification found.']);
    }
}

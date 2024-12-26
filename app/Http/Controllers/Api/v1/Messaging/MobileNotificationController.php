<?php

namespace App\Http\Controllers\Api\v1\Messaging;

use App\Enums\Email\PageTriggerEnum;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Messaging\MobileNotificationResource;
use App\Model\MobileNotification;
use App\Services\Messaging\MobileNotificationService;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileNotificationController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(MobileNotification::query(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MobileNotificationService $MobileNotificationService): MobileNotificationResource
    {
        return MobileNotificationResource::make(
            $MobileNotificationService->createOrUpdate($request),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, MobileNotification $mobileNotification): JsonResponse
    {
        return new JsonResponse(
            $this->applyRequestParametersToModel($mobileNotification, $request)
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, MobileNotification $mobileNotification, MobileNotificationService $mobileNotificationService): Bool
    {
        return $mobileNotificationService->updateNotification($mobileNotification, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MobileNotification $mobileNotification, MobileNotificationService $MobileNotificationService): Response
    {
        $MobileNotificationService->delete($mobileNotification);

        return response()->noContent();
    }

    public function getPageTriggers()
    {
        return ['data' => PageTriggerEnum::getPageTriggers()];
    }

    public function triggerMobileNotification(Request $request, MobileNotificationService $mobileNotificationService)
    {
        $notification = $mobileNotificationService->findTriggerByScreen($request);
        if ($notification) {
            return ['data'=>$notification];
        }

        return response()->json(['data' => null, 'message' => 'No notification found.']);
    }
}

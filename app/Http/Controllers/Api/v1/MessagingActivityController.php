<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\MessagingActivity;
use App\Model\User;
use App\Services\EmailSendService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class MessagingActivityController extends ApiBaseController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, EmailSendService $emailSendService)
    {
        if (isset($request->event)) {
            return $emailSendService->recordWebhook($request->all());
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function showByUser(int $userId, Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(MessagingActivity::whereHas('user', function (Builder $q) use ($userId) {
            $q->where('messaging_activityables_id', $userId);
        }), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Display the specified resource.
     */
    public function showByEvent(int $eventId, Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(MessagingActivity::whereHas('event', function (Builder $q) use ($eventId) {
            $q->where('messaging_activityables_id', $eventId);
        }), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessagingActivity $messagingActivity, EmailSendService $emailSendService): Response
    {
        $emailSendService->delete($messagingActivity);

        return response()->noContent();
    }

    public function sendAgain(Request $request, EmailSendService $emailSendService)
    {
        $validation = Validator::make(Request::all(), [
            'id' => 'required',
        ]);

        if ($validation->fails()) {
            return ['status' => 'error', 'message' => $validation->errors()];
        } else {
            return $request->all();
            // $activity = MessagingActivity::where('id', $request->id)->first();
            // $emailSendService->sendAgain($request->all());
        }
    }

    public function getEmail($activityId, EmailSendService $emailSendService)
    {
        return $emailSendService->getMessagingEmailByTemplate($activityId);
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Contracts\Api\v1\Event\IEventStatistic;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventStatsController extends ApiBaseController
{
    public function __invoke(Event $event, Request $request, IEventStatistic $eventStatisticService): JsonResponse
    {
        return response()->json([
            'data' => $eventStatisticService->calculateEventStatistics(
                $event,
                $request->only(
                    ['calculateSubscription'],
                ),
            )]);
    }
}

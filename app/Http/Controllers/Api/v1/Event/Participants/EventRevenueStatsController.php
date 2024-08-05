<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;

class EventRevenueStatsController extends ApiBaseController
{
    public function __invoke(Event $event): JsonResponse
    {
        return new JsonResponse([
            'data' => [],
        ]);
    }
}

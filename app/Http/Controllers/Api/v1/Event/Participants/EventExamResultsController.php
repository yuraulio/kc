<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventExamResultsController extends ApiBaseController
{
    /**
     * Returns the stats about the course exams.
     */
    public function __invoke(Event $event): JsonResponse
    {
        $exam = $event->exam()
            ->orderBy('created_at', 'desc')
            ->first();

        if (empty($exam)) {
            throw new NotFoundHttpException(sprintf('No exams were found for the %s event.', $event->id));
        }

        [, $averageTime, $averagePercentage] = $exam->getResults();

        return new JsonResponse([
            'data' => [
                'average_percentage' => $averagePercentage,
                'average_time' => $averageTime,
            ],
        ]);
    }
}

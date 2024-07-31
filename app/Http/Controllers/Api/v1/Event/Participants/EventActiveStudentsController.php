<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use App\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class EventActiveStudentsController extends ApiBaseController
{

    /**
     * Returns the stats about the course students.
     */
    public function __invoke(Event $event): JsonResponse
    {
        $data = [
            'active_students' => 0,
            'e_learning' => 0,
            'in_class' => 0,
        ];

        $event->users->each(function (User $user) use(&$data): void {
            if ($user->pivot->expiration && $user->pivot->paid == 1) {
                $expiration = Carbon::parse($user->pivot->expiration);

                if ($expiration->lessThanOrEqualTo(Carbon::now())) {
                    return;
                }

                if (empty(trim((string) $user->pivot->comment))) {
                    $data['e_learning'] += 1;
                }

                if (str_contains((string) $user->pivot->comment, 'enroll from')) {
                    $data['in_class'] += 1;
                }
            }
        });

        $data['active_students'] = $data['e_learning'] + $data['in_class'];

        return new JsonResponse(compact('data'));
    }
}

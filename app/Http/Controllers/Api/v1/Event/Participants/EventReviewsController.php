<?php

namespace App\Http\Controllers\Api\v1\Event\Participants;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Event\Participants\ReviewResource;
use App\Model\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventReviewsController extends ApiBaseController
{
    public function __invoke(Event $event): AnonymousResourceCollection
    {
        $data = collect([
            (object) [
                'id' => 1,
                'kc_id' => 'kc_id',
                'firstname' => 'Matt',
                'lastname' => 'Matovich',
                'email' => 'matt@student.com',
                'rating' => 5,
                'review_title' => 'Great',
                'review_desc' => 'This is text of the review',
                'date' => '2024-08-12 11:11',
                'published' => true,
                'event_id' => $event->id,
                'student_id' => 1,
            ],
        ]);

        return ReviewResource::collection($data);
    }
}

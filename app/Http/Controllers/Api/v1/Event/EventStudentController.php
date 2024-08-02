<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use App\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventStudentController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($event->users(), $request);
        $students = $this->paginateByRequestParameters($query, $request);

        // Add the data about users progress.
        $students->through(function (User $user) use ($event): User {
            $user->setAttribute('progress', round($event->progress($user), 2));
            $user->setAttribute('videos_seen', $event->video_seen($user));

            return $user;
        });

        return new JsonResponse($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

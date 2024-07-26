<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventInstructorController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($event->instructors(), $request);

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
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

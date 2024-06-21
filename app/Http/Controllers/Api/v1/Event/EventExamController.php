<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Model\Event;
use App\Model\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventExamController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery($event->exam(), $request);

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
    public function show(Request $request, Event $event, Exam $exam): JsonResponse
    {
        $exam = $this->applyRequestParametersToModel($exam, $request);

        return new JsonResponse($exam);
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

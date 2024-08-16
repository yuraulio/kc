<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Resources\Api\v1\Event\Topics\TopicResource;
use App\Model\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventTopicController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        $query = $this->applyRequestParametersToQuery($event->allTopics(), $request);
        $topics = $this->paginateByRequestParameters($query, $request);

        return TopicResource::collection($topics)->response()->getData(true);
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

<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\Api\v1\Topic\ITopicService;
use App\Http\Requests\Api\v1\Topic\CreateTopicRequest;
use App\Http\Requests\Api\v1\Topic\UpdateTopicRequest;
use App\Http\Resources\Api\v1\Event\Topics\TopicResource;
use App\Model\Event;
use App\Model\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TopicController extends ApiBaseController
{
    public function index(Request $request)
    {
        $query = $this->applyRequestParametersToQuery(Topic::query(), $request);

        return TopicResource::collection(
            $this->paginateByRequestParameters($query, $request)
        )->response()->getData(true);
    }

    public function store(CreateTopicRequest $request, ITopicService $topicService): TopicResource
    {
        return TopicResource::make(
            $topicService->create($request->toDto()),
        );
    }

    public function show(string $id)
    {
        //
    }

    public function update(UpdateTopicRequest $request, Topic $topic, ITopicService $topicService): TopicResource
    {
        return TopicResource::make(
            $topicService->update($topic, $request->toDto()),
        );
    }

    public function destroy(Topic $topic): Response
    {
        $topic->delete();

        return response()->noContent();
    }
}

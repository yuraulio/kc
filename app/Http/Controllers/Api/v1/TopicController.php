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
        $query = Topic::query()->with('lessons.event')->withCount(['lessons', 'exam']);
        $query = $this->applyRequestParametersToQuery($query, $request);

        if ($delivery = $request->query->get('delivery')) {
            $query->whereHas('lessons', function ($query) use ($delivery) {
                $query->whereHas('event', function ($query) use ($delivery) {
                    $query->whereHas('deliveries', function ($query) use ($delivery) {
                        $query->where('deliveries.id', $delivery);
                    });
                });
            });
        }

        if ($course = $request->query->get('course')) {
            $query->whereHas('lessons', function ($query) use ($course) {
                $query->whereHas('event', function ($query) use ($course) {
                    $query->where('events.id', $course);
                });
            });
        }

        $query->latest();

        $topicsData = $this->paginateByRequestParameters($query, $request);

        $topicsData->each(function ($topic) {
            $coursesIds = $topic->lessons?->pluck('event.id')->toArray() ?? [];
            $topic->courses_count = count(array_unique($coursesIds));
        });

        return TopicResource::collection($topicsData)->response()->getData(true);
    }

    /**
     * Display a listing of the resource with course.
     */
    public function topicWithEvent(Request $request, ITopicService $topicService)
    {
        return TopicResource::addEventsInfo($topicService->getAllTopicWithEvents());
    }

    public function store(CreateTopicRequest $request, ITopicService $topicService): TopicResource
    {
        return TopicResource::make(
            $topicService->create($request->toDto()),
        );
    }

    public function clone(Topic $topic)
    {
        $newTopic = $topic->replicate();
        $newTopic->title = $topic->title . ' (Copy)';
        $newTopic->save();

        return TopicResource::make($newTopic);
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

<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\Api\v1\Topic\ITopicService;
use App\Http\Requests\Api\v1\Topic\ChangeLessonOrderRequest;
use App\Http\Requests\Api\v1\Topic\CreateTopicRequest;
use App\Http\Requests\Api\v1\Topic\UpdateTopicRequest;
use App\Http\Resources\Api\v1\Event\Topics\TopicResource;
use App\Model\Delivery;
use App\Model\Lesson;
use App\Model\Topic;
use App\Services\v1\TopicService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TopicController extends ApiBaseController
{
    public function __construct(private TopicService $service)
    {
    }

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

    public function show(Topic $topic): TopicResource
    {
        $this->attachAdditionalFields($topic);

        return TopicResource::make($topic);
    }

    public function attachAdditionalFields(Topic $topic): void
    {
        $events = $topic->events()->with('delivery')->get();

        $topic->classroom_courses = $this->eventWithDelivery($events, Delivery::CLASSROM_TRAINING);
        $topic->video_courses = $this->eventWithDelivery($events, Delivery::VIDEO_TRAINING);
        $topic->live_streaming_courses = $this->eventWithDelivery($events, Delivery::VIRTUAL_CLASS_TRAINING);
        $topic->exams = $topic->exam()->get()?->pluck('id')->toArray() ?? [];
        $topic->messages = [];
        $topic->messages_rules = '';
    }

    public function eventWithDelivery(Collection $events, int $type): array
    {
        return $events->where(function ($event) use ($type) {
            if ($event->delivery->where('id', $type)->count() > 0) {
                return true;
            }

            return false;
        })->pluck('id')->toArray();
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

    public function clone(Topic $topic): TopicResource
    {
        $newTopic = $topic->replicate();
        $newTopic->title = $topic->title . ' (Copy)';
        $newTopic->status = 0;
        $newTopic->save();

        return TopicResource::make($newTopic);
    }

    public function update(UpdateTopicRequest $request, Topic $topic, ITopicService $topicService): TopicResource
    {
        $topicService->update($topic, $request->toDto());
        $this->attachAdditionalFields($topic);

        return TopicResource::make($topic);
    }

    public function destroy(Topic $topic): Response
    {
        $topic->delete();

        return response()->noContent();
    }

    public function attachLesson(Topic $topic, Lesson $lesson): JsonResponse
    {
        return \response()->json(
            ['success' => $this->service->attachLesson($topic, $lesson)],
            Response::HTTP_OK);
    }

    public function changeLessonOrder(Topic $topic, Lesson $lesson, ChangeLessonOrderRequest $request): JsonResponse
    {
        return \response()->json(
            ['success' => $this->service->changePriority($topic, $lesson, $request->order)],
            Response::HTTP_OK);
    }
}

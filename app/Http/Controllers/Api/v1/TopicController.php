<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\Api\v1\Topic\ITopicService;
use App\Http\Requests\Api\v1\Topic\ChangeOrderRequest;
use App\Http\Requests\Api\v1\Topic\CreateTopicRequest;
use App\Http\Requests\Api\v1\Topic\FilterRequest;
use App\Http\Requests\Api\v1\Topic\OrphansRequest;
use App\Http\Requests\Api\v1\Topic\UpdateTopicRequest;
use App\Http\Resources\Api\v1\Event\Topics\TopicResource;
use App\Model\Delivery;
use App\Model\Lesson;
use App\Model\Topic;
use App\Services\v1\TopicService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TopicController extends ApiBaseController
{
    public function __construct(private TopicService $service)
    {
    }

    public function index(FilterRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Topic::class);

        $filterQuery = $this->service->filterQuery($request->validated());

        return TopicResource::collection($filterQuery->paginate($request->pet_page ?? 25));
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
            Response::HTTP_OK
        );
    }

    public function changeLessonOrder(Topic $topic, Lesson $lesson, ChangeOrderRequest $request): JsonResponse
    {
        return \response()->json(
            ['success' => $this->service->changePriority($topic, $lesson, $request->order)],
            Response::HTTP_OK
        );
    }

    public function getOrphans(OrphansRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();

        $topics = Topic::query()
            ->where(function ($q) {
                $q->whereDoesntHave('lessonList')
                    ->whereDoesntHave('eventList')
                    ->whereDoesntHave('events');
            })->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->whereDate('events.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->whereDate('events.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('events.title', 'like', '%' . $data['query'] . '%');
                });
            })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);

        return TopicResource::collection($topics);
    }
}

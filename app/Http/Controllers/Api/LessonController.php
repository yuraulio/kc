<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\v1\Lesson\CreateLessonRequest;
use App\Http\Requests\Api\v1\Lesson\UpdateLessonRequest;
use App\Http\Resources\Api\v1\Event\Lesson\LessonResource;
use App\Model\Delivery;
use App\Model\Lesson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LessonController extends ApiBaseController
{
    public function index(Request $request)
    {
        $query = Lesson::query()->with('category')->withCount(['event', 'topic']);
        $query = $this->applyRequestParametersToQuery($query, $request);

        if ($delivery = $request->query->get('delivery')) {
            $query->whereHas('event', function ($query) use ($delivery) {
                $query->whereHas('deliveries', function ($query) use ($delivery) {
                    $query->where('deliveries.id', $delivery);
                });
            });
        }

        if ($course = $request->query->get('course')) {
            $query->whereHas('event', function ($query) use ($course) {
                $query->where('events.id', $course);
            });
        }

        if ($topic = $request->query->get('topic')) {
            $query->whereHas('topic', function ($query) use ($topic) {
                $query->where('topics.id', $topic);
            });
        }

        $lessons = $this->paginateByRequestParameters($query, $request);

        return LessonResource::collection($lessons)->response()->getData(true);
    }

    public function show(Lesson $lesson): LessonResource
    {
        $lesson->load(['category']);
        $this->attachAdditionalFields($lesson);

        return LessonResource::make($lesson);
    }

    public function attachAdditionalFields(Lesson $lesson): void
    {
        $events = $lesson->event()->with('delivery')->get();

        $lesson->classroom_courses = $this->eventWithDelivery($events, Delivery::CLASSROM_TRAINING);
        $lesson->video_courses = $this->eventWithDelivery($events, Delivery::VIDEO_TRAINING);
        $lesson->live_streaming_courses = $this->eventWithDelivery($events, Delivery::VIRTUAL_CLASS_TRAINING);
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

    public function store(CreateLessonRequest $request, ILessonService $lessonService): LessonResource
    {
        return LessonResource::make(
            $lessonService->create($request->toDto()),
        );
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson, ILessonService $lessonService): LessonResource
    {
        return LessonResource::make(
            $lessonService->update($lesson, $request->toDto()),
        );
    }

    public function destroy(Lesson $lesson): Response
    {
        $lesson->delete();

        return response()->noContent();
    }

    public function saveNote(Request $request)
    {
        $user = Auth::user();
        $event_id = $request->event_id;
        $new_note = $request->note;
        $vimeo_id = $request->vimeo_id;
        $statistic = $user->statistic()->wherePivot('event_id', $event_id)->first();

        if (!$statistic) {
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_note = $statistic->pivot['notes'];
        $db_note = json_decode($db_note, true);

        if (isset($db_note[$vimeo_id])) {
            $db_note[$vimeo_id] = $new_note;
            $message = 'Update note successfully';
        } else {
            $db_note[$vimeo_id] = $new_note;
            $message = 'Create note successfully';
        }

        $db_note = json_encode($db_note);

        /*$user->statistic()->updateExistingPivot($user['id'], [
            'notes' => $db_note,
        ]);*/

        $user->statistic()->updateExistingPivot($event_id, [
            'notes' => $db_note,
        ]);

        return response()->json([
            'message' => $message,
        ]);
    }

    public function saveVideoProgress(Request $request)
    {
        $user = Auth::user();

        $event_id = $request->event_id;
        $vimeo_id = $request->vimeo_id;
        $progress = $request->progress;
        $stop_time = $request->stop_time;
        $seen = 0;

        if ($progress >= 0.8) {
            $seen = 1;
        }

        $db_video_original = $user->statistic()->wherePivot('event_id', $event_id)->first();

        if (!$db_video_original) {
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_video = json_decode($db_video_original->pivot['videos'], true);

        $arr = [];

        if (isset($db_video[$vimeo_id])) {
            //dd($db_video[$vimeo_id]);

            //dd($db_video[$vimeo_id]['seen']);

            $db_video[$vimeo_id]['seen'] = $db_video[$vimeo_id]['seen'] == 1 ? 1 : $seen;
            $db_video[$vimeo_id]['stop_time'] = $stop_time;
            $db_video[$vimeo_id]['percentMinutes'] = $progress;
            $db_video[$vimeo_id]['is_new'] = strval(0);

            if ((float) $stop_time > (float) $db_video[$vimeo_id]['total_seen']) {
                $db_video[$vimeo_id]['total_seen'] = $stop_time;
            }

            $db_video = json_encode($db_video);

            $message = 'Update video info successfully';
        } else {
            $arr['seen'] = $seen;
            $arr['stop_time'] = $stop_time;
            $arr['percentMinutes'] = $progress;
            $arr['total_seen'] = $stop_time;
            $arr['is_new'] = strval(0);
            $arr['send_automate_email'] = strval(0);

            //$arr = json_encode($arr);
            $db_video[$vimeo_id] = $arr;
            $db_video = json_encode($db_video);
            $message = 'Create video info successfully';
        }

        /*$user->statistic()->updateExistingPivot($user['id'], [
            'videos' => $db_video,
        ]);*/

        $user->statistic()
            ->wherePivot('event_id', $event_id)
            ->updateExistingPivot($event_id, [
                'videos' => $db_video,
                'lastVideoSeen' => $vimeo_id,
            ], false);

        /*$user->statistic()->updateExistingPivot($event_id, [
            'videos' => $db_video,
            'lastVideoSeen' => $vimeo_id
        ]);*/

        return response()->json([
            'message' => $message,
        ]);
    }

    public function updateVideoIsNew(Request $request)
    {
        $input = $request->all();

        $user = Auth::user();

        $event_id = $input['event_id'];
        $vimeo_id = $input['vimeo_id'];

        $db_video_original = $user->statistic()->wherePivot('event_id', $event_id)->first();

        if (!$db_video_original) {
            return new JsonResponse([
                'message' => 'Event statistic has not been found.',
            ], 404);
        }

        $db_video = json_decode($db_video_original->pivot['videos'], true);

        $arr = [];

        if (isset($db_video[$vimeo_id])) {
            $db_video[$vimeo_id]['is_new'] = 0;
            $db_video = json_encode($db_video);

            $message = 'Update video info successfully';
        } else {
            $arr['seen'] = strval(0);
            $arr['stop_time'] = strval(0);
            $arr['percentMinutes'] = strval(0);
            $arr['total_seen'] = strval(0);
            $arr['is_new'] = strval(0);

            //$arr = json_encode($arr);
            $db_video[$vimeo_id] = $arr;
            $db_video = json_encode($db_video);

            $message = 'Create video info is new successfully';
        }

        $user->statistic()
            ->wherePivot('event_id', $event_id)
            ->updateExistingPivot($event_id, [
                'videos' => $db_video,
                'lastVideoSeen' => $vimeo_id,
            ], false);

        return response()->json([
            'message' => $message,
        ]);
    }
}

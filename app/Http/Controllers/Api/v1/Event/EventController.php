<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\v1\Event\UpdateEventRequest;
use App\Http\Resources\Api\v1\Event\EventProgressCollection;
use App\Http\Resources\Api\v1\Event\Settings\CourseSettingsResource;
use App\Model\Event;
use App\Model\User;
use App\Services\v1\EventDuplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class EventController extends ApiBaseController
{
    public function __construct(
        private readonly EventDuplicationService $eventDuplicationService
    ) {
    }

    /**
     * Display a listing of the events.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Event::query(), $request);

        $userId = $request->userId;
        $query = $query->when($userId, function ($q) use ($userId) {
            $q->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        });

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * Display the event.
     */
    public function show(Request $request, Event $event, IEventSettingsService $settingsService): JsonResponse
    {
        $event = $this->applyRequestParametersToModel($event, $request);
        $event->settings = CourseSettingsResource::make($settingsService->getEventSettings($event));

        return new JsonResponse($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event, IEventSettingsService $settingsService): JsonResponse
    {
        // update other fields
        // ...
        // update settings tab
        $settingsService->updateSettings($event, $request->toDto());

        return new JsonResponse([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        return new JsonResponse([]);
    }

    public function certificatePreview(string $template)
    {
        $templates = [
            'certificate',
            'certificate_facebook_marketing',
            'kc_attendance',
            'kc_attendance_2022a',
            'kc_attendance_2022b',
            'kc_deree_attendance',
            'kc_deree_attendance_2022',
            'kc_deree_diploma',
            'kc_deree_diploma_2022',
            'kc_diploma',
            'kc_diploma_2022a',
            'kc_diploma_2022b',
            'new_kc_certificate',
            'new_kc_deree_certificate',
        ];

        $certificate = [
            'success' => true,
            'meta_title' => '#META TITLE#',
            'firstname' => '#FIRSTNAME#',
            'lastname' => '#LASTNAME#',
            'certification_title' => '#CERTIFICATION TITLE#',
            'certification_date' => '#DATE#',
            'expiration_date' => '#EXP. DATE#',
            'credential' => '#CREDENTIAL#',
        ];

        if (in_array($template, $templates)) {
            return view("admin.certificates.{$template}", compact('certificate'));
        }

        throw new NotFoundHttpException('Certificate template not found');
    }

    /**
     * @throws Throwable
     */
    public function checkSlugAvailability(Event $event, Request $request): JsonResponse
    {
        $inUse = Event::query()->whereHas('slugable', function ($query) use ($event, $request) {
            $query->where('slug', $request->input('slug'));
            $query->where('slugable_id', '!=', $event->id);
        })->exists();

        if ($inUse) {
            return response()->json(['message' => 'This slug is already in use', 'usage' => 'used_for_another_course'], 409);
        }

        $slug = $event->slugable()->first();

        if ($slug->slug == $request->input('slug')) {
            return response()->json(['message' => 'This slug is already in use for this course', 'usage' => 'used_for_this_course']);
        }

        return response()->json(['message' => 'This slug is available for using', 'usage' => 'available_for_using']);
    }

    /**
     * @throws Throwable
     */
    public function duplicateEvent(Event $event): Response
    {
        $status = $this->eventDuplicationService->duplicate($event) ? Response::HTTP_CREATED : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response('Duplication event attempt', $status);
    }

    public function userSubscriptions(User $user): JsonResponse
    {
        return $this->response(['subscriptions' => $user->subscriptionEvents]);
    }

    public function getEventProgress(User $user, Request $request): AnonymousResourceCollection
    {
        $events = $user->events_for_user_list1()
            ->with('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy($request->order_by ?? 'id', $request->order_type ?? 'desc')
            ->paginate($request->per_page ?? 5);

        return EventProgressCollection::collection($events);
    }
}

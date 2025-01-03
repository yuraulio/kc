<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\DeleteCourseRequest;
use App\Http\Requests\Api\v1\User\ExtendExpirationCourseRequest;
use App\Http\Requests\Api\v1\User\FilterCourseRequest;
use App\Http\Requests\Api\v1\User\MoveCourseRequest;
use App\Http\Resources\Api\v1\User\UserCourseResource;
use App\Http\Resources\Api\v1\User\UserSubscriptionResource;
use App\Model\Event;
use App\Model\User;
use App\Services\v1\UserEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Laravel\Cashier\Subscription;
use Symfony\Component\HttpFoundation\Response;

class UserEventController extends Controller
{
    public function __construct(private UserEventService $service)
    {
    }

    public function attachToCourse(User $user, Event $event): JsonResponse
    {
        return \response()->json(['success' => $this->service->attachToCourse($user, $event)], Response::HTTP_OK);
    }

    public function attachToSubscription(User $user, Subscription $subscription): JsonResponse
    {
        return \response()->json(['success' => $this->service->attachToSubscription($user, $subscription)], Response::HTTP_OK);
    }

    public function getUserCourses(User $user, FilterCourseRequest $request): AnonymousResourceCollection
    {
        return UserCourseResource::collection($this->service->getUserCourses($user, $request->validated()));
    }

    public function getUserSubscriptions(User $user, FilterCourseRequest $request): AnonymousResourceCollection
    {
        return UserSubscriptionResource::collection($this->service->getUserSubscriptions($user, $request->validated()));
    }

    public function getAbsences(User $user, Event $event): JsonResponse
    {
        return \response()->json($user->getAbsencesByEvent($event));
    }

    public function extendExpiration(User $user, Event $event, ExtendExpirationCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->extendExpiration($user, $event, $request->validated()),
                ],
                Response::HTTP_OK,
            );
    }

    public function extendSubscriptionExpiration(User $user, Subscription $subscription, ExtendExpirationCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->extendSubscriptionExpiration($user, $subscription, $request->validated()),
                ],
                Response::HTTP_OK,
            );
    }

    public function extendSubscriptionExpiration(User $user, Subscription $subscription, ExtendExpirationCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->extendSubscriptionExpiration($user, $subscription, $request->validated()),
                ], Response::HTTP_OK,
            );
    }

    public function extendSubscriptionExpiration(User $user, Subscription $subscription, ExtendExpirationCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->extendSubscriptionExpiration($user, $subscription, $request->validated()),
                ], Response::HTTP_OK,
            );
    }

    public function delete(User $user, Event $event, DeleteCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->removeTicket($user, $event, $request->validated()),
                ],
                Response::HTTP_OK,
            );
    }

    public function moveUser(User $user, Event $event, MoveCourseRequest $request): JsonResponse
    {
        return \response()
            ->json(
                [
                    'success' => $this->service->moveUser($user, $event, $request->validated()),
                ],
                Response::HTTP_OK,
            );
    }
}

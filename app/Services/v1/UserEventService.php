<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Model\Event;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription;

class UserEventService
{
    public function attachToCourse(User $user, Event $event): bool
    {
        $user->eventList()->sync([$event->id], false);

        $tagIds = $event->tags()->pluck('tags.id')->toArray();

        $user->tags()->syncWithoutDetaching($tagIds);

        return true;
    }

    public function attachToSubscription(User $user, Subscription $subscription): bool
    {
        $user->eventSubscriptions()->sync($subscription);

        return true;
    }

    public function getUserCourses(User $user, array $data): LengthAwarePaginator
    {
        $courses = $user->events_for_user_list1()->with([
            'transactions' => function ($q) use ($user) {
                $q->whereHas('user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                })->orderBy('created_at');
            },
            'delivery',
            'tickets'      => function ($q) use ($user) {
                $q->where('event_user_ticket.user_id', $user->id);
            },
        ])->when(array_key_exists('date_from', $data), function ($q) use ($data) {
            $q->whereDate('events.created_at', '>=', Carbon::parse($data['date_from']));
        })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
            $q->whereDate('events.created_at', '<=', Carbon::parse($data['date_to']));
        })->when(array_key_exists('query', $data), function ($q) use ($data) {
            $q->where(function ($q) use ($data) {
                $q->where('events.title', 'like', '%' . $data['query'] . '%');
            });
        })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);

        foreach ($courses as $course) {
            $course->progress = round($course->progress($user));
        }

        return $courses;
    }

    public function getUserSubscriptions(User $user, array $data): LengthAwarePaginator
    {
        $subscriptions = $user->eventSubscriptions()
            ->with([
                'transactions' => function ($q) use ($user) {
                    $q->whereHas('user', function ($query) use ($user) {
                        $query->where('id', $user);
                    })->orderBy('created_at');
                },
            ])->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);

        foreach ($subscriptions as $subscription) {
            $event = $subscription->event()->where('subscription_user_event.user_id', $user->id)->first();
            $subscription->progress = round($event->progress($user));
        }

        return $subscriptions;
    }

    public function extendExpiration(User $user, Event $event, array $data): bool
    {
        return (bool) $user->eventList()->updateExistingPivot($event, ['expiration' => $data['expiration']]);
    }

    public function removeTicket(User $user, Event $event, array $data): bool
    {
        //TODO Stripe remove actions

        $user->ticket()
            ->wherePivot('event_id', $event->id)
            ->wherePivotIn('ticket_id', $data['tickets'] ?? [])
            ->detach($data['tickets'] ?? []);

        $user->events_for_user_list()
            ->wherePivot('event_id', '=', $event->id)
            ->detach($event->id);

        $transaction = $event->transactionsByUser($user->id)->first();

        if ($transaction) {
            $transaction->event()->detach($event->id);
            $transaction->user()->detach($user->id);
            $transaction->delete();
        }

        event(new ActivityEvent($user, ActivityEventEnum::CourseDeleted->value, $event->title, Auth::user(), $event));

        return true;
    }

    public function moveUser(User $user, Event $event, array $data): bool
    {
        $user->ticket()
            ->wherePivot('event_id', '=', $event->id)
            ->update(['event_user_ticket.event_id' => $data['event_id']]);

        $user->eventList()->updateExistingPivot($event, ['event_id' => $data['event_id']]);

        $transaction = $event->transactionsByUser($user->id)->first();

        if ($transaction) {
            $transaction->event()->sync($data['event_id'], true);
        }

        $newEvent = Event::find($data['event_id']);

        event(
            new ActivityEvent(
            $user,
            ActivityEventEnum::UserMoved->value,
            'From ' . $event->title . ' to ' . $newEvent->title,
            Auth::user(),
            $newEvent
        )
        );

        return true;
    }

    public function extendSubscriptionExpiration(User $user, Subscription $subscription, array $data): bool
    {
        return (bool)$user->eventSubscriptions()->updateExistingPivot($subscription, ['expiration' => $data['expiration']]);
    }
}

<?php

namespace App\Services;

use App\Exceptions\UserAlreadyEnrolledToTheCourseException;
use App\Model\Event;
use App\Model\PaymentMethod;
use App\Model\Plan;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;
use Stripe\Stripe;

class SubscriptionService
{
    /**
     * This function prevents student to subscribe to the same course multiple times.
     */
    public function checkMultiplePayments(
        User $user,
        Event $event,
    ): void {
        $sameEvent = $user->subscriptionEvents->where('id', $event->id)->first();
        if ($sameEvent) {
            if (Carbon::parse($sameEvent->pivot->expiration)->subDays(60)->greaterThan(Carbon::now())) {
                throw new UserAlreadyEnrolledToTheCourseException(
                    [$user->email],
                    [$sameEvent->title],
                );
            }
        }
    }

    public function walletGetTotal(array $requestData): float | int
    {
        $plan = Plan::select('cost')->where('name', $requestData['plan'])->first();

        return $plan->cost * 100;
    }

    public function changeSubscriptionStatus(User $user, int $subscriptionId, string $newStatus): Subscription
    {
        $subscription = Subscription::where('user_id', $user->id)->where('id', $subscriptionId)->first();

        if (!$subscription) {
            throw new ModelNotFoundException('The subscription with id: ' . $subscriptionId . ' not found');
        }

        $subscription->syncStripeStatus();
        $subscription->refresh();

        DB::beginTransaction();

        Log::info("Attempt to change a status for subscription: {$subscription->stripe_id}. Current status is: {$subscription->status}");

        try {
            $paymentMethod = PaymentMethod::find(2);
            if (config('app.PAYMENT_PRODUCTION')) {
                Stripe::setApiKey($paymentMethod->processor_options['secret_key']);
            } else {
                Stripe::setApiKey($paymentMethod->test_processor_options['secret_key']);
            }
            session()->put('payment_method', $paymentMethod->id);

            if ($newStatus == 'Cancel') {
                if (!$subscription->status) {
                    DB::commit();

                    return $subscription;
                }

                $subscription->status = false;
                $subscription->stripe_status = 'cancelled';
                $subscription->save();
                $subscription = $subscription->cancel();
            } elseif ($newStatus == 'Active') {
                if (!$subscription->onGracePeriod()) {
                    $subscription['need_new_subscription'] = true;
                    $subscription['new_subscription_link'] = $this->getNewSubscriptionPaymentLink($subscription);

                    DB::commit();

                    return $subscription;
                }

                $subscription->status = true;
                $subscription->stripe_status = 'active';
                $subscription->save();
                $subscription = $subscription->resume();
            } else {
                throw new \Exception('SubscriptionService@changeSubscriptionStatus Error: Incorrect subscription status: ' . $newStatus);
            }

            Log::info("The status of subscription: {$subscription->stripe_id} is successfully changed to : {$newStatus}.");
            DB::commit();
        } catch (\Exception $exception) {
            Log::info("Error during the changing the status of the subscription: {$subscription->stripe_id}.");
            DB::rollBack();

            throw $exception;
        }

        return $subscription;
    }

    private function getNewSubscriptionPaymentLink(Subscription $subscription): ?string
    {
        $eventIds = $subscription->event->pluck('id')->toArray();

        if (in_array(2304, $eventIds)) {
            $eventIds[] = 4724;
        }

        $plan = Plan::where('plans.published', true)
            ->leftjoin('plan_events as pe', 'pe.plan_id', '=', 'plans.id')
            ->leftjoin('events as ev', 'ev.id', '=', 'pe.event_id')
            ->whereIn('pe.event_id', $eventIds)
            ->first();

        return $plan ? "/myaccount/subscription/{$plan->title}/{$plan->name}" : null;
    }

    public function checkIfUserAlreadyHasSameSubscription(?User $user, int $eventForSubscriptionId, ?string $excludeSubscriptionId = null): ?Event
    {
        $subscribableELearningEventIds = [2304, 1350, 4724];

        if (in_array($eventForSubscriptionId, $subscribableELearningEventIds)) {
            $query = $user
                ->subscriptionEvents()
                ->wherePivotIn('event_id', $subscribableELearningEventIds)
                ->leftJoin('subscriptions', 'subscription_user_event.subscription_id', '=', 'subscriptions.id')
                ->wherePivot('expiration', '>', Carbon::now());

            if ($excludeSubscriptionId) {
                $query = $query->where('stripe_id', '!=', $excludeSubscriptionId);
            }
        } else {
            $query = $user
                ->subscriptionEvents()
                ->wherePivotIn('event_id', $eventForSubscriptionId)
                ->leftJoin('subscriptions', 'subscription_user_event.subscription_id', '=', 'subscriptions.id')
                ->wherePivot('expiration', '>', Carbon::now());

            if ($query) {
                $query = $query->where('stripe_id', '!=', $excludeSubscriptionId);
            }
        }

        return $query->first();
    }

    public function checkIfUserAlreadyHasSameEvent(?User $user, int $eventForSubscriptionId): ?Event
    {
        $subscribableELearningEventIds = [2304, 1350, 4724];

        if (in_array($eventForSubscriptionId, $subscribableELearningEventIds)) {
            $query = $user
                ->events()
                ->wherePivotIn('event_id', $subscribableELearningEventIds)
                ->wherePivot('expiration', '>', Carbon::now());
        } else {
            $query = $user
                ->events()
                ->wherePivotIn('event_id', $eventForSubscriptionId)
                ->wherePivot('expiration', '>', Carbon::now());
        }

        return $query->first();
    }

    public function calculateAnchorDateForSubscription(?User $user, int $eventId): ?Carbon
    {
        $anchor = null;
        $subscriptionEvent = $this->checkIfUserAlreadyHasSameSubscription($user, $eventId);
        if ($subscriptionEvent) {
            $offsetInDays = Carbon::parse($subscriptionEvent->pivot->expiration)->diffInDays(Carbon::now());
            $anchor = Carbon::now()->addDays($offsetInDays);
        }

        $regularEvent = $this->checkIfUserAlreadyHasSameEvent($user, $eventId);
        if ($regularEvent) {
            $offsetInDays = Carbon::parse($regularEvent->pivot->expiration)->diffInDays(Carbon::now());
            $anchor = Carbon::now()->addDays($offsetInDays);
        }

        return $anchor;
    }
}

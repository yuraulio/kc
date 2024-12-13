<?php

namespace App\View\Components;

use App\Model\Event;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnnualSubscriptionComponent extends Component
{
    public function __construct(
        private readonly mixed $plans,
        private readonly ?array $events,
        private readonly ?array $subscriptionEvents,
    ) {
    }

    public function render(): View|Closure|string
    {
        //TODO: move to database
        $subscribableELearningEventIds = [2304, 1350, 4724];

        $allEvents = collect([...$this->events ?? [], ...$this->subscriptionEvents ?? []]);

        $hasPlan = false;
        $availablePlans = [];
        $showPlan = null;

        if (isset($this->plans)) {
            foreach ($this->plans as $key => $plan) {
                foreach ($plan->events as $ev) {
                    $ev['plan'] = $plan;
                    $availablePlans[$ev->id] = $ev;
                }

                if ($plan->events->first()) {
                    $hasPlan = true;
                }
            }

            foreach ($subscribableELearningEventIds as $eventId) {
                if (isset($availablePlans[$eventId])) {
                    $showPlan = $availablePlans[$eventId];
                }
            }
        }

        $annualSubscriptionLinks = [];

        // plan with event exists
        $canBeShown = $showPlan != null && $hasPlan;

        if ($canBeShown) {
            $subscriptionIsAlreadyActive = false;
            $hasEventToShowAnnualSubscriptionPopup = false;

            // if user has event that expired or will expire in next 60 days
            // in case event is subscription
            // 1. subscription is unpaid
            // 2. subscription cancelled and will expire in next 60 days
            foreach ($subscribableELearningEventIds as $eventId) {
                $subscribableEvent = $allEvents->where('id', $eventId)->first();

                if ($subscribableEvent) {
                    if (isset($subscribableEvent['mySubscription'])) { // if event is a subscription
                        // if subscription is inactive
                        if (!$subscribableEvent['mySubscription']['status']) {
                            $hasEventToShowAnnualSubscriptionPopup =
                                $subscribableEvent['mySubscription']['stripe_status'] == 'unpaid'
                                || $this->checkExpirationIsIn60Days($subscribableEvent['mySubscription']['ends_at'] ?? null);
                        }
                    } else { // regular event
                        $hasEventToShowAnnualSubscriptionPopup =
                            $this->checkExpirationIsIn60Days($subscribableEvent['expiration'] ?? null);
                    }
                }
            }

            if (!$hasEventToShowAnnualSubscriptionPopup) {
                $eventsWithBonusCourseSubscribableExists = Event::whereIn('id', $allEvents->pluck('id')
                    ->toArray())
                    ->whereHas('event_info1', function ($query) use ($subscribableELearningEventIds) {
                        $query->where(function ($query) use ($subscribableELearningEventIds) {
                            foreach ($subscribableELearningEventIds as $subscribableELearningEventId) {
                                $query->orWhere('course_elearning_access', 'like', '%' . $subscribableELearningEventId . '%');
                            }
                        });
                    })
                    ->exists();

                $hasEventToShowAnnualSubscriptionPopup = $eventsWithBonusCourseSubscribableExists;
            }

            //check by category
//            if (!$hasEventToShowAnnualSubscriptionPopup && !$subscriptionIsAlreadyActive) {
//                foreach ($allEvents as $event) {
//                    $eventCategories = isset($event['category']) ? $event['category']->pluck('id')->toArray() : [];
//                    if ($showPlan['plan']->categories->whereIn('id', $eventCategories)->count()) {
//                        $completedAtParsed = isset($event['completed_at'])
//                                ? Carbon::createFromFormat('Y-m-d', $event['completed_at'])
//                                : null;
//
//                        // if event with same category is completed two months ago
//                        if ($event['status'] == 3 // 3 - completed
//                            && $completedAtParsed
//                            && $completedAtParsed->addDays(60)->lessThan(Carbon::now())
//                        ) {
//                            $hasEventToShowAnnualSubscriptionPopup = true;
//                        }
//                    }
//                }
//            }

            $canBeShown = $hasEventToShowAnnualSubscriptionPopup;

            if ($canBeShown) {
                foreach ($this->plans as $plan) {
                    $annualSubscriptionLinks[$showPlan['id']] = "/myaccount/subscription/{$showPlan['title']}/{$plan->name}";
                }
            }
        }

        return view('components.annual-subscription-component', [
            'showPlan'                => $showPlan,
            'annualSubscriptionLinks' => $annualSubscriptionLinks,
            'canBeShown'              => $canBeShown,
        ]);
    }

    private function checkExpirationIsIn60Days(?string $expiration): bool
    {
        if (!$expiration) {
            return false;
        }

        return Carbon::parse($expiration)->subDays(60)->lessThan(Carbon::now());
    }
}

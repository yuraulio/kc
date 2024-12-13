<?php

namespace App\View\Components;

use App\Model\Event;
use App\Services\EventService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StudentsCourseComponent extends Component
{
    private EventService $eventService;

    /**
     * Create a new component instance.
     */
    public function __construct(
        private readonly mixed $keyType,
        private ?array $event,
        private readonly int $tab,
        private readonly mixed $mySubscriptions,
        private readonly mixed $instructors,
        private readonly bool $isSubscription = false,
    ) {
        $this->eventService = app()->make(EventService::class);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $allInstallmentsPayed = false;
        $requiredInstalmentIsPaidAndEvenIsAvailable = false;
        $eventTicketPrice = 0;
        $subscriptionReenrolLink = '';

        if (!empty($this->event) && !empty($this->event['id'])) {
            $eventEloquent = Event::find($this->event['id']);

            $allInstallmentsPayed = $eventEloquent->allInstallmentsPayed();
            $requiredInstalmentIsPaidAndEvenIsAvailable =
                $this->eventService->checkRequiredInstalmentIsPaidAndEvenIsAvailable(
                    auth()->user(),
                    $this->event['id']
                );

            if ($this->event['view_tpl'] != 'elearning_free') {
                $ticket = $eventEloquent->ticket->where('type', 'Alumni')->first() ?? $eventEloquent->ticket->where('type', 'Alumni')->first();
                if ($ticket) {
                    $eventTicketPrice = intval($ticket->price);
                }
            }

            if ($this->event['view_tpl'] != 'elearning_free' && isset($this->event['mySubscription'])) {
                if ($eventEloquent->id == 2304) {
                    $newEvent = Event::find(4724);

                    if ($newEvent->plans->first()) {
                        $subscriptionReenrolLink = route('subscription.reenroll', ['event' => $newEvent->title, 'plan' => $newEvent->plans->first()->name]);
                    }
                } else {
                    if ($eventEloquent->plans->first()) {
                        $subscriptionReenrolLink = route('subscription.reenroll', ['event' => $eventEloquent->title, 'plan' => $eventEloquent->plans->first()->name]);
                    }
                }
            }
        }

        if ($this->isSubscription) {
            $this->event['status'] = null;
        }

        $releaseDateIsSet = false;

        // /admin/delivery
        // Class training: 139
        // Corporate training: 216
        // Video training: 143
        // Virtual class training: 215
        $isVideoTraining = $this->event['delivery'] == 143;

        if (!$isVideoTraining) {
            $releaseDateIsSet = strtotime($this->event['release_date_files']) >= 0;
        }

        $bonusFiles = ['_Bonus', 'Bonus', 'Bonus Files', 'Βonus', '_Βonus', 'Βonus', 'Βonus Files'];

        return view('components.students-course-component', [
            'keyType'                                    => $this->keyType,
            'event'                                      => $this->event,
            'allInstallmentsPayed'                       => $allInstallmentsPayed,
            'requiredInstalmentIsPaidAndEvenIsAvailable' => $requiredInstalmentIsPaidAndEvenIsAvailable,
            'tab'                                        => $this->tab,
            'mySubscriptions'                            => $this->mySubscriptions,
            'releaseDateIsSet'                           => $releaseDateIsSet,
            'isVideoTraining'                            => $isVideoTraining,
            'eventTicketPrice'                           => $eventTicketPrice,
            'subscriptionReenrolLink'                    => $subscriptionReenrolLink,
            'instructors'                                => $this->instructors,
            'isSubscription'                             => $this->isSubscription,
            'bonusFiles'                                 => $bonusFiles,
        ]);
    }
}

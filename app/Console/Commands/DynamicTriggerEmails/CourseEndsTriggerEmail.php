<?php

namespace App\Console\Commands\DynamicTriggerEmails;

use App\Events\EmailSent;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Services\EmailSendService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseEndsTriggerEmail extends Command
{
    // Command name and description
    protected $signature = 'email:courseEndsTriggerEmail';
    protected $description = 'Send automated email at the end of the course';

    public function __construct(protected readonly EmailSendService $emailSendService)
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->courseEndsTriggerEmail();
    }

    private function courseEndsTriggerEmail()
    {
        $emailTriggers = EmailTrigger::where('trigger_type', 'course_end_date')->get();

        foreach ($emailTriggers as $emailTrigger) {
            $filters = ($emailTrigger->trigger_filters);

            $statuses = array_column($filters['status'], 'id');
            $deliveries = array_column($filters['course_deliveries'], 'id');
            $courses = array_column($filters['course_ids'], 'id');

            $events = Event::has('transactions')->whereHas('eventInfo', function ($eventInfo) {
                return $eventInfo->where('course_payment_method', '<>', 'free');
            })->where('published', true)->with('users');
            if (count($statuses)) {
                $events = $events->whereIn('status', $statuses);
            }

            if (count($courses)) {
                $events = $events->whereIn('id', $courses);
            }

            if (count($deliveries)) {
                $events = $events->whereHas('eventInfo', function ($query) use ($deliveries) {
                    $query->whereIn('course_delivery', $deliveries);
                });
            }

            $events = $events->get();

            $today = date('Y-m-d', strtotime(($emailTrigger->value_sign * $emailTrigger->value) . ' day'));
            foreach ($events as $event) {
                foreach ($event['users'] as $user) {
                    if ($event->eventInfo->course_delivery !== '143') { // Not an Elearning course
                        $lesson = $event->lessons->last();
                        if (!isset($lesson['pivot']['time_ends']) ||
                            $today !== date('Y-m-d', strtotime($lesson['pivot']['time_ends']))
                        ) {
                            continue;
                        }
                        $expDate = date('Y-m-d', strtotime($lesson['pivot']['time_ends']));
                    } else { // Elearning course
                        if ($user->pivot->expiration !== $today || !$user->pivot->expiration) {
                            continue;
                        }
                        $expDate = $user->pivot->expiration;
                    }

                    $data['FNAME'] = $user->firstname;
                    $data['CourseName'] = $event->title;
                    $data['eventId'] = $event->id;
                    $data['LINK'] = url('/') . '/myaccount';
                    if (count($event->plans)) {
                        $data['SubscriptionPrice'] = $event->plans[0]['cost'];
                    }
                    $data['ExpirationDate'] = date('d-m-Y', strtotime($user->pivot->expiration));

                    $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, array_merge([
                        'FNAME'=> $user->firstname,
                    ], $data), ['event_id'=>$event->id]);

                    event(new EmailSent($user->email, $emailTrigger->email->title));
                }
            }
        }
    }
}

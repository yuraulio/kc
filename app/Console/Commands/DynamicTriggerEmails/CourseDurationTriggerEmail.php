<?php

namespace App\Console\Commands\DynamicTriggerEmails;

use App\Events\EmailSent;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\HalfPeriod;
use App\Services\EmailSendService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CourseDurationTriggerEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendCourseDurationTriggerEmail';
    protected $description = 'Send automated email based on course duration confirations of the course';

    public function __construct(protected readonly EmailSendService $emailSendService)
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendCourseDurationTriggerEmail();
    }

    private function sendCourseDurationTriggerEmail()
    {
        $emailTriggers = EmailTrigger::where('trigger_type', 'course_duration')->whereHas('email', function (Builder $q) {
            $query->where('status', 1);
        })->get();

        foreach ($emailTriggers as $emailTrigger) {
            $courseProgress = $emailTrigger->value / 100; //Convert to percentage
            $filters = ($emailTrigger->trigger_filters);

            $statuses = array_column($filters['status'], 'id');
            $deliveries = array_column($filters['course_deliveries'], 'id');
            $courses = array_column($filters['course_ids'], 'id');

            $events = Event::has('transactions')->where('published', true)->with('users');
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

            $today = date_create(date('Y/m/d'));
            $today1 = date('Y-m-d');
            foreach ($events as $event) {
                $eventInfo = $event->event_info();
                if ($event->eventInfo->course_delivery === '143') {
                    $expiration = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : null;
                } else {
                    $lastLessonEndTime = $event->finishClassDuration();
                    //Total number of months from launch date till the end of the last lesson of the course.
                    $launchDate = date_create($event->launch_date);
                    $endDate = date_create($lastLessonEndTime);
                    $expiration = $launchDate->diff($endDate)->m + ($launchDate->diff($endDate)->y * 12);
                }

                $courseExpirationDays = $expiration * 30 * $courseProgress;

                foreach ($event['users'] as $user) {
                    if ($event->eventInfo->course_delivery === '143' && ($user->pivot->expiration < $today1 || !$user->pivot->expiration || !$expiration)) {
                        continue;
                    } elseif ($event->eventInfo->course_delivery !== '143' &&
                    ($lastLessonEndTime < $today1 || !$lastLessonEndTime)
                    ) {
                        continue;
                    }

                    if ($event->eventInfo->course_delivery === '143') {
                        $date = date_create($user->pivot->expiration);
                        $date = date_diff($date, $today);
                    } else {
                        $date = date_diff($endDate, $today);
                    }

                    if ($date->days == $courseExpirationDays) {
                        $data['email_template'] = $emailTrigger->email->template['label'];

                        $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, [
                            'FNAME'=> $user->firstname,
                            'CourseName' => $event->title,
                            'LINK'=> $event->fb_group,
                        ], ['event_id'=>$event->id]);

                        event(new EmailSent($user->email, $emailTrigger->email->title));
                    }
                }
                $emailTrigger->course_trigger_logs()->save($event);
            }
        }
    }
}

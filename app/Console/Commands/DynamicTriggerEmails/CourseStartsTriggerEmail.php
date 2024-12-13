<?php

namespace App\Console\Commands\DynamicTriggerEmails;

use App\Events\EmailSent;
use App\Model\EmailTrigger;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Transaction;
use App\Notifications\InClassReminder;
use App\Services\EmailSendService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CourseStartsTriggerEmail extends Command
{
    // Command name and description
    protected $signature = 'email:courseStartsTriggerEmail';
    protected $description = 'Send automated email reminders about in-class events about to start';

    public function __construct(protected readonly EmailSendService $emailSendService)
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->courseStartsTriggerEmail();
    }

    private function courseStartsTriggerEmail()
    {
        $emailTriggers = EmailTrigger::where('trigger_type', 'course_start_date')->whereHas('email', function (Builder $query) {
            $query->where('status', 1);
        })->get();

        foreach ($emailTriggers as $emailTrigger) {
            $filters = ($emailTrigger->trigger_filters);

            $statuses = array_column($filters['status'], 'id');
            $deliveries = array_column($filters['course_deliveries'], 'id');
            $courses = array_column($filters['course_ids'], 'id');

            if (in_array(143, $deliveries)) { //Has elarning course
                $this->elearningCourseStartsTriggerEmail($emailTrigger, $statuses, $deliveries, $courses);
                $deliveries = array_diff($deliveries, [143]);
            }
            $this->sendCourseStartEmails($emailTrigger, $statuses, $deliveries, $courses);
        }
    }

    private function elearningCourseStartsTriggerEmail(EmailTrigger $emailTrigger, array $statuses, array $deliveries, array $courses)
    {
        // Logic for sending elearning course start emails
        $eventDate = date('Y-m-d', strtotime(($emailTrigger->value_sign * $emailTrigger->value) . ' day'));
        $transactions = Transaction::with('event', 'user')->has('event')
        ->whereDate('created_at', $eventDate)
        ->where(function ($q) use ($deliveries, $statuses, $courses) {
            if (count($deliveries)) {
                $q->whereHas('event.eventInfo', function ($query) use ($deliveries) {
                    $query->whereIn('course_delivery', $deliveries);
                });
            }
            if (count($statuses)) {
                $q->whereHas('event', function ($query) use ($statuses) {
                    $query->whereIn('status', $statuses);
                });
            }
            if (count($courses)) {
                $q->whereHas('event', function ($query) use ($courses) {
                    $query->whereIn('id', $courses);
                });
            }
        })
        ->get();

        foreach ($transactions as $transaction) {
            $event = $transaction->event->first();
            if (count($event->getExams()) <= 0 || !$event->expiration) {
                continue;
            }

            foreach ($transaction['user'] as $user) {
                $expiration = $event->users()->wherePivot('user_id', $user->id)->first();
                if (!$expiration) {
                    continue;
                }

                $data['elearningSlug'] = url('/') . '/myaccount/elearning/' . $event->title;

                $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, [
                    'FIRST_NAME'=> $user->firstname,
                    'CourseName'=>$event->title,
                    'CourseExpiration'=>date('d-m-Y', strtotime($expiration->pivot->expiration)),
                    'COURSE_LINK'=>$data['elearningSlug'],
                ], ['event_id'=>$event->id]);
                event(new EmailSent($user->email, $emailTrigger->email->title));
            }
            $emailTrigger->course_trigger_logs()->save($event);
        }
    }

    private function sendCourseStartEmails(EmailTrigger $emailTrigger, array $statuses, array $deliveries, array $courses)
    {
        $date1 = date('Y-m-d', strtotime(($emailTrigger->value_sign * $emailTrigger->value) . ' day'));

        $dates = [$date1];

        $events = Event::
        where('published', true)
            ->whereIn('launch_date', $dates)
            ->with('users');

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

        foreach ($events as $event) {
            $first_lesson = $event->lessons->first();

            if ($first_lesson) {
                $data['first_lesson_date'] = isset($first_lesson->pivot->date) ? date('d-m-Y', strtotime($first_lesson->pivot->date)) : '';
                $data['first_lesson_time'] = isset($first_lesson->pivot->time_starts) ? date('H:i', strtotime($first_lesson->pivot->time_starts)) : '';
                $data['DATE'] = $data['first_lesson_date'] . ' ' . $data['first_lesson_time'];
            }

            $info = $event->event_info();
            $venues = $event->venues;
            if ($venues) {
                $data['LOCATION'] = isset($venues[0]['name']) ? $venues[0]['name'] : '';
                $data['ADDRESS'] = isset($venues[0]['address']) ? $venues[0]['address'] : '';
            } elseif ($event->course_delivery == 143) {
                $data['LOCATION'] = 'Virtual Online';
                $data['ADDRESS'] = '-';
            }

            if (isset($info['inclass'])) {
                $data['DURATION'] = isset($info['inclass']['dates']['text']) ? $info['inclass']['dates']['text'] : '';
                $data['course_hours'] = isset($info['inclass']['days']['text']) ? $info['inclass']['days']['text'] : '';
            } else {
                $data['DURATION'] = $event->getTotalHours();
            }

            $data['FAQ'] = url('/') . '/' . $event->slugable->slug . '/#faq?utm_source=Knowcrunch.com&utm_medium=Registration_Email';
            $data['FB_GROUP'] = ($event->fb_group) ? $event->fb_group : '';
            foreach ($event->users as $user) {
                $this->emailSendService->sendEmailByEmailId($emailTrigger->email->id, $user->toArray(), null, array_merge([
                    'FIRST_NAME'=> $user->firstname,
                    'CourseName'=>$event->title,
                    'COURSE_LINK'=>url(config('app.url')) . '/myaccount',
                ], $data), ['event_id'=>$event->id]);
                event(new EmailSent($user->email, $emailTrigger->email->title));
            }
            $emailTrigger->course_trigger_logs()->save($event);
        }
    }
}

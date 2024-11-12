<?php

namespace App\Console\Commands\Emails;

use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\SurveyEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseSurveyEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendSurveyMail';
    protected $description = 'Send automated email of the course survey';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendSurveyMail();
    }

    private function sendSurveyMail()
    {
        $events = Event::has('transactions')->with('users')->where('view_tpl', 'elearning_event')->get();
        $today = date('Y/m/d');
        $today = date('Y-m-d', strtotime('-1 day', strtotime($today)));
        foreach ($events as $event) {
            $sendEmail = false;
            foreach ($event['users'] as $user) {
                if ($user->pivot->expiration !== $today || !$user->pivot->expiration) {
                    continue;
                }
                if ($event->evaluate_instructors || $event->evaluate_topics || $event->fb_testimonial) {
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;

                if ($sendEmail) {
                    $user->notify(new SurveyEmail($data, $user));
                    event(new EmailSent($user->email, 'SurveyEmail'));
                }
            }
        }

        $events = Event::has('transactions')->with('users')->where('view_tpl', 'event')->get();

        foreach ($events as $event) {
            $sendEmail = false;
            $lessons = $event->lessons;
            foreach ($event['users'] as $user) {
                $lesson = $lessons->last();

                if (!isset($lesson['pivot']['time_ends'])) {
                    continue;
                }

                $lastDayLesson = date('Y-m-d', strtotime($lesson['pivot']['time_ends']));

                if ($lastDayLesson !== $today) {
                    continue;
                }

                if ($event->evaluate_instructors || $event->evaluate_topics || $event->fb_testimonial) {
                    $sendEmail = true;
                }

                $data['firstName'] = $user->firstname;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' please take our survey';
                $data['template'] = 'emails.user.survey_email';
                $data['evaluateTopics'] = $event->evaluate_topics;
                $data['evaluateInstructors'] = $event->evaluate_instructors;
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;

                if ($sendEmail) {
                    $user->notify(new SurveyEmail($data, $user));
                    event(new EmailSent($user->email, 'SurveyEmail'));
                }
            }
        }
    }
}

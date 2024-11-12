<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\HalfPeriod;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseHalfPeriodEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendHalfPeriod';
    protected $description = 'Send automated email about the half period of the course';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendHalfPeriod();
    }

    private function sendHalfPeriod()
    {
        $adminemail = 'info@knowcrunch.com';

        $events = Event::has('transactions')->where('published', true)->with('users')
            ->whereHas('eventInfo', function ($query) {
                $query->whereCourseDelivery(143);
            })
            ->get();

        $today = date_create(date('Y/m/d'));
        $today1 = date('Y-m-d');
        foreach ($events as $event) {
            $eventInfo = $event->event_info();
            $expiration = isset($eventInfo['elearning']['expiration']) ? $eventInfo['elearning']['expiration'] : '';

            foreach ($event['users'] as $user) {
                if (!($user->pivot->expiration >= $today1) || !$user->pivot->expiration || !$expiration) {
                    continue;
                }
                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if ($date->y == 0 && $date->m == ($expiration / 2) && $date->d == 0) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;
                    $data['fbGroup'] = $event->fb_group;
                    $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' you are almost there';
                    $data['template'] = 'emails.user.half_period';

                    $user->notify(new HalfPeriod($data, $user));
                    event(new EmailSent($user->email, 'HalfPeriod'));
                }
            }
        }
    }
}

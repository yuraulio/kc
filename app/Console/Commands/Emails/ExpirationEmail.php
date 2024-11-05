<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Model\Pages;
use App\Notifications\ExpirationMails;
use App\Notifications\InstructorsMail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirationEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendExpirationEmails';
    protected $description = 'Send automated email reminders to student about course expiration';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendExpirationEmails();
    }

    private function sendExpirationEmails()
    {
        $adminemail = 'info@knowcrunch.com';
        $events = Event::has('transactions')->with('users')->where('view_tpl', 'elearning_event')->get();

        $today = date_create(date('Y/m/d'));
        $today1 = date('Y-m-d');

        foreach ($events as $event) {
            foreach ($event['users'] as $user) {
                if (!($user->pivot->expiration >= $today1) || !$user->pivot->expiration) {
                    continue;
                }

                $date = date_create($user->pivot->expiration);
                $date = date_diff($date, $today);

                if ($event->id == 2304 && ($date->y == 0 && $date->m == 0 && ($date->d == 7 || $date->d == 15))) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;

                    $data['expirationDate'] = date('d-m-Y', strtotime($user->pivot->expiration));

                    $page = Pages::find(4752);

                    $data['subscriptionSlug'] = url('/') . '/' . $page->getSlug();
                    $data['template'] = 'emails.user.courses.masterclass_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';
                    $data['subscription_price'] = $event->plans[0]['cost'];

                    $user->notify(new ExpirationMails($data, $user));
                    event(new EmailSent($user->email, 'ExpirationMails'));
                } elseif ($event->id !== 2304 && ($date->y == 0 && $date->m == 0 && ($date->d == 7 || $date->d == 15))) {
                    $data['firstName'] = $user->firstname;
                    $data['eventTitle'] = $event->title;
                    $data['eventId'] = $event->id;

                    $data['expirationDate'] = date('d-m-Y', strtotime($user->pivot->expiration));

                    $data['template'] = 'emails.user.courses.week_expiration';
                    $data['subject'] = 'Knowcrunch | ' . $data['firstName'] . ' your course expires soon';

                    $user->notify(new ExpirationMails($data, $user));
                    event(new EmailSent($user->email, 'ExpirationMails'));
                }
            }
        }
    }
}

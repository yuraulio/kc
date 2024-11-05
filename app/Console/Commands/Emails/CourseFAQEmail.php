<?php

namespace App\Console\Commands\Emails;

use App\Model\Event;
use App\Model\Instructor;
use App\Model\Transaction;
use App\Notifications\ElearningFQ;
use App\Notifications\InstructorsMail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CourseFAQEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendElearningFAQ';
    protected $description = 'Send automated email 15 days on the elearning course, send FAQ in the emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendElearningFQ();
    }

    private function sendElearningFQ()
    {
        $today = date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))));
        $adminemail = 'info@knowcrunch.com';
        $transactions = Transaction::with('event', 'user')->whereDay('created_at', date('d', strtotime($today)))
            ->whereMonth('created_at', date('m', strtotime($today)))
            ->whereYear('created_at', date('Y', strtotime($today)))
            ->where(function ($q) use ($today) {
                $q->whereHas('event', function ($query) use ($today) {
                    $query->whereViewTpl('elearning_event');
                });
            })->get();

        foreach ($transactions as $transaction) {
            if (!($event = $transaction->event->first())) {
                continue;
            }

            if (count($event->getExams()) <= 0 || !$event->expiration) {
                continue;
            }

            foreach ($transaction['user'] as $user) {
                $expiration = $event->users()->wherePivot('user_id', $user->id)->first();
                if (!$expiration) {
                    continue;
                }

                $data['firstName'] = $user->firstname;
                $data['eventTitle'] = $event->title;
                $data['eventId'] = $event->id;
                $data['subject'] = 'Knowcrunch - ' . $data['firstName'] . ' enjoying ' . $event->title . '?';
                $data['elearningSlug'] = url('/') . '/myaccount/elearning/' . $event->title;
                $data['expirationDate'] = date('d-m-Y', strtotime($expiration->pivot->expiration));
                // $data['template'] = 'emails.user.elearning_f&qemail';

                $user->notify(new ElearningFQ($data, $user));
                event(new EmailSent($user->email, 'ElearningFQ'));
            }
        }
    }
}

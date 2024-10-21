<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userStatusChange extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly User $user
    ) {
    }

    public function via($notifiable)
    {
        return SendMailchimpMail::class;
    }

    public function toMailchimp(object $notifiable)
    {
        //send the user
        if ($this->user->statusAccount['completed'] == 1) {
            $loadForm = 'userStatusActivate';
        } else {
            $loadForm = 'userStatusDeactivate';
        }

        $subject = 'User Status Change';
        $fullName = $this->user->firstname . ' ' . $this->user->lastname;

        SendEmail::dispatch($loadForm, $this->user->toArray(), $subject, [
            'FNAME'=> $this->user->firstname,
        ], []);

        return true;
    }
}

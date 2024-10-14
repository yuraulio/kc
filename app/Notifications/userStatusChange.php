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
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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

        SendEmail::dispatch($loadForm, $this->user, $subject, [
            'FNAME'=> $this->user->firstname,
        ], []);

        return true;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //send the user
        if ($this->user->statusAccount['completed'] == 1) {
            $loadForm = 'emails.user.account_activated';
        } else {
            $loadForm = 'emails.user.account_deactivated';
        }

        return (new MailMessage)
            ->subject('User Status Change')
            ->view($loadForm, ['user' => $this->user]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user,
        ];
    }
}

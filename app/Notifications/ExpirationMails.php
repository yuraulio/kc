<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpirationMails extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array $data,
        private readonly User $user
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return SendBrevoMail::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBrevo($notifiable)
    {
        $emailEvent = 'ExpirationMailsInWeek';
        if ($this->data['template'] === 'emails.user.courses.masterclass_expiration') {
            $emailEvent = 'ExpirationMailsMasterClass';
        }
        //$this->data['subject']
        SendEmail::dispatch($emailEvent, $this->user->toArray(), null, [
            'FNAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'ExpirationDate'=>$this->data['expirationDate'],
            'SubscriptionPrice'=>isset($this->data['subscription_price']) ? $this->data['subscription_price'] : 0,
        ], ['event_id'=>$this->data['eventId']]);
    }
}

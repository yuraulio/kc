<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HalfPeriod extends Notification
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
        return SendMailchimpMail::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMailchimp($notifiable)
    {
        //system-user-all-courses-half-period-reminder
        SendEmail::dispatch('HalfPeriod', $this->user->toArray(), $this->data['subject'], [
            'FNAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'LINK'=>$this->data['fbGroup'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

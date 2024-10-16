<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InClassReminder extends Notification
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
        SendEmail::dispatch('InClassReminder', $this->user->toArray(), 'Knowcrunch - Welcome ' . $this->data['firstname'] . '. Reminder about your course', [
            'FNAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['eventTitle'],
            'LINK'=>$this->data['slug'],
            'DATE'=>$this->data['first_lesson_date'] . ' ' . $this->data['first_lesson_time'],
            'LOCATION'=>$this->data['venue'] . ' ' . $this->data['address'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

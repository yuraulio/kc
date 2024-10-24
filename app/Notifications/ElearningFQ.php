<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElearningFQ extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array $data,
        private readonly array $user
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
        //$this->data['subject']
        SendEmail::dispatch('ElearningFQ', $this->user, null, [
            'FNAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'CourseExpiration'=>$this->data['expirationDate'],
            'LINK'=>$this->data['elearningSlug'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

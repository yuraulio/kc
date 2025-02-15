<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorsMail extends Notification
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
        SendEmail::dispatch('InstructorsMail', $this->user->toArray(), null, [
            'FIRST_NAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['title'],
            'DATE'=>$this->data['date'],
            'LOCATION'=>$this->data['location'],
        ]);
    }
}

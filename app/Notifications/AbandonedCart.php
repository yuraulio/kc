<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedCart extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly array $data,
        private readonly User $user,
        private readonly bool $second = false
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
        $subject = $this->data['firstName'] . ' - do you need help with your enrollment';
        SendEmail::dispatch($this->data['emailEvent'], $this->user->toArray(), null, [
            'FNAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'FAQ_LINK'=>$this->data['faqs'],
            'LINK'=>$this->data['slug'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

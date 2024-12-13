<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
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
        $subject = 'Knowcrunch - Welcome ' . $this->data['firstname'] . '. Reminder about your course';
        SendEmail::dispatch('InClassReminder', $this->user->toArray(), null, [
            'FIRST_NAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['eventTitle'],
            'COURSE_LINK'=>$this->data['slug'],
            'FAQ'=>$this->data['faq'],
            'FB_GROUP'=>$this->data['fb_group'],
            'DATE'=>$this->data['first_lesson_date'] . ' ' . $this->data['first_lesson_time'],
            'DURATION'=>$this->data['duration'],
            'LOCATION'=>$this->data['venue'],
            'ADDRESS'=>$this->data['address'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

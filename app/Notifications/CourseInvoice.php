<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseInvoice extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private readonly array $data)
    {
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
        //system-user-admin-all-courses-payment-receipt
        $subject = 'Knowcrunch | ' . $this->data['firstName'] . ' - download your receipt';
        SendEmail::dispatch('CourseInvoice', $this->data['user'], null, [
            'FIRST_NAME'=> $this->data['firstName'],
            'CourseName'=>$this->data['eventTitle'],
            'COURSE_LINK'=>$this->data['slugInvoice'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

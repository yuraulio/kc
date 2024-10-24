<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpireReminder extends Notification
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
        //$this->data['subject']
        $emailEvent = '';
        $link = 'https://knowcrunch.com/knowcrunch-elite?utm_source=Knowcrunch&utm_medium=Email%20&utm_content=Promo&utm_campaign=SUBSCRIPTION';
        if ($this->data['template'] === 'emails.user.courses.expired') {
            $emailEvent = 'SubscriptionExpireReminder';
        } elseif ($this->data['template'] === 'emails.user.courses.expired_after_six_months') {
            $emailEvent = 'SubscriptionExpireReminder6Months';
        } elseif ($this->data['template'] === 'emails.user.courses.expired_after_one_year') {
            $emailEvent = 'SubscriptionExpireReminder1Year';
        }

        SendEmail::dispatch($emailEvent, $this->user->toArray(), null, [
            'FNAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['event_name'],
            'LINK'=>$link,
            'SubscriptionPrice'=>$this->data['subscription_price'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

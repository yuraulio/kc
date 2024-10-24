<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTopicAutomateMail extends Notification
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
        $subject = '';
        $emailEvent = '';
        if ($this->data['email_template'] == 'activate_social_media_account_email') {
            $subject = 'activate your social media accounts!';
            $emailEvent = 'SendTopicAutomateMailSocialAccount';
        } elseif ($this->data['email_template'] == 'activate_advertising_account_email') {
            $subject = 'activate your personal advertising accounts!';
            $emailEvent = 'SendTopicAutomateMailAdAccount';
        } elseif ($this->data['email_template'] == 'activate_production_content_account_email') {
            $subject = 'activate your content production accounts!';
            $emailEvent = 'SendTopicAutomateMailContentAccount';
        }
        //$this->data['subject'] . $subject
        SendEmail::dispatch($emailEvent, $this->user->toArray(), null, [
            'FNAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['eventTitle'],
            'SubscriptionPrice'=>isset($this->data['subscription_price']) ? $this->data['subscription_price'] : '0',
        ], ['event_id'=>$this->data['eventId']]);
    }
}

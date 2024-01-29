<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedCart extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $second = false)
    {
        $this->data = $data;
        $this->second = $second;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $template = 'emails.user.abandoned_email';

        $now_date = now();
        $now_date = date_format($now_date, 'Y-m-d');

        if (strtotime(env('BLACKFRIDAY')) == strtotime($now_date)) {
            if ($this->second) {
                $template = 'emails.user.abandoned_blackfriday_email';
            }
        } elseif (strtotime(env('CYBERMONDAY')) == strtotime($now_date)) {
            if ($this->second) {
                $template = 'emails.user.abandoned_cybermonday_email';
            }
        }

        return (new MailMessage)
                    ->from('info@knowcrunch.com', 'Knowcrunch')
                    ->subject($this->data['firstName'] . ' - do you need help with your enrollment')
                    ->view($template, $this->data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

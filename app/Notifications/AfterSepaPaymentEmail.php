<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AfterSepaPaymentEmail extends Notification
{
    use Queueable;

    public $user;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;

        if (isset($data['duration'])) {
            $this->data['duration'] = strip_tags($data['duration']);
        }
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
        $slug = [];
        $slug['id'] = $this->user->id;
        $slug['email'] = $this->user->email;
        $slug['create'] = true;

        $slug = encrypt($slug);

        $template = 'emails.user.after_sepa_payment';

        // $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome ' .  $this->user->firstname . '. Activate your account​ now' : 'Knowcrunch - Welcome ' . $this->data['subject'];
        $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome to our course ' . $this->user->firstname : 'Knowcrunch – Welcome to our course ' . $this->data['subject'];

        $this->data['slug'] = url(config('app.url')) . '/myaccount';

        return (new MailMessage)
                    ->from('info@knowcrunch.com', 'Knowcrunch')
                    ->subject($subject)
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

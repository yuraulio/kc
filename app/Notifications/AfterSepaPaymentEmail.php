<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AfterSepaPaymentEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly User $user,
        private readonly array $data
    ) {
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
        return SendMailchimpMail::class;
    }

    public function toMailchimp(object $notifiable)
    {
        //system-user-all-courses-sepa-payment
        //system-user-waiting-list-welcome-email
        $slug = [];
        $slug['id'] = $this->user->id;
        $slug['email'] = $this->user->email;
        $slug['create'] = true;

        $slug = encrypt($slug);

        $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome to our course ' . $this->user->firstname : 'Knowcrunch – Welcome to our course ' . $this->data['subject'];

        $this->data['slug'] = url(config('app.url')) . '/myaccount';

        SendEmail::dispatch('AfterSepaPaymentEmail', $this->user->toArray(), $subject, [
            'FNAME'=> $this->user->firstname,
            'CourseName'=>$this->data['eventTitle'],
            'DurationDescription'=>$this->data['duration'],
            'LINK'=>$this->data['slug'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

<?php

namespace App\Notifications;

use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Jobs\SendEmail;
use App\Model\Activation;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class WelcomeEmail extends Notification
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
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return SendMailchimpMail::class;
    }

    public function toMailchimp(object $notifiable)
    {
        //system-user-all-courses-welcome-email
        //system-user-waiting-list-welcome-email
        $slug = [];
        $slug['id'] = $this->user->id;
        $slug['email'] = $this->user->email;
        $slug['create'] = true;

        $slug = encrypt($slug);

        $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome to our course ' . $this->user->firstname : 'Knowcrunch – Welcome to our course ' . $this->data['subject'];

        if (isset($this->data['user']['createAccount'])) {
            $this->data['slug'] = $this->data['user']['createAccount'] ? url(config('app.url')) . '/create-your-password/' . $slug : url('/') . '/myaccount';
        } else {
            $this->data['slug'] = url(config('app.url')) . '/myaccount';
        }

        if ($this->user->statusAccount) {
            $this->user->statusAccount->completed = true;
            $this->user->statusAccount->completed_at = Carbon::now();
            $this->user->statusAccount->save();
        } else {
            $activation = Activation::firstOrCreate(['user_id' => $this->user['id']]);

            if ($activation->code == '') {
                $activation->code = Str::random(40);
                $activation->completed = true;
                $activation->save();
            }
        }

        SendEmail::dispatch('WelcomeEmail', $this->user->toArray(), $subject, [
            'FNAME'=> $this->user->firstname,
            'CourseName'=>$this->data['eventTitle'],
            'DurationDescription'=>$this->data['duration'],
            'LINK'=>$this->data['slug'],
        ], ['event_id'=>$this->data['eventId']]);

        event(new ActivityEvent($this->user, ActivityEventEnum::EmailSent->value, $subject . ', ' . Carbon::now()->format('d F Y')));
    }
}

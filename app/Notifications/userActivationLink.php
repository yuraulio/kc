<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\Activation;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class userActivationLink extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private readonly User $user,
        private readonly string $template
    )
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
        return SendMailchimpMail::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMailchimp($notifiable)
    {
        $activation = Activation::firstOrCreate(['user_id' => $this->user['id']]);

        $email = $this->user['email'];
        $firstName = $this->user['firstname'];

        if ($activation->code == '') {
            $activation->code = Str::random(40);
            $activation->completed = false;
            $activation->save();
        }

        $code = $activation->code;
        $url = url('/') . 'myaccount/activate/' . $code;

        //send the user
        SendEmail::dispatch('userActivationLink', $this->user->toArray(), 'Knowcrunch - ' . $firstName . ' your account​ is active', [
            'FNAME'=> $firstName,
            'email'=>$email,
            'LINK'=> $url,
        ], []);
    }
}

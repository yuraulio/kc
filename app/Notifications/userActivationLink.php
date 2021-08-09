<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Model\User;
use App\Model\Activation;

class userActivationLink extends Notification
{
    use Queueable;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //$loadForm = 'sentinel.emails.re-activate';

        //$code = Activation::firstOrCreate($user)->code;
        //$activation = Activation::exists($user) ?: Activation::create($user);

        //$code = $activation->code;
        $loadForm = 'activation.emails.re-activate';
        $activation = Activation::firstOrCreate(array('id' => $this->user['id']));

        //dd(Activation::exists(array('id' => $this->user['id'])));
        $code = $activation->code;
        
         //send the user

        return (new MailMessage)
                    ->subject('Activate Your Student Account')
                    ->view( $loadForm, ['code' => $code]);
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
            'user_id' => $this->user
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Model\User;
use App\Model\Activation;
use Illuminate\Support\Str;

class userActivationLink extends Notification
{
    use Queueable;
    private $user;
    private $template;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$template)
    {
        $this->user = $user;
        $this->template = $template;
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
        $loadForm = 'activation.emails.' . $this->template;
       
        $activation = Activation::firstOrCreate(array('user_id' => $this->user['id']));

        
        if($activation->code == ''){
            $activation->code = Str::random(40);
            $activation->completed = false;
            $activation->save();
        }

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

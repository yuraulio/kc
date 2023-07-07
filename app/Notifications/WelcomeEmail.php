<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class WelcomeEmail extends Notification
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

        
        if(isset($data['duration'])){
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

        $template = isset($this->data['template']) ? 'emails.user.'.$this->data['template'] : 'emails.user.welcome';

        // $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome ' .  $this->user->firstname . '. Activate your account​ now' : 'Knowcrunch - Welcome ' . $this->data['subject'];
        $subject = !isset($this->data['subject']) ? 'Knowcrunch - Welcome to our course ' .  $this->user->firstname : 'Knowcrunch – Welcome to our course ' . $this->data['subject'];

        $this->data['slug'] = $this->data['user']['createAccount'] ? url('/') . '/create-your-password/' . $slug : url('/') . '/myaccount';

        if($this->user->statusAccount){
            $this->user->statusAccount->completed = true;
            $this->user->statusAccount->completed_at = Carbon::now();
            $this->user->statusAccount->save();
        }

        return (new MailMessage)
                    ->from('info@knowcrunch.com', 'Knowcrunch')
                    ->subject($subject)
                    ->view($template,$this->data);
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

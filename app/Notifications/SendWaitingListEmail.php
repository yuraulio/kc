<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Model\User;
use App\Model\Event;

class SendWaitingListEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $user;
    private $event;

    public function __construct($user, $event)
    {
        $this->user = User::find($user);
        $this->event = Event::find($event);
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

        $data['urlEnrol'] = url('/') . '/' . $this->event->getSlug() . '?lo=' . encrypt($this->user->email);
        $data['eventTitle'] = $this->event->title;
        $data['firstname'] = $this->user->firstname;
        $template = 'emails.user.waiting_list_open';

        return (new MailMessage)
                    ->from('info@knowcrunch.com', 'Knowcrunch')
                    ->subject('Knowcrunch - Hi '. $data['firstname'] . '. Course is available')
                    ->view($template,$data);
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

<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\Event;
use App\Model\User;
use App\Notifications\SendBrevoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

    public function __construct(int $user, int $event)
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
        //system-user-waiting-list-open-notification-emai
        $data = [];
        $data['urlEnrol'] = url('/') . '/' . $this->event->getSlug() . '?lo=' . encrypt($this->user->email);
        $data['eventTitle'] = $this->event->title;
        $data['eventId'] = $this->event->id;
        $data['firstname'] = $this->user->firstname;

        $subject = 'Knowcrunch - Hi ' . $data['firstname'] . '. Course is available';
        SendEmail::dispatch('SendWaitingListEmail', $this->user, null, [
            'FIRST_NAME'=> $this->data['firstname'],
            'CourseName'=>$this->data['eventTitle'],
            'COURSE_LINK'=>$this->data['urlEnrol'],
        ], ['event_id'=>$this->data['eventId']]);
    }
}

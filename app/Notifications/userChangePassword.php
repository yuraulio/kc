<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Notifications\SendMailchimpMail;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class userChangePassword extends Notification
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
        return SendMailchimpMail::class;
    }

    public function toMailchimp(object $notifiable)
    {
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $this->user->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
        $subject = 'Knowcrunch - ' . $this->user->firstname . ' change your password';
        $fullName = $this->user->firstname . ' ' . $this->user->lastname;

        SendEmail::dispatch('userChangePassword', $this->user, $subject, [
            'FNAME'=> $this->user->firstname, 'LINK' => \URL::to("myaccount/reset/{$this->user->id}/{$token}"),
        ]);

        return true;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $this->user->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return (new MailMessage)
                    ->subject('Knowcrunch - ' . $this->user->firstname . ' change your password')
                    ->view('activation.emails.student-reminder', ['user'=> $this->user, 'code' => $token]);
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

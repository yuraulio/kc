<?php

namespace App\Notifications;

use App\Jobs\SendEmail;
use App\Model\User;
use App\Notifications\SendMailchimpMail;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
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
    public function __construct(
        private readonly User $user
    ) {
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
        //system-user-all-courses-password-reset
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email'      => $this->user->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);
        $subject = 'Knowcrunch - ' . $this->user->firstname . ' change your password';
        $fullName = $this->user->firstname . ' ' . $this->user->lastname;
        SendEmail::dispatch('userChangePassword', $this->user->toArray(), $subject, [
            'FNAME'=> $this->user->firstname, 'LINK' => \URL::to("myaccount/reset/{$this->user->id}/{$token}"),
        ], []);

        return true;
    }
}

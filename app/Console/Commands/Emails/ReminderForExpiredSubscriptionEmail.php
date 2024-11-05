<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\User;
use App\Notifications\SubscriptionExpireReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReminderForExpiredSubscriptionEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendReminderForExpiredSubscription';
    protected $description = 'Send automated email reminders to about the expired subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendReminderForExpiredSubscription();
    }

    private function sendReminderForExpiredSubscription()
    {
        $now = Carbon::now();

        $users = User::with('events_for_user_list1_expired')->get();

        foreach ($users as $user) {
            foreach ($user['events_for_user_list1_expired'] as $event) {
                $data = [];

                $expiration = Carbon::parse($event->pivot->expiration);
                $expiration_status = $event->pivot->expiration_email;

                $diffInMonths = $expiration->diffInMonths($now);

                if ($expiration_status == 0 && $diffInMonths == 0) {
                    //expired NOW
                    $status = 0;
                    $data['template'] = 'emails.user.courses.expired';
                    $data['subject'] = 'Knowcrunch | ' . (($user['firstname']) ? $user['firstname'] : '') . ' want to keep watching?';

                    $updatedStatus = 1;
                } elseif ($expiration_status == 1 && $diffInMonths == 6) {
                    // expired 6 MOMTHS
                    $status = 1;
                    $data['template'] = 'emails.user.courses.expired_after_six_months';
                    $data['subject'] = 'Knowcrunch | ' . (($user['firstname']) ? $user['firstname'] : '') . " don't you want to be updated?";

                    $updatedStatus = 2;
                } elseif ($expiration_status == 2 && $diffInMonths == 12) {
                    // expired 12 MONTHS
                    $status = 2;
                    $data['template'] = 'emails.user.courses.expired_after_one_year';
                    $data['subject'] = 'Knowcrunch | ' . (($user['firstname']) ? $user['firstname'] : '') . " it's been a long time";

                    $updatedStatus = 3;
                }

                if ($expiration_status < 3 && isset($data['template'])) {
                    $data['firstname'] = $user['firstname'];
                    $data['event_name'] = $event['title'];
                    $data['eventId'] = $event['id'];
                    $data['subscription_price'] = $event['plans'][0]['cost'];

                    $user->notify(new SubscriptionExpireReminder($data, $user));
                    event(new EmailSent($user->email, 'SubscriptionExpireReminder'));

                    // Update Pivot Table
                    $user->events_for_user_list1_expired()->updateExistingPivot($event, ['expiration_email' => $updatedStatus], false);
                }
            }
        }
    }
}

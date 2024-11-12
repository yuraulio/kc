<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\SubscriptionReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Cashier\Subscription;

class SubscriptionReminderEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendSubscriptionRemind';
    protected $description = 'Send automated email reminders about the upcoming subscription renewals';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendSubscriptionRemind();
    }

    private function sendSubscriptionRemind()
    {
        $adminemail = 'info@knowcrunch.com';
        $today = strtotime(date('Y/m/d'));
        //if user not set of status off subscription from my account page
        $subscriptions = Subscription::where('must_be_updated', '>', $today)->where('stripe_status', 'active')->where('status', true)->get();

        $today = date_create(date('Y/m/d'));
        foreach ($subscriptions as $subscription) {
            if ($subscription->event->first() && $subscription->event->first()->pivot->expiration && $subscription->user) {
                $date = date_create($subscription->event->first()->pivot->expiration);
                $date = date_diff($date, $today);

                if (($date->y == 0 && $date->m == 0 && ($date->d == 7 || $date->d == 1)) || ($date->y == 0 && $date->m == 1 && $date->d == 0)) {
                    $muser['name'] = $subscription->user->firstname . ' ' . $subscription->user->lastname;
                    $muser['first'] = $subscription->user->firstname;
                    $muser['eventTitle'] = $subscription->event->first()->title;
                    $muser['email'] = $subscription->user->email;

                    $data['subject'] = 'Knowcrunch - ' . $subscription->user->firstname . ' your subscription will be renewed soon';
                    $data['firstName'] = $subscription->user->firstname;
                    $data['eventTitle'] = $subscription->event->first()->title;
                    $data['eventId'] = $subscription->event->first()->id;
                    $data['ExpirationDate'] = date('d/m/Y', strtotime($subscription->event->first()->pivot->expiration));
                    $data['SubscriptionPrice'] = $subscription->event->first()->plans[0]['cost'];
                    $data['template'] = 'emails.user.subscription_reminder';
                    $subscription->user->notify(new SubscriptionReminder($data, $subscription->user));
                    event(new EmailSent($subscription->user->email, 'SubscriptionReminder'));
                }
            }
        }
    }
}

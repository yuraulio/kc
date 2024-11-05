<?php

namespace App\Console\Commands\Emails;

use App\Events\EmailSent;
use App\Model\Event;
use App\Model\Instructor;
use App\Notifications\InstructorsMail;
use App\Notifications\SubscriptionFailedPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Cashier\Subscription;

class SubscriptionNonPaymentEmail extends Command
{
    // Command name and description
    protected $signature = 'email:sendSubscriptionNonPayment';
    protected $description = 'Send automated email reminders to about the non-payment subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the private function to execute the email sending logic
        $this->sendSubscriptionNonPayment();
    }

    private function sendSubscriptionNonPayment()
    {
        $adminemail = 'info@knowcrunch.com';

        $today = strtotime(date('Y-m-d'));
        $subscriptions = Subscription::where('must_be_updated', '<', $today)->whereIn('stripe_status', ['active', 'trialing'])->where('email_send', false)->where('must_be_updated', '!=', 0)->get();

        foreach ($subscriptions as $subscription) {
            $subscription->email_send = true;
            $subscription->save();

            //dd($subscription->user);
            $user = $subscription->user;
            $event = $user->subscriptionEvents()->where('subscription_id', $subscription->id)->first();

            $muser['name'] = $user->firstname . ' ' . $user->lastname;
            $muser['first'] = $user->firstname;
            $muser['eventTitle'] = $event->title;
            $muser['email'] = $user->email;

            $data['firstName'] = $user->firstname;
            $data['eventTitle'] = $event->title;
            $data['eventId'] = $event->id;
            $data['subject'] = 'Knowcrunch |' . $data['firstName'] . ' - Subscription Payment Declined';
            $data['expirationDate'] = $event->pivot->expiration;
            $data['template'] = 'emails.user.subscription_non_payment';
            $data['amount'] = round($subscription->price, 2);

            $user->first()->notify(new SubscriptionFailedPayment($data, $user));
            event(new EmailSent($user->first()->email, 'SubscriptionFailedPayment'));
        }
    }
}

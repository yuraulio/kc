<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;

class CheckSubscriptionIsPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:check-subscription-is-paid {--limit=50} {--syncLocalStatus=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the subscription status and sync it';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $limit = $this->option('limit');
        $syncLocalStatus = boolval($this->option('syncLocalStatus'));

        if (!is_numeric($limit)) {
            throw new \Exception('The limit option has to be integer.');
        }

        $this->info('Start checking subscription statuses.');

        // based on config here https://dashboard.stripe.com/settings/billing/automatic
        // a students have up to 1 week and 4 retries to be charged for a subscription
        // must_be_updated is a value when a student should be charged
        //
        // in case a student was successfully charged the must_be_updated value will be updated
        //
        // in case must_be_updated + 8 days is less than today which means a student wasn't successfully charged
        // we need to turn of the subscription and restrict access to the courses related to this subscription
        $today = strtotime(date('Y/m/d'));
        $eightDaysInSeconds = 8 * 24 * 60 * 60; // 8 days in seconds
        $cutoff = $today - $eightDaysInSeconds;

        if ($syncLocalStatus) {
            $subscriptions = Subscription::where('must_be_updated', '<', $cutoff)
                ->whereNotIn('stripe_status', ['active', 'trialing'])
                ->where('status', true)
                ->where('email_send', true)
                ->where('must_be_updated', '!=', 0)
                ->get();

            $this->info('[Sync subscription status] Sync local statuses for: ' . implode(',', $subscriptions->pluck('id')->toArray()));
            Log::info('[Sync subscription status] Sync local statuses for: ' . implode(',', $subscriptions->pluck('id')->toArray()));
        } else {
            $subscriptions = Subscription::where('must_be_updated', '<', $cutoff)
                ->whereIn('stripe_status', ['active', 'trialing'])
                ->where('email_send', true)
                ->where('must_be_updated', '!=', 0)
                ->get();
        }

        Log::info('[Sync subscription status] Count of subscriptions to be updated: ' . count($subscriptions));

        $handledSubscriptions = 0;
        foreach ($subscriptions as $subscription) {
            if ($handledSubscriptions >= $limit) {
                break;
            }

            if ($subscription->event) {
                $this->info("Sync subscription \"{$subscription->id}\", current stripe status: \"{$subscription->stripe_status}\", local status: \"{$subscription->status}\".");
                Log::info("Sync subscription \"{$subscription->id}\", current stripe status: \"{$subscription->stripe_status}\", local status: \"{$subscription->status}\".");

                $subscription->syncStripeStatus();

                // wait 3 seconds before handle next subscription to avoid reaching the limits of requests to stripe
                sleep(3);
                $handledSubscriptions++;
            }
        }

        Log::info('[Sync subscription status] Count of subscriptions updated: ' . $handledSubscriptions);

        $this->info('Subscription statuses checked and synced with the Stripe.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Cashier\Subscription;

class SubscriptionsEnds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:ends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $subscriptions = Subscription::whereHas('event')->get();

        foreach($subscriptions as $subscription){
            $subscription->event->first()->pivot->expiration  = date('Y-m-d', strtotime($subscription->ends_at));
			$subscription->event->first()->pivot->save();
        }

        
        return 0;
    }
}

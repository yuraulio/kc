<?php

namespace App\Console\Commands;

use App\Model\Transaction;
use Illuminate\Console\Command;

class GetPaymentDetailsFromTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:billing-details';

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
        $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category')->where('status', 1)->orderBy('created_at', 'desc')->get();

        foreach ($transactions as $transaction) {
            if (!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()) {
                $user = $transaction->user->first();

                $transactionBillingDetails = json_decode($transaction->billing_details, true);
                if (isset($transactionBillingDetails['billing']) && $transactionBillingDetails['billing'] == 1) {
                    if ($user->receipt_details) {
                        continue;
                    }
                    $user->receipt_details = $transactionBillingDetails;
                } elseif (isset($transactionBillingDetails['billing']) && $transactionBillingDetails['billing'] == 2) {
                    if ($user->invoice_details) {
                        continue;
                    }
                    $user->invoice_details = $transactionBillingDetails;
                }

                $user->save();
            }
        }
    }
}

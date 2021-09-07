<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Transaction;

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
        

  
        $transactions = Transaction::with('user.statisticGroupByEvent','user.events','user.ticket','subscription','event','event.delivery','event.category')->where('status', 1)->orderBy('created_at','desc')->get();

        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){
                $user = $transaction->user->first();

                if($transaction->billing_details){
                    continue;
                }

                if($user->receipt_details){
                    $transaction->billing_details = $user->receipt_details;
                }else{
                    $transaction->billing_details = $user->invoice_details;
                }                

                $transaction->save();
            }
        }
    }
}

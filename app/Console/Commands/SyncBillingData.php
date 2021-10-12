<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Transaction;

class SyncBillingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:billing';

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
        $transactions = Transaction::all();
        foreach($transactions as $transaction){
            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()){
                if($billing_details = json_decode($transaction->billing_details,true)){
                    
                    $user = $transaction->user->first();
                    
                    $billing = [];

                    if(isset($billing_details['billing']) && $billing_details['billing'] == 1){
                        
                        $billname = '';

                        $billname .= isset($billing_details['billname']) ? $billing_details['billname'] : '';
                        $billname .= isset($billing_details['billsurname']) ? ' '. $billing_details['billsurname'] : '';

                        $billing['billname'] = trim($billname);
                        $billing['billafm'] = isset($billing_details['billafm']) ? $billing_details['billafm'] : $user->afm;
                        $billing['billaddress'] = isset($billing_details['billaddress']) ? $billing_details['billaddress'] : '';
                        $billing['billaddressnum'] = isset($billing_details['billaddressnum']) ? $billing_details['billaddressnum'] : '';
                        $billing['billpostcode'] = isset($billing_details['billpostcode']) ? $billing_details['billpostcode'] : '';
                        $billing['billcity'] = isset($billing_details['billcity']) ? $billing_details['billcity'] : '';
                        $billing['billemail'] = isset($billing_details['billemail']) ? $billing_details['billemail'] : '';

                    }else if(isset($billing_details['billing']) && $billing_details['billing'] == 2){
                        $billing['billname'] = $billing_details['companyname'];
                        $billing['billafm'] = $billing_details['companyafm'];
                        $billing['billaddress'] = $billing_details['companyaddress'];
                        $billing['billaddressnum'] = $billing_details['companyaddressnum'];
                        $billing['billpostcode'] = $billing_details['companypostcode'];
                        $billing['billcity'] = $billing_details['companycity'];
                        $billing['billemail'] = isset($billing_details['companyemail']) ? $billing_details['companyemail'] : '';

                    }

                    $user->receipt_details = json_encode($billing_details);
                    $user->save();
                }
            }
        }

        return 0;
    }
}

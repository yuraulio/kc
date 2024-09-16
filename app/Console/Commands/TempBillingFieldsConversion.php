<?php

namespace App\Console\Commands;

use App\Model\Transaction;
use Illuminate\Console\Command;

class TempBillingFieldsConversion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp-billing-fields-conversion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update billing details from the transaction table to separate fields. This command can be deleted after running once.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::all();
        foreach ($transactions as $transaction) {
            $billingDetails = json_decode($transaction->billing_details, true);
            $transaction->billing_company_name = (isset($billingDetails['billname'])) ? $billingDetails['billname'] : null;
            $transaction->billing_vat = (isset($billingDetails['billafm'])) ? $billingDetails['billafm'] : null;
            $transaction->billing_address = (isset($billingDetails['billaddress'])) ? $billingDetails['billaddress'] : null;
            $transaction->billing_suite = (isset($billingDetails['billaddressnum'])) ? $billingDetails['billaddressnum'] : null;
            $transaction->billing_email = (isset($billingDetails['billemail'])) ? $billingDetails['billemail'] : null;
            $transaction->billing_city = (isset($billingDetails['billcity'])) ? $billingDetails['billcity'] : null;
            $transaction->billing_state = (isset($billingDetails['billstate'])) ? $billingDetails['billstate'] : null;
            $transaction->billing_zipcode = (isset($billingDetails['billpostcode'])) ? $billingDetails['billpostcode'] : null;
            $transaction->billing_country = (isset($billingDetails['billcountry'])) ? $billingDetails['billcountry'] : null;
            $transaction->save();
        }
    }
}

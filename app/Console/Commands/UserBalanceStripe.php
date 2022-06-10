<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\PaymentMethod;
use Stripe\Stripe;
use \Stripe\StripeClient;

class UserBalanceStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:balance';

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

      $paymentMethod = PaymentMethod::find(2);
         
   
       $stripe = new StripeClient(array_merge([
            'api_key' => $paymentMethod->processor_options['secret_key'],
            'stripe_version' => '2020-08-27',
        ], []));
      
      
      //dd($stripe->invoiceItems->all(['pending' => true]));
      
      	foreach($stripe->invoiceItems->all(['pending' => true]) as $invoice){
          
          if($invoice->amount < 0){
            //dd($invoice);
            $stripe->invoiceItems->delete(
			        $invoice->id,
  			      []
			      );
          }
          	
        }
      
        return 0;
    }
}

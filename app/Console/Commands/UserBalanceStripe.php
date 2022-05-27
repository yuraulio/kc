<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\PaymentMethod;
use Stripe\Stripe;

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

        $users = User::whereHas('subscriptions')->get();//where('id',4201)->get();//User::all();
			
        foreach($users as $user){
		
            if(!$user->stripe_id){
                continue;
            }

            $paymentMethod = PaymentMethod::find(2);
            Stripe::setApiKey($paymentMethod->processor_options['secret_key']);
            session()->put('payment_method',2);

         
          try{
            if(!$user->asStripeCustomer()){
           		continue; 
          	}
            
     
            
            if($user->upcomingInvoice() && $user->upcomingInvoice()->total < 0){
             
              $user->applyBalance($user->upcomingInvoice()->total);
             
             
            }
            
            if(!empty($user->invoicesIncludingPending())){
              $amount = 0;
              foreach($user->invoicesIncludingPending() as $invoice){
               
                if($invoice->total < 0){
                 $amount += $invoice->total;
                }
                             
              	
              }
              
              /*if($amount != 0){
               $user->applyBalance($amount); 
              }*/
              
            
              
            }
            
            
          }catch(\Stripe\Exception\InvalidRequestException $e){
           	
            continue;
            
          }
          catch(\InvalidArgumentException  $e){
           	
            continue;
            
          }
   
        }

        return 0;
    }
}

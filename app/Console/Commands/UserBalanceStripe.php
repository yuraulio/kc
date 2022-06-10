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
			//$id = 'ii_1KJfI1IppxFyGi0YGCFL4Ugj';
        foreach($users as $user){
		
            if(!$user->stripe_id){
                continue;
            }

            $paymentMethod = PaymentMethod::find(2);
            //dd($paymentMethod);
            Stripe::setApiKey($paymentMethod->processor_options['secret_key']);
            session()->put('payment_method',2);

         
          try{
            if(!$user->asStripeCustomer()){
           		continue; 
          	}
            //dd($user);
            //$user->applyBalance(100);
            //dd($user);
            
            if($user->upcomingInvoice() && $user->upcomingInvoice()->total < 0){
              
              //dd($user->upcomingInvoice()->invoiceLineItems());
              
              /*foreach($user->upcomingInvoice()->invoiceLineItems() as $invoice){
                $invoice->void();
              }*/
              //dd($user->upcomingInvoice());
              //$user->upcomingInvoice()->markUncollectible();
              //$user->upcomingInvoice()->void();
              //dd($user->upcomingInvoice());
              //$user->upcomingInvoice()->finalize();
              
              //dd('fsdfa');
              
              //$user->upcomingInvoice()->void();
              //$user->applyBalance($user->upcomingInvoice()->total);
             
             
            }
            
            if(!empty($user->invoicesIncludingPending())){
              $amount = 0;
              foreach($user->invoicesIncludingPending() as $invoice){
               
                if($invoice->total < 0 ){
                  //dd($invoice);
                  //$inv = $invoice->markUncollectible();
                  //dd($inv);
                  $invoice->void(['consume_applied_balance'=>false]);
                  //$invoice->save();
                  //print_r('is void = '. $invoice->isVoid());
                  
                  //$invoice->void();
                  //dd($invoice);
                 //$amount += $invoice->total;
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Transaction;

class UpdateCavenTransactionDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:caven-transaction-details';

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

        $transactions = Transaction::whereHas('user',function($q1){

            $q1->whereHas('events',function($q2){
                return $q2->where('payment_method',3);
            });
        })->get();

        foreach($transactions as $transaction){

            $eventId = $transaction->event->first()->id;

            foreach($transaction->user as $user){
                
                foreach($user->events()->wherePivot('event_id',$eventId)->get() as $event){
                    if($event->pivot->payment_method == 3){
                            
                        $transaction->billing_details = $user->receipt_details;
                        $transaction->save();

                        
                        
                    }
                }
             

            }

        }
        
        return 0;
    }
}

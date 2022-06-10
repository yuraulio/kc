<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Invoice;

class ClearInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:invoices';

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

        //$invoices = Invoice::where('id',17)->get();
        $invoices = Invoice::all();

        foreach($invoices as $invoice){

            $transaction = $invoice->transaction->first();
            $user = $invoice->user->first();
            $event = $invoice->event->first();

            if(!$transaction || !$user || !$event || !$user->transactions){
                continue;
            }
            if(!in_array($transaction->id,$user->transactions->pluck('id')->toArray())){
                //dd($user->transactions);
                $invoice->transaction()->detach();
                foreach($user->transactions as $newTransaction){
                   
                    foreach($newTransaction->event as $eventTrans){
                        

                        if($eventTrans->id == $event->id){
                            $invoice->transaction()->attach($newTransaction->id);
                        }

                    }

                    
                }


            
            }

            
        }

        return 0;
    }
}

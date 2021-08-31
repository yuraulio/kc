<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Invoice;

class AttachInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:invoices';

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

        $invoices = Invoice::with('event','transaction')->get();

        $s = 0;

        foreach($invoices as $invoice){
            //if($invoice->id == 559){
                if(count($invoice->transaction) == 0){
                    $event = $invoice->event->first();
                    //dd($invoice->user->first()->id);
                    $transactionId = $event->transactionsByUser($invoice->user->first()->id)->first();
                    //dd($transactionId);
                    $invoice->transaction()->detach();
                    $invoice->transaction()->attach($transactionId);
                    //dd($event->transactionsByUser($invoice->user->first()->id)->first()->id);
                }
            //}
            
        }
        return 0;
    }
}

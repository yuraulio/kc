<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\Event;

class AttachPaymentMethod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:payment_method';

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

        $users = User::has('transactions')->with('transactions','events')->get();

        foreach($users as $user){
            
            foreach($user['transactions'] as $transaction){

                if($transaction['payment_method_id'] != 100 || !$transaction->event->first()){
                    continue;
                }

                //dd($transaction->event->first()->id);
                $event = $user->events()->wherePivot('event_id',$transaction->event->first()->id)->first();

                if(!$event){
                    continue;
                }

                $event->pivot->payment_method = 2;
                $event->pivot->save();

                //dd($event->pivot->payment_method);

            }


            foreach($user->subscriptionEvents as $event){
                $event->pivot->payment_method = 2;
                $event->pivot->save();
            }

        }

        return 0;
    }
}

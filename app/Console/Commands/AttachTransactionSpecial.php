<?php

namespace App\Console\Commands;

use App\Model\Transaction;
use App\Model\User;
use Illuminate\Console\Command;

class AttachTransactionSpecial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:special';

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
        $transactions = Transaction::with('user.statisticGroupByEvent', 'user.events', 'user.ticket', 'subscription', 'event', 'event.delivery', 'event.category')->where('status', 1)->orderBy('created_at', 'desc')->get();
        foreach ($transactions as $transaction) {
            if (!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first()) {
                if (!isset($transaction->status_history[0]['pay_seats_data']['emails'])) {
                    continue;
                }

                if (count($transaction->status_history[0]['pay_seats_data']['emails']) == 1) {
                    continue;
                }

                for ($i = 1; $i < count($transaction->status_history[0]['pay_seats_data']['emails']); $i++) {
                    $email = $transaction->status_history[0]['pay_seats_data']['emails'][$i];
                    $user = User::where('email', $email)->first();

                    if (!$user) {
                        continue;
                    }
                    //dd($transaction->id);
                    //dd()
                    ///$user->transactions()->detach($transaction->id);
                    ///$user->transactions()->save($transaction->id);

                    $transaction->user()->detach($user->id);
                    $transaction->user()->attach($user->id);
                }
            }
        }

        return 0;
    }
}

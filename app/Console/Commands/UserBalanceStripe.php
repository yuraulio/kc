<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;

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

        $user = User::all();

        foreach($users as $user){
            $balance = preg_replace('/[^0-9.]+/', '', $user->balance);
            if($balance > 0){
                dd($balance . ' user => '. $user->id);
            }
        }

        return 0;
    }
}

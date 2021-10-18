<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;

class FixUsersName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:name';

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
        $users = User::all();

        foreach($users as $user){
            $user->firstname = ucfirst($user->firstname);
            $user->lastname =ucfirst($user->lastname);
            $user->save();
        }

        return 0;
    }
}

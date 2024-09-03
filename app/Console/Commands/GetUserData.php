<?php

namespace App\Console\Commands;

use App\Model\User;
use Illuminate\Console\Command;

class GetUserData extends Command
{
    protected $signature = 'user:get-data {user}';

    protected $description = 'Get user data by id';

    public function handle()
    {
        $this->info(User::find($this->argument('user')));
    }
}

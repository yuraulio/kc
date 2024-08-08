<?php

namespace Database\Seeders;

use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@argon.com',
            'password' => Hash::make('123123##Qwe'),
        ]);
    }
}

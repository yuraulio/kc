<?php

namespace Database\Seeders;

use App\Model\User;
use Illuminate\Database\Seeder;

class ProfileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::doesntHave('profileStatus')
            ->cursor()
            ->each(function (User $user) {
                $user->profileStatus()->create();
            });
    }
}

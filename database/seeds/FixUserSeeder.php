<?php

namespace Database\Seeders;

use App\Enums\WorkExperience;
use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FixUserSeeder extends Seeder
{
    public function run()
    {
        User::whereNull('work_experience')->update([
            'work_experience' => WorkExperience::ENTRY_LEVEL->value,
        ]);
    }
}

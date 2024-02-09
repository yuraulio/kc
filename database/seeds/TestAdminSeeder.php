<?php

namespace Database\Seeders;

use App\Model\Admin\Admin;
use App\Model\Admin\AdminActivation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminToCreate = [
            'firstname' => 'AdminDrupfan',
            'lastname' => 'Drupfan',
            'username' => 'admin',
            'email' => 'admin@drupfan.com',
            'password' => Hash::make('TV8cfBj856p0'),
        ];

        $admin = Admin::create($adminToCreate);
        AdminActivation::create([
            'user_id' => $admin->id,
            'completed' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Model\Activation;
use App\Model\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersToCreate = [
            [
                'firstname' => 'SuperAdminDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'super-administrator_drupfan',
                'email' => 'super-administrator@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '1',
            ],
            [
                'firstname' => 'AministratorDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'administrator_drupfan',
                'email' => 'administrator@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '2',
            ],
            [
                'firstname' => 'MemberDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'member_drupfan',
                'email' => 'member@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '6',
            ],
            [
                'firstname' => 'KnowcrunchStudentDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'knowcrunch-student_drupfan',
                'email' => 'knowcrunch-student@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '7',
            ],
            [
                'firstname' => 'KnowcrunchPayerDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'knowcrunch-payer_drupfan',
                'email' => 'knowcrunch-payer@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '8',
            ],
            [
                'firstname' => 'KnowcrunchPartnerDrupfan',
                'lastname' => 'Drupfan',
                'username' => 'knowcrunch-partner_drupfan',
                'email' => 'knowcrunch-partner@drupfan.com',
                'password' => Hash::make('TV8cfBj856p0'),
                'role' => '9',
            ],
        ];

        foreach ($usersToCreate as $item) {
            $roleId = $item['role'];
            unset($item['role']);

            $user = User::create($item);

            Activation::create([
                'user_id' => $user->id,
                'completed' => 1,
            ]);

            $user->role()->attach($roleId);
        }
    }
}

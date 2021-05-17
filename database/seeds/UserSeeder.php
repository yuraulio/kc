<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::insert([
            'id' => 1,
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@argon.com',
            'password' => '$2y$10$aRbkyvFyO.aUjmcTWkzvceEkbLX1yFiDCWkCu1c7A20jRXK.JNdS2',
            'birthday' => '10/10/2000',
            'created_at' => now(),
            'updated_at' => now()


        ]);
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');



        $this->call([RoleSeeder::class, UserRoleSeeder::class, UsersSeeder::class, ActivationsSeeder::class]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

<?php

namespace Database\Seeders;

use App\Model\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $roles = [
          [
              'name' => 'Administrator',
              'permissions' => [
                  'sudo' => true,
                  'admin' => true,
              ],
              'level' => 100,
          ],
            [
                'name' => 'Instructor',
                'permissions' => [
                    'instructor' => true,
                ],
                'level' => 9,
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                [
                    'name' => $role['name']
                ],
                [
                    'permissions' => json_encode($role['permissions']),
                    'level' => $role['level'],
                ]
            );
        }
    }
}

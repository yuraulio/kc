<?php

namespace App\Console\Commands;

use App\Model\Role;
use App\Model\User;
use Illuminate\Console\Command;

class TransformInstructorIntoUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transform-instructor-into-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instructorRole = Role::where('name', 'Instructor')->firstOrFail();
        $users = User::has('instructor')
            ->with('roles', 'instructor')
            ->get();

        foreach ($users as $user) {
            if ($user->roles->contains(fn (Role $role) => $role->name === 'Instructor')) {
                continue;
            }

            $user->roles()->attach($instructorRole);
        }
    }
}

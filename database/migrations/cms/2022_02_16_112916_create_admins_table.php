<?php

use App\Model\Admin\Admin;
use App\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE TABLE admins LIKE users; ');

        $adminUsers = User::whereHas(
            'role',
            function ($q) {
                return $q->where('roles.id', 1);
            }
        )->get();

        foreach ($adminUsers as $user) {
            $admin = $user->replicate();
            $admin->setTable('admins');
            $admin->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}

<?php

use App\Model\Admin\Admin;
use App\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('CREATE TABLE admin_activations LIKE activations; ');

        $adminUsers = User::whereHas(
            'role',
            function ($q) {
                return $q->where('roles.id', 1);
            }
        )->get();

        foreach ($adminUsers as $user) {
            $activate = $user->statusAccount->replicate();
            $activate->user_id = Admin::where('email', $user->email)->first()->id;
            $activate->setTable('admin_activations');
            $activate->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_activations');
    }
}

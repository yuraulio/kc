<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $users = \App\Model\User::query()
            ->whereNotNull('meta_title')
            ->orWhereNotNull('meta_description')
            ->get();
        foreach ($users as $user) {
            $user->meta_title = $user->firstname . ' ' . $user->lastname;
            $user->meta_description = $user->firstname . ' ' . $user->lastname;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

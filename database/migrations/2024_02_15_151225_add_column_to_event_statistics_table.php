<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_statistics', function (Blueprint $table) {
            $table->dateTime('last_seen')->default('2023-01-01 00:00:00');
        });
        DB::statement('UPDATE `event_statistics` SET `last_seen` = `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_statistics', function (Blueprint $table) {
            $table->dropColumn('last_seen');
        });
    }
};

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
        Schema::table('event_statistics', function (Blueprint $table) {
            $table->double('total_seen', 8, 2)->default(0);
            $table->double('total_duration', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_statistics', function (Blueprint $table) {
            $table->dropColumn('total_seen');
            $table->dropColumn('total_duration');
        });
    }
};

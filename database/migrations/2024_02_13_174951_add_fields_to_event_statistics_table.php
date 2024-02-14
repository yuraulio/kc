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
            $table->float('total_seen')->default(0)->change();
            $table->float('total_duration')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_statistics', function (Blueprint $table) {
            $table->double('total_seen', 8, 2)->default(0)->change();
            $table->double('total_duration', 8, 2)->default(0)->change();
        });
    }
};

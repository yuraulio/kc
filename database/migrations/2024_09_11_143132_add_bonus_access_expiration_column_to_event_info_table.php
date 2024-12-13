<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->integer('bonus_access_expiration')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('bonus_access_expiration');
        });
    }
};

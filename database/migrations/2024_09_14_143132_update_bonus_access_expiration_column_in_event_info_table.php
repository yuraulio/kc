<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->date('bonus_access_expiration')->nullable()->default(null)->change();
        });

        \App\Model\EventInfo::query()->update([
            'bonus_access_expiration' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->integer('bonus_access_expiration')->nullable()->default(null)->change();
        });
    }
};

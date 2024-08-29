<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_bonuses', function (Blueprint $table) {
            $table->boolean('exams_required')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('event_bonuses', function (Blueprint $table) {
            $table->dropColumn('exams_required');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->string('course_satisfaction_url')->nullable()->default(null);
            $table->string('instructors_url')->nullable()->default(null);
            $table->integer('send_after_days')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('course_satisfaction_url');
            $table->dropColumn('instructors_url');
            $table->dropColumn('send_after_days');
        });
    }
};

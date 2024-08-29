<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->foreignId('language_id')
                ->nullable()
                ->default(null)
                ->after('course_language_title')
                ->constrained('languages');
        });
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropForeign('event_info_language_id_foreign');
            $table->dropColumn('language_id');
        });
    }
};

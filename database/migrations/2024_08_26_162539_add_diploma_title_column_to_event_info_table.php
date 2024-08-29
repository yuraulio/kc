<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->longText('diploma_title')->nullable()->default(null)->after('course_certification_completion');
        });
    }

    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('diploma_title');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('access_duration')->nullable()->default(null);
            $table->date('files_access_till')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('access_duration');
            $table->dropColumn('files_access_till');
        });
    }
};

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
        Schema::table('career_paths', function (Blueprint $table) {
            $table->dropColumn('priority');
        });

        Schema::drop('careerpathables');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_paths', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('cms_files', function (Blueprint $table) {
            $table->string('admin_label', 255)
                ->nullable()
                ->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_files', function (Blueprint $table) {
            $table->dropColumn('admin_label');
        });
    }
};

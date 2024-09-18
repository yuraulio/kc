<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('examables', function (Blueprint $table) {
            $table->boolean('whole_amount_should_be_paid')->nullable()->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('examables', function (Blueprint $table) {
            $table->dropColumn('whole_amount_should_be_paid');
        });
    }
};

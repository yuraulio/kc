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
        Schema::create('messaging_activityables', function (Blueprint $table) {
            $table->integer('messaging_activity_id');
            $table->integer('messaging_activityables_id');
            $table->string('messaging_activityables_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messaging_activityables');
    }
};

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
        Schema::create('messaging_activities', function (Blueprint $table) {
            $table->id();
            $table->text('subject');
            $table->string('type');
            $table->string('event_id');
            $table->string('email')->nullable();
            $table->string('status');
            $table->tinyInteger('opened')->default(0);
            $table->tinyInteger('clicked')->default(0);
            $table->text('activity_log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messaging_activities');
    }
};

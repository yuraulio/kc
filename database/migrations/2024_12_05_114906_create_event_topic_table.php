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
        Schema::create('event_topics', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('topic_id');
            $table->integer('priority');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('topic_id')->references('id')->on('topics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_topics');
    }
};

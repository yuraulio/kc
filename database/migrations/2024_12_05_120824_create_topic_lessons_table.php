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
        Schema::create('topic_lessons', function (Blueprint $table) {
            $table->id();
            $table->integer('topic_id');
            $table->integer('lesson_id');
            $table->integer('priority');

            $table->foreign('lesson_id')->references('id')->on('lessons');
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_lessons');
    }
};

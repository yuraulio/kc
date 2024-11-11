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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->integer('rating')->nullable();
            $table->string('status')->default('pending');
            $table->string('facebook_post_id')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_id')->nullable();
            $table->string('facebook_access_token')->nullable();
            $table->string('facebook_page_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_id');
            $table->dropColumn('facebook_access_token');
            $table->dropColumn('facebook_page_token');
        });
    }
};

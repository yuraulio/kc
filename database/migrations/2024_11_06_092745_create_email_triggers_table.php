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
        Schema::create('email_triggers', function (Blueprint $table) {
            $table->id();
            $table->string('trigger_type')->nullable();
            $table->text('trigger_filters')->nullable();
            $table->integer('value')->nullable();
            $table->integer('value_sign')->default(-1);
            $table->integer('email_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_triggers');
    }
};

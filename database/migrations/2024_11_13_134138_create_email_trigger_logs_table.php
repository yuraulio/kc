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
        Schema::create('email_triggerables', function (Blueprint $table) {
            $table->id();
            $table->integer('email_trigger_id');
            $table->integer('email_triggerables_id');
            $table->string('email_triggerables_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_triggerables');
    }
};

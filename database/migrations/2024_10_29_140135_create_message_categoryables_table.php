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
        Schema::create('messaging_categoryables', function (Blueprint $table) {
            $table->integer('message_category_id');
            $table->integer('messaging_categoryables_id');
            $table->string('messaging_categoryables_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_categoryables');
    }
};

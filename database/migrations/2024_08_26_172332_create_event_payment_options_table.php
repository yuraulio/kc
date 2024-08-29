<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_payment_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('option_id');
            $table->boolean('active')->default(true);
            $table->boolean('installments_allowed')->default(true);
            $table->integer('monthly_installments_limit')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_payment_options');
    }
};

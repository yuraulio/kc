<?php

use App\Model\PaymentOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        PaymentOption::create(['title' => 'Cards']);
        PaymentOption::create(['title' => 'Mobile payments']);
        PaymentOption::create(['title' => 'SEPA direct debit']);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_options');
    }
};

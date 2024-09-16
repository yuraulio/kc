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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('billing_city')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_zipcode')->nullable();
            $table->string('billing_company_name')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_vat')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_suite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};

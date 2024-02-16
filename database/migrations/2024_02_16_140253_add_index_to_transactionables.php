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
        Schema::table('transactionables', function (Blueprint $table) {
            $table->index(['transaction_id', 'transactionable_type'], 'transaction_id_type_idx');
            $table->index(['transactionable_id', 'transactionable_type'], 'target_id_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactionables', function (Blueprint $table) {
            $table->dropIndex('transaction_id_type_idx');
            $table->dropIndex('target_id_type_idx');
        });
    }
};

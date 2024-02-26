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
        Schema::table('invoiceables', function (Blueprint $table) {
            $table
                ->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('NO ACTION');
            $table->index(['invoiceable_id', 'invoiceable_type'], 'target_id_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoiceables', function (Blueprint $table) {
            $table->dropIndex('invoice_id');
            $table->dropIndex('target_id_type_idx');
        });
    }
};

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
        Schema::table('event_info', function (Blueprint $table) {
            $table->string('summary_title')->nullable();
            $table->boolean('summary_visible')->default(false);
            $table->string('topics_title')->nullable();
            $table->string('topics_text')->nullable();
            $table->boolean('topics_visible')->default(false);
            $table->string('location_title')->nullable();
            $table->string('location_text')->nullable();
            $table->boolean('location_visible')->default(false);
            $table->string('ticket_title')->nullable();
            $table->string('ticket_text')->nullable();
            $table->boolean('ticket_visible')->default(false);
            $table->string('review_title')->nullable();
            $table->string('review_text')->nullable();
            $table->boolean('review_visible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('summary_title');
            $table->dropColumn('summary_visible');
            $table->dropColumn('topics_title');
            $table->dropColumn('topics_text');
            $table->dropColumn('topics_visible');
            $table->dropColumn('location_title');
            $table->dropColumn('location_text');
            $table->dropColumn('location_visible');
            $table->dropColumn('ticket_title');
            $table->dropColumn('ticket_text');
            $table->dropColumn('ticket_visible');
            $table->dropColumn('review_title');
            $table->dropColumn('review_text');
            $table->dropColumn('review_visible');
        });
    }
};

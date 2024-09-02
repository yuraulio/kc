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
        Schema::table('exams', function (Blueprint $table) {
            $table->integer('exam_category')->default(1)->change();
            $table->integer('repeat_exam_in_failure')->nullable()->default(0);
            $table->integer('course_elearning_exam_activate_months')->nullable()->default(0);
            $table->integer('minutes_after_completion')->nullable()->default(0);
            $table->string('exam_activation_datetime')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            //
        });
    }
};

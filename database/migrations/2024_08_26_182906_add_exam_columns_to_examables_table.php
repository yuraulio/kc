<?php

use App\Enums\Event\ExamAccessibilityTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('examables', function (Blueprint $table) {
            $table->enum('exam_accessibility_type', [
                ExamAccessibilityTypeEnum::PERIOD_AFTER->value,
                ExamAccessibilityTypeEnum::PROGRESS_PERCENTAGE->value,
            ])->nullable()->default(null)->comment('by_period_after, by_progress_percentage (months after their enrollment to the course | Who watched % of the course)');
            $table->integer('exam_accessibility_value')->nullable()->default(null)->comment('count of months | % of the course');
            $table->integer('exam_repeat_delay')->nullable()->default(null)->comment('days after previous exams (or previous try)');
        });
    }

    public function down(): void
    {
        Schema::table('examables', function (Blueprint $table) {
            $table->dropColumn('exam_accessibility_type');
            $table->dropColumn('exam_accessibility_value');
            $table->dropColumn('exam_repeat_delay');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCourseExamActivateMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->integer('course_elearning_exam_activate_months')->after('course_elearning_exam_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('course_elearning_exam_activate_months');
        });
    }
}

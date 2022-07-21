<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnExamInfosEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->string('course_elearning_exam_text')->after('course_elearning_text')->nullable();
            $table->string('course_elearning_exam_visible')->after('course_elearning_text')->nullable();
            $table->string('course_elearning_exam_icon')->after('course_elearning_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

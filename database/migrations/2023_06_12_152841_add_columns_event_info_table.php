<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->string('course_hours_title')->after('course_hours')->nullable();
            $table->string('course_language_title')->after('course_language')->nullable();
            $table->string('course_delivery_title')->after('course_delivery_text')->nullable();
            $table->string('course_students_title')->after('course_students_text')->nullable();
            $table->string('course_certification_title')->after('course_certification_type')->nullable();
            $table->string('course_elearning_expiration_title')->after('course_elearning_expiration')->nullable();
            $table->string('course_elearning_exam_title')->after('course_elearning_exam_visible')->nullable();
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
            $table->dropColumn('course_hours_title');
            $table->dropColumn('course_language_title');
            $table->dropColumn('course_delivery_title');
            $table->dropColumn('course_students_title');
            $table->dropColumn('course_certification_title');
            $table->dropColumn('course_elearning_expiration_title');
            $table->dropColumn('course_elearning_exam_title');
        });
    }
}

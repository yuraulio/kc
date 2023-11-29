<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendanceCertificationTitleToEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->text('course_certification_attendance_title')->after('course_certification_title')->nullable();
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
            $table->dropColumn('course_certification_attendance_title');
        });
    }
}

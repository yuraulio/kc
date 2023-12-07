<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCourseCertificationTextToEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->longText('course_certification_text')->after('course_certification_title')->nullable();
            $table->longText('course_certification_completion')->after('has_certificate')->nullable();
            $table->tinyInteger('has_certificate_exam')->after('course_certification_completion')->default(0);
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
            $table->dropColumn('course_certification_text');
            $table->dropColumn('course_certification_completion');
            $table->dropColumn('has_certificate_exam');
        });
    }
}

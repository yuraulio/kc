<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');

            $table->string('course_status')->nullable();

            $table->string('course_hours')->nullable();
            $table->longtext('course_hours_visible')->nullable();
            $table->longtext('course_hours_icon')->nullable();

            $table->string('course_language')->nullable();
            $table->longtext('course_language_visible')->nullable();
            $table->longtext('course_language_icon')->nullable();

            $table->string('course_delivery')->nullable();

            $table->string('course_inclass_city')->nullable();
            $table->longtext('course_inclass_city_icon')->nullable();
            $table->longtext('course_inclass_dates')->nullable();
            $table->longtext('course_inclass_days')->nullable();
            $table->longtext('course_inclass_times')->nullable();
            $table->double('course_inclass_absences')->nullable();

            $table->longtext('course_elearning_access')->nullable();
            $table->longtext('course_elearning_access_icon')->nullable();

            $table->string('course_payment_method')->nullable();
            $table->longtext('course_payment_icon')->nullable();

            $table->boolean('course_partner')->nullable();
            $table->longtext('course_partner_icon')->nullable();
            $table->boolean('course_manager')->nullable();
            $table->longtext('course_manager_icon')->nullable();

            $table->boolean('course_awards')->nullable();
            $table->string('course_awards_text')->nullable();
            $table->longtext('course_awards_icon')->nullable();

            $table->string('course_certification_name_success')->nullable();
            $table->string('course_certification_name_failure')->nullable();
            $table->string('course_certification_type')->nullable();
            $table->longtext('course_certification_visible')->nullable();
            $table->longtext('course_certification_icon')->nullable();

            $table->string('course_students_number')->nullable();
            $table->string('course_students_text')->nullable();
            $table->longtext('course_students_visible')->nullable();
            $table->longtext('course_students_icon')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_info');
    }
}

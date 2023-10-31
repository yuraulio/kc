<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToEventTopicLessonInstructorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_topic_lesson_instructor', function (Blueprint $table) {
            $table->index('event_id');
            $table->index('topic_id');
            $table->index('lesson_id');
            $table->index('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_topic_lesson_instructor', function (Blueprint $table) {
            $table->dropIndex('event_topic_lesson_instructor_event_id_index');
            $table->dropIndex('event_topic_lesson_instructor_topic_id_index');
            $table->dropIndex('event_topic_lesson_instructor_lesson_id_index');
            $table->dropIndex('event_topic_lesson_instructor_instructor_id_index');
        });
    }
}

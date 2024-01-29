<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAutomateMailFieldToTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_topic_lesson_instructor', function (Blueprint $table) {
            $table->boolean('automate_mail')->default(false)->nullable();
            $table->boolean('send_automate_mail')->default(false)->nullable();
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
            //
        });
    }
}

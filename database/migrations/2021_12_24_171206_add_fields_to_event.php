<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('evaluate_topics')->nullable()->after('fb_group');
            $table->text('evaluate_instructors')->nullable()->after('evaluate_topics');
            $table->text('fb_testimonial')->nullable()->after('evaluate_instructors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('evaluate_topics');
            $table->dropColumn('evaluate_instructors');
            $table->dropColumn('fb_testimonial');
        });
    }
}

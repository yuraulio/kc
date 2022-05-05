<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToCatygorablesAndToCategoryTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categoryables', function (Blueprint $table) {
            $table->integer('priority')->nullable();
        });

        Schema::table('categories_topics_lesson', function (Blueprint $table) {
            $table->integer('priority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categoryables', function (Blueprint $table) {
            //
        });

        Schema::table('categories_topics_lesson', function (Blueprint $table) {
            //
        });
    }
}

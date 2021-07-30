<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();

            $table->integer('priority')->nullable(); // priority inside option groups where applicable
            $table->string('group', 40)->nullable()->index(); // option grouping where applicable
            $table->string('type', 40)->index(); // banner, block, system, config, post, meta, setup
            $table->string('name', 40)->index();
            $table->string('abbr', 40)->nullable()->index();
            $table->string('title', 255)->nullable(); // a somewhat descriptive title

            $table->tinyInteger('status'); // 0: inactive, 1: active, 2: hidden ...
            $table->integer('parent_id')->nullable(); // usable in the case of linking custom fields to post types, or other relationships
            $table->longText('settings'); // a json object with further settings
            $table->string('value', 40)->nullable(); // for simple option value pairs

            $table->timestamps();
        });


        Schema::table('exams', function (Blueprint $table) {
            $table->string('publish_time')->change();
            $table->boolean('indicate_crt_incrt_answers')->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->longText('status_history')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}

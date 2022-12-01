<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsCountdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_countdown', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('button_status')->default(false);
            $table->string('button_title')->nullable();
            $table->date('published_from')->nullable();
            $table->date('published_to')->nullable();
            $table->timestamp('countdown_from');
            $table->timestamp('countdown_to');
            $table->timestamps();
        });

        Schema::create('cms_countdown_delivery', function (Blueprint $table) {
            $table->id();
            $table->integer('countdown_id');
            $table->integer('delivery_id');

        });
        Schema::create('cms_countdown_category', function (Blueprint $table) {
            $table->id();
            $table->integer('countdown_id');
            $table->integer('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_countdown');
        Schema::dropIfExists('cms_countdown_delivery');
        Schema::dropIfExists('cms_countdown_category');
    }
}

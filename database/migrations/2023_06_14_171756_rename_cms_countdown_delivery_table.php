<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCmsCountdownDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cms_countdown_delivery');

        Schema::create('cms_countdown_event', function (Blueprint $table) {
            $table->id();
            $table->integer('countdown_id');
            $table->integer('event_id');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_countdown_event');
    }
}

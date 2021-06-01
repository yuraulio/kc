<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsFaqeventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_faqevent', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->foreign('event_id')->references('id')->on('events');

            $table->integer('events_faqevent');
            $table->foreign('events_faqevent')->references('id')->on('categories_faqs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_faqevent');
    }
}

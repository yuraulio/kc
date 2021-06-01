<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsCategoryfaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::dropIfExists('faqs_categoryfaqs');
        Schema::create('faqs_categoryfaqs', function (Blueprint $table) {
            $table->id();
            $table->integer('faq_id');
            $table->foreign('faq_id')->references('id')->on('faqs');

            $table->integer('faqs_categoryfaqs');
            $table->foreign('faqs_categoryfaqs')->references('id')->on('categories_faqs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faqs_categoryfaqs');
    }
}

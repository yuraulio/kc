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
        //'faq_categoryfaqs'
        Schema::create('faqables', function (Blueprint $table) {
            /*$table->id();
            $table->integer('categoryfaq_id');
            $table->integer('faq_id');*/

            $table->id();
            $table->integer('faq_id');
            $table->integer('faqable_id');
            $table->string('faqable_type');
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
        Schema::dropIfExists('faqables');
    }
}

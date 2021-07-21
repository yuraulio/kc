<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            /*$table->string('slug')->unique();*/
            /*$table->string('stripe_plan');
            $table->float('cost');
            $table->text('interval');
            $table->text('interval_count');
            $table->integer('trial_days')->nullable();
            $table->timestamps();
        });*/

        /*Schema::create('plan_events', function(Blueprint $table) {
           
            $table->increments('id');
            $table->integer('plan_id')->unsigned()->index();
            $table->integer('event_id')->unsigned()->index();
           
        });

        Schema::create('plan_categories', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('plan_id')->unsigned()->index();            
            $table->integer('category_id')->unsigned()->index();
         
        });*/

        Schema::table('plan_events', function(Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('plan_categories', function(Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('plan_events', function(Blueprint $table) {
            $table->increments('id')->first();
        });

        Schema::table('plan_categories', function(Blueprint $table) {
            $table->increments('id')->first();
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn(['published']);
           
        });

        Schema::table('plans', function (Blueprint $table) {
           
            $table->id()->first();
            $table->boolean('published')->default(false);
        });

        

        Schema::create('plan_noevents', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('plan_id')->unsigned()->index();        
            $table->bigInteger('event_id')->unsigned()->index();
    
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}

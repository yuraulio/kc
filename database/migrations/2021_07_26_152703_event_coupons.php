<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventCoupons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->text('coupon_id');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('price');
            $table->boolean('used')->default(false)->after('status');
            $table->string('price')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

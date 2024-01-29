<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('stripe_id');
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            //$table->timestamp('trial_ends_at')->nullable();
            //$table->timestamp('ends_at')->nullable();
            $table->string('trial_ends_at')->nullable();
            $table->string('ends_at')->nullable();
            $table->text('metadata')->nullable();
            $table->decimal('price', 10, 4)->default(0);
            $table->boolean('email_send')->default(false);
            $table->bigInteger('must_be_updated')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'stripe_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('subscriptions');
    }
}

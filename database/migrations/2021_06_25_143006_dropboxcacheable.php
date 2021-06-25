<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Dropboxcacheable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropboxcacheables', function (Blueprint $table) {
            $table->integer('dropbox_id');
            $table->integer('dropboxcacheable_id');
            $table->string('dropboxcacheable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dropboxcacheables');
    }
}

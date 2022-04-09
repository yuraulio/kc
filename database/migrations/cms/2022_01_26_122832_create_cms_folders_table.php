<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_folders');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('extension')->nullable();
            $table->double('size')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
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
        Schema::dropIfExists('cms_files');
    }
}

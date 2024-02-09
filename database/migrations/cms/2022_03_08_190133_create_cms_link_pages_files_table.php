<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsLinkPagesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_link_pages_files', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id');
            $table->foreign('page_id')->references('id')->on('cms_pages');
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('cms_files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_link_pages_files');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsLinkPagesSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_link_pages_subcategories', function (Blueprint $table) {
            $table->unsignedBigInteger("page_id");
            $table->foreign('page_id')->references('id')->on('cms_pages');
            $table->unsignedBigInteger("category_id");
            $table->foreign('category_id')->references('id')->on('cms_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_link_pages_subcategories');
    }
}

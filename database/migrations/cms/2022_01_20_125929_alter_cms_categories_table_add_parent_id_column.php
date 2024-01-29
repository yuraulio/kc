<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCmsCategoriesTableAddParentIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_categories', function (Blueprint $table) {
            $table->integer('parent_id')->nullable();
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_categories', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->string('description')->nullable();
        });
    }
}

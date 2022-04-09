<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCmsPagesTableRemoveDiscriptionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
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
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->string("description")->nullable();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCmsFilesTableAddWidthHeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_files', function (Blueprint $table) {
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_files', function (Blueprint $table) {
            $table->dropColumn('height');
            $table->dropColumn('width');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCmsFilesTableAddVersionAndLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_files', function (Blueprint $table) {
            $table->string("version")->nullable();
            $table->string("link")->nullable();
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
            $table->dropColumn('version');
            $table->dropColumn('link');
        });
    }
}

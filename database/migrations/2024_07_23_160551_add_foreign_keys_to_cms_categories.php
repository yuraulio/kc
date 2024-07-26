<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cms_categories', function (Blueprint $table) {
            $table->bigInteger('parent_id')->unsigned()->change();
            $table->bigInteger('page_type_id')->unsigned()->change();
            $table->bigInteger('image_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('admins');
            $table->foreign('parent_id')->references('id')->on('cms_categories');
            $table->foreign('image_id')->references('id')->on('cms_files');
            $table->foreign('page_type_id')->references('id')->on('cms_page_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_categories', function (Blueprint $table) {
            $table->dropForeign('cms_categories_user_id_foreign');
            $table->dropForeign('cms_categories_parent_id_foreign');
            $table->dropForeign('cms_categories_image_id_foreign');
            $table->dropForeign('cms_categories_page_type_id_foreign');
        });
    }
};

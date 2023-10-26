<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToTestimoniablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testimoniables', function (Blueprint $table) {
            $table->index('testimoniable_id');
            $table->index('testimoniable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testimoniables', function (Blueprint $table) {
            $table->dropIndex('testimoniables_testimoniable_id_index');
            $table->dropIndex('testimoniables_testimoniable_type_index');
        });
    }
}

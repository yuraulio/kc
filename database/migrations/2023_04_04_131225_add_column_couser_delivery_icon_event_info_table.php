<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCouserDeliveryIconEventInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->longtext('course_delivery_icon')->after('course_delivery')->nullable();
            $table->longtext('course_delivery_text')->after('course_delivery')->nullable();
            $table->longtext('course_delivery_visible')->after('course_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_info', function (Blueprint $table) {
            $table->dropColumn('course_delivery_icon');
            $table->dropColumn('course_delivery_text');
            $table->dropColumn('course_delivery_visible');
        });
    }
}

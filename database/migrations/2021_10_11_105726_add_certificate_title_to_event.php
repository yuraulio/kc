<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCertificateTitleToEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('certificate_title')->after('enroll')->nullable();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->text('firstname')->after('id')->nullable();
            $table->text('lastname')->after('firstname')->nullable();
            $table->text('certificate_title')->after('lastname')->nullable();
            $table->text('credential')->after('expiration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });

        Schema::table('certificates', function (Blueprint $table) {
            //
        });
    }
}

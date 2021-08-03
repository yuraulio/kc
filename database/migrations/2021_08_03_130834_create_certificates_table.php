<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            $table->boolean('success');
            $table->string('certification_date');

            $table->string('create_date');
            $table->string('expiration_date');

            $table->timestamps();
        });

        Schema::create('certificatables', function (Blueprint $table) {
            $table->integer('certificate_id');
            $table->integer('certificatable_id');
            $table->string('certificatable_type');
        });

        Schema::table('video', function (Blueprint $table) {
            $table->text('body')->after('description')->nullable();
            $table->text('url')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificatables');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dynamic_ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('headline')->nullable()->default(null);
            $table->text('short_description')->nullable()->default(null);
            $table->longText('long_description')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_ads');
    }
};

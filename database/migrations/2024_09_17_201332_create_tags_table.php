<?php

use App\Model\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable()->default(null);
        });

        Tag::query()->create([
            'name' => 'Test tag',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};

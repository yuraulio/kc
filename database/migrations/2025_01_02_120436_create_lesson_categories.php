<?php

use App\Model\LessonCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lesson_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        LessonCategory::query()->create([
            'name'        => 'Lecture',
            'description' => 'replaces Lecture lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Platform demonstration',
            'description' => 'replaces Tutorial lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Exercise (personal)',
            'description' => 'replaces Hands-on lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Exercise (team)',
            'description' => 'replaces Team Exercise lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Presentation (team)',
            'description' => 'replaces Presentation lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Exam',
            'description' => 'replaces Final exam lesson type in database',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Graduation',
            'description' => 'new',
        ]);
        LessonCategory::query()->create([
            'name'        => 'Networking',
            'description' => 'replaces Networking event lesson type in database',
        ]);

        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('category_id')->references('id')->on('lesson_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('lesson_categories');
    }
};

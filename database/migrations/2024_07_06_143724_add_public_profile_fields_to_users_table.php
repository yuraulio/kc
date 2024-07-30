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
        Schema::table('users', function (Blueprint $table) {
            // Social links
            $table->json('social_links')->nullable();

            // Biography
            $table->text('biography')->nullable();

            // Employment type flags
            $table->boolean('is_employee')->nullable();
            $table->boolean('is_freelancer')->nullable();

            // Work preferences
            $table->boolean('will_work_remote')->nullable();
            $table->boolean('will_work_in_person')->nullable();

            // Work experience level
            $table->enum('work_experience', ['entry-level', 'mid-level', 'senior-level'])->nullable();

            // Public profile enabled flag
            $table->boolean('is_public_profile_enabled')->nullable();
        });

        Schema::create('city_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'city_id']);
        });

        Schema::create('career_path_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('career_path_id')->references('id')->on('career_paths')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'career_path_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'social_links',
                'biography',
                'is_employee',
                'is_freelancer',
                'will_work_remote',
                'will_work_in_person',
                'work_experience',
                'is_public_profile_enabled',
            ]);
        });

        Schema::dropIfExists('city_user');
        Schema::dropIfExists('career_path_user');
    }
};

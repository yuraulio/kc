<?php

use App\Model\Instructor;
use App\Model\Role;
use App\Model\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_topic_lesson_instructor', function (Blueprint $table) {
            $table->integer('user_id')->after('instructor_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });

        $role = Role::query()->updateOrCreate(['name' => 'Instructor', 'level' => 45]);

        $instructors = Instructor::all();

        foreach ($instructors as $instructor) {
            /** @var User $user */
            $user = $instructor->user()->first();

            if (!$user) {
                /** @var User $user */
                $user = User::create([
                    'firstname'      => $instructor->title,
                    'lastname'       => $instructor->subtitle,
                    'company'        => $instructor->company,
                    'profile_status' => $instructor->status,
                    'account_status' => $instructor->status,
                    'biography'      => $instructor->body,
                    'social_links'   => $instructor->social_media,
                ]);
                $user->instructor()->sync([$instructor->id]);
            }

            $user->roles()->attach($role);

            DB::table('event_topic_lesson_instructor')
                ->where('instructor_id', $instructor->id)
                ->update(['user_id' => $user->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};

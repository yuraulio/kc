<?php

use App\Model\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_creators', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');
        });

        $events = Event::query()->where('creator_id', null)->get();
        foreach ($events as $event) {
            if ($event->creator_id) {
                $event->creators()->sync([$event->creator_id]);
            }
        }

        Schema::table('events', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->boolean('is_promoted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_promoted');
            $table->dropColumn('video_url');
        });
        Schema::dropIfExists('event_creators');
    }
};

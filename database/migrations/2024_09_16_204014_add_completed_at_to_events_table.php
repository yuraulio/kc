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
        Schema::table('events', function (Blueprint $table) {
            $table->date('completed_at')->after('updated_at')->nullable();
        });

        \App\Model\Event::query()
            ->where('status', \App\Model\Event::STATUS_COMPLETED)
            ->chunk(10, function ($chunk) {
                foreach ($chunk as $event) {
                    $firstTopic = $event->allTopics()->first();
                    if ($firstTopic && $firstTopic->pivot->date != '') {
                        $event->completed_at = $firstTopic->pivot->date;
                    } else {
                        $event->completed_at = \Carbon\Carbon::parse($event->updated_at);
                    }
                    $event->save();
                }
            });

        \App\Model\EventInfo::query()->update([
            'bonus_access_expiration' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};

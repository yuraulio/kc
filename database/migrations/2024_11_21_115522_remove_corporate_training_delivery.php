<?php

use App\Audience;
use App\Model\Delivery;
use App\Model\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $delivery = Delivery::where('delivery_type', 'corporate_training')->first();

        $events = $delivery->event;

        foreach ($events as $event) {
            $event->audiences()->sync([2]);
        }

        DB::table('event_delivery')->where('delivery_id', $delivery->id)->update(['delivery_id' => 139]);

        $basicAudience = Audience::find(1);
        $basicAudience->events()->sync(Event::whereNotIn('id', $events->pluck('id')->toArray())->pluck('id')->toArray());

        $delivery->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

namespace App\Jobs;

use App\Model\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateEventAccessToFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $event;

    public function __construct($event)
    {
        $this->event = Event::with('lessons')->find($event);

        if ($this->event->is_inclass_course()) {
            return 0;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lessons = $this->event['lessons'];

        $maxDate = null;
        foreach ($lessons as $key => $lesson) {
            $lessonDate = Carbon::parse($lesson->pivot->date)->format('Y-m-d');

            if ($key == 0) {
                $maxDate = $lessonDate;
            }

            if (strtotime($lessonDate) > strtotime($maxDate)) {
                $maxDate = $lessonDate;
            }
        }

        $this->event->update([
            'release_date_files' => $maxDate,
        ]);
    }
}

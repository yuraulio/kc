<?php

namespace App\Console\Commands;

use App\Model\Event;
use App\Model\Lesson;
use App\Services\Vimeo\VimeoService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Vimeo\Vimeo;

class UpdateVimeoDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_vimeo_duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly VimeoService $vimeoService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('UpdateVimeoDuration');
        Lesson::where('vimeo_video', '!=', '')->chunk(10, function ($lessons) {
            foreach ($lessons as $lesson) {
                $vimeoVideo = explode('/', $lesson->vimeo_video);

                $client = new Vimeo(config('services.vimeo.id'), config('services.vimeo.secret'), config('services.vimeo.token'));
                $response = $client->request('/videos/' . end($vimeoVideo) . '/?password=' . config('services.vimeo.password'), [], 'GET');

                if ($response['status'] === 200) {
                    $duration = $response['body']['duration'];
                    $lesson->vimeo_duration = $this->vimeoService->formatDuration($duration);
                    $lesson->save();
                }
            }
        });

        $events = Event::has('lessons')->get();
        $request = new \Illuminate\Http\Request();

        foreach ($events as $event) {
            $duration = app('App\Http\Controllers\EventController')->calculateTotalHours($request, $event->id);
            $hours = number_format($duration / 60, 1);

            $info = $event->eventInfo;
            $info->course_hours = $hours;
            $info->save();
        }
    }
}

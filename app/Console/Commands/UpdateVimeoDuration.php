<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Vimeo\Vimeo;
use App\Model\Lesson;
use App\Model\Event;

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Lesson::where('vimeo_video','!=', '')->chunk(10, function ($lessons) {
        //     foreach ($lessons as $lesson) {

        //         $vimeoVideo = explode("/",$lesson->vimeo_video);

        //         $client = new Vimeo(env('client_id'), env('client_secret'), env('vimeo_token'));
        //         $response = $client->request("/videos/". end($vimeoVideo) . "/?password=".env('video_password'), array(), 'GET');

        //         if($response['status'] === 200){
        //             $duration = $response['body']['duration'];
        //             $lesson->vimeo_duration = app('App\Http\Controllers\LessonController')->formatDuration($duration);
        //             $lesson->save();
        //         }
        //     }
        // });

        $events = Event::has('lessons')->get();
        $request = new \Illuminate\Http\Request();

        foreach($events as $event){

            $duration = app('App\Http\Controllers\EventController')->calculateTotalHours($request,$event->id);
            $hours = ceil($duration/60);

            $info = $event->event_info1;
            $info->course_hours = $hours;
            $info->save();

        }
    }
}

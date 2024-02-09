<?php

namespace App\Console\Commands;

use App\Model\Event;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetOldLessonInstrunctor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:lessons';

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
        //http://knowcrunchls.j.scaleforce.net/

        $client = new Client(['base_uri' => 'http://knowcrunchls.j.scaleforce.net', 'verify' => false]);
        // Send a request to https://foo.com/api/test
        $response = $client->request('GET', 'http://knowcrunchls.j.scaleforce.net/get-event-lesson');
        $count = 0;
        $lessons = json_decode($response->getBody()->getContents(), true);
        foreach ($lessons['lessons'] as $lesson) {
            $event = Event::find($lesson['event_id']);

            if (!$event) {
                continue;
            }

            //if($event->id !== 4612){
            //    continue;
            //}

            /*if($event->id == 4612){
                //dd($event->lessons()->where('lesson_id',3)->first()->pivot->time_starts);

                $l = $event->lessons()->where('lesson_id',3)->get();
                dd(count($l));
            }*/

            $newLesson = $event->lessons->where('lesson_id', $lesson['lesson_id'])->first();
            if ($newLesson) {
                //if($event->id == 4612 && $newLesson->lesson_id == 3){
               //    $count += 1;
               //    dd($lesson['timestarts']);
                //}

                //$event->lessons()->updateExistingPivot($lesson['lesson_id'],['time_starts'=>$lesson['timestarts']]);

                $newLesson->pivot->date = date('Y-m-d', strtotime($lesson['timestarts']));
                $newLesson->pivot->time_starts = $lesson['timestarts'];
                $newLesson->pivot->time_ends = $lesson['timeends'];
                $newLesson->pivot->duration = $lesson['duration'];
                $newLesson->pivot->save();
            }
        }

        //dd($count);
    }
}

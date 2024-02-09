<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\Event;
use App\Model\Lesson;
use App\Model\Topic;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class INSERTLESSONS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:lessons';

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
        //#######     EVENT   ##########3
        $fileName = public_path() . '/import/' . 'EVENT_LESSON1.csv';

        if (!file_exists($fileName)) {
            return 0;
        }

        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);

        $file = $reader->load($fileName);
        $file = $reader->load($fileName);
        $sheets = $file->getSheetNames();

        //dd($sheets);

        foreach ((array) $sheets as $key => $sheet) {
            $file1 = $file->getSheet($key);
            $file1 = $file1->toArray();
            $movedLessons = [];
            $noMovedLessons = [];
            foreach ($file1 as $key1 =>  $line) {
                if ($key1 == 0) {
                    continue;
                }

                $event = Event::find($line[1]);
                if ($event) {
                    $movedLessons[$line[3]] = [
                        'topic_id'=>$line[2],
                        'lesson_id'=>$line[3],
                        'event_id' => $line[1],
                        'instructor_id' => $line[4],
                        'date' => null,
                        'time_starts' => $line[6],
                        'time_ends' => $line[7],
                        'duration' => $line[8],
                        'room' => $line[9],
                        'location_url'=> $line[10],
                        'automate_mail'=>$line[12],
                        'send_automate_mail'=>$line[13],
                        'priority' => $line[11],
                    ];

                    //$category->lessons()->wherePivot('topic_id',$line[2])->wherePivotIn('lesson_id',array_keys($movedLessonsCate))->detach();
                    //$category->lessons()->attach($movedLessonsCate);
                }
            }

            $nonPrio = [];
            $nonPrio2 = [];
            foreach ($event->allLessons as $l) {
                if (!in_array($l->pivot->lesson_id, array_keys($movedLessons))) {
                    $priorityLesson = $event->allLessons()->wherePivot('topic_id', $l->pivot->topic_id)->orderBy('priority')->get();
                    $priority = isset($priorityLesson->last()['pivot']['priority']) ? $priorityLesson->last()['pivot']['priority'] + 1 : count($priorityLesson) + 1;

                    if (!isset($nonPrio[$l->pivot->topic_id])) {
                        $nonPrio[$l->pivot->topic_id] = $priority;
                    } else {
                        $priority = $nonPrio[$l->pivot->topic_id] + 1;
                        $nonPrio[$l->pivot->topic_id] = $priority;
                    }

                    $nonPrio2[183][$l->pivot->topic_id][$l->pivot->lesson_id] = $priority;

                    $noMovedLessons[$l->pivot->lesson_id] = [
                        'topic_id'=>$l->pivot->topic_id,
                        'lesson_id'=>$l->pivot->lesson_id,
                        'event_id' => $l->pivot->event_id,
                        'instructor_id' => $l->pivot->instructor_id,
                        'date' => null,
                        'time_starts' => null,
                        'time_ends' => null,
                        'duration' => $l->pivot->duration,
                        'room' => '',
                        'location_url'=> '',
                        'automate_mail'=> null,
                        'send_automate_mail'=> null,
                        'priority' => $priority,
                    ];
                }
            }

            $event->allLessons()->detach();
            $event->allLessons()->attach($movedLessons);
            $event->allLessons()->attach($noMovedLessons);
            //dd($key1);
        }
        //dd('hello');
        //category
        $fileName = public_path() . '/import/' . 'CAT_TOPIC1.csv';

        if (!file_exists($fileName)) {
            return 0;
        }

        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);

        $file = $reader->load($fileName);
        $file = $reader->load($fileName);
        $sheets = $file->getSheetNames();

        //dd($sheets);

        foreach ((array) $sheets as $key => $sheet) {
            $file1 = $file->getSheet($key);
            $file1 = $file1->toArray();

            $movedLessonsCate = [];
            $noLessonsCat = [];
            foreach ($file1 as $key1 =>  $line) {
                if ($key1 == 0) {
                    continue;
                }

                $category = Category::find($line[1]);
                if ($category) {
                    //$allEvents = $category->events;
                    /*foreach($allEvents as $event)
                    {

                    }*/

                    $movedLessonsCate[$line[3]] = [
                        'topic_id'=>$line[2],
                        'lesson_id'=>$line[3],
                        'category_id' => $line[1],
                        'priority' => $line[4],
                    ];

                    //$category->lessons()->wherePivot('topic_id',$line[2])->wherePivotIn('lesson_id',array_keys($movedLessonsCate))->detach();
                    //$category->lessons()->attach($movedLessonsCate);
                }
            }

            foreach ($category->lessons as $l) {
                if (!in_array($l->pivot->lesson_id, array_keys($movedLessonsCate))) {
                    $noLessonsCat[$l->pivot->lesson_id] = [
                        'topic_id'=>$l->pivot->topic_id,
                        'lesson_id'=>$l->pivot->lesson_id,
                        'category_id' => $l->pivot->category_id,
                        'priority' => $nonPrio2[$l->pivot->category_id][$l->pivot->topic_id][$l->pivot->lesson_id],
                    ];
                }
            }

            //dd($noLessonsCat);

            //dd(count($movedLessonsCate));
            $category->lessons()->detach();
            $category->lessons()->attach($movedLessonsCate);
            $category->lessons()->attach($noLessonsCat);
            /*foreach($noLessonsCat as $l){
                $topic = Topic::find($l['topic_id']);
                $lesson = Lesson::find($l['lesson_id']);
                $category->updateLesson($topic,$lesson);
            }*/

            //dd($key1);
        }
    }
}

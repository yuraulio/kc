<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\Category;
use App\Model\Event;

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
        $fileName = public_path() . '/import/' . 'CAT_TOPIC.xlsx';
        
        if(!file_exists($fileName)){
            
            return 0;
        }

        
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);

        $file = $reader->load($fileName);
        $file = $reader->load($fileName);
        $sheets = $file->getSheetNames();

        //dd($sheets);

        foreach((array) $sheets as $key => $sheet){
            
            $file1 = $file->getSheet($key);
            $file1 = $file1->toArray();
            $movedLessonsCate = [];
            foreach($file1 as $key1 =>  $line){

                if($key1 == 0){
                    continue;
                }

                $category = Category::find($line[1]);
                if($category){
                    
                    //$allEvents = $category->events;
                    /*foreach($allEvents as $event)
                    {

                    }*/

                    
                    $movedLessonsCate[$line[3]] = [
                        'topic_id'=>$line[2],
                        'lesson_id'=>$line[3],
                        'category_id' => $line[1],
                        'priority' => $line[4]
                    ];

                    //$category->lessons()->wherePivot('topic_id',$line[2])->wherePivotIn('lesson_id',array_keys($movedLessonsCate))->detach();
                    //$category->lessons()->attach($movedLessonsCate);

                }
                

            }
            $category->lessons()->detach();
            $category->lessons()->attach($movedLessonsCate);
            //dd($key1);
        }

        ########     EVENT   ##########3
        $fileName = public_path() . '/import/' . 'EVENT_LESSON.xlsx';
        
        if(!file_exists($fileName)){
            
            return 0;
        }

        
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);

        $file = $reader->load($fileName);
        $file = $reader->load($fileName);
        $sheets = $file->getSheetNames();

        //dd($sheets);

        foreach((array) $sheets as $key => $sheet){
            
            $file1 = $file->getSheet($key);
            $file1 = $file1->toArray();
            $movedLessons = [];
            foreach($file1 as $key1 =>  $line){

                if($key1 == 0){
                    continue;
                }

                $event = Event::find($line[1]);
                if($event){
                    
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
                        'priority' => $line[11]
                    ];

                    //$category->lessons()->wherePivot('topic_id',$line[2])->wherePivotIn('lesson_id',array_keys($movedLessonsCate))->detach();
                    //$category->lessons()->attach($movedLessonsCate);

                }
                

            }
            $event->allLessons()->detach();
            $event->allLessons()->attach($movedLessons);
            //dd($key1);
        }

    }
}

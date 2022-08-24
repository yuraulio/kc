<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Lesson;
use App\Model\Instructor;

class LessonLinksFixed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesson-links:fixed';

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
        $lessons = Lesson::where('links','!=',NULL)->get();

        foreach($lessons as $lesson){

            $newLinks = [];

            $links = json_decode($lesson->links,true);

            if(!$links){
                continue;
            }

            if(count($links) <= 0){
                continue;
            }
           

            foreach($links as $link){
                $link['link'] = str_replace('https://', '', $link['link']);
                $link['link'] = str_replace('http://', '', $link['link']);
                $link['link'] = 'https://'.$link['link'];
                //dd($link);
                $newLinks[] = ['name' => $link['name'], 'link' => $link['link']];

            }

            $lesson->links = json_encode($newLinks);
            $lesson->save();


        }


        $instructors = Instructor::all();

        foreach($instructors as $instructor){

            $link = $instructor->ext_url;

            $link = str_replace('https://', '', $link);
            $link = str_replace('http://', '', $link);
            $link = 'https://'.$link;
            
            $instructor->ext_url = $link;
            $instructor->save();

        }

        return 0;
    }
}

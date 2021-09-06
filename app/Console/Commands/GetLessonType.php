<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Model\Lesson;
use App\Model\Type;

class GetLessonType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesson-type:get';

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
        $client = new Client(['base_uri' => 'http://knowcrunchls.j.scaleforce.net','verify' => false]);
        //client = new Client(['base_uri' => 'http://lcknowcrunch.test','verify' => false]);

        $response = $client->request('GET', 'http://knowcrunchls.j.scaleforce.net/get-lesson-type');
        //$response = $client->request('GET', 'http://lcknowcrunch.test/get-lesson-type');

        $lessons = json_decode($response->getBody()->getContents(),true);

        //dd($lessons);

        $types = $lessons['types'];
        $lessonsType = $lessons['lessonsType'];

        foreach($types as $type){

            if(Type::where('name',$type)->first()){
                continue;
            }

            $t = new Type;

            $t->name = $type;
            $t->save();
            $t->createSlug($type);
        }


        foreach($lessonsType as $key => $lessonType){
            //dd($lessonsType);
            $lessons = Lesson::where('title',$lessonType['name'])->get();
            $type = Type::where('name',$lessonType['type'])->first();
            foreach($lessons as $lesson){
                $lesson->bold = $lessonType['bold'];
                $lesson->links = $lessonType['links'];
                $lesson->save();

                if($type){
                    $lesson->type()->detach(0);
                    $lesson->type()->detach($type->id);
                    $lesson->type()->attach($type->id);
                }
            }
                       
        }

    }
}

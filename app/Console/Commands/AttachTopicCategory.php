<?php

namespace App\Console\Commands;

use App\Model\Topic;
use Illuminate\Console\Command;

class AttachTopicCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:category';

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
        $topics = Topic::all();

        foreach ($topics as $topic) {
            if ($topic->id == 4) {
//                dd($topic->lessonsCategory->first()->pivot->category_id);
            }
            //dd(count($topic->lessonsCategory));
            foreach ($topic->lessonsCategory as $key => $lesson) {
                $topic->category()->detach($lesson->pivot->category_id);
                $topic->category()->attach($lesson->pivot->category_id);
            }
        }

        return 0;
    }
}

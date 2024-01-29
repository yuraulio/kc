<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\Event;
use App\Model\Topic;
use Illuminate\Console\Command;

class ClearTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:topics';

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

        $topicsNames = [];

        foreach ($topics as $topic) {
            $topicsNames[trim($topic->title)][] = $topic->id;
        }

        foreach ($topicsNames as $key => $name) {
            if (count($topicsNames[$key]) <= 1) {
                unset($topicsNames[$key]);
            }
        }

        foreach ($topicsNames as $names) {
            for ($i = 1; $i < count($names); $i++) {
                $topic = Topic::find($names[$i]);

                foreach ($topic->lessons as $lesson) {
                    $lesson->pivot->topic_id = $names[0];
                    $lesson->pivot->save();
                }

                $topic->category()->detach();
                $topic->delete();
            }
        }

        $event = Event::find(1350);
        $category = Category:: find(277);

        foreach ($event->allLessons as $lesson) {
            //dd($lesson->pivot->topic_id);

            $category->topics()->detach($lesson->pivot->topic_id);
            $category->topics()->attach($lesson->pivot->topic_id);

            $category->topic()->detach($lesson->pivot->lesson_id);
            $category->topic()->attach($lesson->pivot->topic_id, ['lesson_id'=>$lesson->pivot->topic_id]);

            //lessonsCategory
        }

        return 0;
    }
}

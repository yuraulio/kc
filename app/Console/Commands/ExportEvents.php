<?php

namespace App\Console\Commands;

use App\Model\Category;
use Illuminate\Console\Command;

class ExportEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:events';

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
        $categories = Category::where('id', '!=', 46)->get();
        $columns = ['ID', 'Title', 'Published'];

        /*$file = fopen('events.csv', 'w');
        fputcsv($file, $columns);

        foreach($category->events as $event){

            fputcsv($file, array($event->id, $event->title,  date('d-m-Y',strtotime($event->published_at))));
        }*/

        $file = fopen('events2.csv', 'w');
        fputcsv($file, $columns);

        foreach ($categories as $category) {
            foreach ($category->events as $event) {
                fputcsv($file, [$event->id, $event->title,  date('d-m-Y', strtotime($event->published_at))]);
            }
        }

        fclose($file);

        return 0;
    }
}

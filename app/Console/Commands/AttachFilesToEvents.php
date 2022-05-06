<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;

class AttachFilesToEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:to-event';

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

        $categories = Category::whereHas('dropbox')->whereHas('events')->get();

        foreach($categories as $category){

            $dropbox = $category->dropbox->first();

            foreach($category->events as $event){
                $event->dropbox()->sync($dropbox->id); 
            }


            $category->dropbox()->detach();


        }


        return 0;
    }
}

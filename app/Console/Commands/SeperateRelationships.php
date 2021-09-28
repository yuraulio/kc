<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;

class SeperateRelationships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seperate:relationship';

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

        $events = Event::where('status',0)->get();
        
        foreach($events as $event){
            
            if(!$event->is_inclass_course()){
                continue;
            }

            $event->load('summary1','sections','benefits');

            foreach ($event->getRelations() as $relationName => $values){
                
                $newValues = [];
                //dd($values);
                //dd(count($values));
                foreach($values as $value){

    
                    $valuee = $value->replicate();
                    $valuee->push();

                    if($value->medias){
                        $valuee->medias()->delete();
                        //$valuee->createMedia();
                        
                        $medias = $value->medias->replicate();
                        $medias->push();
                        //dd($medias);
                        $valuee->medias()->save($medias);
                    }
                    

                    $newValues[] = $valuee; 

                }
                
                $newValues = collect($newValues);
                $event->{$relationName}()->detach();

                foreach($newValues as $value){
                    $event->{$relationName}()->attach($value);
                }
               
               
            }
           
        }
        
        return 0;
    }
}

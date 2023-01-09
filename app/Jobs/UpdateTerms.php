<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\User;

class UpdateTerms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $pageId;
    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->pageId == 4 || $this->pageId == 6){

            $users = User::all();
            foreach($users as $user){
                if($user->instructor->first()){
                    continue;
                }
                
                $user->terms = 0;
                $user->save();
                

            }

        }else if($this->pageId == 4753 || $this->pageId == 48){

            $users = User::all();
            foreach($users as $user){
                if(!$user->instructor->first()){
                    continue;
                }
                
                $user->terms = 0;
                $user->save();
                
            }
        }
    }
}

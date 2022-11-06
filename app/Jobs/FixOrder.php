<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Topic;

class FixOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $model;
    protected $type;

    public function __construct($model,$type)
    {
        $this->model = $model;
        $this->type = $type;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->model->fixOrder();

        /*if(get_class($this->model) == 'App\\Model\\Category'){
            $this->topicOrder();
        }else if(get_class($this->model) == 'App\\Model\\Event'){
            $this->eventLessonOrder();
        }*/
    }


    function topicOrder(){
        $this->model->fixOrder();
    }


    function eventLessonOrder(){
        $this->model->fixOrder();
    }

}

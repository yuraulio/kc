<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\User;

class RemoveProfileWebpImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user_id);
        $media = $user->image;

        if($media){
            $path_crop = explode('.', $media['original_name']);
            $path_crop = $media['path'].$path_crop[0].'-crop'.$media['ext'];
            $path_crop = substr_replace($path_crop, "", 0, 1);

            $path = $media['path'].$media['original_name'];
            $path = substr_replace($path, "", 0, 1);

            if(file_exists($path_crop)){
                //unlink crop image
                unlink($path_crop);
            }

            if(file_exists($path)){
                //unlink crop image
                unlink($path);
            }
        }


    }
}

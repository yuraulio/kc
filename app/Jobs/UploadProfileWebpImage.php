<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\User;

class UploadProfileWebpImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user);

        $media = $user->image;

        $source = 'C:\laragon\www\kcversion8\public\uploads//courses/Knowcrunch-Digital-Marketing-Course-Athens.JPG';
        //dd($source);

        $destination = 'C:\laragon\www\kcversion8\public\uploads//courses/Knowcrunch-Digital-Marketing-Course-Athens.webp';
        $options = [
            'quality'=> 80,
            'auto-limit'=> true,
        ];
    }
}

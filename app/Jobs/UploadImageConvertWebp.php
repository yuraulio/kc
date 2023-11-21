<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UploadImageConvertWebp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;
    private $name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $name)
    {
        $this->path = $path;
        $this->name = $name;

        //dd($this->path.$this->name);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ext = explode('.',$this->name)[count(explode('.',$this->name)) - 1];

        if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){

            $destination = str_replace($ext,'webp',$this->path.$this->name);


            $a = Image::make(public_path('/uploads/').$this->path.$this->name)->stream("webp", config('app.WEBP_IMAGE_QUALITY'));
            Storage::disk('public')->put($destination, $a, 'public');


        }
    }
}

<?php

namespace App\Jobs;

use App\Model\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic as Image;

class SaveImageWebp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $request)
    {
        $this->details = $details;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $media = Media::find($this->request['media_id']);

        //dd(public_path($media['path'].$media['original_name'].$media['ext']));

        $image = Image::make(public_path($media['path'] . $media['original_name']));

        $image->crop($this->request['width'], $this->request['height'], $this->request['x'], $this->request['y']);
        $name = explode('.', $media['original_name']);
        $image->save(public_path($media['path'] . $name[0] . '-crop' . '.webp'), 80);
    }
}

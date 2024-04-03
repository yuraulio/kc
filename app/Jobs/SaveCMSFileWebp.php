<?php

namespace App\Jobs;

use App\CMSFile;
use App\Model\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic as Image;

class SaveCMSFileWebp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $media_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($media_id)
    {
        $this->media_id = $media_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $media = CMSFile::find($this->media_id);

        //dd(public_path($media['path'].$media['original_name'].$media['ext']));

        $image = Image::make(public_path($media->full_path));

        $name = explode('.', $media->full_path);
        $image->save(public_path($name[0] . '.webp'), 80);
    }
}

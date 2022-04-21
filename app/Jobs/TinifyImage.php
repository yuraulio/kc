<?php

namespace App\Jobs;

use App\Http\Controllers\Admin_api\AdminController;
use App\Model\Admin\MediaFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use msonowal\LaravelTinify\Facades\Tinify;

class TinifyImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;
    private $id;

    public function __construct($path, $id)
    {
        $this->path = $path;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Tinify::fromFile($this->path)->toFile($this->path);

        $mediaFile = MediaFile::where("id", $this->id)->first();
        if ($mediaFile) {
            $mediaFile->size = filesize($this->path);
            $mediaFile->save();
        }
    }
}

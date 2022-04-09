<?php

namespace App\Jobs;

use App\Model\Admin\Page;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MoveFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileId;
    private $folderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileId, $folderId)
    {
        $this->fileId = $fileId;
        $this->folderId = $folderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parentFile = MediaFile::find($this->fileId);
        $folder = MediaFolder::find($this->folderId);

        $files = $parentFile->subfiles()->get();
        $files->push($parentFile);

        foreach ($files as $file) {
            $newPath = '/' . trim(rtrim('/' . $folder->path, '/'), '/') . '/' . $file->name;
            Storage::disk('public')->move($file->path, $newPath);

            $file->url = Str::replaceLast($file->path, $newPath, $file->url);
            $file->full_path = Str::replaceLast($file->path, $newPath, $file->full_path);
            $file->path = Str::replaceLast($file->path, $newPath, $file->path);
            $file->folder_id = $folder->id;

            $file->save();
        }
    }
}

<?php

namespace App\Jobs;

use App\Model\Admin\Page;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RenameFolder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $oldPath;
    private $newPath;
    private $folderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldPath, $newPath, $folderId)
    {
        $this->oldPath = $oldPath;
        $this->newPath = $newPath;
        $this->folderId = $folderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $folder = MediaFolder::find($this->folderId);

        $folder->mediaFiles()->chunk(100, function ($files) {
            foreach ($files as $file) {
                $file->path = Str::replaceLast($this->oldPath, $this->newPath, $file->path);
                $file->url = Str::replaceLast($this->oldPath, $this->newPath, $file->url);
                $file->full_path = Str::replaceLast($this->oldPath, $this->newPath, $file->full_path);
                $file->save();

                foreach ($file->pages()->get() as $page) {
                    if ($page->content = str_replace($this->oldPath, $this->newPath, $page->content, $count) && $count) {
                        $page->save();
                    };
                }
            }
        });

        $folder->path = Str::replaceLast($this->oldPath, $this->newPath, $folder->path);
        $folder->url = Str::replaceLast($this->oldPath, $this->newPath, $folder->url);
        $folder->save();

        Log::info("Move images done.");
    }
}

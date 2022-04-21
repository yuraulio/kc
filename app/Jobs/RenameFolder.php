<?php

namespace App\Jobs;

use App\Model\Admin\Page;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            // rename files and folder
            $folder = MediaFolder::find($this->folderId);
            $folder->mediaFiles()->chunk(100, function ($files) {
                foreach ($files as $file) {
                    $oldUrl = $file->url;

                    $file->path = Str::replaceLast($this->oldPath, $this->newPath, $file->path);
                    $file->url = Str::replaceLast($this->oldPath, $this->newPath, $file->url);
                    $file->full_path = Str::replaceLast($this->oldPath, $this->newPath, $file->full_path);
                    $file->save();

                    $newUrl = $file->url;

                    // update pages content
                    foreach ($file->pages()->get() as $page) {
                        $content = $page->content;

                        $content = str_replace($oldUrl, $newUrl, $content);

                        $page->content = $content;
                        $page->save();
                    }
                }
            });

            $folder->path = Str::replaceLast($this->oldPath, $this->newPath, $folder->path);
            $folder->url = Str::replaceLast($this->oldPath, $this->newPath, $folder->url);
            $folder->save();

            DB::commit();
        } catch (Exception $e) {
            throw $e;
            DB::rollback();
            Log::error("Failed to update files or pages when renaming folder. " . $e->getMessage());
        }
    }
}

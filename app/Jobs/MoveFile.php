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
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\DB;

class MoveFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        Log::info("Move file job - start");
        DB::beginTransaction();
        try {
            $parentFile = MediaFile::find($this->fileId);
            $folder = MediaFolder::find($this->folderId);

            $files = $parentFile->subfiles()->get();
            $files->push($parentFile);

            foreach ($files as $file) {
                Log::info("Move file job - move file in db");
                $newPath = '/' . trim(rtrim('/' . $folder->path, '/'), '/') . '/' . $file->name;

                $oldFileUrl = $file->url;
                $oldFilePath = $file->path;

                $file->url = Str::replaceLast($file->path, $newPath, $file->url);
                $file->full_path = Str::replaceLast($file->path, $newPath, $file->full_path);
                $file->path = Str::replaceLast($file->path, $newPath, $file->path);
                $file->folder_id = $folder->id;

                $file->save();

                $newFileUrl = $file->url;
                $newFilePath = $file->path;

                $pages = $file->pages()->get();

                foreach ($pages as $page) {
                    $content = $page->content;
                    $content = str_replace($oldFileUrl, $newFileUrl, $content);
                    $content = str_replace($oldFilePath, $newFilePath, $content);
                    $page->content = $content;
                    $page->save();
                }

                // move file
                Log::info("Move file job - move file on disk");
                Storage::disk('public')->move($oldFilePath, $newPath);
            }

            Log::info("Move file job - commit");
            DB::commit();
            Log::info("Move file job - success");
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed to move file. " . $e->getMessage());
        }
    }
}

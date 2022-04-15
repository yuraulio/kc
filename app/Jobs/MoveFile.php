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
        DB::beginTransaction();
        try {
            $parentFile = MediaFile::find($this->fileId);
            $folder = MediaFolder::find($this->folderId);

            $files = $parentFile->subfiles()->get();
            $files->push($parentFile);

            foreach ($files as $file) {
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

                Log::debug($file->load("pages"));
                Log::debug($pages);
                foreach ($pages as $page) {
                    $content = $page->content;
                    $content = str_replace($oldFileUrl, $newFileUrl, $content);
                    $content = str_replace($oldFilePath, $newFilePath, $content);
                    $page->content = $content;
                    $page->save();
                }

                // move file
                Storage::disk('public')->move($oldFilePath, $newPath);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
            Log::error("Failed to move file. " . $e->getMessage());
        }
    }
}

<?php

namespace App\Jobs;

use App\Model\Admin\MediaFolder;
use App\Model\Admin\Page;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RenameFolder implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $oldPath;
    private $newPath;
    private $folderId;
    private $renameFolder;
    private $folderName;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldPath, $newPath, $folderId, $renameFolder = false, $folderName = null)
    {
        $this->oldPath = $oldPath;
        $this->newPath = $newPath;
        $this->folderId = $folderId;
        $this->renameFolder = $renameFolder;
        $this->folderName = $folderName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Rename folder job - start');
        DB::beginTransaction();
        try {
            // rename files and folder
            $folder = MediaFolder::find($this->folderId);
            $folder->mediaFiles()->chunk(100, function ($files) {
                foreach ($files as $file) {
                    Log::info('Rename folder job - change db file');
                    $oldUrl = $file->url;

                    $file->path = Str::replaceLast($this->oldPath, $this->newPath, $file->path);
                    $file->url = Str::replaceLast($this->oldPath, $this->newPath, $file->url);
                    $file->full_path = Str::replaceLast($this->oldPath, $this->newPath, $file->full_path);
                    $file->save();

                    $newUrl = $file->url;

                    // update pages content
                    foreach (Page::withoutGlobalScopes()->get() as $page) {
                        $content = $page->content;

                        $content = str_replace($oldUrl, $newUrl, $content);

                        $page->content = $content;
                        $page->save();
                    }
                }
            });

            Log::info('Rename folder job - change db folder');
            $folder->path = Str::replaceLast($this->oldPath, $this->newPath, $folder->path);
            $folder->url = Str::replaceLast($this->oldPath, $this->newPath, $folder->url);
            $folder->save();

            // loop through subdirectories
            foreach ($folder->children()->get() as $subfolder) {
                Log::info('Rename folder job - start new job for subfolder');
                $subfolderName = $subfolder->name;
                $oldPathSubfolder = $this->oldPath . '/' . $subfolderName;
                $newPathSubfolder = $this->newPath . '/' . $subfolderName;
                $subfolderID = $subfolder->id;
                RenameFolder::dispatch($oldPathSubfolder, $newPathSubfolder, $subfolderID, false);
            }

            if ($this->renameFolder) {
                Log::info('Rename folder job - rename folder on disk');
                $oldFullPath = public_path('/uploads/' . $this->oldPath);
                $newFullPath = public_path('/uploads/' . $this->newPath);
                $result = rename($oldFullPath, $newFullPath);
                if ($result) {
                    $folder->name = $this->folderName;
                    $folder->save();

                    Log::info('Rename folder job - commit');
                    DB::commit();
                    Log::info('Rename folder job - success');
                } else {
                    Log::error('Failed to rename folder in RenameFolder job.');
                    DB::rollback();
                }
            } else {
                Log::info('Rename folder job - commit');
                DB::commit();
                Log::info('Rename folder job - success');
            }
        } catch (Exception $e) {
            Log::error('Failed to update files or pages when renaming folder (job). ' . $e->getMessage());
            DB::rollback();
        }
    }
}

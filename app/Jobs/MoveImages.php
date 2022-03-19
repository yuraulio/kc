<?php

namespace App\Jobs;

use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Model\Admin\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MoveImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $old_path;
    private $new_path;
    private $folder_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($old_path, $new_path, $folder_id = null)
    {
        $this->old_path = $old_path;
        $this->new_path = $new_path;
        $this->folder_id = $folder_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = MediaFile::where("path", "LIKE", "%" . $this->old_path . "%")->get();
        Log::info("Files count: " . count($files));
        foreach ($files as $file) {
            $path = $file->path;
            if (strpos($path, $this->old_path) !== false) {
                $path = str_replace($this->old_path, $this->new_path, $path);
                $file->path = $path;
            }
            $url = $file->url;
            if (strpos($url, $this->old_path) !== false) {
                $url = str_replace($this->old_path, $this->new_path, $url);
                $file->url = $url;
            }
            $full_path = $file->full_path;
            if (strpos($full_path, $this->old_path) !== false) {
                $full_path = str_replace($this->old_path, $this->new_path, $full_path);
                $file->full_path = $full_path;
            }
            if ($this->folder_id) {
                $file->folder_id = $this->folder_id;
            }
            $file->save();
            $pages = $file->pages()->get();
            Log::info("Pages count: " . count($pages));
            $updated_pages = [];
            foreach ($pages as $page) {
                if (!in_array($page->id, $updated_pages)) {
                    $content = $page->content;
                    if (strpos($content, $this->old_path) !== false) {
                        $content = str_replace($this->old_path, $this->new_path, $content);
                        $page->content = $content;
                        $page->save();
                        array_push($updated_pages, $page->id);
                    }
                }
            }
        }

        $folders = MediaFolder::where("path", "LIKE", "%" . $this->old_path . "%")->get();
        foreach ($folders as $folder) {
            $path = $folder->path;
            if (strpos($path, $this->old_path) !== false) {
                $path = str_replace($this->old_path, $this->new_path, $path);
                $folder->path = $path;
            }
            $url = $folder->url;
            if (strpos($url, $this->old_path) !== false) {
                $url = str_replace($this->old_path, $this->new_path, $url);
                $folder->url = $url;
            }
            $folder->save();
        }

        Log::info("Move images done.");
    }
}

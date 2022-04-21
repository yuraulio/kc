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

class RenameFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $oldUrl;
    private $newUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldUrl, $newUrl)
    {
        $this->oldUrl = $oldUrl;
        $this->newUrl = $newUrl;
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
            $pages = Page::withoutGlobalScope("publish")->get();

            foreach ($pages as $page) {
                $content = $page->content;

                $content = str_replace($this->oldUrl, $this->newUrl, $content);

                $page->content = $content;
                $page->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed to update pages when renaming file. " . $e->getMessage());
        }
    }
}

<?php

namespace App\Jobs;

use App\Model\Admin\MediaFile;
use App\Model\Admin\MediaFolder;
use App\Model\Admin\Page;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RenameFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $oldUrl;
    private $newUrl;
    private $alttext;
    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldUrl, $newUrl, $alttext, $link)
    {
        $this->oldUrl = $oldUrl;
        $this->newUrl = $newUrl;
        $this->alttext = $alttext;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Rename file job - start');
        DB::beginTransaction();
        try {
            $pages = Page::withoutGlobalScopes()->get();

            foreach ($pages as $page) {
                $content = $page->content;

                // update url
                $content = str_replace($this->oldUrl, $this->newUrl, $content);

                // update alttext
                $rows = json_decode($content);
                foreach ($rows as $row_key => $row) {
                    foreach ($row->columns as $column_key => $column) {
                        foreach ($column->template->inputs as $input_key => $input) {
                            if (isset($input->value->url) && $input->value->url == $this->newUrl) {
                                $rows[$row_key]->columns[$column_key]->template->inputs[$input_key]->value->alt_text = $this->alttext;
                                $rows[$row_key]->columns[$column_key]->template->inputs[$input_key]->value->link = $this->link;
                            }
                            if (isset($input->value) && is_array($input->value)) {
                                foreach ($input->value as $value_key => $galery_value) {
                                    if (isset($galery_value->url) && $galery_value->url == $this->newUrl) {
                                        $rows[$row_key]->columns[$column_key]->template->inputs[$input_key]->value[$value_key]->alt_text = $this->alttext;
                                        $rows[$row_key]->columns[$column_key]->template->inputs[$input_key]->value[$value_key]->link = $this->link;
                                    }
                                }
                            }
                        }
                    }
                }

                $content = json_encode($rows);

                $page->content = $content;
                $page->save();
            }

            Log::info('Rename file job - commit');
            DB::commit();
            Log::info('Rename file job - success');
        } catch (Exception $e) {
            Log::error('Failed to update pages when editing file. ' . $e->getMessage());
            DB::rollback();
        }
    }
}

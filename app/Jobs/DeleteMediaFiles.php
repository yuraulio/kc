<?php

namespace App\Jobs;

use App\Http\Controllers\Admin_api\MediaController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteMediaFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $selected;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($selected)
    {
        $this->selected = $selected;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->selected as $id) {
            (new MediaController)->deleteFile($id);
        }
    }
}

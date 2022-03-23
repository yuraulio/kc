<?php

namespace App\Jobs;

use App\Http\Controllers\Admin_api\CategoriesController;
use App\Model\Admin\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteMultipleCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->ids as $id) {
            $controller = new CategoriesController;
            $controller->destroy($id);
        }
    }
}

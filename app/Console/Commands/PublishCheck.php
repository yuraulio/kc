<?php

namespace App\Console\Commands;

use App\Model\Admin\Page;
use Illuminate\Console\Command;

class PublishCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publishCheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish or unpublish pages as per page dates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // find unpublished pages that should be published and publish them
        Page::withoutGlobalScope('published')->wherePublished(false)->whereDate('published_from', "<=", now())->update(['published' => true]);

        // find published pages that should be unpublished and unpublish them
        Page::withoutGlobalScope('published')->wherePublished(true)->whereDate('published_to', "<", now())->update(['published' => false]);
    }
}

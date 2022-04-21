<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex cms items';

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
        $this->info("Reindexing - start");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\Admin'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\Admin'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\Category'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\Category'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\Template'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\Template'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\Page'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\Page'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\Comment'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\Comment'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\MediaFile'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\MediaFile'");

        Artisan::call("scout:flush 'App\\\Model\\\Admin\\\MediaFolder'");
        Artisan::call("scout:import 'App\\\Model\\\Admin\\\MediaFolder'");

        $this->info("Reindexing - end");
    }
}

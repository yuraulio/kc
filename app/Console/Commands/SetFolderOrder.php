<?php

namespace App\Console\Commands;

use App\Model\Admin\MediaFolder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetFolderOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setFolderOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $rootFolder = MediaFolder::wherepath('/')->first();

        $rootFolder->order = 0;
        $rootFolder->save();

        $this->setOrder($rootFolder);
    }

    private function setOrder($folder)
    {
        $i = 0;
        $children = $folder->children()->get();
        if ($children) {
            foreach ($children as $child) {
                $child->order = $i;
                $child->save();
                $i++;

                $this->setOrder($child);
            }
        }
    }
}

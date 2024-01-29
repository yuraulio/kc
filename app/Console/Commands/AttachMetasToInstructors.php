<?php

namespace App\Console\Commands;

use App\Model\Instructor;
use Illuminate\Console\Command;

class AttachMetasToInstructors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instructor:metas';

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
        $instructors = Instructor::all();

        foreach ($instructors as $instructor) {
            if (!$instructor->metable) {
                $instructor->createMetas();
            }
        }

        return 0;
    }
}

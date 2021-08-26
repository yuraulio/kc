<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Exam;

class InsertExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:exams';

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
        $exams = Exam::all();

        foreach($exams as $exam){   
            $questions = decrypt($exam->questions);
            $questions = json_decode($questions);

            $exam->questions = json_encode($questions);
            $exam->save();
            break;
        }
        return 0;
    }
}

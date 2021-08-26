<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Exam;

class FixExams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:exams';

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
            $questions = [];
            $oldQuestions = json_decode($exam->questions,true);
            
            foreach((array)$oldQuestions as $q){
                $question = strip_tags($q['question']);
                $question = trim(str_replace(":","",$q['question']));
                $answers = [];
                foreach($q["answers"] as $a){
                    $answers[] = strip_tags($a);
                }
                $questions[] = ['question'=>$question,'question-type'=> $q['question-type'],"answer-credit"=>$q["answer-credit"],"answers"=>$answers,
                "correct_answer"=>$q["correct_answer"]];
            }
            //dd($questions);
            $exam->questions = json_encode($questions);
            $exam->save();
            //break;
            
        }
    }
}

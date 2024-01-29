<?php

namespace App\Console\Commands;

use App\Model\Exam;
use App\Model\ExamResult;
use Illuminate\Console\Command;

class FixExamQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:exam-question';

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
        $examResults = ExamResult::where('exam_id', 87)->get();
        foreach ($examResults as $examResult) {
            $answers = json_decode($examResult->answers, true);
            $newAnswers = [];
            $score = 0; //$examResult->score;

            foreach ($answers as $key => $answer) {
                $ga = $answer['correct_answer'][0];
                $ga = preg_replace('~^\s+|\s+$~us', '\1', $ga);

                $ca = $answer['given_answer'];
                $ca = str_replace('&nbsp;', '', $ca);
                $ca = preg_replace('~^\s+|\s+$~us', '\1', $ca);

                if ($ga == $ca) {
                    $score += 1;
                }

                $newAnswers[] = [
                    'question' => $answer['question'],
                    'correct_answer' => [$ga],
                    'given_answer' => $ca,

                ];
            }

            //dd($newAnswers);
            $examResult->score = $score;
            $examResult->answers = json_encode($newAnswers);
            $examResult->save();
        }

        $newQuestions = [];
        $exam = Exam::find(87);
        $questions = json_decode($exam->questions, true);
        //dd($questions);

        foreach ($questions as $key => $question) {
            $newQuestions[] = $question;
            $newAnswers = [];
            $newCorrect = [];
            //dd($newQuestions);
            foreach ($newQuestions[$key]['answers'] as $answer) {
                $ca = $answer;
                $ca = str_replace('&nbsp;', '', $ca);
                $newAnswers[] = preg_replace('~^\s+|\s+$~us', '\1', $ca);
            }
            $newQuestions[$key]['answers'] = $newAnswers;

            $ca = $newQuestions[$key]['correct_answer'][0];
            $ca = preg_replace('~^\s+|\s+$~us', '\1', $ca);
            $newCorrect = [$ca];
            $newQuestions[$key]['correct_answer'] = $newCorrect;
        }

        $exam->questions = $newQuestions;
        $exam->save();

        return 0;
    }
}

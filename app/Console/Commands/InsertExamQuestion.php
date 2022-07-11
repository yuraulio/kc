<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\Exam;

class InsertExamQuestion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam-questions:insert';

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
        $fileName = public_path() . '/exam-questions.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $file = $reader->load($fileName);
        $file = $file->getActiveSheet();

        $file = $file->toArray();

        $questions = [];
        foreach($file as $key =>  $line){

            if($key == 0 || !$line[1]){
                continue;
            }

            $questions[] = ['question' => $line[1], 'answer-credit' => 1, 'answers' => [$line[2],$line[3],$line[4],$line[5]], 'question-type' => "radio buttons", 'correct_answer' => [$line[3]]];
                    
        }
        //dd($questions);
        $exam = Exam::find(87);
        $exam->questions = json_encode($questions);
        $exam->save();

        return 0;
    }
}

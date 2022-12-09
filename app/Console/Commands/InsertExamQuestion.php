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
        //$fileName = public_path() . '/import/Exams3.xlsx';
        //$fileName = public_path() . '/import/EXAMS.xlsx';
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
            
            $qInsert = trim(str_replace(['"',"'"], "", $line[1]));
            $qInsert = preg_replace('~^\s+|\s+$~us', '\1', $qInsert);

            $answer1 = str_replace(['"',"'"], "", $line[2]);
            $answer1 = preg_replace('~^\s+|\s+$~us', '\1', $answer1);

            $answer2 = str_replace(['"',"'"], "", $line[3]);
            $answer2 = preg_replace('~^\s+|\s+$~us', '\1', $answer2);

            $answer3 = str_replace(['"',"'"], "", $line[4]);
            $answer3 = preg_replace('~^\s+|\s+$~us', '\1', $answer3);

            $answer4 = str_replace(['"',"'"], "", $line[5]);
            $answer4 = preg_replace('~^\s+|\s+$~us', '\1', $answer4);

            $questions[] = ['question' => trim($qInsert), 'answer-credit' => 1, 
                            'answers' => [trim($answer1),trim($answer2),trim($answer3),trim($answer4)], 
                            'question-type' => "radio buttons", 
                            'correct_answer' => [trim($answer2)]
                        ];
                    
        }
        
        $exam = Exam::find(98);
        $exam->questions = json_encode($questions);
        $exam->save();

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Model\Exam;
use App\Model\ExamResult;
use App\Model\ExamSyncData;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

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
        //$client = new Client(['base_uri' => 'http://knowcrunchls.j.scaleforce.net','verify' => false]);
        $client = new Client(['base_uri' => 'http://lcknowcrunch.test', 'verify' => false]);

        //$response = $client->request('GET', 'http://knowcrunchls.j.scaleforce.net/get-exams');
        $response = $client->request('GET', 'http://lcknowcrunch.test/get-exams');

        $data = json_decode($response->getBody()->getContents(), true);

        foreach ($data['exams'] as $exam) {
            if (Exam::find($exam[0])) {
                continue;
            }

            $examm = new Exam;

            $examm->id = $exam[0];
            $examm->status = $exam[1];
            $examm->exam_name = $exam[2];
            $examm->duration = $exam[3];
            $examm->indicate_crt_incrt_answers = $exam[4];
            $examm->display_crt_answers = $exam[5];
            $examm->random_questions = $exam[6];
            $examm->random_answers = $exam[7];
            $examm->q_limit = $exam[8];
            $examm->intro_text = $exam[9];
            $examm->end_of_time_text = $exam[10];
            $examm->success_text = $exam[11];
            $examm->failure_text = $exam[12];

            $questions = decrypt($exam[13]);
            $questions = json_decode($questions);
            $examm->questions = json_encode($questions);

            $examm->creator_id = $exam[14];
            $examm->publish_time = $exam[15];
            $examm->examCheckbox = $exam[16];
            $examm->examMethods = $exam[17];
            //$examm->created_at = $exam[18];
            //$examm->updated_at = $exam[19];

            $examm->save();

            foreach ($data['examsEvent'][$examm->id] as $examEvent) {
                $examm->event()->attach($examEvent);
            }
        }

        foreach ($data['examResults'] as $examResult) {
            if (ExamResult::where('user_id', $examResult[0])->where('exam_id', $examResult[1])->first()) {
                continue;
            }

            $examR = new ExamResult;

            $examR->user_id = $examResult[0];
            $examR->exam_id = $examResult[1];
            $examR->first_name = $examResult[2];
            $examR->last_name = $examResult[3];
            $examR->score = $examResult[4];
            $examR->total_score = $examResult[5];
            $examR->answers = $examResult[6];
            $examR->start_time = $examResult[7];
            $examR->end_time = $examResult[8];
            $examR->total_time = $examResult[9];

            $examR->save();
        }

        /*foreach($data['syncsData'] as $key1 => $dataSync){

            foreach($dataSync as $key2 => $examResult){

                if(ExamSyncData::where('user_id',$key2)->where('exam_id',$key1)->first()){
                    continue;
                }


                $exR = json_decode($examResult,true);
                $data = $exR['data'];
                $finish = $exR['finish'];
                $started = $exR['finish'];
                $examR = new ExamSyncData;

                $examR->user_id = $key2;
                $examR->exam_id = $key1;
                $examR->data = json_encode($data);
                $examR->started_at = $started;
                $examR->finish_at = $finish;

                $examR->save();
            }

        }*/

        return 0;
    }
}

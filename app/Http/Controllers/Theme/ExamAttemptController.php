<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Model\Exam;
use App\Model\ExamResult;
use App\Model\ExamSyncData;
use App\Model\User;
use DateTime;
use Mail;
use App\Model\Event;
use \Carbon\Carbon;
use App\Model\Certificate;

class ExamAttemptController extends Controller
{


    public function __construct(){
        //$this->middleware('exam.check');
    }


    public function attemptExam($ex_id) {       

        $exam = Exam::find($ex_id);
        $event = $exam->event->first();
        return view('exams.exam_instructions',['event' => $event, 'exam' => $exam,'event_title' => $event->title,
                        'first_name' => Auth::user()->firstname,'last_name'=>Auth::user()->lastname]);

    }

    public function examStart(Exam $exam) {  

        //$exam = ExamSetting::find($ex_id);
        $event = $exam->event->first();
        $st_id = Auth::user()->id;        
       
        if($exam->random_questions) {

            $ex_scontents = json_decode($exam->questions,true);
            $exam_keys = array_keys($ex_scontents);
            shuffle($exam_keys);
        
        } else {

            $ex_scontents = json_decode($exam->questions,true);
            $exam_keys = array_keys($ex_scontents);

        }

        

        $ex_contents = array();

        //foreach($ex_scontents as $ex_content) {
        foreach($exam_keys as $key => $exam_key) {

            $ex_content = $ex_scontents[$exam_key];
            
            $options = array();
           
            if($ex_content['question-type'] == 1) { //For True False Type

                $unser_data = unserialize($ex_content->answer_keys);

                $opt1 = $unser_data['option_1'];

                $opt2 = $unser_data['option_2'];

                array_push($options, $opt1, $opt2);                

            } elseif($ex_content['question-type'] == 'radio buttons') { //For Multiple Choice Type

                $answer_keys = array_keys($ex_content['answers']);
                
                if($exam->random_answers) {
                    shuffle($answer_keys);
                }

                $opt1 = $ex_content['answers'][$answer_keys[0]];
                $opt2 = $ex_content['answers'][$answer_keys[1]];
                $opt3 = $ex_content['answers'][$answer_keys[2]];
                $opt4 = $ex_content['answers'][$answer_keys[3]];         
                array_push($options, $opt1, $opt2, $opt3, $opt4);


            } elseif($ex_content['question-type'] == 3) { //For Several Answer Type
                //dd($ex_content);
                //$unser_data = unserialize($ex_content->answer_keys);

                $answer_keys = array_keys($ex_content['answers']);
                if($exam->random_answers) {
                    shuffle($answer_keys);
                }

                $opt1 = $ex_content['answers'][$answer_keys[0]];
                $opt2 = $ex_content['answers'][$answer_keys[1]];
                $opt3 = $ex_content['answers'][$answer_keys[2]];
                $opt4 = $ex_content['answers'][$answer_keys[3]];

                array_push($options, $opt1, $opt2, $opt3, $opt4);

            }     
        
            $ex_contents[] = ['id'=> $key,'answers_keys' => $options, 'mark_status' => 0, 'question_title' => $ex_content['question'], 'question_description' => '',
                'question-type' => $ex_content['question-type'],'given_ans'=>'', 'q_id' => $exam_key];

        }
        $redata = 0;

        $getSyncData = ExamSyncData::where(['exam_id' => $exam->id, 'user_id' => $st_id])->value('id');

        if(isset($getSyncData) && !empty($getSyncData)) {

            $exData =  ExamSyncData::where(['exam_id' => $exam->id, 'user_id' => $st_id])->value('data');

            $ex_contents = json_decode($exData,true);

            $redata = ExamSyncData::where(['exam_id' => $exam->id, 'user_id' => $st_id])->value('started_at');

            

            $fndata = ExamSyncData::where(['exam_id' => $exam->id, 'user_id' => $st_id])->value('finish_at');
            $examResultData = ExamResult::where('exam_id',$exam->id)->where('user_id', $st_id)->first();

            if($fndata!='0000-00-00 00:00:00' || $examResultData) {
                return view('exams.exam_end', ['event' => $event,'user_id' => $st_id, 'ex_contents' => $ex_contents, 'exam' => $exam, 'redata' => $redata,
                                'event_title' => $event->title,'first_name' => Auth::user()->firstname,'last_name'=>Auth::user()->lastname]);

            }

        }
        
        return view('exams.exam_start', ['event' => $event,'user_id' => $st_id, 'ex_contents' => $ex_contents, 'exam' => $exam, 'redata' => $redata,
                    'event_title' => $event->title,'first_name' => Auth::user()->firstname,'last_name'=>Auth::user()->lastname]);

    }

    public function syncData(Request $request) {

        if(isset($request->examJson) && isset($request->exam_id) && isset($request->student_id) && isset($request->start_time)){

            $exam_id = $request->exam_id;
            $st_id = $request->student_id;
            $examJson = $request->examJson;  
            $start_time = $request->start_time;

            $getSyncData = ExamSyncData::where(['exam_id' => $exam_id, 'user_id' => $st_id])->value('id');
            

            if(isset($getSyncData) && !empty($getSyncData)) {

                $examSyncData = ExamSyncData::where('id', $getSyncData)

                    ->update([
                        'exam_id' => $exam_id,
                        'user_id' => $st_id,
                        'data' => $examJson,
                        'started_at' => $start_time,
                        'finish_at'  => '0000-00-00 00:00:00',

                        ]);

            } else {

                $examSyncData = ExamSyncData::create([

                    'exam_id' => $exam_id,
                    'user_id' => $st_id,
                    'data' => $examJson,
                    'started_at' => $start_time,
                    'finish_at'  => '0000-00-00 00:00:00',

                ]);

            } 
            return 'success';
        }

        return 'fail';


    }

    public function saveData(Request $request) {

        if(isset($request->examJson) && isset($request->exam_id) && isset($request->student_id) && isset($request->start_time)){ 


            $exam_id = $request->exam_id;
            $st_id = $request->student_id;
            $examJson = $request->examJson;  
            $start_time = $request->start_time;
            $finish = date("Y-m-d H:i:s");

            $datetime1 = new DateTime($start_time);//start time
            $datetime2 = new DateTime($finish);//end time
            $interval = $datetime1->diff($datetime2);

            $total_time = $interval->format('%h:%i:%s');
            $getSyncData = ExamSyncData::where(['exam_id' => $exam_id, 'user_id' => $st_id])->value('id');

            if(isset($getSyncData) && !empty($getSyncData)) {

                $examSyncData = ExamSyncData::where('id', $getSyncData)

                    ->update([
                        'exam_id' => $exam_id,
                        'user_id' => $st_id,
                        'data' => $examJson,
                        'started_at' => $start_time,
                        'finish_at'  => $finish,
                        ]);

            } else {

                $examSyncData = ExamSyncData::create([
                    'exam_id' => $exam_id,
                    'user_id' => $st_id,
                    'data' => $examJson,
                    'started_at' => $start_time,
                    'finish_at'  => $finish,
                ]);

            }

            //Result Calculation and saving to result table

            if($examSyncData) {

                $exam_datas = json_decode($examJson, true);   
                $total_credit = 0;
                $totalCredits = 0;
                $answers = [];
                foreach($exam_datas as $exam_data) {

                    $q_id = $exam_data['q_id'];
                    $ex_id = $exam_id;
                    $q_type_id = $exam_data['question-type'];
                    $given_ans = $exam_data['given_ans'];
                   
                    $credit = 0;


                    $opns_arr = array();

                    if($given_ans!='') {

                    $getDBContents = Exam::find($exam_id)->questions;
                    $getDB = json_decode($getDBContents,true);

                    //foreach($getDBContents as $getDB) {

                        $dbAns = $getDB[$q_id]['correct_answer'];
                        //dd($getDB[$q_id]['correct_answer']);
                        $totalCredits +=  $getDB[$q_id]['answer-credit'];

                        $answers[] = ['question' => $getDB[$q_id]['question'],'correct_answer' => $getDB[$q_id]['correct_answer'],'given_answer' => $given_ans];

                        if($q_type_id == 1) { //For True False Type

                            $Ans = $dbAns;
                            
                            if($given_ans == $Ans) {

                                $credit =  $getDB['answer-credit'];

                            }

                        } else if($q_type_id == 'radio buttons') { //For Multiple Choice

                            //$opSer = unserialize($getDB->answer_keys);
                            
                            $cAns = $dbAns;
                           
                            if(htmlspecialchars_decode($given_ans,ENT_QUOTES) == htmlspecialchars_decode($cAns[0],ENT_QUOTES)) {

                                $credit = $getDB[$q_id]['answer-credit'];

                            }

                        } else if($q_type_id == 3) { //For Several Answer

                            $unSer = unserialize($dbAns);

                            $opSer = unserialize($getDB->answer_keys);

                            if(isset($unSer['sev1']) && ($unSer['sev1']=="on"))

                                array_push($opns_arr, preg_replace('/\s+/', ' ', trim($opSer['option_1'])));

                            if(isset($unSer['sev2']) && ($unSer['sev2']=="on"))

                                array_push($opns_arr, preg_replace('/\s+/', ' ', trim($opSer['option_2'])));

                            if(isset($unSer['sev3']) && ($unSer['sev3']=="on"))

                                array_push($opns_arr, preg_replace('/\s+/', ' ', trim($opSer['option_3'])));

                            if(isset($unSer['sev4']) && ($unSer['sev4']=="on"))

                                array_push($opns_arr, preg_replace('/\s+/', ' ', trim($opSer['option_4'])));

                                

                            $anss = explode("|", $given_ans);

                            if( ( count($opns_arr) == count($anss) ) && !array_diff($opns_arr, $anss) ) {

                                $credit = $getDB->answer_credit;

                            }



                        }

                        $total_credit+= $credit;

                    //}

                    }

                }

                

                //getting user data

                $student = User::find($st_id);

                //$totalQues = count(Examcontent::where('exam_id',$ex_id)->get());
                $totalQues =$totalCredits; //Exam::where('exam_id',$ex_id)->sum('answer_credit');

                $examResultData = ExamResult::where('exam_id',$ex_id)->where('user_id', $st_id)->first();

                if($examResultData){

                    $examResultData->user_id = $st_id;
                    $examResultData->exam_id = $ex_id;
                    $examResultData->first_name = $student->firstname;
                    $examResultData->last_name = $student->lastname;
                    $examResultData->score = $total_credit;
                    $examResultData->answers = json_encode($answers);
                    $examResultData->start_time = $start_time;
                    $examResultData->end_time = $finish;
                    $examResultData->total_time = $total_time;
                    $examResultData->total_score = $totalQues; //$totalCredits,
                
                    $examResultData->save();
                    
                
                }else{

                    $examResultData = ExamResult::create([

                        'user_id' => $st_id,
                        'exam_id' => $ex_id,
                        'first_name' => $student->firstname,
                        'last_name'=>  $student->lastname,
                        'score' => $total_credit,
                        'answers' => json_encode($answers),
                        'start_time' => $start_time,
                        'end_time' => $finish,
                        'total_time' => $total_time,
                        'total_score' => $totalQues, //$totalCredits,
    
                    ]); 
                }

                
                $exam = Exam::find($ex_id);
                $eventType = Event::select('id','view_tpl','certificate_title','title')->where('id',$exam->event->first()->id)->first();

                $successLimit = round(($total_credit*100 / $totalQues), 2);
                $success = false;
                
                if($successLimit >= $exam->q_limit){
                    $success = true;
                }

                if(($eventType->delivery->first() && $eventType->delivery->first()->id == 143) || date('Y') > 2021){
                    if($success){
                        $template ='kc_diploma'; 
                    }else{
                        $template ='kc_attendance';
                    }
                }else{
                    if($success){
                        $template ='kc_deree_diploma'; 
                    }else{
                        $template ='kc_deree_attendance';
                    }
                }


                if( ($cert = $eventType->userHasCertificate($student->id)->first()) ){

                    $cert->success = $success;
                    $cert->certificate_title = $success ? $eventType->certificate_title : $eventType->title;
                    //$createDate = strtotime(date('Y-m-d'));
                    ///$cert->create_date = $createDate;
                    $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
                    //$cert->template = $success ? 'kc_diploma' : 'kc_attendance';
                    $cert->template = $template;
                    $cert->show_certificate = true;
                    $cert->save();

                    $cert->exam()->save($exam);

                }else{

                    $cert = new Certificate;
                    $cert->success = $success;
                    $cert->firstname = $student->firstname;
                    $cert->lastname = $student->lastname;
                    $cert->credential = get_certifation_crendetial();
                    $cert->certificate_title = $success ? $eventType->certificate_title : $eventType->title;
                    $createDate = strtotime(date('Y-m-d'));
                    $cert->create_date = $createDate;
                    $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
                    $cert->certification_date = date('F') . ' ' . date('Y');
                    //$cert->template = $success ? 'kc_diploma' : 'kc_attendance';
                    $cert->template = $template;
                    $cert->show_certificate = true;
                    $cert->save();

                    $cert->event()->save($eventType);
                    $cert->user()->save($student);
                    $cert->exam()->save($exam);

                }

                if($exam->event_id === 1350 || $exam->event_id === 2304)
                    $adminemail = 'info@knowcrunch.com';

                    $muser = [];
                    $muser['email'] = $examResultData->user->email;
                    $muser['fullname'] = $examResultData->user->firstname . ' ' . $examResultData->user->lastname;
                    $muser['first'] = $examResultData->user->firstname;

                    $data['firstName'] = $examResultData->user->firstname;

                    if($exam->event_id === 1350){
                        $view_email = 'emails.student.after_exam_old';
                    }else{
                        $view_email = 'emails.student.after_exam';
                    }

                    $pathFile = url('/') . '/pdf/elearning/KnowCrunch - How to add your certification in Social Media.pdf';
                    $pathFile = str_replace(' ','%20',$pathFile);

                    $sent = Mail::send($view_email, $data,function ($m) use ($adminemail, $muser,$pathFile) {

                        $sub =  'KnowCrunch |' . $muser['first'] . ', Final Evaluation Surveys & how to add your certification in Social Media';
                        $m->from($adminemail, 'Knowcrunch');
                        $m->to($muser['email'], $muser['fullname']);
                        $m->subject($sub);
                        $m->attach($pathFile);

                    });

                }

                
                if($examResultData) {

                    echo '<script>alert("woo!")</script>';

                }

            }

            return 'success';

        }

        return 'fail';

    }


    public function examResults($exam) {

        $nowTime = Carbon::now();

        $user = Auth::user();
        $examResult = ExamResult::where(['exam_id' => $exam, 'user_id' => $user->id])->first();
        $data = $examResult->getResults($user->id);
        $data['first_name'] = $user->firstname;
        $data['last_name'] = $user->lastname;
        $data['image'] = $user->image;
        $data['showAnswers'] = $nowTime->diffInHours($examResult->end_time) < 48;
        
        return view('exams.results',$data);

    }

}

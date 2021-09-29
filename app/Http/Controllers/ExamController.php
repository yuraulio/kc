<?php

namespace App\Http\Controllers;

use App\Model\Exam;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExamRequest;
use App\Model\ExamResult;
use App\Model\ExamSyncData;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $exams = Exam::all();

        
        return view('admin.exams.index', ['exams' => $exams, 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $events = Event::all();
        $exam = new Exam;
        $edit = false;
        $event_edit = false;

        foreach($events as $event){

            if($event->is_elearning_course() || !$event->summary1()->where('section','date')->first()){
                $eventsData[$event->id] = trim($event->title);

            }else{
                $eventsData[$event->id] = trim($event->title . ' ' . $event->summary1()->where('section','date')->first()->title) ;

            }

            
        }

        return view('admin.exams.create', ['user' => $user, 'events' => $eventsData, 'edit' => $edit, 'exam' => $exam,'event_id'=>$event_edit]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request, Exam $model)
    {   
       
        $input =  $request->all();
        $input['publish_time'] = date('Y-m-d H:i',strtotime($request->publish_time));
        $input['status'] = $request->status && $request->status ='on' ? true : false;
        $exam = $model->create($input);
        $exam->event()->attach($request->event_id);

        return redirect('admin/exams/'. $exam->id .'/edit');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        $user = Auth::user();
        $events = Event::all();
        $edit = true;
        $event_edit = $exam->event->first() ? $exam->event->first()->id : -1;

        $results = [];
        $count = 0;
        $averageScore = 0;
        $seconds = 0;
        foreach($exam->results->toArray() as $result){

            $score = $result['score'] . '/' . $result['total_score'];
            $scorePerc =round($result['score'] * 100 / $result['total_score'],2) . '%';

            $duration = explode(":", $result['total_time']);                              
            $seconds += $duration[0]*60*60 + $duration[1]*60 + $duration[2];
            $duration = $duration[0] .' Hrs ' . $duration[1] . ' Min ' . $duration[2] . ' Sec';

           
            $averageScore += $result['score'];

            $results[] = ['first_name' => $result['first_name'], 'last_name' => $result['last_name'], 'score' => $score, 'scorePerc' => $scorePerc,
                        'start_time'=>date("F j, Y | h:i:s a", strtotime($result['start_time'])), 
                        'end_time' => date("F j, Y | h:i:s a", strtotime($result['end_time'])), 'total_time' => $duration,
                        'exam_id' => $exam->id, 'user_id' => $result['user_id']];

            $count++;


        }

        $averageHour = 0 . ' hours '. 0 . ' minutes';         
        if($count > 0){
                     
            $average = $seconds/$count;
            $avg_hr = floor($average/3600);
            $avg_min = floor(($average%3600)/60);
            $avg_sec = ($average%3600)%60;

            $averageHour = $avg_hr . ' hours '. $avg_min . ' minutes';
        }                     
                     
                     
        if($averageScore != 0) {
                 
            $averageScore = ($averageScore/$count);
            $averageScore = $averageScore/$result['total_score']*100;
            $averageScore = number_format((float)$averageScore, 2, '.', '');

        }
              
        $eventsData = [];

        foreach($events as $event){

            if($event->is_elearning_course() || !$event->summary1()->where('section','date')->first()){
                $eventsData[$event->id] = trim($event->title);

            }else{
                $eventsData[$event->id] = trim($event->title . ' ' . $event->summary1()->where('section','date')->first()->title) ;

            }

            
        }
        
        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();

        if(count($results) < count($syncDatas) || count($results) == 0){
            
            $questions = json_decode($exam->questions,true);
            foreach($syncDatas as $syncData){
                //dd($questions);
                
                $answered = 0;
                $allAnswers = json_decode($syncData->data,true);
                $correct = 0;
                foreach($allAnswers as $answer){
                    if(trim($answer['given_ans']) != ''){

                        $answered += 1;
                        $correctAnswer = $questions[$answer['q_id']]['correct_answer'];
                        if(is_array($correctAnswer) &&  
                                htmlspecialchars_decode($answer['given_ans'],ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0],ENT_QUOTES) ){
                                
                                $correct += 1;

                        }elseif(htmlspecialchars_decode($answer['given_ans'],ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0],ENT_QUOTES)){
                            $correct += 1;
                        }
                        
                    }
                    

                }

                $start_at = explode('T', $syncData->started_at);
                $finish_at = explode(' ', $syncData->finish_at);

                $liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;
                
                
            }
        }
        return view('admin.exams.create', ['user' => $user, 'events' => $eventsData, 'edit' => $edit, 'exam' => $exam,'event_id'=>$event_edit,
                    'results' => $results,'averageHour' => $averageHour, 'averageScore' => $averageScore,'liveResults' => $liveResults]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, Exam $exam)
    {
        $input =  $request->all();
        $input['status'] = $request->status && $request->status ='on' ? true : false;
        $input['publish_time'] = date('Y-m-d H:i',strtotime($request->publish_time));
        $exam->update($input);
        $exam->event()->detach();
        $exam->event()->attach($request->event_id);

        return redirect('admin/exams/'. $exam->id .'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }

    public function addQuestion(Request $request, Exam $exam){

       
        $questions = json_decode($exam->questions) ? json_decode($exam->questions,true) : [];

        $questions[] = $request->question;
        $newQ = [];
        foreach($questions as $key1 => $question){

            $question['question'] = trim(str_replace(['"',"'"], "", $question['question']));
            
            foreach($question['answers'] as $key => $answer){
                $question['answers'][$key] = trim(str_replace(['"',"'"], "", $answer));
            }
            
            $newQ[] = $question;
        }

        $exam->questions = json_encode($newQ);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions
        ]);

    }

    public function updateQuestion(Request $request, Exam $exam){

       
       $oldQuestions = json_decode($exam->questions,true);
       //dd($oldQuestion); 
      

       $question = $request->question;
       $question['question'] = trim(str_replace(['"',"'"], "", html_entity_decode($request->question['question'])));

       foreach($request->question['answers'] as $key => $answer){
        $question['answers'][$key] = trim(str_replace(['"',"'"], "", html_entity_decode($answer)));
       }

       $oldQuestions[$request->key] = $question;

       

       $exam->questions = json_encode($oldQuestions);
       $exam->save();

       return response()->json([
        'questions' => $exam->questions
    ]);

    }

    public function orderQuestion(Request $request, Exam $exam){

        $oldQuestions = json_decode($exam->questions,true);
        $questionsNew = $request->questions;

        $sortedQuestions = [];

        ksort($questionsNew);

        

        foreach($questionsNew as $key => $question){
            $sortedQuestions[] = $oldQuestions[$question];
        }
        
        $exam->questions = json_encode($sortedQuestions);
        $exam->save();
    }

    public function cloneExam(Exam $exam){

        $exam = $exam->replicate();
        
        $exam->status = false;
        $exam->push();

        return redirect()->route('exams.edit',$exam->id)->withStatus(__('Exam successfully cloned.'));

    }

    public function getLiveResults(Exam $exam){
        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();
        
        $questions = json_decode($exam->questions,true);
        foreach($syncDatas as $syncData){
            
            $answered = 0;
            $allAnswers = json_decode($syncData->data,true);
            $correct = 0;

            foreach($allAnswers as $answer){

                if(trim($answer['given_ans']) != ''){
                    $answered += 1;
                    $correctAnswer = $questions[$answer['q_id']]['correct_answer'];
                    if(is_array($correctAnswer) &&  
                            htmlspecialchars_decode($answer['given_ans'],ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0],ENT_QUOTES) ){
                            
                            $correct += 1;

                    }elseif(htmlspecialchars_decode($answer['given_ans'],ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0],ENT_QUOTES)){
                        $correct += 1;
                    }
                }

            }

            $start_at = explode('T', $syncData->started_at);
            $finish_at = explode(' ', $syncData->finish_at);

            $liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;
            
        }

        return response()->json([
            'success'=>true,
            'liveResults' => $liveResults
            ]);

    }

}

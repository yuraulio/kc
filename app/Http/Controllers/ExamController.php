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
        $liveResults = [];

        $eventInfo = $event->event_info();
        $date = '';
        
        if(isset($eventInfo['inclass']['dates'])){
            $date = $eventInfo['inclass']['dates'];
        }

        foreach($events as $event){

            $eventsData[$event->id] = trim($event->title . ' ' . $date);

        }

        return view('admin.exams.create', ['user' => $user, 'events' => $eventsData, 'edit' => $edit, 'exam' => $exam,'event_id'=>$event_edit,'liveResults' => $liveResults]);
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

              
        $eventsData = [];

        foreach($events as $event){

            $eventInfo = $event->event_info();
            $date = '';
            
            if(isset($eventInfo['inclass']['dates']['text'])){
                $date = $eventInfo['inclass']['dates']['text'];
                
            }
            
            foreach($events as $event){

                $eventsData[$event->id] = trim($event->title . ' ' . $date);

            }
            
        }
        
        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();

        [$results,$averageHour,$averageScore] = $exam->getResults();


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

                /*$liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;*/

                $liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered, 'correct' => $correct, 'totalAnswers' => count($allAnswers), 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;
                
                
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
            $question['question'] = preg_replace('~^\s+|\s+$~us', '\1', $question['question']);

            foreach($question['answers'] as $key => $answer){
                $answer = str_replace(['"',"'"], "", $answer);
                $question['answers'][$key] = preg_replace('~^\s+|\s+$~us', '\1', $answer);
            }
            
            $newQ[] = $question;
        }

        $exam->questions = json_encode($newQ);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions
        ]);

    }

    public function deleteQuestion(Request $request, Exam $exam){

       
        $questions = json_decode($exam->questions) ? json_decode($exam->questions,true) : [];

        if(isset($questions[$request->question])){
            unset($questions[$request->question]);
        }   
        
        $exam->questions = json_encode($questions);
        $exam->save();

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

            /*$liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;*/

            $liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered, 'correct' => $correct, 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;
            
        }

        [$results,$averageHour,$averageScore] = $exam->getResults();

        return response()->json([
            'success'=>true,
            'liveResults' => $liveResults,
            'results' => $results,
            'averageHour' => $averageHour,
            'averageScore' => $averageScore
            ]);

    }

}

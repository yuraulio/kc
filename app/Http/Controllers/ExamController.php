<?php

namespace App\Http\Controllers;

use App\Model\Exam;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExamRequest;

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

        return view('admin.exams.create', ['user' => $user, 'events' => $events, 'edit' => $edit, 'exam' => $exam,'event_id'=>$event_edit]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request, Exam $model)
    {   
       
        $exam = $model->create($request->all());
        $exam->event()->attach($request->event_id);
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
        $event_edit = $exam->event->first()->id;
        //dd(json_decode($exam->questions,true));
        return view('admin.exams.create', ['user' => $user, 'events' => $events, 'edit' => $edit, 'exam' => $exam,'event_id'=>$event_edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
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

        $exam->questions = json_encode($questions);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions
        ]);

    }

    public function updateQuestion(Request $request, Exam $exam){

       
       $oldQuestions = json_decode($exam->questions,true);
       //dd($oldQuestion); 
       //dd($request->question);
       $oldQuestions[$request->key] = $request->question;

       

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
}

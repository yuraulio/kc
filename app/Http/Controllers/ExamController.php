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

        return view('admin.exams.create', ['user' => $user, 'events' => $events, 'edit' => $edit, 'exam' => $exam]);
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
         
        return view('admin.exams.create', ['user' => $user, 'events' => $events, 'edit' => $edit, 'exam' => $exam]);
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
        //dd($request->question);

        $questions = json_decode($exam->questions) ? json_decode($exam->questions) : [];

        $questions[] = $request->question;

        $exam->questions = json_encode($questions);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions
        ]);

    }

    public function updateQuestion(Request $request, Exam $exam){

        
       

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

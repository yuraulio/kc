<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Exam;
use App\Model\Event;
//use Illuminate\Support\Facades\Auth;
use App\Model\ExamResult;
use App\Model\User;

class ExamResultController extends Controller
{

    public function showResult(ExamResult $examResult, $user = null){
      
        //if(!$user){
        $user = User::find($user);
        //}
        $data = $examResult->getResults($user->id);
        $data['first_name'] = $user->firstname;
        $data['last_name'] = $user->lastname;
        $data['image'] = $user->image;
        $data['showAnswers'] = true;
        return view('exams.results',$data);

    }
}

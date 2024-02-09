<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Model\Event;
use App\Model\Exam;
use App\Model\ExamResult;
//use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function showResult($exam_id, $user = null)
    {
        //if(!$user){
        $user = User::find($user);
        //}
        $examResult = ExamResult::where('exam_id', $exam_id)->where('user_id', $user->id)->first();

        $data = $examResult->getResults($user->id);
        $data['first_name'] = $user->firstname;
        $data['last_name'] = $user->lastname;
        $data['image'] = $user->image;
        $data['showAnswers'] = true;

        return view('exams.results', $data);
    }
}

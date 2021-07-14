<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(){
        //dd('from student controller');
        $data['user'] = Auth::user();

        return view('theme.myaccount.student', $data);
    }
}

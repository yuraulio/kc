<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Lesson;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function index(Category $model)
    {
        $cat = $model->with('topics')->get();

        return view('global_settings.index', ['categories' => $model->with('topics', 'dropbox')->get()]);
    }

    public function course_index()
    {
        return view('global_settings.main');
    }
}

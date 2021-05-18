<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Model\Lesson;

class GlobalController extends Controller
{
    public function index(Category $model)
    {
        $this->authorize('manage-items', User::class);

        $cat = $model->with('topics')->get();
        //dd($model->with('topics', 'lessons')->get());
        return view('global_settings.index', ['categories' => $model->with('topics')->get()]);
    }

    public function course_index()
    {

        //dd('as');
        $this->authorize('manage-items', User::class);

        //$cat = $model->with('topics')->get();
        //dd($model->with('topics', 'lessons')->get());
        return view('global_settings.main');
    }
}

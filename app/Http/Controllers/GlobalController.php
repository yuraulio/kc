<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class GlobalController extends Controller
{
    public function index(Category $model)
    {
        $this->authorize('manage-items', User::class);

        $cat = $model->with('topics')->get();
        //dd($cat);
        return view('global_settings.index', ['categories' => $model->with('topics')->get()]);
    }
}

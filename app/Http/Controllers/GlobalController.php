<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Category;

class GlobalController extends Controller
{
    public function index(Category $model)
    {
        $this->authorize('manage-items', User::class);

        // $cat = $categories->first();
        // dd($cat);
        return view('global_settings.index', ['categories' => $model->get()]);
    }
}

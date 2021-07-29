<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Option;

class OptionsController extends Controller
{
    public function index(){

        $data['options'] = Option::all();

        return view("admin.options.options_list",$data);

    }

    public function edit(Option $option){

        $data['option'] = $option;

        return view("admin.options.create",$data);

    }

    public function update(Request $request, Option $option){

        $option->settings = json_encode($request->settings);
        $option->save();
        return back();
    }
}

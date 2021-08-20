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

        $codes = $option->settings;
        $codes = explode(',', $codes);

        $codes = implode(",\n", $codes);

        $data['codes'] = $codes;

        return view("admin.options.create",$data);
    }

    public function update(Request $request, Option $option){

        //dd(json_decode($request->settings,true));
        $codes = (array) json_decode($request->settings);

        if(empty($codes)){
            $codes = preg_split('/\n|\r\n?/',  $request->settings);
        }

        foreach( $codes as $code){
            //dd($code);
            $codes[] = $code;
        }
        //dd($codes);
        $option->settings = json_encode($codes);
        $option->save();
        return back();
    }

}

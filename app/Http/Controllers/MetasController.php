<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Metas;

class MetasController extends Controller
{
    public function update(Request $request,Metas $metas){
        $metas->meta_title = $request->title;
        $metas->meta_keywords = $request->keywords;
        $metas->meta_description = $request->description;
        $metas->save();

        return back()->withStatus(__('Page successfully updated.'));

    }
}

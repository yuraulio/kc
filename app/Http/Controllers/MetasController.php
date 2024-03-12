<?php

namespace App\Http\Controllers;

use App\Model\Metas;
use Illuminate\Http\Request;

class MetasController extends Controller
{
    public function update(Request $request, Metas $metas)
    {
        $metas->meta_title = $request->title;
        $metas->meta_description = $request->description;
        $metas->save();

        return back()->withStatus(__('Page successfully updated.'));
    }
}

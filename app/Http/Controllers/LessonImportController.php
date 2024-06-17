<?php

namespace App\Http\Controllers;

use App\Imports\LessonsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LessonImportController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Excel::import(new LessonsImport(
            defaultLessonAttributes: [
                'author_id' => $request->user()->id,
                'creator_id' => $request->user()->id,
            ]
        ), $request->file('file'));

        return redirect()->back()->withStatus(__('Lessons imported successfully.'));
    }
}

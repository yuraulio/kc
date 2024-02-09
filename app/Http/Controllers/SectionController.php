<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Model\Event;
use App\Model\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request, Section $section)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $input['section'] = $request->section;
        $input['description'] = $request->description;
        $input['title'] = $request->title;

        $section = $section->create($input);
        $model->sections()->save($section);

        return response()->json([
            'success' => __('Section successfully created.'),
            'section' => $section,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->all());

        return response()->json([
            'success' => __('Section successfully updated.'),
            'section' => $section,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Model\Summary;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummaryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Summary $summary)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $input['title'] = $request->title;

        if($request->description != null){
            $input['description'] = $request->description;
        }

        $input['icon'] = $request->icon;
        $input['section'] = $request->section;

        $summary = $summary->create($input);
        $model->summary1()->save($summary);

        return response()->json([
            'success' => __('Summary successfully created.'),
            'summary' => $summary,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Summary $summary)
    {
        $summary->update($request->all());

        return response()->json([
            'success' => __('Summary successfully updated.'),
            'summary' => $summary,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Summary  $summary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Summary $summary)
    {

    }

    public function orderSummaries(Request $request, Event $event){

        foreach($event->summary1()->get() as $summary){
            //$summary->priority = $request->all()['summaries'][$summary['id']];
            //$summary->save();

        }



    }
}

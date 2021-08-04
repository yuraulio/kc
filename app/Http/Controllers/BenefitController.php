<?php

namespace App\Http\Controllers;

use App\Model\Benefit;
use App\Model\Event;
use App\Model\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BenefitRequest;
use App\Http\Controllers\MediaController;

class BenefitController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BenefitRequest $request,Benefit $benefit)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $input['name'] = $request->name;
        $input['description'] = $request->description;
        $input['priority'] = count($model->benefits) + 1;

        $benefit = $benefit->create($input);
        $benefit->createMedia();
        $model->benefits()->save($benefit);


        return response()->json([
            'success' => __('Benefit successfully created.'),
            'benefit' => $benefit,
        ]);

        ///return redirect()->route('events.index')->withStatus(__('Benefit successfully created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Benefit $benefit)
    {
        //dd($request->all());
        $benefit->update($request->all());

        if($request->svg){
            (new MediaController)->uploadSvg($request, $benefit->medias);
        }

        return response()->json([
            'success' => __('Benefit successfully updated.'),
            'benefit' => $benefit,
        ]);
        //return redirect()->route('events.index')->withStatus(__('Benefit successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Benefit  $benefit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Benefit $benefit)
    {
        //
    }

    public function orderBenefits(Request $request){

        $model = app($request->modelType);
        $model = $model::find($request->id);
        $model->orderBenefits($request->benefits);

    }

}

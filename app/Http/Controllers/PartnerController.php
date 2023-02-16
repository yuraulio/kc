<?php

namespace App\Http\Controllers;

use App\Model\Partner;
use App\Model\Event;
use Illuminate\Http\Request;
use App\Http\Requests\PartnerRequest;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage-users', User::class);

        $data = [];
        $data['partners'] = Partner::with('events')->get();
        $data['user'] = Auth::user();

        return view('partner.index', ['partners' => $data['partners'], 'user' => $data['user']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partner.create');
    }

    public function create_event(Request $request)
    {
        $data['partners'] = Partner::all();
        //$data['event_id'] = $request->event_id;
        $data['event'] = Event::with('partners')->find($request->event_id);
        return view('partner.event.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request, Partner $model)
    {
        $model->create($request->all());
        $model->createMedia();
        return redirect()->route('partner.index')->withStatus(__('Partner successfully created.'));
    }

    public function store_event(Request $request, Partner $model)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        //dd($model);

        //$model->partners()->attach([$request->partner_id]);

        $partner = Partner::find($request->partner_id);

        $model->partners()->sync($request->partner_id);

        return response()->json([
            'success' => __('Partner successfully assigned.'),
            'partner' => $partner,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {

        $media = $partner->mediable;
        if(!$media){
            $media = $partner->createMedia();
        }

        return view('partner.edit', compact('partner','media'));
    }

    public function edit_event(Partner $partner)
    {
        return view('partner.event.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerRequest $request, Partner $partner)
    {
        $partner->update($request->all());

        return redirect()->route('partner.index')->withStatus(__('Partner successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        if (!$partner->events->isEmpty()) {
            return redirect()->route('partner.index')->withErrors(__('This partner has items attached and can\'t be deleted.'));
        }

        $partner->delete();

        return redirect()->route('partner.index')->withStatus(__('Partner successfully deleted.'));
    }

    public function remove_event(Request $request, Partner $model)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $model->partners()->detach([$request->partner_id]);

        return response()->json([
            'success' => __('Partner successfully removed.'),
            'partner_id' => $request->partner_id
        ]);
    }

    public function fetchAllPartners(Request $request, Partner $model)
    {
        $data['partners'] = Partner::all();

        $event = Event::with('partners')->find($request->model_id);
        $data['assignedPartners'] = $event->partners()->get();


        return response()->json([
            'success' => __('Partners successfully fetched.'),
            'data' => $data,
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Model\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DeliveryRequest;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Delivery $model)
    {
        $this->authorize('manage-users', User::class);

        $user = Auth::user();

        $deliveries = $model::all();


        return view('admin.delivery.index', ['user' => $user,'deliveries' => $deliveries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('admin.delivery.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryRequest $request, Delivery $model)
    {
        $model->create($request->all());
        $model->createSlug($request->slug);
        return redirect()->route('delivery.index')->withStatus(__('Delivery successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        return view('admin.delivery.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        $delivery->update($request->all());

        return redirect()->route('delivery.index')->withStatus(__('Delivery successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        dd($delivery->events()->get());
        if (!$delivery->events->isEmpty()) {
            return redirect()->route('delivery.index')->withErrors(__('This type has items attached and can\'t be deleted.'));
        }

        $delivery->delete();

        return redirect()->route('delivery.index')->withStatus(__('Delivery successfully deleted.'));
    }
}

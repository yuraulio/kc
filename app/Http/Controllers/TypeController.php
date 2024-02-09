<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Model\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Type $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        //dd($model->get());

        return view('type.index', ['types' => $model->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('type.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request, Type $model)
    {
        $model->create($request->all());
        $model->createSlug($request->slug);

        return redirect()->route('types.index')->withStatus(__('Type successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        return view('type.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        $type->update($request->all());

        return redirect()->route('types.index')->withStatus(__('Type successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        dd($type);
        if (!$type->events->isEmpty()) {
            return redirect()->route('types.index')->withErrors(__('This type has items attached and can\'t be deleted.'));
        }

        $type->delete();

        return redirect()->route('types.index')->withStatus(__('Type successfully deleted.'));
    }
}

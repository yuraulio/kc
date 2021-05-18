<?php

namespace App\Http\Controllers;

use App\Model\Ticket;
use App\Model\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Ticket $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        //dd($model->get());

        return view('ticket.index', ['tickets' => $model->with('events')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        return view('ticket.create', ['user' => $user, 'events' => Event::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ticket $model)
    {
        $ticket = $model->create($request->all());

        // if($request->event_ids != null){
        //     foreach($request->event_ids as $event){
        //         $event = Event::find($event);



        //         $event->ticket()->attach($event['id']);
        //     }
        // }

        return redirect()->route('ticket.index')->withStatus(__('Ticket successfully created.'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        $id = $ticket['id'];
        $ticket = $ticket->with('events')->find($id);

        $events = Event::all();

        //dd($ticket);

        return view('ticket.edit', compact('ticket', 'events'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //dd($request->all());
        $ticket->update($request->all());
        $ticket->events()->detach();
       // $ticket->events()->sync([$request->event_ids]);
        foreach($request->event_ids as $id){
            $ticket->events()->attach($id);
        }


        return redirect()->route('ticket.index')->withStatus(__('Ticket successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {

        $ticket->events()->detach();

        $ticket->delete();

        return redirect()->route('ticket.index')->withStatus(__('Ticket successfully deleted.'));
    }
}

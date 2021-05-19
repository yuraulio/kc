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

        return view('ticket.main.index', ['tickets' => $model->with('events')->get(), 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        $tickets = Ticket::all();

        return view('ticket.create', ['user' => $user, 'events' => Event::all(), 'event_id' => $request->id, 'tickets'=>$tickets]);
    }

    public function create_main(Request $request)
    {
        //dd('from create');
        $user = Auth::user();

        return view('ticket.main.create', ['user' => $user, 'events' => Event::all(), 'event_id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Ticket $model)
    {
        $ticket = Ticket::find($request->ticket_id);

        if($request->event_id != null)
        {
            $ticket->events()->attach($request->event_id, [
                'priority' => $request->priority,
                'price' => $request->price,
                'options' => $request->option,
                'quantity' => $request->quantity
                ]);

        }

        return redirect()->route('events.index')->withStatus(__('Ticket successfully created.'));

    }

    public function store_main(Request $request, Ticket $model)
    {
        $ticket = $model->create($request->all());

        return redirect()->route('events.index')->withStatus(__('Ticket successfully created.'));

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
    public function edit(Request $request)
    {
        //dd($request->all());
        $event_id = $request->event_id;
        $event = Event::with('ticket')->find($event_id);



        $ticket = Ticket::with('events')->find($request->ticket_id);
        $event = $event->ticket()->wherePivot('ticket_id', $request->ticket_id)->first();

        //dd($event);

        return view('ticket.edit', compact('ticket', 'event'));
    }

    public function edit_main(Ticket $ticket)
    {
        return view('ticket.main.edit', compact('ticket'));
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
        $ticket->update($request->all());

        $event = Event::with('ticket')->find($request->event_id);

        $event->ticket()->wherePivot('ticket_id',$ticket['id'])->updateExistingPivot($ticket['id'],[
            'priority' => $request->priority,
            'price' => $request->price,
            'options' => $request->option,
            'quantity' => $request->quantity
        ], false);

        return redirect()->route('events.index')->withStatus(__('Ticket successfully updated.'));
    }

    public function update_main(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());

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

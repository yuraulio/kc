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
    public function store(Request $request, Ticket $ticket)
    {
        $model = app($request->model_type);
        $model = $model::with('ticket')->find($request->model_id);

        $ticket = Ticket::find($request->ticket_id);

        $features = json_encode($request->features);


        $options['featured'] = ($request->option1 == 1 ? true : false);
        $options['dropdown'] = ($request->option2 == 1 ? true : false);
        $options['alumni'] = ($request->option3 == 1 ? true : false);

        $options = json_encode($options);


        $ticket->events()->attach($request->model_id, [
            'price' => $request->price,
            'quantity' => $request->quantity,
            'features' => $features,
            'options' => $options,
            'priority' => count($model->ticket) + 1
            ]);

            //dd($ticket);
            $ticket->features = $features;
            $ticket->options = $options;
            $ticket->subtitle = $ticket['subtitle'];
            $ticket->quantity = $request->quantity;
            $ticket->price = $request->price;



        return response()->json([
            'success' => __('Ticket successfully assigned.'),
            'ticket' => $ticket,
        ]);

    }

    public function remove_event(Request $request, Ticket $ticket)
    {
        $model = app($request->model_type);
        $model = $model::find($request->model_id);

        $model->ticket()->wherePivot('event_id', '=', $request->model_id)->wherePivot('ticket_id', '=', $request->ticket_id)->detach($request->ticket_id);


        return response()->json([
            'success' => __('Ticket successfully removed.'),
            'ticket_id' => $request->ticket_id
        ]);
    }


    public function store_main(Request $request, Ticket $model)
    {
        $ticket = $model->create($request->all());

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

        //dd($request->all());
        $ticket_id = $ticket['id'];
        $event = Event::find($request->model_id);

        $features = json_encode($request->features);


        $options['featured'] = ($request->option1 == 1 ? true : false);
        $options['dropdown'] = ($request->option2 == 1 ? true : false);
        $options['alumni'] = ($request->option3 == 1 ? true : false);

        $options = json_encode($options);

        //dd($event->ticket()->wherePivot('event_id', '=', $request->model_id)->wherePivot('ticket_id', '=', $ticket_id)->get());

        $event->ticket()->wherePivot('event_id', '=', $request->model_id)->wherePivot('ticket_id', '=', $ticket_id)->updateExistingPivot($ticket_id,[
            'quantity' => $request->quantity,
            'price' => $request->price,
            'options' => $options,
            'features' => $features
        ], false);

        $data['ticket_id'] = $ticket_id;
        $data['quantity'] = $request->quantity;
        $data['price'] = $request->price;
        $data['features'] = $features;
        $data['options'] = $options;

        return response()->json([
            'success' => __('Ticket successfully updated.'),
            'data' => $data,
        ]);

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

    public function fetchAllTickets(Request $request)
    {
        $model = app($request->modelType);
        $model = $model::with('ticket')->find($request->modelId);
        $data['tickets'] = Ticket::all();

        foreach($model->ticket as $key => $ticket){
            foreach($data['tickets'] as $allTicket){
                if($ticket['id'] == $allTicket['id']){
                    unset($data['tickets'][$key]);
                }
            }

        }

        return response()->json([
            'success' => __('Ticket successfully fetched.'),
            'data' => $data,
        ]);
    }

    public function fetchTicketsById(Request $request)
    {
        $data['event'] = Event::with('ticket')->find($request->eventId);
        $data['tickets'] = $data['event']['ticket'];

        return response()->json([
            'success' => __('Ticket by id successfully fetched.'),
            'data' => $data['tickets'],
        ]);

    }

    public function sortTickets(Request $request, Event $event){

        foreach($event->ticket as $ticket){
            $ticket->pivot->priority = $request->all()['tickets'][$ticket['id']];
            $ticket->pivot->save();

        }

    }
}

<?php

namespace App\Http\Middleware;

use App\Model\Event;
use App\Model\EventStudent;
use Auth;
use Closure;

class CheckForEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!isset($request->route()->parameters['course'])) {
            abort(404);
        }

        $slug = $request->route()->parameters['course'];
        $event = Event::where('title', $slug)->first();

        if (!$event) {
            abort(404);
        }

        $eventId = $event->id;
        $user = Auth::user();

        [$user,$instructor] = $user->instructor->first() ? [$user->instructor->first(), true] : [$user, false];
        if ($instructor && $user->event->where('id', $eventId)->first()) {
            return $next($request);
        }

        $user = Auth::user();
        //$event = $user->events->where('id',$eventId)->first();
        $event = $user->events_for_user_list()->wherePivot('event_id', $eventId)->wherePivot('paid', true)->first();

        $eventSub = $user->subscriptionEvents->where('id', $eventId)->last();

        if (!$event && !$eventSub) {
            //return redirect('/myaccount');
            abort(404);
        }

        $event = isset($event) ? $event : $eventSub;

        $today = date('Y/m/d');
        $video_access = false;

        //dd($event->expiration_date);
        if (strtotime($today) <= strtotime($event->pivot->expiration) || !$event->pivot->expiration) {
            $video_access = true;
        }
        if (!$video_access) {
            return redirect('/myaccount');
        }

        return $next($request);
    }
}

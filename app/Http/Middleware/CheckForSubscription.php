<?php

namespace App\Http\Middleware;

use App\Model\Event;
use App\Model\Plan;
use App\Model\User;
use Auth;
use Closure;
use Session;

class CheckForSubscription
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
        $user = Auth::user();
        $dpuser = $user;

        if (!isset($request->route()->parameters['plan']) || !isset($request->route()->parameters['event'])) {
            abort(404);
        }

        $plan = $request->route()->parameters['plan'];
        $plan = Plan::where('name', $plan)->wherePublished(true)->first();
        //$plan = Plan::find($plan);

        $event = $request->route()->parameters['event'];
        $event = Event::where('title', $event)->first();
        //$event = Content::find($event);

        if (!$plan || !$event) {
            abort(404);
        }

        $plans = $plan->events->where('id', $event->id);

        if ($event->plans->count() <= 0 || count($plans) == 0) {
            abort(404);
        }

        if (!$dpuser->checkUserSubscriptionByEventId($event->id)) {
            abort(404);
        }
        //dd($event->plans);

        if (!$dpuser->checkUserPlansById($event->plans, $plan->id)) {
            //dd('3');
            abort(404);
        }
        if ($user->subscriptions()->where('stripe_price', $plan->stripe_plan)->where('stripe_status', 'active')->first()) {
            Session::flash('opmessage', 'You have already been subscribed to this event.');
            Session::flash('opstatus', 1);

            return redirect('/myaccount');
        }

        return $next($request);
    }
}

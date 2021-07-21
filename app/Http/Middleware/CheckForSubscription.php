<?php

namespace PostRider\Http\Middleware;

use Closure;
use PostRider\Content; 
use PostRider\EventStudent;
use PostRider\Plan;
use Sentinel;
use PostRider\User as DPUser;

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
    
        $user = Sentinel::getUser()->id;
        $dpuser = DPUser::find($user);
   
        if(!isset($request->route()->parameters['plan']) || !isset($request->route()->parameters['event'])){
            abort(404);
        }
        
        $plan = $request->route()->parameters['plan'];
        $plan = Plan::where('name',$plan)->first();
        //$plan = Plan::find($plan);

        $event = $request->route()->parameters['event'];
        $event = Content::where('title',$event)->first();
        //$event = Content::find($event);

        if(!$plan || !$event){
            abort(404);
        }
        
        $plans = $plan->events->where('id',$event->id);

        if(!$event->hasPlan() || count($plans) == 0){
            abort(404);
        }

        if(!$dpuser->checkUserSubscriptionByEventId($event->id)){
            abort(404);
        }
        if(!$dpuser->checkUserPlansById($event->plans,$plan->id)){
            abort(404);
        }

        return $next($request);
    }
}

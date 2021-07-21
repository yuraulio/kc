<?php

namespace PostRider\Http\Middleware;

use Closure;
use PostRider\Content; 
use PostRider\EventStudent;
use PostRider\StudentSubscription;
use PostRider\EventLessonInstructor;
use Sentinel;
use PostRider\User as DPUser;

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
        
        if(!isset($request->route()->parameters['course'])){
            abort(404);
        }

        $slug = $request->route()->parameters['course'];
        $event = Content::where('title',$slug)->first();
       
        if(!$event){
            abort(404);
        }

        $eventId = $event->id;
        $user = Sentinel::getUser()->id;
        
        $dpuser = DPUser::find($user);

        $dpuser = $dpuser->instructor ? $dpuser->instructor->id : null;

        if(EventLessonInstructor::where('instructor_id',$dpuser)->where('event_id',$eventId)->first()){

            return $next($request);
        }

        $event = EventStudent::where('student_id',$user)->where('event_id',$eventId)->first();
        $eventSub = StudentSubscription::where('student_id',$user)->where('event_id',$eventId)->first();

      
        if(!$event && !$eventSub){
            //return redirect('/myaccount');
            abort(404);
        }

        $event = isset($event) ? $event : $eventSub;

        $today = date('Y/m/d'); 
        $video_access = false;
        //dd($event->expiration_date);
        if(strtotime($today) <= strtotime($event->expiration_date) || !$event->expiration_date){
            $video_access = true;
        }
        if(!$video_access){
            return redirect('/myaccount');
        }

        return $next($request);
    }
}

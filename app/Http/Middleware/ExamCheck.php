<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\Exam;
use App\Model\User;
use Auth;

class ExamCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(isset($request->route()->parameters['ex_id'])){
            
            if(!$this->checkForExamAccess($request->route()->parameters['ex_id'])){
                abort(404);
            }

        }else if(isset($request->route()->parameters['id'])){
            if(!$this->checkForExamAccess($request->route()->parameters['id'])){
                abort(404);
            }
        }

        else if(isset($request->route()->parameters['exam'])){
            if(!$this->checkForExamAccess($request->route()->parameters['exam'])){
                abort(404);
            }
        }

        return $next($request);
    }

    private function checkForExamAccess($examId){

        $exam = Exam::find($examId);

        if($exam && $exam->event->first()){

            $user = Auth::user();
            $event = $exam->event->first();

            if($event && $event->is_inclass_course()){
                $userEvents = $user->events_for_user_list()->wherePivot('event_id',$event->id)->pluck('event_id')->toArray();
                return in_array($event->id, $userEvents);

            }else if($event && $event->is_elearning_course()){
                return $event->examAccess($user);
            }

            
            

        }

          return true;
      

    }

}

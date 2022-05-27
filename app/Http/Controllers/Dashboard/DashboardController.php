<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Event;

class DashboardController extends Controller
{
    public function searchUser($search_term){

        $searchData = [];
        $users = User::searchUsers($search_term);

        foreach($users as $user){
            $searchData[] = ['name' => $user->firstname . ' ' . $user->lastname, 'link' => '/admin/user/' . $user->id . '/edit', 'email'=>$user->email];
        }

        return response()->json([
            'searchData' => $searchData
        ]);
    }

    public function enrollStudendsToElearning(Event $event,$enroll){

        //dd($enroll);
    
        if($enroll){
            
            $elearningEvent = Event::find(2304);
            
            $students = $event->users()->pluck('user_id')->toArray();
            $existsStudent = $elearningEvent->users()->pluck('user_id')->toArray();
            
            $students = array_diff(  $students, $existsStudent );
            $today = date('Y/m/d');
            $monthsExp2 = '+' . $elearningEvent->expiration .' months';

            foreach($students as $student){
        
                $elearningEvent->users()->attach($student,
                        [
                            'comment'=>'enroll',
                            'expiration'=>date('Y-m-d', strtotime($monthsExp2, strtotime($today))),
                            'paid' => true,
                        ]
                );
                            
        
            }
           
           $event->enroll = true;
           $event->save();

        }else{
            $elearningEvent = Event::find(2304);
            $students = $event->users()->pluck('user_id')->toArray();
            
            $elearningEvent->users()->wherePivotIn('user_id',$students)->wherePivot('comment','enroll')->detach();

            $event->enroll = false;
            $event->save();

        }

        
    }

    public function changeIndex(Event $event,$index){

        $event->index = $index;
        $event->save();

    }


    public function changeFeed(Event $event,$feed){

        $event->feed = $feed;
        $event->save();

    }

}

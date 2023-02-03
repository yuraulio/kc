<?php

namespace App\Exports;

use App\Model\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BigElearningNoSubscriptionExpired implements FromArray,WithHeadings, ShouldAutoSize
{
    public $users;
    public $userss;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function  __construct()
    {
          //$users = User::doesntHave('subscriptionEvents')->whereHas('transactions',function($transaction){
            $users = User::whereHas('transactions',function($transaction){
                $transaction->whereHas('event', function($event){
                    $event->whereHas('category', function($category){
                        return $category->whereIn('categoryables.category_id',[46,277,183]);
                    });
                });

            })
            ->with([
                'events_for_user_list' => function($event){
                    return $event->wherePivot('paid',true)->whereHas('category', function($category){
                        return $category->whereIn('categoryables.category_id',[46,277,183]);
                    });
                },
                'eventSubscriptions'
            ])
            ->get();

            // echo count($users);
            // echo "####";
            $today = strtotime(date('Y-m-d'));

            $this->userss = [];
            $doubleU = [];
            foreach($users as $key => $user){

                $instrunctorId = -1;

                if($user->instructor->first()){
                    $instrunctorId = $user->instructor->first()->id;

                }


                foreach($user->events_for_user_list as $event){

                    $isElearning = $event->is_elearning_course();

                    if(!$event->pivot->paid){
                        //continue;
                    }

                    if(in_array($instrunctorId,$event->instructors()->pluck('instructor_id')->toArray())){
                        unset($users[$key]);
                        unset($this->userss[$user->id]);
                    }

                    if($event->category->first()->id == 46 && $event->status == 0){

                        unset($users[$key]);
                        unset($this->userss[$user->id]);

                    }
                    if((strtotime($event->pivot->expiration) >= $today || !$event->pivot->expiration) && $isElearning){

                        unset($users[$key]);
                        unset($this->userss[$user->id]);

                    }

                    if(!$event->pivot->paid){
                        //unset($users[$key]);
                    }


                    if(isset($users[$key]) && !in_array($user->id,$doubleU)){

                        /*if($user->id == 5726){
                            dd($event);
                        }*/

                        $this->userss[$user->id] = $users[$key];
                        $doubleU[] = $user->id;
                    }
                }

                if(!isset($users[$key])){
                    continue;
                }
                foreach($user->eventSubscriptions as $event){

                    if(!$event->pivot->paid){
                        //continue;
                    }


                    if(strtotime($event->pivot->expiration) >= $today || !$event->pivot->expiration){

                        unset($users[$key]);
                        unset($this->userss[$user->id]);
                    }

                    if(!$event->pivot->paid){
                        //unset($users[$key]);
                    }


                    if(isset($users[$key]) && !in_array($user->id,$doubleU)){



                        $this->userss[$user->id] = $users[$key];
                        $doubleU[] = $user->id;
                    }
                }


            }

            //echo count($this->userss);

    }

    public function array(): array
    {
        $data = array();

        foreach($this->userss as $user){

            $rowdata = array(
                $user['id'],
                $user['firstname'],
                $user['lastname'],
                $user['email'],
                $user['mobile']
            );

            array_push($data, $rowdata);

        }

        return $data;
    }

    public function headings(): array {
        return ["ID", "First Name", "Last Name", "email",'Mobile'];
    }
}

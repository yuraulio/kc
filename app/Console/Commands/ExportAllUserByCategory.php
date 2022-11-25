<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\User;
use App\Model\Event;
use App\Model\Transaction;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportAllUserByCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //$this->complicatedQuery();
        //$this->queryForBigElearningAccess();
        //$this->queryForbUYBigElearningAccess();
        //$this->queryForUsesHasOnlyFreeEvents();
      	//$this->queryForUsesHasSmallElearning();
        $this->queryForInclassElearning();
    }


    private function complicatedQuery(){

        $users = User::doesntHave('subscriptionEvents')->whereHas('transactions',function($transaction){

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
            }
        ])
        ->get();

        echo count($users);
        echo "####";
        $today = strtotime(date('Y-m-d'));
        foreach($users as $key => $user){

            $instrunctorId = -1;

            if($user->instructor->first()){
                $instrunctorId = $user->instructor->first()->id;

            }

            foreach($user->events_for_user_list as $event){

                if(!$event->pivot->paid){
                    continue;
                }

                if(in_array($instrunctorId,$event->instructors()->pluck('instructor_id')->toArray())){
                    unset($users[$key]);
                }

                if($event->category->first()->id == 46 && $event->status == 0){

                    unset($users[$key]);
                    
                    
                }
                if(strtotime($event->pivot->expiration) >= $today || !$event->pivot->expiration){

                    unset($users[$key]);

                }

                if(!$event->pivot->paid){
                    unset($users[$key]);
                }

            
            }


        }

        echo count($users);
        
        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('users_masterclass_big_elearning_no_subsctription_expired_access_completed.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);

    }


    private function queryForBigElearningAccess(){
        $users = 
        
            User::whereHas('events_for_user_list', function($event){
                $event->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[183]);
                });
            })
        
        
        ->orWhereHas('subscriptionEvents')->
        with([
            'events_for_user_list' => function($event){
                return $event->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[183]);
                });
            },

            'subscriptionEvents' => function($event){
                return $event->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[183]);
                });
            }

        ])
        ->get();

        
        $today = strtotime(date('Y-m-d'));
        foreach($users as $key => $user){


            foreach($user->events_for_user_list as $event){

                if(!$event->pivot->paid){
                    continue;
                }
               if(strtotime($event->pivot->expiration) <= $today){

                    unset($users[$key]);

                }

            
            }

            foreach($user->subscriptionEvents as $event){

                if(strtotime($event->pivot->expiration) <= $today){
 
                     unset($users[$key]);
 
                 }
 
             
             }

    
        }

        
        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('big-elearning_access.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);
    }


    private function queryForbUYBigElearningAccess(){
        $users = 
        
            User::whereHas('events_for_user_list', function($event){
                $event->whereHas('category', function($category){
                    $category->whereIn('categoryables.category_id',[183]);
                })
                ->whereHas('ticket',function($ticket){
                    $ticket->where('ticket_id','<>',822);
                })
                ->with([
                    'events_for_user_list' => function($event){
                        return $event->wherePivot('paid',true)->whereHas('category', function($category){
                            $category->whereIn('categoryables.category_id',[183]);
                        })->whereHas('ticket',function($ticket){
                           $ticket->where('ticket_id','<>',822);
                        });
                    },
        
        
                ]);
                
            })->get();


        $today = strtotime(date('Y-m-d'));
        foreach($users as $key => $user){


            foreach($user->events_for_user_list as $event){
    
                if(!$event->pivot->paid){
                    continue;
                }
                if($today >= strtotime($event->pivot->expiration) ){
    
                    unset($users[$key]);
    
                }
    
                
            }
    
        }

        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('big-elearning_access_buy.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);


    }

    private function queryForUsesHasOnlyFreeEvents(){
        $users = User::whereHas('events_for_user_list', function($event){

            return $event->whereHas('event_info1',function($eventInfo){
                return $eventInfo->where('course_payment_method','free');
            });

            

        })
        ->whereDoesntHave('events_for_user_list', function($event){

            return $event->whereHas('event_info1',function($eventInfo){
                return $eventInfo->where('course_payment_method','<>','free');
            });
        })
        ->get();
   

        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('free_event_access_only.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);
    }
  
  	private function queryForUsesHasSmallElearning(){
     
       $users = User::whereHas('events_for_user_list', function($event){

            return $event->whereHas('event_info1',function($eventInfo){
                return $eventInfo->where('course_payment_method','<>','free')->where('course_delivery',143);
            });

            

        })->get();
        
         
        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('small_elearning.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);
         
    }
  
   private function queryForInclassElearning(){

        $users = User::doesntHave('subscriptionEvents')->whereHas('transactions',function($transaction){

            $transaction->whereHas('event', function($event){
                $event->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[46,183]);
                });
            });

        })
          ->whereDoesntHave('events_for_user_list',function($event){
             $event->whereHas('category', function($category){
                    return $category->whereNotIn('categoryables.category_id',[46,183]);
                });
          })
        ->with([
            'events_for_user_list' => function($event){
                return $event->wherePivot('paid',true)->whereHas('category', function($category){
                    return $category->whereIn('categoryables.category_id',[46,183]);
                });
            }
        ])
        ->get();

        echo count($users);
        echo "####";
        $today = strtotime(date('Y-m-d'));
        foreach($users as $key => $user){

            $instrunctorId = -1;

            if($user->instructor->first()){
                $instrunctorId = $user->instructor->first()->id;

            }

            foreach($user->events_for_user_list as $event){

                

                if(in_array($instrunctorId,$event->instructors()->pluck('instructor_id')->toArray())){
                    unset($users[$key]);
                }

               
               

                if(!$event->pivot->paid){
                    unset($users[$key]);
                }

            
            }


        }

        echo count($users);
        
        $columns = array("ID", "First Name", "Last Name", "email",'Mobile');

        $file = fopen('inclass_elearning.csv', 'w');
        fputcsv($file, $columns);

        foreach($users as $user){
            fputcsv($file, array($user->id, $user->firstname,  $user->lastname, $user->email,$user->mobile));
        }

        fclose($file);

    }
    

      
  
}


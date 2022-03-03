<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;
use App\Model\Certificate;

class AttachCerficateByEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:event {event}';

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
        $event = Event::find($this->argument('event'));

        $failedUsers = [1684,1690];

        if(!$event){
            return -1;
        }

        if(!$event->exam->first()){
            return -1;
        }

        $examId = $event->exam->first()->id;
       
        $date = $event->launch_date && $event->launch_date != '1970-01-01' ? date('Y-m-d',strtotime($event->launch_date)) : date('Y-m-d',strtotime($event->published_at));
       
        $users = $event->users;
        
        foreach($users as $user){
            if(! $exam = $user->hasExamResults($examId)){
                continue;
            }

            if($user->instructor->first() && in_array($event->id,$user->instructor->first()->event->pluck('id')->toArray())){
               
                continue;
            }
    
            if(count($event->certificatesByUser($user->id)) > 0){
                continue;
            }

            
            $cert = new Certificate;
            $cert->firstname = $user->firstname;
            $cert->lastname = $user->lastname;
            $cert->certificate_title = $event->certificate_title;
            $cert->show_certificate = true;
            $createDate = strtotime($date);
            $cert->create_date = $createDate;
            $cert->expiration_date = NULL;
            $cert->certification_date = date('F',$createDate) . ' ' . date('Y',$createDate);
            $cert->credential = get_certifation_crendetial2(date('m',$createDate) . date('y',$createDate));

            if(!in_array($user->id,$failedUsers)){
                $cert->template = ($exam->score/$exam->total_score) >= 0.7 ? 'kc_deree_diploma' : 'kc_deree_attendance';
                $cert->success = ($exam->score/$exam->total_score) >= 0.7;
            }else{
                $cert->template = 'kc_deree_attendance';
                $cert->success = false;

            }

            $cert->save();
    
            $cert->event()->save($event);
            $cert->user()->save($user);
            if($event->exam()->first()){
                $cert->exam()->save($event->exam()->first());
            }
    
        
        }

            
        
        
        return 0;
    }
}

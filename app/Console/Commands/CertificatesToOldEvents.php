<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;
use App\Model\Certificate;

class CertificatesToOldEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certs:old-students';

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

        $certifications = [
            '986' => [
                'date' => '2013-06-01'
            ],
            '987' => [
                'date' => '2013-10-01'
            ],
            '989' => [
                'date' => '2014-11-01'
            ],
            '990' => [
                'date' => '2014-11-01'
            ],
            '991' => [
                'date' => '2015-04-01'
            ],
            '992' => [
                'date' => '2015-06-01'
            ],
            '993' => [
                'date' => '2016-04-01'
            ],
            '999' => [
                'date' => '2014-11-01'
            ],
        ];

        $diplomas = [
            '1304' => [
                'date' => '2014-12-01'
            ],
            '1991' => [
                'date' => '2015-04-01'
            ],
            '564' => [
                'date' => '2019-03-01'
            ],
            
        ];

        foreach($certifications as $key => $certification){
            $event = Event::find($key);

            if(!$key){
                continue;
            }

            $users = $event->users;
            $date = $certification['date'];
            foreach($users as $user){

                if($user->instructor->first() && in_array($event->id,$user->instructor->first()->event->pluck('id')->toArray())){
               
                    continue;
                }
        

                if( !($cert = $event->certificatesByUser($user->id)->first()) ){
                    $cert = new Certificate;
                    $cert->firstname = $user->firstname;
                    $cert->lastname = $user->lastname;
                    $cert->certificate_title = $event->certificate_title;
                    $cert->show_certificate = true;
                    $createDate = strtotime($date);
                    $cert->create_date = $createDate;
                    $cert->expiration_date =strtotime(date('Y-m-d', strtotime('+24 months', strtotime($date))));
                    $cert->certification_date = date('F',$createDate) . ' ' . date('Y',$createDate);
                    $cert->credential = get_certifation_crendetial2(date('m',$createDate) . date('y',$createDate));
                    $cert->template = 'new_kc_certificate';

                    $cert->save();
    
                    $cert->event()->save($event);
                    $cert->user()->save($user);
                    if($event->exam()->first()){
                        $cert->exam()->save($event->exam()->first());
                    }
    
                }

            }

        }

        foreach($diplomas as $key => $certification){
            $event = Event::find($key);

            if(!$key){
                continue;
            }

            $users = $event->users;
            $date = $certification['date'];
            foreach($users as $user){

                if($user->instructor->first() && in_array($event->id,$user->instructor->first()->event->pluck('id')->toArray())){
               
                    continue;
                }
        

                if( !($cert = $event->certificatesByUser($user->id)->first()) ){
                    $cert = new Certificate;
                    $cert->firstname = $user->firstname;
                    $cert->lastname = $user->lastname;
                    $cert->certificate_title = $event->certificate_title;
                    $cert->show_certificate = true;
                    $cert->success = true;
                    $createDate = strtotime($date);
                    $cert->create_date = $createDate;
                    $cert->expiration_date =strtotime(date('Y-m-d', strtotime('+24 months', strtotime($date))));
                    $cert->certification_date = date('F',$createDate) . ' ' . date('Y',$createDate);
                    $cert->credential = get_certifation_crendetial2(date('m',$createDate) . date('y',$createDate));
                    $cert->template = 'kc_diploma_2022b';

                    $cert->save();
    
                    $cert->event()->save($event);
                    $cert->user()->save($user);
                    if($event->exam()->first()){
                        $cert->exam()->save($event->exam()->first());
                    }
    
                }

            }

        }

        return 0;
    }
}

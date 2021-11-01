<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Certificate;

class InitCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:init';

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
        $certificates = Certificate::all();
        $months = [];
        $sum = 0;
        foreach($certificates as $certificate){

            if($certificate->credential){
                continue;
            }

            /*if($certificate->event->first()->id == 2304){
                
                $certificate->certificate_title = 'Professional Diploma in Digital & Social Media Marketing';
            
            }else{

                $certificate->certificate_title = 'E-Learning Masterclass in Facebook Marketing';

            }*/

            if($certificate->event->first()->delivery->first() && $certificate->event->first()->delivery->first()->id == 143){
                if($certificate->success){
                    $certificate->template ='kc_diploma'; 
                }else{
                    $certificate->template ='kc_attendance';
                }
            }else{
                if($certificate->success){
                    $certificate->template ='kc_deree_diploma'; 
                }else{
                    $certificate->template ='kc_deree_attendance';
                }
            }

            if(!key_exists(date('Y_m',$certificate->create_date),$months)){
                $months[date('Y_m',$certificate->create_date)] = 1;
            }else{
            
                $months[date('Y_m',$certificate->create_date)] += 1;
            }


            //$certificateNumber =  date('m',$certificate->create_date) . date('y',$certificate->create_date) . str_pad($months[date('Y_m',$certificate->create_date)], 6, '0', STR_PAD_LEFT);
            $certificateNumber = get_certifation_crendetial2(date('m',$certificate->create_date) . date('y',$certificate->create_date));
            $certificate->certificate_title = ($certificate->success && $certificate->event->first()->certificate_title) ? $certificate->event->first()->certificate_title : $certificate->event->first()->title;
            $certificate->firstname = $certificate->user->first()->firstname;
            $certificate->lastname = $certificate->user->first()->lastname;

            $certificate->expiration_date = NULL;
            $certificate->credential = $certificateNumber;
            $certificate->show_certificate = true;

            $certificate->save();
        }
        return 0;
    }
}

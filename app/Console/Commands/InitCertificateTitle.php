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

        foreach($certificates as $certificate){
            if($certificate->event->first()->id == 2304){
                
                $certificate->certificate_title = 'Professional Diploma in Digital & Social Media Marketing';
            
            }else{

                $certificate->certificate_title = 'E-Learning Masterclass in Facebook Marketing';

            }

            $certificate->firstname = $certificate->user->first()->firstname;
            $certificate->lastname = $certificate->user->first()->lastname;

            $certificate->expiration_date = NULL;
            $certificate->credential = 'KC00' . date('y',$certificate->create_date) . decbin($certificate->user->first()->id) . date('m',$certificate->create_date);
            $certificate->save();
        }

        return 0;
    }
}

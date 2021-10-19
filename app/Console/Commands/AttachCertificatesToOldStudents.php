<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Event;
use App\Model\Certificate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DateTime;

class AttachCertificatesToOldStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:certificates';

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

        $nonEvents = [96,1347,4611,4612,4613,4614,2035,4616];

        $fileName =  public_path() . '/certificates_import/Users with kc id and events.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $file = $reader->load($fileName);
        $file = $file->getActiveSheet();

        $file = $file->toArray();
        
        foreach($file as $key =>  $line){
            
            if(in_array($line[0],$nonEvents) || $key == 0){
                continue;
            }
            $event = Event::find($line[0]);
            //dd($line);
            $event->certificate_title = $line[4] ? $line[4] : $event->title;
            $event->save();
            //dd($line[3]);

            if(!$line[3]){
                continue;
            }

            $date = new DateTime('1899-12-31');
            $date->modify("+".$line[3]." day -1 day");

            $date = $date->format('Y-m-d');

            $users = $event->users;
            foreach($users as $user){
          
                if($user->instructor->first()){
                    continue;
                }
        
        
                $cert = new Certificate;
                $cert->success = true;
                $cert->firstname = $user->firstname;
                $cert->lastname = $user->lastname;
                $cert->certificate_title = $event->certificate_title;
                $createDate = strtotime($date);
                $cert->create_date = $createDate;
                $cert->expiration_date = NULL;
                $cert->certification_date = date('F',$createDate) . ' ' . date('Y',$createDate);
                $cert->template = 'kc_deree_diploma';
                $cert->save();
        
            
                $cert->event()->save($event);
                $cert->user()->save($user);
                if($event->exam()->first()){
                    $cert->exam()->save($event->exam()->first());
                }
        
            
            }

            
        }


        return 0;
    }
}

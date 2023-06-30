<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\Event;

class RoyaltiesExport implements FromArray,WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $events;


    public function __construct($instructor){

        $this->instructor = $instructor;

        $this->createDir(storage_path('app/export/royalties'));

    }
     /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {

        $data = array();

        foreach($this->instructor as $key => $inst){

            foreach($inst['events'] as $key1 => $event){

                $rowdata = array(
                    $inst['title'].' '.$inst['subtitle'],
                    $event['title'],
                    '€ '.number_format((float)$event['income'], 2, '.', ''),
                    date("d-m-Y", strtotime($event['created_at']))
                );

                array_push($data, $rowdata);

            }

        }




        return $data;
    }

    public function headings(): array {
        return ['Instructor','Event', 'Income', 'Created At'];
    }

    public function createDir($dir, $permision = 0775, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }


}

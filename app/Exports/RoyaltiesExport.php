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
                    number_format((float)$event['total_event_minutes']/ 3600,2,'.','').' h',
                    number_format((float)$event['total_instructor_minutes'] / 3600,2,'.','').' h',
                    number_format((float)$event['percent'], 2, '.', '').' %'
                );

                array_push($data, $rowdata);

            }

        }




        return $data;
    }

    public function headings(): array {
        return ['Instructor','Event', 'Royalties', 'Total Event Minutes', 'Total Instructor Minutes', 'Percent'];
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

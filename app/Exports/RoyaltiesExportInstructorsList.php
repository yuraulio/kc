<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RoyaltiesExportInstructorsList implements FromArray,WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($instructors){

        $this->instructors = $instructors;

        $this->createDir(storage_path('app/export/royalties'));

    }
     /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {

        $data = array();

        foreach($this->instructors as $key => $inst){



            $rowdata = array(
                $inst['title'].' '.$inst['subtitle'],
                $inst['header'],
                $inst['company'],
                '€ '.number_format((float)$inst['cache_income'], 2, '.', '')
            );

            array_push($data, $rowdata);

        }

        return $data;
    }

    public function headings(): array {
        return ['Instructor','Header', 'Company','Income'];
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

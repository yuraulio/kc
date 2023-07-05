<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RoyaltiesExportInstructorsList implements FromArray,WithHeadings, ShouldAutoSize,WithColumnFormatting
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
                $inst['cache_income']
            );

            array_push($data, $rowdata);

        }

        return $data;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_CURRENCY_EUR,
        ];
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

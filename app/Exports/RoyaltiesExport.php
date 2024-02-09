<?php

namespace App\Exports;

use App\Model\Event;
use App\Model\User;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RoyaltiesExport implements FromArray, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $events;

    public function __construct($instructor)
    {
        $this->instructor = $instructor;

        $this->createDir(storage_path('app/export/royalties'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = [];

        foreach ($this->instructor as $key => $inst) {
            foreach ($inst['events'] as $key1 => $event) {
                $rowdata = [
                    $inst['title'] . ' ' . $inst['subtitle'],
                    $event['title'],
                    $event['income'],
                    ($event['total_event_minutes'] / 3600),
                    ($event['total_instructor_minutes'] / 3600),
                    ($event['percent']),
                ];

                array_push($data, $rowdata);
            }
        }

        return $data;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_CURRENCY_EUR,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function headings(): array
    {
        return ['Instructor', 'Event', 'Royalties', 'Total Event Hours', 'Total Instructor Hours', 'Percent'];
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

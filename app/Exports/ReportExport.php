<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ReportExport implements FromArray
{
    protected $reportRows;

    public function __construct(array $reportRows)
    {
        $this->reportRows = $reportRows;
    }

    public function array(): array
    {
        return $this->reportRows;
    }
}

<?php

namespace App\Exports;

use App\Model\Transaction;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LessonsNoVimeoLinkExport implements FromArray, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $lessons;

    public function __construct($lessons)
    {
        $this->createDir(base_path('public/tmp/exports/'));

        $this->lessons = $lessons;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $data = [];
        foreach ($this->lessons as $lesson) {
            $rowdata = [$lesson->id, $lesson->title, url('/') . '/admin/lessons/' . $lesson->id . '/edit'];
            array_push($data, $rowdata);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Id', 'Name', 'Link',
        ];
    }

    public function createDir($dir, $permision = 0777, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }
}

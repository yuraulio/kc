<?php

namespace App\Exports;

use App\Model\Event;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportStudentResults implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->createDir(base_path('public/tmp/exports/'));
    }

    public function headings(): array
    {
        return [
            'Name',
            'Score',
            'Percentage',
            'Start Time',
            'End Time',
            'Total Time',
        ];
    }

    public function array(): array
    {
        $data = [];

        $exams = $this->event->getExams();

        if (!empty($exams)) {
            $exam = $exams[0];
            $results = $exam->getResults();

            if (!empty($results[0])) {
                foreach ($results[0] as $result) {
                    $data[] = [
                        $result['first_name'] . ' ' . $result['last_name'],
                        $result['score'],
                        $result['scorePerc'],
                        $result['start_time'],
                        $result['end_time'],
                        $result['total_time'],
                    ];
                }
            }
        }

        return $data;
    }

    private function createDir($dir, $permission = 0777, $recursive = true): bool
    {
        return is_dir($dir) || mkdir($dir, $permission, $recursive);
    }
}

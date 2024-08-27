<?php

namespace App\Enums\Student;

enum StudentExportType: string
{
    case LIST = 'student_list';
    case WAITING_LIST = 'student_waiting_list';

    public function getExportFileName(): ?string
    {
        return match ($this) {
            StudentExportType::LIST => 'StudentsListExport.xlsx',
            StudentExportType::WAITING_LIST => 'StudentsWaitingListExport.xlsx',
        };
    }
}

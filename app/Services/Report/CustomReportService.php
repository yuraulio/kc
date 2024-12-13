<?php

namespace App\Services\Report;

use App\Enums\Report\ReportEnum;
use App\Exports\ReportExport;
use App\Model\Report;
use Illuminate\Http\Request;

class CustomReportService
{
    public static function getCustomReport($sql, $report)
    {
        switch ($report->title) {
            case ReportEnum::HIGHEST_SPENDING_STUDENTS:
                $customSql = ' select `users`.id from `users` ';
                $customSql .= " JOIN transactionables ON `transactionables`.transactionable_id = `users`.id AND `transactionables`.transactionable_type LIKE '%User'";
                $customSql .= ' JOIN transactions ON `transactionables`.transaction_id = `transactions`.id ';
                $customSql .= ' WHERE `users`.id IN (' . $sql . ') ';
                $customSql .= ' GROUP BY `users`.id ';
                $customSql .= ' ORDER BY SUM(`transactions`.total_amount) DESC ';
                $customSql .= ' LIMIT 10 ';
                $data = \DB::select($customSql);
                $ids = array_map(function ($item) {
                    return $item->id;
                }, $data);

                return implode(',', $ids);
            default:
                return $sql;
        }
    }
}

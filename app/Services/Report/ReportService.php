<?php

namespace App\Services\Report;

use App\Enums\Report\ReportEnum;
use App\Exports\ReportExport;
use App\Model\Report;
use App\Services\Report\CustomReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class ReportService
{
    // Define properties if needed
    protected $joins;

    // Constructor
    public function __construct()
    {
        $this->joins = [];
    }

    public function createOrUpdate(Request $request)
    {
        return Report::updateOrCreate(
            [
                'id'=>$request->id,
            ],
            [
                'title' => $request->title,
                'file_export_criteria' => $request->file_export_criteria,
                'date_range'=>$request->date_range,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'filter_criteria' => $request->filter_criteria,
            ]
        );
    }

    public function delete(Report $report)
    {
        return $report->delete();
    }

    /**
     * Report query.
     */
    public function exportReportResults(Request $request, Report $report)
    {
        $filterCriteria = $report->filter_criteria;
        $fileExportSettings = $report->file_export_criteria;
        $date_filter = ($report->date_range) ? explode(' - ', $report->date_range) : [];
        $filterCriteriaGroups = [];
        $sql = '';
        foreach ($filterCriteria as $singleCriteria) {
            if (isset($singleCriteria['primary_operator']) && $singleCriteria['primary_operator'] === 'group_and') {
                $sql .= $this->generateQuery($filterCriteriaGroups, $date_filter) . ' INTERSECT ';
                $filterCriteriaGroups = [$singleCriteria];
            } elseif (isset($singleCriteria['primary_operator']) && $singleCriteria['primary_operator'] === 'group_or') {
                $sql .= $this->generateQuery($filterCriteriaGroups, $date_filter) . ' UNION ';
                $filterCriteriaGroups = [$singleCriteria];
            } else {
                $filterCriteriaGroups[] = $singleCriteria;
            }
        }
        $sql .= $this->generateQuery($filterCriteriaGroups, $date_filter);

        return $this->runQueryAndReturnArray($sql, $fileExportSettings, $report);
    }

    private function addJoins($key)
    {
        $key = str_replace(' ', '', ucwords($key));
        switch($key) {
            case 'Tags':
                $this->joins[] = ' JOIN `taggables` ON `taggables`.taggable_id = `users`.id ';
                $this->joins[] = ' JOIN `tags` ON `taggables`.tag_id = `tags`.id ';
                break;
            case 'CourseDelivery':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                $this->joins[] = ' JOIN `events` ON `event_user`.event_id = `events`.id ';
                $this->joins[] = ' JOIN `event_info` ON `event_info`.event_id = `events`.id ';
                $this->joins[] = ' JOIN `deliveries` ON `event_info`.course_delivery = `deliveries`.id ';
                break;
            case 'Courses':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                $this->joins[] = ' JOIN `events` ON `event_user`.event_id = `events`.id ';
                break;
            case 'PaymentStatus':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                break;
            case 'SubscriptionPlans':
                $this->joins[] = ' JOIN `subscriptions` ON `subscriptions`.user_id = `users`.id ';
                break;
            case 'SubscriptionStatus':
                $this->joins[] = ' JOIN `subscriptions` ON `subscriptions`.user_id = `users`.id ';
                break;
            case 'Transaction':
                $this->joins[] = ' JOIN `transactionables` ON `transactionables`.transactionable_id = `users`.id ';
                $this->joins[] = ' JOIN `transactions` ON `transactions`.id = `transactionables`.transaction_id ';
                break;
            case 'UserRole':
                $this->joins[] = ' JOIN `role_users` ON `role_users`.user_id = `users`.id ';
                break;
            case 'CourseDurationStatus':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                $this->joins[] = ' JOIN `events` ON `event_user`.event_id = `events`.id ';
                break;
            case 'ContestStatus':
                $this->joins[] = ' LEFT JOIN `give_aways` ON `users`.email = `give_aways`.email ';
                break;
            case 'UserActivity':
                $this->joins[] = ' JOIN `activations` ON `activations`.user_id = `users`.id ';
                break;
            case 'Coupon':
                $this->joins[] = ' JOIN `transactionables` ON `transactionables`.transactionable_id = `users`.id ';
                $this->joins[] = ' JOIN `transactions` ON `transactions`.id = `transactionables`.transaction_id ';
                break;
        }
    }

    private function generateQuery($filters, $date)
    {
        $sql = 'SELECT DISTINCT ';
        $sql .= '`users`.id';

        $sql .= ' FROM users';

        $where = ' WHERE ';

        // Process filters
        $isFirst = true;

        foreach ($filters as $filter) {
            if (!$isFirst) {
                $where .= ' ' . $filter['primary_operator'] . ' ';
            }
            $isFirst = false;
            $this->addJoins($filter['key']['label']);
            $where .= $this->buildWhereClause($filter);
        }

        // Process date filter
        if (count($date) > 0) {
            $where .= " AND `users`.created_at BETWEEN '" . $date[0] . "' AND '" . $date[1] . "'";
        }
        $sql .= implode(' ', array_unique($this->joins)) . $where;

        return $sql;
    }

    private function buildWhereClause($filter)
    {
        $table = $filter['key']['label'];
        //Course Duration Case
        if (ReportEnum::getFilterKey($table) === 'CourseDurationStatus') {
            foreach ($filter['values'] as $val) {
                if ($val['id'] == 1) {
                    return " ( `event_user`.expiration > now()  OR `event_user`.expiration IS NULL OR `event_user`.expiration = '' ) ";
                } else {
                    return ' `event_user`.expiration < now() ';
                }
            }
        } //Contest Participation Status Case
        if (ReportEnum::getFilterKey($table) === 'ContestStatus') {
            foreach ($filter['values'] as $val) {
                if ($val['id'] == 1) {
                    return ' `give_aways`.email IS NOT NULL ';
                } else {
                    return ' `give_aways`.email IS NULL ';
                }
            }
        } else { //Other Cases
            $operator = $filter['operator'];
            $values = array_map(function ($value) {
                return is_string($value['id']) ? "'" . $value['id'] . "'" : $value['id'];
            }, $filter['values']);

            $operator = ($operator == 'are' ? 'IN' : 'NOT IN');

            $where = ReportEnum::getFilterSQL($table) . ' ' . $operator . ' (';
            $where .= implode(',', $values);
            $where .= ')';

            return $where;
        }
    }

    private function runQueryAndReturnArray($sql, $fileExportSettings, $report)
    {
        $sql = CustomReportService::getCustomReport($sql, $report);
        list($sql, $columnNames) = $this->reportResultColumns($sql, $fileExportSettings);
        $data = \DB::select($sql);

        $csvName = ($fileExportSettings['fileType'] === 'xls') ? "report-{$report->id}.xlsx" : "report-{$report->id}.csv";
        $path = 'reports';
        $exportData[] = $columnNames;
        foreach ($data as $row) {
            $exportData[] = array_values((array) $row);
        }
        Excel::store(new ReportExport($exportData), "{$path}/{$csvName}", 'local');
        $pathURL = Storage::disk('local')->url("app/public/{$path}/{$csvName}");

        return ['data' => env('APP_URL') . $pathURL, 'count' => count($data)];
    }

    private function reportResultColumns($subSQL, $fileExportSettings)
    {
        $hasTransactionData = false;
        $columnNames = array_values(array_filter(array_map(function ($value) use ($fileExportSettings) {
            if (in_array($value['key'], $fileExportSettings['fields'])) {
                return $value['column_name'];
            }
        }, ReportEnum::getExportColumnNames())));

        $friendlyColumnNames = array_values(array_filter(array_map(function ($value) use ($fileExportSettings) {
            if (in_array($value['key'], $fileExportSettings['fields'])) {
                return $value['key'];
            }
        }, ReportEnum::getExportColumnNames())));

        foreach ($columnNames as $value) {
            $hasTransactionData = strpos($value, 'transactions') !== false;
            if ($hasTransactionData) {
                break;
            }
        }
        $sql = 'SELECT ';
        $sql .= implode(' , ', $columnNames);
        $sql .= ' FROM users JOIN role_users ON role_users.user_id = users.id JOIN roles ON role_users.role_id = roles.id ';

        $sql .= ' LEFT JOIN event_user ON event_user.user_id = users.id LEFT JOIN events ON event_user.event_id = events.id ';

        $sql .= ' LEFT JOIN `event_info` ON `event_info`.event_id = `events`.id LEFT JOIN `deliveries` ON `event_info`.course_delivery = `deliveries`.id ';

        $sql .= ' LEFT JOIN (SELECT user_id, stripe_status, stripe_price, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY id DESC) AS rn FROM subscriptions) latest_subscription ON users.id = latest_subscription.user_id AND latest_subscription.rn = 1 ';

        $sql .= ' LEFT JOIN ( ';
        $sql .= ' SELECT ';
        $sql .= '     event_user_ticket.user_id, ';
        $sql .= '     MAX(event_user_ticket.ticket_id) AS event_user_ticket_id ';
        $sql .= ' FROM event_user_ticket ';
        $sql .= ' GROUP BY event_user_ticket.user_id ';
        $sql .= ' ) AS latest_user_event_ticket ON latest_user_event_ticket.user_id = users.id ';
        $sql .= ' LEFT JOIN tickets ON tickets.id = latest_user_event_ticket.event_user_ticket_id ';

        if ($hasTransactionData) {
            $sql .= ' LEFT JOIN ( ';
            $sql .= "     SELECT  transaction_id, transactionable_id FROM transactionables WHERE transactionable_type LIKE '%Event' ";
            $sql .= '     ) as eventt ON ( ';
            $sql .= "         eventt.transactionable_id = events.id AND eventt.transaction_id IN (SELECT  transaction_id FROM transactionables WHERE transactionable_type LIKE '%User' AND transactionable_id = `users`.id)) ";
            $sql .= ' LEFT JOIN transactions ON transactions.id = eventt.transaction_id ';
        }

        $sql .= ' LEFT JOIN plans ON `plans`.stripe_plan = `latest_subscription`.stripe_price ';

        $sql .= ' WHERE `users`.id IN (' . $subSQL . ') ';

        //Limiting the number of rows
        if ($fileExportSettings['dataVisibility'] === 'limited') {
            $sql .= ' LIMIT  ' . ($fileExportSettings['rowLimit'] ? $fileExportSettings['rowLimit'] : '1000');
        }

        return [$sql, $friendlyColumnNames];
    }
}

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
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'file_export_criteria' => $request->file_export_criteria,
                'date_range' => $request->date_range,
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

    private function buildQuery($filterCriteria, $date_filter)
    {
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

        return $sql;
    }

    public function getLiveCount(Request $request, $is_user = false)
    {
        $filterCriteria = $request->filter_criteria;
        $date_filter = null;
        if(isset($request->date_range) && $request->date_range[0]) {
            $date_filter = $request->date_range;
        }
        $sql = $this->buildQuery($filterCriteria, $date_filter);
        $data = \DB::select($sql);
        return ['count' => count($data), 'user_ids' => $is_user ? $data : null];
    }

    /**
     * Report query.
     */
    public function exportReportResults(Request $request, Report $report)
    {
        $filterCriteria = $report->filter_criteria;
        $date_filter = null;
        if(isset($report->date_range) && $report->date_range[0]) {
            $date_filter = $report->date_range;
        }
        $sql = $this->buildQuery($filterCriteria, $date_filter);
        $sql = CustomReportService::getCustomReport($sql, $report);
        return $this->runQueryAndReturnArray($sql, $report);
    }

    private function addJoins($key)
    {
        $key = str_replace(' ', '', ucwords($key));
        switch ($key) {
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
            case 'CourseName':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                $this->joins[] = ' JOIN `events` ON `event_user`.event_id = `events`.id ';
                break;
            case 'CoursePricingStatus':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                $this->joins[] = ' JOIN `events` ON `event_user`.event_id = `events`.id ';
                $this->joins[] = ' JOIN `event_info` ON `event_info`.event_id = `events`.id ';
                break;
            case 'EnrollmentStatus':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                break;
            case 'CoursePaymentStatus':
                $this->joins[] = ' JOIN `event_user` ON `event_user`.user_id = `users`.id ';
                break;
            case 'SubscriptionPlans':
                $this->joins[] = ' JOIN `subscriptions` ON `subscriptions`.user_id = `users`.id ';
                break;
            case 'SubscriptionPaymentStatus':
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
            case 'UserAccountActivity':
                $this->joins[] = ' JOIN `activations` ON `activations`.user_id = `users`.id ';
                break;
            case 'CouponName':
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
        if ($date && count($date) > 0) {
            $where .= " AND `users`.created_at BETWEEN '" . $date[0] . "' AND '" . $date[1] . "'";
        }
        $sql .= implode(' ', array_unique($this->joins)) . $where;

        return $sql;
    }

    private function buildWhereClause($filter)
    {
        $table = $filter['key']['label'];
        $filterKey = ReportEnum::getFilterKey($table);
        $operator = $filter['operator'];
        $values = $filter['values'];

        switch ($filterKey) {
            case 'CourseDurationStatus':
                foreach ($values as $val) {
                    if (($val['id'] == 1 && $operator === 'are') || ($val['id'] != 1 && $operator === 'not_are')) {
                        return " ( `event_user`.expiration > now()  OR `event_user`.expiration IS NULL OR `event_user`.expiration = '' ) ";
                    } else {
                        return ' `event_user`.expiration < now() ';
                    }
                }
                break;

            case 'EnrollmentStatus':
                $where = '';
                foreach ($values as $val) {
                    if ($val['id'] === '2') {
                        $where .= $operator === 'are' ? ' `users`.id IN (SELECT `user_id` from cart_caches) ' : ' `users`.id NOT IN (SELECT `user_id` from cart_caches) ';
                    } else {
                        $selectedValue = ($operator === 'are') ? (bool) $val['id'] : !(bool) $val['id'];
                        if ($selectedValue) {
                            $where .= " ( `event_user`.paid = 1 AND `event_user`.comment != 'free' 
                                    AND (`event_user`.expiration = '' || `event_user`.expiration > now())
                            ) ";
                        } else {
                            $where .= " `event_user`.comment = 'free' 
                            AND (`event_user`.expiration = '' || `event_user`.expiration > now())
                            ";
                        }
                    }
                    if (next($values)) {
                        $where .= ' OR ';
                    }
                }

                return $where;
            case 'ContestStatus':
                foreach ($values as $val) {
                    if (($val['id'] == 1 && $operator === 'are') || ($val['id'] != 1 && $operator === 'not_are')) {
                        return ' `give_aways`.email IS NOT NULL ';
                    } else {
                        return ' `give_aways`.email IS NULL ';
                    }
                }
                break;

            default:
                $operator = ($operator == 'are' ? 'IN' : 'NOT IN');
                $valueList = implode(',', array_map(function ($value) {
                    return is_string($value['id']) ? "'" . $value['id'] . "'" : $value['id'];
                }, $values));

                return ReportEnum::getFilterSQL($table) . ' ' . $operator . ' (' . $valueList . ')';
        }
    }

    private function runQueryAndReturnArray($sql, $report)
    {
        $fileExportSettings = $report->file_export_criteria;
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
        
        $sql .= ' LEFT JOIN subscription_user_event ON subscription_user_event.user_id = users.id AND subscription_user_event.event_id = events.id LEFT JOIN (SELECT user_id, id AS subscription_id, stripe_status, stripe_price, ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY id DESC) AS rn FROM subscriptions) latest_subscription ON subscription_user_event.subscription_id = latest_subscription.subscription_id AND latest_subscription.rn = 1 ';
        if (strpos(implode(', ', $columnNames), 'giveaway_status') !== false) {
            $sql .= ' LEFT JOIN `give_aways` ON `users`.email = `give_aways`.email ';
        }
        $sql .= ' LEFT JOIN ( ';
        $sql .= ' SELECT ';
        $sql .= '     event_user_ticket.user_id, ';
        $sql .= '     MAX(event_user_ticket.ticket_id) AS event_user_ticket_id ';
        $sql .= ' FROM event_user_ticket ';
        $sql .= ' GROUP BY event_user_ticket.user_id ';
        $sql .= ' ) AS latest_user_event_ticket ON latest_user_event_ticket.user_id = users.id ';
        $sql .= ' LEFT JOIN tickets ON tickets.id = latest_user_event_ticket.event_user_ticket_id ';

        if ($hasTransactionData) { 
            $sql = "WITH latest_event_transactions AS (SELECT t.transactionable_id AS event_id, t2.transactionable_id AS user_id, MAX(t.transaction_id) AS latest_transaction_id FROM transactionables t JOIN transactionables t2 ON t.transaction_id = t2.transaction_id WHERE t.transactionable_type LIKE '%Event' AND t2.transactionable_type LIKE '%User' GROUP BY t.transactionable_id, t2.transactionable_id)" . $sql;
            $sql .= " LEFT JOIN     latest_event_transactions let ON let.user_id = users.id AND let.event_id = events.id ";
            $sql .= " LEFT JOIN     transactions ON transactions.id = let.latest_transaction_id ";
        }

        $sql .= ' LEFT JOIN plans ON `plans`.stripe_plan = `latest_subscription`.stripe_price ';

        $sql .= ' WHERE `users`.id IN (' . $subSQL . ') ';

        //Limiting the number of rows
        if ($fileExportSettings['dataVisibility'] === 'limited') {
            $sql .= ' LIMIT  ' . ($fileExportSettings['rowLimit'] ? $fileExportSettings['rowLimit'] : '1000');
        }

        return [$sql, $friendlyColumnNames];
    }

    public function getReportWithData($report) {
        if(in_array($report->title, ReportEnum::getCustomReportTitles())) {
            $report->is_custom_report = true;
        }
        return $report;
    }
}

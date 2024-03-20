<?php

namespace App\Http\Controllers\Api\v1\Transactions\Participants;

use App\DataTables\Transactions\TransactionRegistrationsDataTable;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Model\Event;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Laravel\Cashier\Subscription as Sub;

class StatisticsController extends ApiBaseController
{
    const DELIVERY_VIDEO_TRAINING_ID = 143;

    protected $authorize = [User::class, Transaction::class];

    public function __invoke(Request $request)
    {
        if ($this->authorize) {
            $this->authorize('view', $this->authorize);
        }

        $data = $request->has('filter') ? $this->stats($request) : Cache::remember('transactioosParticipantsStatistics', 10, function () use ($request) {
            return $this->stats($request);
        });

        return $this->response($data);
    }

    protected function stats(Request $request)
    {
        $total = $this->getBaseQuery($request)->count(DB::raw('DISTINCT users.id'));
        $elearning = $this
            ->getBaseQuery($request)
            ->select([
                DB::raw('COUNT(users.id) as total_users'),
                DB::raw('SUM(transactions.amount) as total_amount'),
            ])
            ->join('event_delivery', 'events.id', '=', 'event_delivery.event_id')
            ->where('event_delivery.delivery_id', self::DELIVERY_VIDEO_TRAINING_ID)
            ->whereIn('transactions.id', $this->getRegistrationTransactionQuery())
            ->first();
        $inClass = $this
            ->getBaseQuery($request)
            ->select([
                DB::raw('COUNT(users.id) as total_users'),
                DB::raw('SUM(transactions.amount) as total_amount'),
            ])
            ->join('event_delivery', 'events.id', '=', 'event_delivery.event_id')
            ->where('event_delivery.delivery_id', '<>', self::DELIVERY_VIDEO_TRAINING_ID)
            ->whereIn('transactions.id', $this->getRegistrationTransactionQuery())
            ->first();

        $byType = $this
            ->getBaseQuery($request)
            ->select([
                DB::raw('DISTINCT users.id'),
                DB::raw('transactions.type as type'),
                DB::raw('SUM(transactions.amount) as total_amount'),
            ])
            ->groupBy('transactions.type')
            ->when($request->input('type') === 'revenues', function (Builder $query) {
                $query
                    ->select([
                        DB::raw('users.id'),
                        DB::raw('transactions.type as type'),
                        DB::raw('SUM(CASE WHEN invoices.amount IS NOT NULL THEN invoices.amount ELSE transactions.amount END ) as total_amount'),
                    ])
                    ->leftJoin('invoiceables', function ($query) {
                        $query
                            ->whereColumn('invoiceables.invoiceable_id', '=', 'transactions.id')
                            ->where('invoiceables.invoiceable_type', '=', (new Transaction())->getMorphClass());
                    })
                    ->leftJoin('invoices', function ($query) {
                        $query
                            ->whereColumn('invoiceables.invoice_id', '=', 'invoices.id');
                    });
            })
            ->get()
            ->reduce(function ($a, $item) {
                $k = str_replace(' ', '_', strtolower(trim($item->type)));
                if ($k === 'special_tickets') {
                    $k = 'special';
                } elseif ($k === 'early_birds') {
                    $k = 'early_bird';
                }
                if (!isset($a[$k])) {
                    $a[$k] = 0;
                }
                $a[$k] += $item->total_amount;

                return $a;
            }, []);

        $byType['total'] = $inClass->total_amount + $elearning->total_amount;

        $incomeAccurate = [
            'total' => 0,
            'in_class' => 0,
            'elearning' => 0,
        ];
        if ($request->input('type') === 'revenues') {
            $elearningAccurate = $this
                ->getBaseQuery($request)
                ->select([
                    DB::raw('SUM(transactions.amount) as total_amount'),
                ])
                ->join('event_delivery', 'events.id', '=', 'event_delivery.event_id')
                ->where('event_delivery.delivery_id', self::DELIVERY_VIDEO_TRAINING_ID)
                ->first();
            $inClassAccurate = $this
                ->getBaseQuery($request)
                ->select([
                    DB::raw('SUM(transactions.amount) as total_amount'),
                ])
                ->join('event_delivery', 'events.id', '=', 'event_delivery.event_id')
                ->where('event_delivery.delivery_id', '<>', self::DELIVERY_VIDEO_TRAINING_ID)
                ->first();
            $incomeAccurate = [
                'total' => $inClassAccurate->total_amount + $elearningAccurate->total_amount,
                'in_class' => 0 + $inClassAccurate->total_amount,
                'elearning' => 0 + $elearningAccurate->total_amount,
            ];
            $byType['total'] = $incomeAccurate['total'];
        }

        return [
            'users' => [
                'total' => $total,
                'in_class' => $inClass->total_users,
                'elearning' => $elearning->total_users,
            ],
            'income' => [
                'total' => $inClass->total_amount + $elearning->total_amount,
                'in_class' => 0 + $inClass->total_amount,
                'elearning' => 0 + $elearning->total_amount,
            ],
            'tickets' => $byType,
            'income_accurate' => $incomeAccurate,
        ];
    }

    protected function getRegistrationTransactionQuery()
    {
        return Transaction::select(DB::raw('min(transactions.id)'))
            ->join('transactionables as transactionables_users', function ($query) {
                $query
                    ->whereColumn('transactionables_users.transaction_id', '=', 'transactions.id')
                    ->where('transactionables_users.transactionable_type', '=', (new User())->getMorphClass());
            })
            ->groupBy('transactionables_users.transactionable_id');
    }

    protected function getBaseQuery($request)
    {
        $query = Transaction::query()
            ->join('transactionables', function ($query) {
                $query
                    ->whereColumn('transactionables.transaction_id', '=', 'transactions.id')
                    ->where('transactionables.transactionable_type', '=', (new Event())->getMorphClass());
            })
            ->join('transactionables as transactionables_users', function ($query) {
                $query
                    ->whereColumn('transactionables_users.transaction_id', '=', 'transactions.id')
                    ->where('transactionables_users.transactionable_type', '=', (new User())->getMorphClass());
            })
            ->join('events', function ($query) {
                $query
                    ->whereColumn('transactionables.transactionable_id', '=', 'events.id')
                    ->where('transactionables.transactionable_type', '=', (new Event())->getMorphClass());
            })
            ->join('users', function ($query) {
                $query
                    ->whereColumn('transactionables_users.transactionable_id', '=', 'users.id')
                    ->where('transactionables_users.transactionable_type', '=', (new User())->getMorphClass());
            })
            ->where('transactions.status', 1)
            ->whereNotExists(
                DB::table('transactionables', 'transactionables_subs')
                    ->select('transactionables_subs.transaction_id')
                    ->whereColumn('transactionables_subs.transaction_id', '=', 'transactions.id')
                    ->where('transactionables_subs.transactionable_type', '=', (new Sub())->getMorphClass())
            );

        if ($request->has('filter')) {
            $query = (new TransactionRegistrationsDataTable)->applyFilters($query, $request);
        }

        return $query;
    }
}

<?php

namespace App\DataTables\Transactions;

use App\DataTables\AppDataTable;
use App\DataTables\Extensions\AppEloquentDataTable;
use App\Helpers\EventHelper;
use App\Model\Category;
use App\Model\City;
use App\Model\Event;
use App\Model\EventStatistic;
use App\Model\EventUser;
use App\Model\PaymentMethod;
use App\Model\Transaction;
use App\Model\Transactionable;
use App\Model\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;

class TransactionRevenuesDataTable extends TransactionRegistrationsDataTable
{
    protected $filters = [];

    protected $tableId = 'transaction-revenues';

    protected $searching = true;

    protected $lengthChange = true;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = AppEloquentDataTable::create($query);
        $dataTable->filter(function ($query) {
            return $this->applyFilters($query, $this->request());
        });

        $dataTable->setBeforeProcessResult(function (Collection $data) {
            $paymentMethods = PaymentMethod::pluck('method_name', 'id');
            $data->transform(function ($item) use ($paymentMethods) {
                $userEvent = EventUser::where('user_id', $item['user_id'])->where('event_id', $item['event_id'])->first();

                $item->expiration = $userEvent ? $userEvent->expiration : null;
                $item->payment_method = $userEvent && isset($paymentMethods[$userEvent->payment_method]) ? $paymentMethods[$userEvent->payment_method] : 'Alpha Bank';
                $item->payment_amount = $item->amoount;
                if ($item->invoice_amount) {
                    $totalUsers = Transactionable::where('transaction_id', $item->id)
                        ->where('transactionable_type', (new User())->getMorphClass())
                        ->count();
                    if ($totalUsers > 0) {
                        $item->payment_amount = $item->invoice_amount / $totalUsers;
                    }
                }

                return $item;
            });

            return $data;
        });

        return $dataTable
            ->editColumn('user_id', '<a href="{{ route(\'user.edit\', $user_id) }}">{{$user_name}}</a>')
            ->editColumn('type', '{{ \App\Services\Transactions\TransactionParticipantsService::getValidType($type, $amount, $coupon_code) }}')
            ->editColumn('event_name', '{{$event_name}} / {{ FormatHelper::dateYmd($event_published_at) }}')
            ->editColumn('amount', '€ {{ number_format($payment_amount, 2, ".", "") }}')
            ->editColumn('coupon_code', '{{ empty($coupon_code) ? "-" : $coupon_code }}')
            ->editColumn('created_at', '{{ FormatHelper::dateYmd($created_at) }}')
            ->editColumn('expiration', '{{ FormatHelper::dateYmd($expiration) }}')
            ->setRowId('row-{!! $id !!}')
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model)
    {
        return parent::query($model)
            ->select([
                DB::Raw('transactions.*'),
                DB::Raw('events.id as event_id'),
                DB::Raw('events.title as event_name'),
                DB::Raw('events.published_at as event_published_at'),
                DB::Raw('users.id as user_id'),
                DB::Raw('CONCAT(users.firstname, " ", users.lastname) as user_name'),
                DB::Raw('expiration'),
                DB::Raw('invoices.amount as invoice_amount'),
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
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('user_id')->title(trans('transaction_participants.form.user')),
            Column::make('event_name')->title(trans('transaction_participants.form.event')),
            Column::make('type')->title(trans('transaction_participants.form.type')),
            Column::make('amount')->title(trans('transaction_participants.form.amount')),
            Column::make('coupon_code')->title(trans('transaction_participants.form.coupon_code'))->orderable(false),
            Column::make('created_at')->title(trans('transaction_participants.form.created_at')),
            Column::make('expiration')->title(trans('transaction_participants.form.expiration'))->orderable(false),
            Column::make('payment_method')->title(trans('transaction_participants.form.payment_method'))->orderable(false),
        ];
    }
}

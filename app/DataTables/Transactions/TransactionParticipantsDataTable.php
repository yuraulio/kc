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
use App\Model\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Column;

class TransactionParticipantsDataTable extends AppDataTable
{
    protected $filters = [];

    protected $tableId = 'transaction-participants';

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
            $cities = [];
            $categories = [];
            $data->transform(function ($item) use ($paymentMethods, $cities, $categories) {
                $statistic = EventStatistic::where('user_id', $item['user_id'])->where('event_id', $item['event_id'])->first();
                $userEvent = EventUser::where('user_id', $item['user_id'])->where('event_id', $item['event_id'])->first();

                if (!isset($categories[$item['event_id']])) {
                    $categories[$item['event_id']] = Category::whereHas('events', function ($query) use ($item) {
                        $query->where('events.id', $item['event_id']);
                    })->first();
                }

                $category = $categories[$item['event_id']];

                if (!isset($cities[$item['event_id']])) {
                    $cities[$item['event_id']] = City::whereHas('event', function ($query) use ($item) {
                        $query->where('events.id', $item['event_id']);
                    })->first();
                }
                $city = $cities[$item['event_id']];

                $item->videos_seen = $statistic ? EventHelper::getVideosSeen(json_decode($statistic->videos, true)) : 0;
                $item->expiration = $userEvent ? $userEvent->expiration : null;
                $item->payment_method = $userEvent && isset($paymentMethods[$userEvent->payment_method]) ? $paymentMethods[$userEvent->payment_method] : 'Alpha Bank';
                $item->category = $category ? $category->name : null;
                $item->city = $city ? $city->name : null;

                return $item;
            });

            return $data;
        });

        return $dataTable
            ->editColumn('user_id', '<a href="{{ route(\'user.edit\', $user_id) }}">{{$user_name}}</a>')
            ->editColumn('type', '{{ \App\Services\Transactions\TransactionParticipantsService::getValidType($type, $amount, $coupon_code) }}')
            ->editColumn('event_name', '{{$event_name}} / {{ FormatHelper::dateYmd($event_published_at) }}')
            ->editColumn('amount', '€ {{ number_format($amount, 2, ".", "") }}')
            ->editColumn('coupon_code', '{{ empty($coupon_code) ? "-" : $coupon_code }}')
            ->editColumn('created_at', '{{ FormatHelper::dateYmd($created_at) }}')
            ->editColumn('expiration', '{{ FormatHelper::dateYmd($expiration) }}')
            ->setRowId('row-{!! $id !!}')
            ->escapeColumns([]);
    }

    public function applyFilters($query, $request)
    {
        $query->when($request->input('search.value'), function ($query, $value) {
            $query->where(function ($q) use ($value) {
                $q->where('events.title', 'like', '%' . escapeLike($value) . '%')
                    ->orWhere(
                        DB::Raw('CONCAT(users.firstname, " ", users.lastname)'),
                        'like',
                        '%' . escapeLike($value) . '%'
                    );
            });
        });
        $query->when($request->input('filter.coupons'), function ($query, $value) {
            $query->whereIn('transactions.coupon_code', is_array($value) ? $value : [$value]);
        });
        $query->when($request->input('filter.event'), function ($query, $value) {
            $query->where('events.id', $value);
        });
        $query->when($request->input('filter.pricing'), function ($query, $value) {
            if ($value === 'free') {
                $query->where('transactions.amount', 0);

                return;
            }
            $query->where('transactions.amount', '>', 0);
        });
        $query->when($request->input('filter.payment_method'), function ($query, $value) {
            $query->whereExists(function ($query) use ($value) {
                $query->select(DB::raw(1))
                    ->from((new EventUser)->getTable() . ' as eu')
                    ->whereRaw(DB::raw('eu.user_id = users.id'))
                    ->whereRaw(DB::raw('eu.event_id = events.id'))
                    ->where('eu.payment_method', $value);
            });
        });

        $query->when($request->input('filter.delivery'), function ($query, $value) {
            $query->whereIn('events.id', function ($query) use ($value) {
                $query
                    ->select('event_id')
                    ->from('event_delivery')
                    ->where('delivery_id', $value);
            });
        });

        $query->when($request->input('filter.city'), function ($query, $value) {
            $query->whereIn('events.id', function ($query) use ($value) {
                $query
                    ->select('event_id')
                    ->from('event_city')
                    ->where('city_id', $value);
            });
        });

        $query->when($request->input('filter.category'), function ($query, $value) {
            $query->whereIn('events.id', function ($query) use ($value) {
                $query
                    ->select('categoryable_id')
                    ->from('categoryables')
                    ->where('categoryable_type', (new Event())->getMorphClass())
                    ->where('category_id', $value);
            });
        });

        $query->when($request->input('filter.daterange'), function ($query, $value) {
            list($from, $to) = explode(' - ', $value);
            if (!$from || !$to) {
                return;
            }
            $query->whereBetween('transactions.created_at', [Carbon::parse($from), Carbon::parse($to)]);
        });

        return $query;
    }

    /**
     * Get query source of dataTable.
     *
     * @param Transaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model)
    {
        return $model
            ->newQuery()
            ->select([
                DB::Raw('transactions.*'),
                DB::Raw('events.id as event_id'),
                DB::Raw('events.title as event_name'),
                DB::Raw('events.published_at as event_published_at'),
                DB::Raw('users.id as user_id'),
                DB::Raw('CONCAT(users.firstname, " ", users.lastname) as user_name'),
                DB::Raw('expiration'),
            ])
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
            ->where('transactions.status', 1);
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
            Column::make('amount')->title(trans('transaction_participants.form.ticket_price')),
            Column::make('coupon_code')->title(trans('transaction_participants.form.coupon_code'))->orderable(false),
            Column::make('videos_seen')->title(trans('transaction_participants.form.videos_seen'))->orderable(false),
            Column::make('created_at')->title(trans('transaction_participants.form.registered_at')),
            Column::make('expiration')->title(trans('transaction_participants.form.expiration'))->orderable(false),
            Column::make('payment_method')->title(trans('transaction_participants.form.payment_method'))->orderable(false),
        ];
    }

    public function html()
    {
        $html = parent::html();
        $html->orderBy(6, 'desc');

        return $html;
    }

    protected function buttons()
    {
        return [
            'dom' => [
                'container' => [
                    'className' => 'dt-buttons',
                ],
                'button' => [
                    'className' => 'btn btn-icon btn-primary',
                ],
            ],

            'buttons' => [
                [
                    'text' => '<span class="btn-inner--icon"><i class="ni ni-folder-17"></i></span>',
                    'className' => 'js-invoice-button',
                ],
                [
                    'text' => '<span class="btn-inner--icon"><i class="ni ni-cloud-download-95"></i></span>',
                    'className' => 'js-excel-button',
                ],
            ],
        ];
    }
}

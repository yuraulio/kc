<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionRevenuesDataTable;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\User;

class TransactionRevenuesReportController extends Controller
{
    protected $datatable = TransactionRevenuesDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.revenues_datatable',
    ];
    protected $authorize = [User::class, Transaction::class];

    public function __invoke()
    {
        if ($this->authorize) {
            $this->authorize('view', $this->authorize);
        }

        $datatable = app($this->datatable);

        $indexView = $this->views['index'];

        return $datatable->render($indexView);
    }
}

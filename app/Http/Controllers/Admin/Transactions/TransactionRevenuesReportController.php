<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionRevenuesDataTable;
use App\Http\Controllers\Admin\BaseGridController;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\User;

class TransactionRevenuesReportController extends BaseGridController
{
    protected $datatable = TransactionRevenuesDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.revenues_datatable',
    ];
    protected $authorize = [User::class, Transaction::class];
}

<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionRegistrationsDataTable;
use App\Http\Controllers\Admin\BaseGridController;
use App\Model\Transaction;
use App\Model\User;

class TransactionRegistrationsReportController extends BaseGridController
{
    protected $datatable = TransactionRegistrationsDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.registrations_datatable',
    ];
    protected $authorize = [User::class, Transaction::class];
}

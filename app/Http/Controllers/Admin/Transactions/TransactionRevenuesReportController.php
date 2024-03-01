<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionRevenuesDataTable;
use App\Http\Controllers\Admin\BaseGridController;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class TransactionRevenuesReportController extends BaseGridController
{
    protected $datatable = TransactionRevenuesDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.revenues_datatable',
    ];
    protected $authorize = [User::class, Transaction::class];

    public function __invoke()
    {
        $isPartner = Auth::user()->role()->where('roles.id', 9)->exists();
        if ($isPartner) {
            abort(405);
        }

        return parent::__invoke();
    }
}

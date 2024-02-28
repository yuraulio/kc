<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionRegistrationsDataTable;
use App\Http\Controllers\Admin\BaseGridController;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class TransactionRegistrationsReportController extends BaseGridController
{
    protected $datatable = TransactionRegistrationsDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.registrations_datatable',
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

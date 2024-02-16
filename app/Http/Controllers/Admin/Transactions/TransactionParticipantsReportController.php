<?php

namespace App\Http\Controllers\Admin\Transactions;

use App\DataTables\Transactions\TransactionParticipantsDataTable;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\User;
use Illuminate\Support\Facades\View;

class TransactionParticipantsReportController extends Controller
{
    protected $datatable = TransactionParticipantsDataTable::class;
    protected $views = [
        'index' => 'admin.transaction.participants_datatable',
    ];
    protected $authorize = [User::class, Transaction::class];

    public function __invoke()
    {
        if ($this->authorize) {
            $this->authorize('view', $this->authorize);
        }

        $datatable = app($this->datatable);

        $indexView = $this->views['index'];

        View::share('total_users', 0);
        View::share('usersInClassAll', 0);
        View::share('usersElearningAll', 0);

        return $datatable->render($indexView);
    }
}

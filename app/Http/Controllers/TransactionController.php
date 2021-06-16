<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaction;

class TransactionController extends Controller
{
    public function participants()
    {
        $data['transactions'] = Transaction::with('user', 'event')->orderBy('created_at', 'DESC')->has('event')->get();
        //dd($data['transactions']);

        return view('admin.transaction.participants', $data);
    }
}

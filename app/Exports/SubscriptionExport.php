<?php

namespace App\Exports;

use App\Model\Event;
use App\Model\Transaction;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionExport implements FromArray, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $fromDate;
    public $toDate;
    public $transaction;

    public function __construct($request)
    {
        $this->createDir(base_path('public/tmp/exports/'));
        $this->fromDate = date('Y-m-d', strtotime($request->fromDate));

        $this->toDate = $request->toDate ? date('Y-m-d', strtotime($request->toDate)) : date('Y-m-d');
        $this->toDate = date('Y-m-d', strtotime($this->toDate . ' +1 day'));

        if ($request->transaction) {
            $this->transaction = $request->transaction;
        } else {
            $this->transaction = Transactions::all()->pluck('id')->toArray();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $subscriptions = [];

        $transactions = Transaction::whereBetween('created_at', [$this->fromDate, $this->toDate])->
                                with('user.events', 'subscription.event')->get();

        $userRole = Auth::user()->role->pluck('id')->toArray();
        $data = [];
        foreach ($transactions as $key => $sub) {
            if (count($sub['subscription']) != 0 && in_array($sub['id'], $this->transaction)) {
                if (!isset($sub['subscription'][0]['event'][0]['title'])) {
                    continue;
                }
                $status = $sub['subscription'][0]['stripe_status'];

                if ($sub['trial'] && $status == 'trialing') {
                    $status = 'trialing';
                } elseif ($status == 'active' && $sub['subscription'][0]['status'] && !$sub['trial']) {
                    $status = 'active';
                } elseif (($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && !$sub['trial']) {
                    $status = 'paid_and_cancelled';
                } elseif (($status == 'cancelled' || $status == 'cancel' || $status == 'canceled') && $sub['trial']) {
                    $status = 'cancelled';
                }

                $name = $sub['user'][0]['firstname'];
                $surname = $sub['user'][0]['lastname'];
                $email = $sub['user'][0]['email'];
                $mobile = $sub['user'][0]['mobile'];
                $amount = '€' . number_format(intval($sub['total_amount']), 2, '.', '');

                $rowdata = [$name, $surname, $sub['subscription'][0]['event'][0]['title'], $email, $mobile, $status, $sub['ends_at'], $amount];
                array_push($data, $rowdata);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Name', 'Surname', 'Event', 'Email', 'Mobile', 'Status', 'Sub End At', 'Amount',
        ];
    }

    public function createDir($dir, $permision = 0777, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }
}

<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/
namespace App\Http\Controllers;
use App\Model\Transaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transactions = (new TransactionController)->participants()['transactions'];

        $current_year = strtotime("now");
        $current_year = date('Y',$current_year);

        $revenueByYear = [];

        foreach($transactions as $key => $item){
            $time = strtotime($item['date']);
            $year = date('Y',$time);
            if($year == $current_year){
                $revenueByYear[$key] = $item;
                $revenueByYear[$key]['month'] = $year = date('m',$time);
            }
        }

        $data['revenueByYear'] = group_by('month', $revenueByYear);

        //dd($data['transactions']);

        return view('pages.dashboard', $data);
    }
}

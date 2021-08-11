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
        //dd($transactions);

        $current_year = strtotime("now");
        $current_year = date('Y',$current_year);

        $revenueByYear = [];
        $elearningByYear = [];
        $alumniByYear = [];
        $ticketName = [];

        foreach($transactions as $key => $item){
            $time = strtotime($item['date']);
            $year = date('Y',$time);
            if($year == $current_year){
                $revenueByYear[$key] = $item;
                $revenueByYear[$key]['month'] = date('m',$time);
                if($item['is_elearning']){
                    $elearningByYear[$key] = $item;
                    $elearningByYear[$key]['month'] = date('m',$time);
                }
                if($item['type'] == 'Alumni'){
                    $alumniByYear[$key] = $item;
                    $alumniByYear[$key]['month'] = date('m',$time);
                }
                array_push($ticketName,[
                    'name' => $item['ticketName'],
                    'amount'=> $item['amount']
                ]);
            }

        }





        //$data['elearningByYear']
        $data['revenueByYear'] = group_by('month', $revenueByYear);
        $data['revenueByEvent'] = group_by('event_id', $revenueByYear);
        //dd($data['revenueByEvent']);



        $data['elearningByYear'] = group_by('month', $elearningByYear);
        $data['elearningByEvent'] = group_by('event_id', $revenueByYear);

        $data['alumniByYear'] = group_by('month', $alumniByYear);
        $data['alumniByEvent'] = group_by('event_id', $alumniByYear);

        $data['ticketName'] = $ticketName;
        //dd($ticketNam);


        $data['booking'] = (new SubscriptionController)->subs_for_dashboard();


        //$data['e_learning'] = group_by()

        //dd($data['transactions']);

        return view('pages.dashboard', $data);
    }
}

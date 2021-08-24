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
use Illuminate\Http\Request;
use Auth;

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

        $data['revenueByYear'] = group_by('month', $revenueByYear);
        $data['revenueByEvent'] = group_by('event_id', $revenueByYear);
        //dd($data['revenueByEvent']);



        $data['elearningByYear'] = group_by('month', $elearningByYear);
        //dd($data['elearningByYear']);
        $data['elearningByEvent'] = group_by('event_id', $elearningByYear);
        //dd($elearningByYear);

        $data['alumniByYear'] = group_by('month', $alumniByYear);
        $data['alumniByEvent'] = group_by('event_id', $alumniByYear);

        $data['ticketName'] = $ticketName;


        $data['booking'] = $transactions;// (new SubscriptionController)->subs_for_dashboard();
        return view('pages.dashboard', $data);
    }

    public function fetchByDate(Request $request)
    {
        $transactions = (new TransactionController)->participants_for_select_date($request->start,$request->end)['transactions'];


        $revenueByDate = [];
        $elearningByDate = [];
        $alumniByDate = [];
        $ticketName = [];

        foreach($transactions as $key => $item){
            $time = strtotime($item['date']);
            $revenueByDate[$key] = $item;
            $revenueByDate[$key]['month'] = date('m',$time);
            if($item['is_elearning']){
                $elearningByDate[$key] = $item;
                $elearningByDate[$key]['month'] = date('m',$time);
            }
            if($item['type'] == 'Alumni'){
                $alumniByDate[$key] = $item;
                $alumniByDate[$key]['month'] = date('m',$time);
            }
            array_push($ticketName,[
                'name' => $item['ticketName'],
                'amount'=> $item['amount'],
                'event_id' => $item['event_id']
            ]);
        }

        $data['revenueByDate'] = group_by('month', $revenueByDate);
        $data['revenueByEventDate'] = group_by('event_id', $revenueByDate);
        //dd($data['revenueByEvent']);



        $data['elearningByDate'] = group_by('month', $elearningByDate);
        //dd($data['elearningByDate']);
        $data['elearningByEventDate'] = group_by('event_id', $elearningByDate);
        //dd($elearningByDate);

        $data['alumniByDate'] = group_by('month', $alumniByDate);
        $data['alumniByEventDate'] = group_by('event_id', $alumniByDate);

        $data['ticketNameDate'] = $ticketName;

        return response()->json([
            'success' => __('Already fetched chart data.'),
            'data' => $data,
        ]);
    }


}

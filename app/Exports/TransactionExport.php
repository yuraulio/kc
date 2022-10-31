<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use App\Model\Transaction;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Model\Event;

class TransactionExport implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $fromDate;
    public $toDate;
    public $event;
    public function __construct($request){

        $this->createDir(base_path('public/tmp/exports/'));
        $this->fromDate = date('Y-m-d',strtotime($request->fromDate));

        $this->toDate = $request->toDate ? date('Y-m-d',strtotime($request->toDate)) : date('Y-m-d');
        $this->toDate = date('Y-m-d', strtotime($this->toDate . ' +1 day'));

        if($request->event){
            $this->event = is_array($request->event) ? $request->event : [$request->event];
        }else{
            $this->event = Event::all()->pluck('id')->toArray();
        }

    }
     /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {

        $transactions = Transaction::whereBetween('created_at', [$this->fromDate,$this->toDate])->
                                with('user.events','user.ticket','subscription','event','event.delivery','event.category')->get();
        $userRole = Auth::user()->role->pluck('id')->toArray();
        $data = array();
        foreach($transactions as $transaction){

            if(!$transaction->subscription->first() && $transaction->user->first() && $transaction->event->first() && in_array($transaction->event->first()->id,$this->event)/*&& $transaction->event->first()->id == $this->event*/){

                $category =  $transaction->event->first()->category->first() ? $transaction->event->first()->category->first()->id : -1;

                if(in_array(9,$userRole) &&  ($category !== 46)){
                    continue;
                }

                $tickets = $transaction->user->first()['ticket']->groupBy('event_id');
                $ticketType = isset($tickets[$transaction->event->first()->id]) ? $tickets[$transaction->event->first()->id]->first()->type : '-';

                $statusHistory = $transaction->status_history;
                $billingDetails = json_decode($transaction->billing_details,true);
                $invoice = 'NO';
                $companyName = '';
                $companyProfession = '';
                $companyafm = '';
                $companydoy= '';
                $companyaddress= '';
                $companyaddressnum= '';
                $companypostcode= '';
                $companycity = '';
                $companyemail= '';
                $city = '';
                $bankDetails = '';

                $event = $transaction->event->first()->title;
                //$name = $transaction->user->first()->firstname;
                //$last = $transaction->user->first()->lastname;
                //$email = $transaction->user->first()->email;
                //$mobile = $transaction->user->first()->mobile;
                //$jobTitle = isset($statusHistory[0]['pay_seats_data']['jobtitles'][0]) ? $statusHistory[0]['pay_seats_data']['jobtitles'][0] : '' ;
                //$company = isset($statusHistory[0]['pay_seats_data']['companies'][0]) ? $statusHistory[0]['pay_seats_data']['companies'][0] : '' ;
                $amount = round(($transaction->amount/count($transaction->user)),2);
                //$kcId =  $transaction->user->first()->kc_id;
                //$partnerId = $transaction->user->first()->partner_id;
                //$ticketType = $transaction->type;
                $seats = isset($statusHistory[0]['pay_seats_data']['name']) ? count(isset($statusHistory[0]['pay_seats_data']['companies'])) : 1;
                $datePlaced = date('d-m-Y',strtotime($transaction->placement_date));

                $bankDetails = '-';

                if(isset($statusHistory[0]['cardtype'])){

                    if($statusHistory[0]['cardtype'] == 2 &&  (isset($statusHistory[0]['installments']))){
                        $bankDetails = 'Credit Card ' . $statusHistory[0]['installments'] . ' installments';
                    }

                    elseif($statusHistory[0]['cardtype'] == 4){
                        $bankDetails =   'Cash';
                    }

                    elseif($statusHistory[0]['cardtype'] == 3){
                        $bankDetails ='Bank Transfer';
                    }

                    else{
                        $bankDetails = 'Debit Card';
                    }
                }

                //$paymentResponse = json_decode($transaction->payment_response,TRUE);
                /*if($paymentResponse){

                    if(isset($paymentResponse['payMethod'])){
                        $bankDetails .= $paymentResponse['payMethod'];
                    }

                    if(isset($paymentResponse['paymentRef'])){
                        $bankDetails .= ' ' . $paymentResponse['paymentRef'];
                    }

                    $bankDetails = trim($bankDetails);
                }*/

                $status = 'CANCELLED / REFUSED';
                if($transaction->status == 1){
                    $status = 'APPROVED';
                }elseif($transaction->status == 2){
                    $status = 'ABANDONDED';
                }


                if( isset($billingDetails['billing']) && $billingDetails['billing'] == 2 ){

                    if(!isset($billingDetails['companyname'])){
                        $billingDetails = json_decode($transaction->user->first()->invoice_details,true);
                    }


                    $invoice = 'YES';
                    $companyName = isset($billingDetails['companyname']) ? $billingDetails['companyname'] : '';
                    $companyProfession = isset($billingDetails['companyprofession']) ? $billingDetails['companyprofession'] : '';
                    $companyafm = isset($billingDetails['companyafm']) ? $billingDetails['companyafm'] : '';
                    $companydoy= isset($billingDetails['companydoy']) ? $billingDetails['companydoy'] : '';
                    $companyaddress= isset($billingDetails['companyaddress']) ? $billingDetails['companyaddress'] : '';
                    $companyaddressnum= isset($billingDetails['companyaddressnum']) ? $billingDetails['companyaddressnum'] : '';
                    $companypostcode=  isset($billingDetails['companypostcode']) ? $billingDetails['companypostcode'] : '';
                    $companycity =isset($billingDetails['companycity']) ? $billingDetails['companycity'] : '';
                    $email= isset($billingDetails['companyemail']) ? $billingDetails['companyemail'] : $email;

                }else if( isset($billingDetails['billing']) && $billingDetails['billing'] == 1 ){

                    if(!isset($billingDetails['billcity'])){
                        $billingDetails = json_decode($transaction->user->first()->receipt_details,true);
                    }

                    $city = isset($billingDetails['billcity']) ? $billingDetails['billcity'] : '';
                    $companyafm = isset($billingDetails['billafm']) ? $billingDetails['billafm'] : '';
                    $companypostcode = isset($billingDetails['billpostcode']) ? $billingDetails['billpostcode'] : '';
                    $companyaddress= isset($billingDetails['billaddress']) ? $billingDetails['billaddress'] : '';
                    $companyaddressnum= isset($billingDetails['billaddressnum']) ? $billingDetails['billaddressnum'] : '';
                }

                foreach( $transaction->user as $keyU => $user){

                    $name = $user->firstname;
                    $last = $user->lastname;
                    $email = $user->email;
                    $mobile = '+'.strval($user->country_code).strval($user->mobile);
                    //$mobile = $user->mobile;
                    $jobTitle = isset($statusHistory[0]['pay_seats_data']['jobtitles'][$keyU]) ? $statusHistory[0]['pay_seats_data']['jobtitles'][$keyU] : '' ;
                    $company = isset($statusHistory[0]['pay_seats_data']['companies'][$keyU]) ? $statusHistory[0]['pay_seats_data']['companies'][$keyU] : '' ;
                    $kcId =  $user->kc_id;
                    $partnerId = $user->partner_id;

                    $rowdata = array($event, $name, $last, $email, $mobile, $jobTitle,$companyName,$companyProfession,$companyafm,$companydoy,$companyaddress.' '.$companyaddressnum,
                    $companypostcode,$companycity,$city, $company, $kcId, $partnerId, $amount, $invoice, $ticketType, $seats, $datePlaced,
                        $status, $bankDetails);


                    array_push($data, $rowdata);
                }




            }
        }
        return $data;
    }

    public function headings(): array {
        return [
            'Event', 'Name', 'Surname', 'Email', 'Mobile', 'Job Title','Επωνυμία/Company','Δραστηριότητα','ΑΦΜ','ΔΟΥ','Διεύθυνση/Address','Τ.Κ./PostCode',
            'Πόλη/City','Πόλη','Company', 'Knowcrunch Id', 'DereeId', 'Amount', 'Invoice', 'Ticket Type', '# of Seats', 'Date Placed', 'Status', 'Bank Details'
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

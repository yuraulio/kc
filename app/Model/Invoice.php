<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\Transaction;
use App\Model\Event;
use App\Model\PaymentMethod;
use Laravel\Cashier\Subscription;
use PDF;
use App\Model\Option;

class Invoice extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->morphedByMany(User::class, 'invoiceable');
    }

    public function event()
    {
        return $this->morphedByMany(Event::class, 'invoiceable');
    }

    public function subscription()
    {
        return $this->morphedByMany(Subscription::class, 'invoiceable');
    }

    public function transaction()
    {
        return $this->morphedByMany(Transaction::class, 'invoiceable');
    }


    public function scopeFilterInvoiceElearning($query, $filters)
    {


        if (isset($filters['status']) && $filters['status'] != 'any') {

            $transactions = Transaction::where('status',$filters['status'])->pluck('id');

            $query->whereIn('trans_id',  $transactions);
        }

        if (isset($filters['event']) && $filters['event'] != 'any') {
            $query->where('event_id', '=' ,$filters['event']);
        }


        if (isset($filters['search_term']) && strlen($filters['search_term']) > 1) {

            $search_term_str = '%'.implode("%", explode(" ", $filters['search_term'])).'%';
            $query->where('name', 'like', $search_term_str);


        }


        /*if (isset($filters['search_term']) && strlen($filters['search_term']) > 1) {

                $search_term_str = '%'.implode("%", explode(" ", $filters['search_term'])).'%';
                $events = Content::where('title', 'like', $search_term_str)
                    ->orWhere('slug', 'like', $search_term_str)
                    ->orWhere('id', 'like', $search_term_str)
                    ->orWhere('meta_title', 'like', $search_term_str)
                    ->orWhere('meta_keywords', 'like', $search_term_str)
                    ->where('view_tpl','elearning_event')->pluck('id');

                    $query->whereIn('event_id', $events);

        }*/


        return $query;
    }


    public function generateInvoice(){


        $data=[];

        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if( $user = $this->user->first() ){

            if($event = $user->events()->wherePivot('event_id',$eventId)->first()){
            //if($event = $user->events_for_user_list()->wherePivot('event_id',$eventId)->first()){
                $paymentMethodId = $event->pivot->payment_method;

            }

        }

        $billing = json_decode($this->transaction->first()->billing_details,true);

        //dd($this);

        $billInfo = '';
        $billafm = '';

        if(isset($billing['billaddress'])){
            $billInfo .= $billing['billaddress'];
        }

        if(isset($billing['billaddressnum'])){
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if(isset($billing['billpostcode'])){
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if(isset($billing['billcity'])){
            $billInfo .= ' ' . $billing['billcity'];
        }

        if(isset($billing['billafm'])){
            $billafm = $billing['billafm'];
        }

        if($this->amount - floor($this->amount)>0){
            $data['amount'] = number_format ($this->amount , 2 , ',', '.');
        }else{
            $data['amount'] = number_format ($this->amount , 0 , ',', '.');
        }


        $data['date'] = date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        $data['billInfo'] = $billInfo ;
        $data['invoice'] = $this->invoice ;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';

        $this->instalments_remaining = $this->instalments_remaining - 1;
        if( $this->instalments_remaining >=1){
            $this->date = date('Y-m-d', strtotime('+1 month', strtotime($this->date)));
        }else{
            $this->date = '-';
        }

        $this->save();

        $data['description'] = $this->event->first()->summary1->where('section','date')->first() ? $this->event->first()->summary1->where('section','date')->first()->title : '';
        $data['installments']= ($this->instalments > 1) ? ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments : '';

        //dd($data);

        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

          ]);

          $pdf->getDomPDF()->setHttpContext($contxt);
          $pdf->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');
         //$fn = 'myinvoice' . '.pdf';
         $fn = date('Y-m-d',strtotime($this->created_at)) . ' - Invoice - ' . $this->invoice . '.pdf';
        return $pdf;

    }

    public function generateCronjobInvoice(){


        $data=[];
        $remainingInst=0;
        $date = '-';


        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if( $user = $this->user->first() ){

            if($event = $user->events()->wherePivot('event_id',$eventId)->first()){

                $paymentMethodId = $event->pivot->payment_method;

            }

        }

        $billing = json_decode($this->transaction()->first()->billing_details,true);

        //dd($this->transaction);

        $billInfo = '';
        $billafm = '';

        if(isset($billing['billaddress'])){
            $billInfo .= $billing['billaddress'];
        }

        if(isset($billing['billaddressnum'])){
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if(isset($billing['billpostcode'])){
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if(isset($billing['billcity'])){
            $billInfo .= ' ' . $billing['billcity'];
        }

        if(isset($billing['billafm'])){
            $billafm = $billing['billafm'];
        }


        /*$this->instalments_remaining = $this->instalments_remaining - 1;
        if( $this->instalments_remaining >=1){
            //$this->date = date('d-m-Y', strtotime('+1 month', strtotime($this->date)));
        }else{
            $this->date = '-';
        }*/

        $remainingInst = $this->instalments_remaining - 1;
        if( $remainingInst >=1){
            $date = date('Y-m-d', strtotime('+1 month', strtotime($this->date)));
        }
        $this->instalments_remaining = 0;
        $this->save();

        /*if(!Invoice::latest()->doesntHave('subscription')->first()){
            $invoiceNumber = sprintf('%04u', 1);
        }else{
            $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
            $invoiceNumber = (int) $invoiceNumber + 1;
            $invoiceNumber = sprintf('%04u', $invoiceNumber);
        }*/


        $user = $this->user()->first();
        $event = $this->event()->first();
        $transaction = $this->transaction()->first();

        $newInvoice = new Invoice;

        $newInvoice->name = $this->name;
        $newInvoice->amount = $this->amount;
        $newInvoice->invoice = generate_invoice_number($paymentMethodId);
        $newInvoice->date = $date;
        $newInvoice->instalments_remaining = $remainingInst;
        $newInvoice->instalments = $this->instalments;

        $newInvoice->save();


        //$newInvoice->event()->save($this->event()->first());
        //$newInvoice->user()->save($this->user()->first());
        //$newInvoice->transaction()->save($this->transaction()->first());

        $newInvoice->event()->save($event);
        $newInvoice->user()->save($user);
        $newInvoice->transaction()->save($transaction);

        if($this->amount - floor($this->amount)>0){
            $data['amount'] = number_format ($newInvoice->amount , 2 , ',', '.');
        }else{
            $data['amount'] = number_format ($newInvoice->amount , 0 , ',', '.');
        }

        $data['date'] = date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $newInvoice->event->first()->title;
        $data['name'] = $newInvoice->name;
        $data['billInfo'] = $billInfo ;
        $data['invoice'] = $newInvoice->invoice ;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';
        $data['description'] =  $newInvoice->event->first()->summary1->where('section','date')->first() ?  $newInvoice->event->first()->summary1->where('section','date')->first()->title : '';
        $data['installments']= ( $newInvoice->instalments > 1) ? ( $newInvoice->instalments -  $newInvoice->instalments_remaining) . ' of ' . $newInvoice->instalments : '';

        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

          ]);

          $pdf->getDomPDF()->setHttpContext($contxt);
          $pdf->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');
        //$fn = 'myinvoice' . '.pdf';
        $fn = date('Y-m-d',strtotime($this->created_at)) . ' - Invoice - ' . $this->invoice . '.pdf';
        return [$pdf,$newInvoice];

    }

    public function getInvoice($planDecription = false){

        $data=[];

        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if( $user = $this->user->first() ){

            if($event = $user->events()->wherePivot('event_id',$eventId)->first()){

                $paymentMethodId = $event->pivot->payment_method;

            }

        }


        $billing = json_decode($this->transaction->first()->billing_details,true);
        //$billing = json_decode($this->user->first()->billing_details,true);

        $billInfo = '';
        $billafm = '';

        if(isset($billing['billaddress'])){
            $billInfo .= $billing['billaddress'];
        }

        if(isset($billing['billaddressnum'])){
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if(isset($billing['billpostcode'])){
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if(isset($billing['billcity'])){
            $billInfo .= ' ' . $billing['billcity'];
        }

        if(isset($billing['billafm'])){
            $billafm = $billing['billafm'];
        }
        if($this->amount - floor($this->amount)>0){
            $data['amount'] = number_format ($this->amount , 2 , ',', '.');
        }else{
            $data['amount'] = number_format ($this->amount , 0 , ',', '.');
        }
        $data['date'] = $this->created_at->format('d-F-Y');//date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        //$data['name'] = isset($billing['billname']) ? $billing['billname'] : '';
        $data['billInfo'] = $billInfo ;
        $data['invoice'] = $this->invoice ;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        //$data['footer'] = $this->event->first()->paymentMethod->first() ? $this->event->first()->paymentMethod->first()->footer : '';
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';
        $data['installments']= ($this->instalments > 1) ? ($this->instalments - $this->instalments_remaining) . ' of ' . $this->instalments : '';

        if($planDecription){

            $plan = $this->event->first()->plans->first();
            $data['description'] = $plan->invoice_text ? $plan->invoice_text : $plan->name;


        }else{
            //dd($this->event->first()->summary1->where('section','date')->first()->title);
            //$data['description'] = $this->event->first()->summary1->where('section','date')->first() ? $this->event->first()->summary1->where('section','date')->first()->title : '';

            $data['description'] = '';
            if($this->event->first()->event_info != null){
                if($this->event->first()->event_info != null){
                    $info = $this->event->first()->event_info->formedData();
                }


                $hours = isset($info['hours']) ? $info['hours'] : null;
                $hours_visible = isset($hours['visible']) ? $hours['visible'] : null;
                $language_visible = isset($info['language']['visible']) ? $info['language']['visible'] : null;
                $certificate_visible = isset($info['certificate']['visible']) ? $info['certificate']['visible'] : null;
                $students_visible = isset($info['students']['visible']) ? $info['students']['visible'] : null;

                if(isset($info['inclass'])){
                    $inclass_dates = isset($info['inclass']['dates']) ? $info['inclass']['dates'] : null;
                    $inclass_days = isset($info['inclass']['days']) ? $info['inclass']['days'] : null;
                    $inclass_times = isset($info['inclass']['times']) ? $info['inclass']['times'] : null;
                }

                if(isset($hours_visible['invoice']) && $hours_visible['invoice'] && isset($hours['hour']) && $hours['hour'] != null){
                    $data['description'] = $data['description'] .$hours['hour'].' '. ($hours['text'] != null ? $hours['text'] : '').', ';
                }


                if(isset($language_visible['invoice']) && $language_visible['invoice'] && isset($info['language']['text']) && $info['language']['text'] != null){
                    $data['description'] =  $data['description'] .$info['language']['text'].', ';
                }
                if(isset($certificate_visible['invoice']) && $certificate_visible['invoice'] && isset($info['certificate']['type']) && $info['certificate']['type'] != null){
                    $data['description'] =  $data['description'] . $info['certificate']['type'].', ';
                }

                if(isset($students_visible['invoice']) && $students_visible['invoice'] && isset($info['students']['number']) && get_sum_students_course($event->category->first()) > (int)$info['students']['number']){
                    $data['description'] =  $data['description'] . get_sum_students_course($event->category->first()). ($info['students']['text'] != null ? $info['students']['text'].', ' : ', ');
                }

                if(isset($info['inclass'])){
                    if(isset($inclass_dates['visible']['invoice']) && $inclass_dates['visible']['invoice'] && $inclass_dates['text'] != null){
                        $data['description'] =  $data['description'] . $inclass_dates['text'] .', ';
                    }
                    if(isset($inclass_days['visible']['invoice']) && $inclass_days['visible']['invoice'] && $inclass_days['text'] != null){
                        $data['description'] =  $data['description'] . $inclass_days['text'].', ';
                    }
                    if(isset($inclass_times['visible']['invoice']) && $inclass_times['visible']['invoice'] && $inclass_times['text'] != null){
                        $data['description'] =  $data['description'] . $inclass_times['text'].', ';
                    }

                }
                $data['description'] = rtrim($data['description'], ', ');

            }

        }



        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
        ]);

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

          ]);

          $pdf->getDomPDF()->setHttpContext($contxt);
          $pdf->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');

         //$fn = 'myinvoice' . '.pdf';
         $fn = date('Y-m-d',strtotime($this->created_at)) . '-Invoice-' . $this->invoice . '.pdf';
        return $pdf->stream($fn);

    }

    function getZipOfInvoices($zip, $planDecription = false,$invoicesNumber){


        $data=[];
        $installments = 1;
        $eventId = $this->event->first() ? $this->event->first()->id : -1;
        $paymentMethodId = 2;
        if( $user = $this->user->first() ){

            if($event = $user->events()->wherePivot('event_id',$eventId)->first()){

                if(!isset($invoicesNumber[$user->id.'-'.$eventId])){
                    $invoicesNumber[$user->id.'-'.$eventId] = 0;
                }
                $invoicesNumber[$user->id.'-'.$eventId] += 1;
                $installments = $invoicesNumber[$user->id.'-'.$eventId];
                $paymentMethodId = $event->pivot->payment_method;

            }

        }


        $billing = json_decode($this->transaction->first()->billing_details,true);
        //$billing = json_decode($this->user->first()->billing_details,true);

        $billInfo = '';
        $billafm = '';

        if(isset($billing['billaddress'])){
            $billInfo .= $billing['billaddress'];
        }

        if(isset($billing['billaddressnum'])){
            $billInfo .= ' ' . $billing['billaddressnum'];
        }

        if(isset($billing['billpostcode'])){
            $billInfo .= ' ' . $billing['billpostcode'];
        }

        if(isset($billing['billcity'])){
            $billInfo .= ' ' . $billing['billcity'];
        }

        if(isset($billing['billafm'])){
            $billafm = $billing['billafm'];
        }
        if($this->amount - floor($this->amount)>0){
            $data['amount'] = number_format ($this->amount , 2 , ',', '.');
        }else{
            $data['amount'] = number_format ($this->amount , 0 , ',', '.');
        }
        $data['date'] = $this->created_at->format('d-F-Y');//date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->first()->title;
        $data['name'] = $this->name;
        //$data['name'] = isset($billing['billname']) ? $billing['billname'] : '';
        $data['billInfo'] = $billInfo ;
        $data['invoice'] = $this->invoice ;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billafm;
        //$data['footer'] = $this->event->first()->paymentMethod->first() ? $this->event->first()->paymentMethod->first()->footer : '';
        $data['footer'] = ($paymentMethod = PaymentMethod::find($paymentMethodId)) ? $paymentMethod->footer : '';
        $data['installments']=  $installments . ' of ' . $this->instalments;

        if($planDecription){

            $plan = $this->event->first()->plans->first();
            $data['description'] = $plan->invoice_text ? $plan->invoice_text : $plan->name;


        }else{
            $data['description'] = $this->event->first()->summary1->where('section','date')->first() ? $this->event->first()->summary1->where('section','date')->first()->title : '';

        }


        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
          ]);

          $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,

          ]);


          $pdf->getDomPDF()->setHttpContext($contxt);

          $pdf;

          //$fn = 'myinvoice' . '.pdf';
          $fn = date('Y-m-d',strtotime($this->created_at)) . '-Invoice-' . $this->invoice . '.pdf';
          $pdf->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait')->save(public_path('invoices_folder/'.$fn))->stream($fn);

          $zip->addFile(public_path('invoices_folder/'.$fn), $fn);
          //dd('gfdg');
          return $invoicesNumber;

    }

}

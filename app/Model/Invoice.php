<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\User;
use App\Model\Transaction;
use App\Model\Event;
use Laravel\Cashier\Subscription;
use PDF;

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
        
        $billing = json_decode($this->transaction->first()->billing_details,true);

        //dd($this);

        $billInfo = $billing['billaddress'] . ' ' . $billing['billaddressnum'] . ', ' .  $billing['billpostcode'] . ', ' . $billing['billcity'];
    
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
        $data['vat'] = $billing['billafm'];

        $this->instalments_remaining = $this->instalments_remaining - 1;
        if( $this->instalments_remaining >=1){
            $this->date = date('d-m-Y', strtotime('+1 month', strtotime($this->date)));
        }else{
            $this->date = '-';
        }

        $this->save();

        //dd($data);
        
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,
            'defaultFont', 'Foco',
          ])->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');
        $fn = 'myinvoice' . '.pdf';
        return $pdf;
    
    }

    public function generateCronjobInvoice(){


        $data=[];
        $remainingInst=0;
        $date = '-';
        $billing = json_decode($this->transaction()->first()->billing_details,true);

        //dd($this->transaction);

        $billInfo = $billing['billaddress'] . ' ' . $billing['billaddressnum'] . ', ' .  $billing['billpostcode'] . ', ' . $billing['billcity'];
    
    
        /*$this->instalments_remaining = $this->instalments_remaining - 1;
        if( $this->instalments_remaining >=1){
            //$this->date = date('d-m-Y', strtotime('+1 month', strtotime($this->date)));
        }else{
            $this->date = '-';
        }*/

        $remainingInst = $this->instalments_remaining - 1;
        if( $remainingInst >=1){
            $date = date('d-m-Y', strtotime('+1 month', strtotime($this->date)));
        }
        $this->instalments_remaining = 0;
        $this->save();
    
        if(!Invoice::latest()->doesntHave('subscription')->first()){
            $invoiceNumber = sprintf('%04u', 1);
        }else{
            $invoiceNumber = Invoice::latest()->doesntHave('subscription')->first()->invoice;
            $invoiceNumber = (int) $invoiceNumber + 1;
            $invoiceNumber = sprintf('%04u', $invoiceNumber);
        }

        
        $newInvoice = new Invoice;

        $newInvoice->name = $this->name;
        $newInvoice->amount = $this->amount;
        $newInvoice->invoice = $invoiceNumber;
        $newInvoice->date = $date;
        $newInvoice->instalments_remaining = $remainingInst;
        $newInvoice->instalments = $this->instalments;

        $newInvoice->save();

        $newInvoice->event()->save($this->event()->first());
        $newInvoice->user()->save($this->user()->first());
        $newInvoice->transaction($this->transaction()->first());

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
        $data['vat'] = $billing['billafm'];

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,
            'defaultFont', 'Foco',
          ])->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');
        $fn = 'myinvoice' . '.pdf';
        return $pdf;
    
    }


    public function getInvoice(){


        $data=[];
       
        $billing = $this->transaction->billing_details;

        $billInfo = $billing['billaddress'] . ' ' . $billing['billaddressnum'] . ', ' .  $billing['billpostcode'] . ', ' . $billing['billcity'];
    
        if($this->amount - floor($this->amount)>0){
            $data['amount'] = number_format ($this->amount , 2 , ',', '.');
        }else{
            $data['amount'] = number_format ($this->amount , 0 , ',', '.');
        }
        $data['date'] = $this->created_at->format('d-F-Y');//date('d') . '-' . date('F') . '-' . date('Y');
        $data['title'] = $this->event->title;
        $data['name'] = $this->name;
        $data['billInfo'] = $billInfo ;
        $data['invoice'] = $this->invoice ;
        $data['country'] = 'Ελλάδα';
        $data['vat'] = $billing['billafm'];

        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled' => true,
            'defaultFont', 'Foco',
          ])->loadView('admin.invoices.elearning_invoice',compact('data'))->setPaper('a4', 'portrait');
        $fn = 'myinvoice' . '.pdf';
        return $pdf->stream($fn);
    
    }
}

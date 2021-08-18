<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Invoice;

class InvoiceController extends Controller
{
    public function getInvoice($invoice){

       
        $invoice = Invoice::where('id',$invoice)->first();
        if($invoice){
            return $invoice->getInvoice();
        }

    }
}

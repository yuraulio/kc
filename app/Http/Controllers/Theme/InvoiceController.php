<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Model\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function getInvoice($invoice)
    {
        $invoice = Invoice::where('id', $invoice)->first();

        $planDescription = $invoice->subscription->first() ? true : false;

        if ($invoice) {
            return $invoice->getInvoice($planDescription);
        }
    }
}

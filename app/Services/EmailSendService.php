<?php

namespace App\Services;

use Illuminate\Http\Request;
use Storage;

class EmailSendService
{
    public function createOrUpdate(Request $request)
    {
    }

    public function recordWebhook(Request $request)
    {
        \Log::info('Webhook received', $request->all());
    }
}

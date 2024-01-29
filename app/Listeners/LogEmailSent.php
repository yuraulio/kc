<?php

namespace App\Listeners;

use App\Events\EmailSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogEmailSent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EmailSent  $event
     * @return void
     */
    public function handle(EmailSent $event)
    {
        // Crear un canal de log personalizado
        Log::channel('email')->info('Email sent', ['email' => $event->email, 'type' => $event->type]);
    }
}

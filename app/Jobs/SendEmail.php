<?php

namespace App\Jobs;

use App\Model\User;
use App\Services\EmailSendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // private $data;
    // private $event;
    // private $to;
    // private $subject;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $event,
        private readonly User $to,
        private readonly string $subject,
        private readonly array $data = []
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(EmailSendService $service): void
    {
        // Send email
        $service->sendEmail($this->event, $this->to, $this->subject, $this->data);
    }
}

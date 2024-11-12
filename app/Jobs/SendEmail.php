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

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $event,
        private readonly array $to,
        private $subject,
        private readonly array $data = [],
        private readonly array $metaData = []
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(EmailSendService $service): void
    {
        // Send email
        $service->sendEmailByTriggerName($this->event, $this->to, $this->subject, $this->data, $this->metaData);
    }
}

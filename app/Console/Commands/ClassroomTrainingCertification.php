<?php

namespace App\Console\Commands;

use App\Model\Certificate;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\User;
use App\Services\CreateCertificatesTrainingEventsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ClassroomTrainingCertification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'classroomtraining:certificates {eventId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Classroom Training certificates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eventId = $this->argument('eventId');
        CreateCertificatesTrainingEventsService::generateCertificates($eventId);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Certificate;

class InitExpritationCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $certificates = Certificate::all();

        foreach($certificates as $certificate){
            $certificate->expiration_date = NULL;
            $certificate->save();
        }

        return 0;
    }
}

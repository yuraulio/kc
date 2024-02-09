<?php

namespace App\Console\Commands;

use App\Model\Instructor;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetInstructorsCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instructor:company';

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
        $client = new Client(['base_uri' => 'http://knowcrunchls.j.scaleforce.net', 'verify' => false]);
        $response = $client->request('GET', 'http://knowcrunchls.j.scaleforce.net/get-company');

        $companies = json_decode($response->getBody()->getContents(), true);

        foreach ($companies['companies'] as $key => $company) {
            if ($instructor = Instructor::find($key)) {
                $instructor->company = $company;
                $instructor->save();
            }
        }

        return 0;
    }
}

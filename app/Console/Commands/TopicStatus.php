<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Model\Topic;

class TopicStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'topic:status';

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
        $client = new Client(['base_uri' => 'http://knowcrunchls.j.scaleforce.net','verify' => false]);
        //$client = new Client(['base_uri' => 'http://lcknowcrunch.test','verify' => false]);

        $response = $client->request('GET', 'http://knowcrunchls.j.scaleforce.net/get-topic-status');
        //$response = $client->request('GET', 'http://lcknowcrunch.test/get-topic-status');

        $topics = json_decode($response->getBody()->getContents(),true);
        
        foreach($topics['topics'] as $key => $topic){
            $t = Topic::find($key);
            $t->status = $topic;
            $t->save();
        }   

        return 0;
    }
}

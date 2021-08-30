<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Summary;
use App\Model\Benefit;
use App\Model\Media;

class AttachBenefitsSummaryMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'summary-benefit:media';

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
        $summaries = Summary::all();
        $benefits = Benefit::all();

        foreach($summaries as $summary){
            if(!$summary->mediable){
                $media = new Media;
                $summary->mediable()->save($media);
            }
            
        }

        foreach($benefits as $summary){
            if(!$summary->mediable){
                $media = new Media;
                $summary->mediable()->save($media);
            }
            
        }

        return 0;
    }
}

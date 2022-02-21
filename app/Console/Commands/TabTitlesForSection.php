<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Section;

class TabTitlesForSection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'section:tab-titles';

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
        $sections = Section::all();

        foreach($sections as $section){
            $section->tab_title = $section->section;
            $section->visible = true;
            $section->save();
        }

        return 0;
    }
}

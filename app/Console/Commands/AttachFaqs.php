<?php

namespace App\Console\Commands;

use App\Model\Category;
use App\Model\Event;
use Illuminate\Console\Command;

class AttachFaqs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:faqs';

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
        $masterEvent = Event::find(2304);
        $events = Event::whereIn('id', [4628, 4627, 4626, 4625, 4624, 4623, 4622, 4621])->get();

        $faqs = $masterEvent->faqs;

        foreach ($events as $event) {
            //$category = Category::find($event->category->first()->id);

            foreach ($faqs as $faq) {
                $faq->categoryEvent()->attach($event->category->first()->id);
                $faq->event()->attach($event->id);
            }
        }

        return 0;
    }
}

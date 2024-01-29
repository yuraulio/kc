<?php

namespace App\Console\Commands;

use App\Model\Faq;
use Illuminate\Console\Command;

class ChangeFaqsLogic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faqs:logic-change';

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
        $idsChecked = [];
        $faqsEl = Faq::whereHas('event', function ($event) {
            return $event->whereHas('delivery', function ($delivery) {
                return $delivery->where('delivery_id', 143);
            });
        })
        ->whereDoesntHave('event', function ($event) {
            return $event->whereHas('delivery', function ($delivery) {
                return $delivery->where('delivery_id', '<>', 143);
            });
        })
        ->get();

        foreach ($faqsEl as $faq) {
            $idsChecked[] = $faq->id;
            $faq->type = 'elearning';

            $faq->save();
        }

        $faqsINC = Faq::whereDoesntHave('event', function ($event) {
            return $event->whereHas('delivery', function ($delivery) {
                return $delivery->where('delivery_id', 143);
            });
        })
        ->get();

        foreach ($faqsINC as $faq) {
            $idsChecked[] = $faq->id;
            $faq->type = 'in_class';

            $faq->save();
        }

        $faqsBoth = Faq::whereNotIn('id', $idsChecked)->get();

        foreach ($faqsBoth as $faq) {
            $idsChecked[] = $faq->id;
            $faq->type = 'both';

            $faq->save();
        }

        echo count($faqsEl);
        echo '++';
        echo count($faqsINC);
        echo '++';
        echo count($faqsBoth);
        echo '++';

        return 0;
    }
}

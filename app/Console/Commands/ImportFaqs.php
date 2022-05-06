<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\Model\Faq;
use App\Model\CategoriesFaqs;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportFaqs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:faqs {delivery} {sheet}';

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

        $del = $this->argument('delivery');
        $events = [];
        $categories = Category::whereHas('events',function($event) use($del){
            return $event->whereHas('delivery',function($delivery) use($del){
                return $delivery->where('deliveries.id', $del);
            });
        })->get();//->pluck('id')->toArray();

        foreach($categories as $category){

            foreach($category->events as $event){
                $events[] = $event->id;
            }

        }

        $categories = Category::whereHas('events',function($event) use($del){
            return $event->whereHas('delivery',function($delivery) use($del){
                return $delivery->where('deliveries.id', $del);
            });
        })->pluck('id')->toArray();

        $fileName = public_path() . '/FAQs.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $file = $reader->load($fileName);
        $file = $file->getSheet($this->argument('sheet'));

        $file = $file->toArray();
       
        foreach($file as $key =>  $line){

            /*if($key == 28){
                dd(htmlspecialchars_decode($line[2]));
            }else{
                continue;
            }*/


            if($key == 0 ){
                continue;
            }

            $faq = new Faq;
            $faq->title = $line[1];
            $faq->answer = $line[2];//htmlspecialchars($line[2], ENT_QUOTES);
            $faq->status = true;
            $faq->priority = $key;

            $faq->save();

            $categoryFaq = CategoriesFaqs::where('name',trim($line[0]))->first();

            $faq->categoryEvent()->attach($categories,['priority' => $key]);
            $faq->category()->attach($categoryFaq->id,['priority' => $key]);
            $faq->event()->attach($events,['priority' => $key]);


        }


        return 0;
    }
}


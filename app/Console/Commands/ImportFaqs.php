<?php

namespace App\Console\Commands;

use App\Model\CategoriesFaqs;
use App\Model\Category;
use App\Model\Faq;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportFaqs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:faqs {file_name}';

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
        try {
            //$fileName = public_path() . '/import/FAQs.xlsx';
            $fileName = public_path() . '/import/' . $this->argument('file_name');

            if (!file_exists($fileName)) {
                return 0;
            }

            $faqs = Faq::all();

            foreach ($faqs as $faq) {
                $faq->category()->detach();
                $faq->categoryEvent()->detach();
                $faq->event()->detach();

                $faq->delete();
            }
            //return;

            $spreadsheet = new Spreadsheet();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
            $reader->setReadDataOnly(true);

            $file = $reader->load($fileName);
            $sheets = $file->getSheetNames();

            foreach ((array) $sheets as $key => $sheet) {
                if ($key == 0) {
                    $del = 'all';
                    $type = 'both';
                } elseif ($key == 1) {
                    $del = [143];
                    $type = 'elearning';
                } elseif ($key == 2) {
                    $del = [139, 215];
                    $type = 'in_class';
                }

                $events = [];

                if ($del == 'all') {
                    $categories = Category::whereHas('events')->get();
                } else {
                    $categories = Category::whereHas('events', function ($event) use ($del) {
                        return $event->whereHas('eventInfo', function ($query) use ($del) {
                            $query->whereIn('course_delivery', $del);
                        });
                    })->get();
                }

                foreach ($categories as $category) {
                    foreach ($category->events as $event) {
                        $events[] = $event->id;
                    }
                }

                if ($del == 'all') {
                    $categories = Category::whereHas('events')->pluck('id')->toArray();
                } else {
                    $categories = Category::whereHas('events', function ($event) use ($del) {
                        return $event->whereHas('eventInfo', function ($query) use ($del) {
                            $query->whereIn('course_delivery', $del);
                        });
                    })->pluck('id')->toArray();
                }

                $file1 = $file->getSheet($key);
                $file1 = $file1->toArray();

                foreach ($file1 as $key1 =>  $line) {
                    if ($key1 == 0) {
                        continue;
                    }

                    $faq = new Faq;
                    $faq->title = $line[1];
                    $faq->answer = $line[2]; //htmlspecialchars($line[2], ENT_QUOTES);
                    $faq->status = true;
                    $faq->priority = $key;
                    $faq->type = $type;

                    $faq->save();

                    $categoryFaq = CategoriesFaqs::where('name', trim($line[0]))->first();

                    $faq->categoryEvent()->attach($categories, ['priority' => $key1]);
                    $faq->category()->attach($categoryFaq->id, ['priority' => $key1]);
                    $faq->event()->attach($events, ['priority' => $key1]);
                }

                /*foreach($file1 as $key1 =>  $line){
                    if($key == 1){
                        echo $key1;
                    }
                    if($key1 == 0 ){
                        continue;
                    }


                    if($key == 0){
                        $faq = Faq::whereTitle(trim($line[1]))->first();
                    }else{

                        $faq = Faq::whereTitle(trim($line[1]))->get();


                        if(count($faq) == 2){
                            $faq = $faq[$key-1];
                        }else{
                            $faq =$faq[0];
                        }



                    }


                    if(!$faq){
                        continue;
                    }

                    $categoryFaq = CategoriesFaqs::where('name',trim($line[0]))->first();

                    $faq->category()->detach();
                    $faq->categoryEvent()->detach();
                    $faq->event()->detach();


                    $faq->categoryEvent()->attach($categories,['priority' => $key1]);
                    $faq->category()->attach($categoryFaq->id,['priority' => $key1]);
                    $faq->event()->attach($events,['priority' => $key1]);


                }*/
            }

            return 1;
        } catch(\Exception $e) {
            return 0;
        }

        return 0;
    }
}

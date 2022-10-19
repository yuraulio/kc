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
    protected $signature = 'insert:faqs';

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
        $fileName = public_path() . '/import/FAQs.xlsx';
      
        if(!file_exists($fileName)){
            return;
        }


        $faqs = Faq::all();

        foreach($faqs as $faq){
            $faq->category()->detatch();
            $faq->categoryEvent()->detatch();

            $faq->delete();
        }

        return;

        //Faq::delete();


        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        
        $file = $reader->load($fileName);
        $sheets = $file->getSheetNames();

       
        foreach((array) $sheets as $key => $sheet){

            $del = $this->argument('delivery');

            if($key == 0){
                $del = 'all';
            }elseif($key == 1){
                $del = 143;
            }elseif($key == 2){
                $del = 139;
            }


            $events = [];
            

            if($del == 'all'){
                $categories = Category::whereHas('events')->get();
            }else{
                $categories = Category::whereHas('events',function($event) use($del){
                    return $event->where('view_tpl',$del);

                })->get();
            }

            foreach($categories as $category){

                foreach($category->events as $event){
                    $events[] = $event->id;
                }

            }

        
            if($del == 'all'){
                $categories = Category::whereHas('events')->pluck('id')->toArray();
            }else{
                $categories = Category::whereHas('events',function($event) use($del){
                    return $event->where('view_tpl',$del);
                })->pluck('id')->toArray();
            }




            $file1 = $file->getSheet($key);
            $file1 = $file1->toArray();
            
            foreach($file1 as $key =>  $line){

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

        }


        


        return 0;
    }
}


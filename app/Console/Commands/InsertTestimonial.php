<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Category;
use App\Model\Testimonial;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\Media;

class InsertTestimonial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:testimonials';

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


        $dir_path=public_path() ."/uploads/originals/testimonials/";
        $files = array_diff(scandir($dir_path), array('.', '..'));

        $images = [];

        foreach($files as $file){
            
            $name = explode('.',$file)[0];
            $images[$name] = $file;
        }
        
        $fileName = public_path() . '/Testimonials.xlsx';
        $spreadsheet = new Spreadsheet();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileName);
        $reader->setReadDataOnly(true);
        $file = $reader->load($fileName);
        $file = $file->getSheet(0);

        $file = $file->toArray();
       
        foreach($file as $key =>  $line){

            if($key == 0 ){
                continue;
            }
            //dd($line);
            $testimonial = new Testimonial;
            $testimonial->name = $line[1];
            $testimonial->lastname = $line[2];
            $testimonial->title = $line[3];
            $testimonial->status = true;
            $testimonial->testimonial = $line[6];
            $testimonial->video_url = $line[7];

            $socials = [];
            if($line[8]){
                $socials['facebook'] = $line[8];
            }

            if($line[9]){
                $socials['linkedin'] = $line[9];
            }

            $testimonial->social_url = json_encode($socials);
            $testimonial->save();

            $categories = [];
            
            if($line[4]){
                $categories[] = $line[4];
            }

            if($line[5]){
                $categories[] = $line[5];
            }
            $categories = Category::whereIn('id',[46,183])->get();

            foreach($categories as $category){
                $testimonial->category()->attach([$category->id]);
            }

            if($line[0]){

                $name = str_replace('.','',$line[0]);
                $media = new Media;

                $media->path = '/uploads/originals/testimonials/';
                $media->ext = '.' . explode('.',$images[$name])[1];
                $media->name = str_replace('.','',$line[0]);
                $media->original_name = $images[$name];
                $media->mediable_id = $testimonial->id;
                $media->mediable_type = get_class($testimonial);

                $media->save();

                //$testimonial->mediable()->attach($media->id);

            }else{
                $testimonial->createMedia();
            }

            //$faq->answer = $line[2];//htmlspecialchars($line[2], ENT_QUOTES);
            //$faq->status = true;
            //$faq->priority = $key;
//
            //$faq->save();
//
            //$categoryFaq = CategoriesFaqs::where('name',trim($line[0]))->first();
//
            //$faq->categoryEvent()->attach($categories,['priority' => $key]);
            //$faq->category()->attach($categoryFaq->id,['priority' => $key]);
            //$faq->event()->attach($events,['priority' => $key]);


        }


        return 0;
    }
}

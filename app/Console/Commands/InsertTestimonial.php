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
    protected $signature = 'insert:testimonials {file_name}';

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

        try{
            $dir_path=public_path() ."/uploads/originals/testimonials/";
            $files = array_diff(scandir($dir_path), array('.', '..'));

            $images = [];

            foreach($files as $file){

                $name = explode('.',$file)[0];
                $images[$name] = $file;
            }

            //$fileName = public_path() . '/import/Testimonials.xlsx';
            $fileName = public_path() . '/import/' . $this->argument('file_name');

            if(!file_exists($fileName)){
                return 0;
            }

            $testimonials = Testimonial::all();

            foreach($testimonials as $testimonial){

                $testimonial->category()->detach();
                $testimonial->instructors()->detach();
                $testimonial->medias()->delete();

                $testimonial->delete();

            }


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
                $testimonial->testimonial = $line[4];
                $testimonial->video_url = $line[5];

                $socials = [];
                if(isset($line[6])){

                    $link = $line[6];
                    if($link){
                        $link = str_replace('https://', '', $link);
                        $link = str_replace('http://', '', $link);
                        $link = 'https://'.$link;
                    }

                    $socials['facebook'] = $link;
                
                }

                if(isset($line[7])){

                    $link = $line[7];
                    if($link){
                        $link = str_replace('https://', '', $link);
                        $link = str_replace('http://', '', $link);
                        $link = 'https://'.$link;
                    }

                    $socials['linkedin'] = $link;
                }

                $testimonial->social_url = json_encode($socials);
                $testimonial->save();

                $categories = [];

                if(isset($line[4])){
                    //$categories[] = $line[4];
                }

                if(isset($line[5])){
                    //$categories[] = $line[5];
                }
                //$categories = Category::whereIn('id',[46,183])->get();
                $categories = Category::all();

                foreach($categories as $category){
                    $testimonial->category()->attach([$category->id]);
                }

                if($line[0] && isset($images[$name]) ){

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

            

            }

            return 1;

        }catch(\Exception $e){

            return 0;

        }
        


        //return 0;
    }
}

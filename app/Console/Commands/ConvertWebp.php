<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Media;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class ConvertWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:webp';

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

        //awards folder

        $files = Storage::disk('awards')->files();
        foreach($files as $file){
            $source = $file;

            $ext = explode('.',$source)[count(explode('.',$source)) - 1];

            if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){
                $destination = '/'.str_replace($ext,'webp',$source);


                $a = Image::make(public_path('/awards/').$source)->stream("webp", env('WEBP_IMAGE_QUALITY'));
                Storage::disk('awards')->put($destination, $a, 'public');

            }
        }


        // upload folder
        $folders = Storage::disk('public')->directories();



        foreach($folders as $folder){
            if(str_contains($folder, '__MACOSX')){
                continue;
            }
            $files = Storage::disk('public')->files($folder);
            foreach($files as $file){
                //$path = public_path('/uploads/'.$file);


                $source = $file;
                $ext = explode('.',$source)[count(explode('.',$source)) - 1];

                if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){
                    $destination = '/'.str_replace($ext,'webp',$source);

                    try{
                        $a = Image::make(public_path('/uploads/').$source)->stream("webp", env('WEBP_IMAGE_QUALITY'));
                        Storage::disk('public')->put($destination, $a, 'public');
                    }catch(\Exception $e){}
                   

                }



            }
            $folders1 = Storage::disk('public')->directories($folder);
            foreach($folders1 as $fol){
                if(str_contains($fol, '__MACOSX')){
                    continue;
                }
                //$path = public_path('/uploads/'.$file);


                $files = Storage::disk('public')->files($fol);
                foreach($files as $file){
                    //$path = public_path('/uploads/'.$file);


                    $source = $file;
                    $ext = explode('.',$source)[count(explode('.',$source)) - 1];

                    if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){
                        $destination = '/'.str_replace($ext,'webp',$source);


                        $a = Image::make(public_path('/uploads/').$source)->stream("webp", env('WEBP_IMAGE_QUALITY'));
                        Storage::disk('public')->put($destination, $a, 'public');

                    }



                }



            }

        }

        $files = Storage::disk('public')->files();
        foreach($files as $file){
            $source = $file;
            $ext = explode('.',$source)[count(explode('.',$source)) - 1];

            if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){
                $destination = '/'.str_replace($ext,'webp',$source);


                $a = Image::make(public_path('/uploads/').$source)->stream("webp", env('WEBP_IMAGE_QUALITY'));
                Storage::disk('public')->put($destination, $a, 'public');

            }



        }



    }
}

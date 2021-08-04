<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Media;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class MakePhotoVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:versions';

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
        $medias = Media::all();
        foreach($medias as $media){
            
            if(!$media['original_name']){
                continue;
            }

            $details = json_decode($media['details'],true);
           
            if(!$details){
                continue;
            }
            foreach(get_image_versions() as $value){
                

                if(isset($details['img_align'][$value['version']]) && $details['img_align'][$value['version']]['x'] > 0 && $details['img_align'][$value['version']]['y'] > 0){
                    $edit = $details['img_align'][$value['version']];
                    
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->crop(intval($edit['width']),intval($edit['height']), intval($edit['x']), intval($edit['y']));
                    $image_resize->save(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']), $value['q']);

                }

                /*if(!isset($details[$value['version']])){
                    continue;
                }*/
                /*Image::make(public_path($media['path'] . $media['original_name']))->widen($value['w'], function ($constraint) {
                    $constraint->upsize();
                })->save(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']), $value['q']);*/


                /*if ($value['w'] > $value['h']) {
                    
                    //landscape, resize by height
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->heighten($value['h']);
                    //$image_resize->fit($value['w'], $value['h']);
                    $image_resize->crop($details[$value['version']]['width'], $details[$value['version']]['height'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                    //$image_resize->fit($details[$value['version']]['x'],$details[$value['version']]['y']);


                } else if ($value['w'] < $value['h']) {

                    //portrait, resize by width
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->widen($value['w']);
                    //$image_resize->fit($value['w'], $value['h']);
                    $image_resize->crop($details[$value['version']]['width'], $details[$value['version']]['height'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                    //$image_resize->fit($details[$value['version']]['x'],$details[$value['version']]['y']);

                } else {
                    //square, all is well
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->resize($value['w'], $value['h']);
                    //$image_resize->crop($details[$value['version']]['width'], $details[$value['version']]['height'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                    $image_resize->crop($value['w'], $value['h'],$details[$value['version']]['x'],$details[$value['version']]['y']);

                    //$image_resize->fit($details[$value['version']]['x'],$details[$value['version']]['y']);

                //}*/


                /*if(!isset($details['img_align'])){
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->resize($value['w'], $value['h']);
                    //$image_resize->crop($details[$value['version']]['width'], $details[$value['version']]['height'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                    //$image_resize->crop($value['w'], $value['h'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                }else{
                    
                    $details = $details['img_align'];
                    if(!isset($details[$value['version']])){
                        continue;
                    }
                    $image_resize = Image::make(public_path($media['path'] . $media['original_name']));
                    $image_resize->resize($value['w'], $value['h']);
                    //$image_resize->crop($details[$value['version']]['width'], $details[$value['version']]['height'],$details[$value['version']]['x'],$details[$value['version']]['y']);
                    $image_resize->crop($value['w'], $value['h'],intval($details[$value['version']]['x']),intval($details[$value['version']]['y']));
                }*/
                
                //$image_resize = Image::make(public_path($media['path'] . $media['original_name']));

     
                /*$image->crop(intval($request->width),intval($request->height), intval($request->x), intval($request->y));
                $image->save(public_path('/uploads').$word.$path[0].'-'.$request->version.'.'.$path[1], 50);
                $data['version'] = $request->version;*/

                //$image_resize->resize($value['w'], $value['h']);              
                //$image_resize->save(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']), $value['q']);
            }
        }
        
    
    }
}

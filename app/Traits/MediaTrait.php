<?php

namespace App\Traits;
use App\Model\Media;
use App\Model\Event;
use Eloquent;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{

    public function createMedia(){

        $media = new Media;
        $media->save();

        $this->mediable()->save($media);
    }

    /*public function createMedia($mediaKey){

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));

        $path = explode(".",$id);

        $image = Image::make(public_path($mediaKey));

        $media = new Media;
        $media->original_name = $id;
        $media->path = $folders.'/';
        $media->name = $path[0];
        $media->ext = '.'.$path[1];
        $media->width = $image->width();
        $media->height = $image->height();

        $this->mediable()->save($media);

        foreach(get_image_versions() as $value){
            $image->resize($value['w'], $value['h']);
            $image->fit($value['w'], $value['h']);
            $image->save(public_path($folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1]), $value['q']);
        }
    }*/

    public function updateMedia($mediaKey){
        //dd($mediaKey);
        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));

        $path = explode(".",$id);

        $image = Image::make(public_path($mediaKey));

        if($this->mediable()->first()){
            $this->mediable()->update([
                'original_name' => $id,
                'name' => $path[0],
                'path' => $folders.'/',
                'ext' => '.'.$path[1],
                'width' => $image->width(),
                'height' => $image->height()
            ]);
        }else{
            $media = new Media;
            $media->original_name = $id;
            $media->path = $folders.'/';
            $media->name = $path[0];
            $media->ext = '.'.$path[1];
            $media->width = $image->width();
            $media->height = $image->height();

            $this->mediable()->save($media);
        }



        foreach(get_image_versions() as $value){
            $image->resize($value['w'], $value['h']);
            $image->fit($value['w'], $value['h']);
            $image->save(public_path($folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1]), $value['q']);
        }
    }

    /**
     * @return string
     */

     public function mediable()
    {
        return $this->morphOne(Media::class,'mediable');
    }

    public function getMedia(){
        return $this->media;
    }

}

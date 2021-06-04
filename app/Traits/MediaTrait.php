<?php

namespace App\Traits;
use App\Model\Media;
use Eloquent;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{

    public function createMedia($mediaKey){

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));

        $path = explode(".",$id);

        $media = new Media;
        $media->original_name = $mediaKey;
        $media->name = $path[0];
        $media->ext = $path[1];

        $this->mediable()->save($media);

        foreach(get_image_versions() as $value){
            $image_resize = Image::make(public_path($mediaKey));
            $image_resize->crop($value['w'], $value['h']);
            $image_resize->save(public_path($folders.'/'.$path[0].'-'.$value['w'].'-'. $value['h'].'.'.$path[1]), $value['q']);
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

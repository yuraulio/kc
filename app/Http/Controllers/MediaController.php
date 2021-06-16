<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Media;
use Intervention\Image\ImageManagerStatic as Image;


class MediaController extends Controller
{
    public function index()
    {

        return view('admin.media2.index');
    }

    public function uploadImage(Request $request, Media $media){

        $mediaKey = $request->image_upload;

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);
        $image = Image::make(public_path($mediaKey));

        $media->original_name = $id;
        $media->path = $folders.'/';
        $media->name = $path[0];
        $media->ext = '.'.$path[1];
        $media->width = $image->width();
        $media->height = $image->height();

        $media->save();

        foreach(get_image_versions() as $value){
            $image->resize($value['w'], $value['h']);
            $image->fit($value['w'], $value['h']);
            $image->save(public_path($folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1]), $value['q']);
        }

        return back();

    }

    public function crop_image(Request $request)
    {
        //dd($request->all());
        $media = Media::find($request->media_id);
        if($media['details'] != null){
            $details = json_decode($media['details'], true);

            //find version
            //dd((array)$details->img_align);

            $found = false;
            foreach($details['img_align'] as $key => $value){
                //dd($request->version);
                if($key == $request->version){
                    $found = true;
                }

            }

            //dd($found);

            if($found){
                //dd('found in json');

                //dd($details['img_align'][$request->version]);
                $details['img_align'][$request->version]['x'] = $request->x;
                $details['img_align'][$request->version]['y'] = $request->y;
                $details['img_align'][$request->version]['width'] = $request->width;
                $details['img_align'][$request->version]['height'] = $request->height;
                $details['img_align'][$request->version]['slug'] = $request->version;


            }else{
                $details['img_align'][$request->version]['x'] = $request->x;
                $details['img_align'][$request->version]['y'] = $request->y;
                $details['img_align'][$request->version]['width'] = $request->width;
                $details['img_align'][$request->version]['height'] = $request->height;
                $details['img_align'][$request->version]['slug'] = $request->version;

            }

            $details = json_encode($details);

            Media::where('id', $request->media_id)->update(['details' => $details]);


            //dd($details);
        }else{
            //dd('dhmiourgw to array');
        }

        $mediaKey = $request->path;
        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders = substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);

        $name = $path[0];
        $ext = $path[1];

        $image = Image::make(public_path($mediaKey));
        $image->crop(intval($request->width),intval($request->height), intval($request->x), intval($request->y));
        $image->save(public_path($folders.'/'.$path[0].'-'.$request->version.'.'.$path[1]), 50);

        $data['version'] = $request->version;

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $data,
        ]);
    }

}

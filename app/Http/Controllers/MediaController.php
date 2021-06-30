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

    public function uploadSvg(Request $request, Media $media){
        $mediaKey = $request->svg;

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);
        
        $image = Image::make(public_path('uploads').$mediaKey);

        $media->original_name = $id;
        $media->path = $folders.'/'.$path[0].'.'.$path[1];
        $media->name = $path[0];
        $media->ext = '.'.$path[1];
        $media->width = $image->width();
        $media->height = $image->height();

        $media->save();
    }

    public function uploadVersionImage(Request $request, Media $media){
        //dd($request->all());
        $versions = json_decode($request->versions);
        //dd($versions);
        $mediaKey = $request->image_upload;
        ///  test/companyAvatar.png

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);

        $image = Image::make(public_path('uploads').$mediaKey);

        $media->original_name = $id;
        $media->path = $folders.'/';
        $media->name = $path[0];
        $media->ext = '.'.$path[1];
        $media->width = $image->width();
        $media->height = $image->height();

        $media->save();




        foreach(get_image_versions() as $value){
            //dd($value['version']);
            foreach($versions as $version){
                if($value['version'] == $version){
                    $image->resize($value['w'], $value['h']);
                    $image->fit($value['w'], $value['h']);
                    $image->save(public_path('uploads').$folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1], $value['q']);
                }

            }

        }

        return back();

    }

    public function uploadProfileImage(Request $request, Media $media){
            //parse old image
            $old_image = $media;
            //delete old image
            if($old_image['name'] != null){
                unlink('uploads/profile_user/'.$old_image['original_name']);
            }


            $content = $request->file('photo');
            $name1 = explode(".",$content->getClientOriginalName());

            $path_name = $request->photo->store('profile_user', 'public');

            $name = explode('profile_user/',$path_name);
            $size = getimagesize('uploads/'.$path_name);
            $media->name = $name1[0];
            $media->ext = $content->guessClientExtension();
            $media->original_name = $name[1];
            $media->file_info = $content->getClientMimeType();
            $media->path = $path_name;
            $media->width = $size[0];
            $media->height = $size[1];
            $media->save();

    }



    public function crop_profile_image(Request $request)
    {
        //dd('asd');
        //dd($request->all());
        $media = Media::find($request->media_id);
        if($media['details'] != null){
            //dd('has details');
            $details = json_decode($media['details'], true);

            if($details != null || $details != ''){
                $details['x'] = $request->x;
                $details['y'] = $request->y;
                $details['width'] = $request->width;
                $details['height'] = $request->height;


            }else{
                $details['x'] = $request->x;
                $details['y'] = $request->y;
                $details['width'] = $request->width;
                $details['height'] = $request->height;

            }

            $details = json_encode($details);

            Media::where('id', $request->media_id)->update(['details' => $details]);

        }else{
            $arr = [];
            $arr['x'] = $request->x;
            $arr['y'] = $request->y;
            $arr['width'] = $request->width;
            $arr['height'] = $request->height;

            $details = json_encode($arr);

            Media::where('id', $request->media_id)->update(['details' => $details]);

        }

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => 'profile_image',
        ]);
    }

    public function crop_image(Request $request)
    {
        //dd(public_path('uploads'));
        //dd($request->all());
        $media = Media::find($request->media_id);
        //dd($media);
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
                //dd('not found');
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
            $arr = [];
            $arr['img_align'][$request->version]['x'] = $request->x;
            $arr['img_align'][$request->version]['y'] = $request->y;
            $arr['img_align'][$request->version]['width'] = $request->width;
            $arr['img_align'][$request->version]['height'] = $request->height;

            $details = json_encode($arr);

            Media::where('id', $request->media_id)->update(['details' => $details]);
        }



        $mediaKey = $request->path;
        //dd($mediaKey);
        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders = substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);

        $name = $path[0];
        $ext = $path[1];

        $folders = explode('/', $folders);
        //dd($folders);
        $word = '';
        foreach($folders as $key => $folder){
            //dd($folder);
            if($key >= 4){
                $word = $word.'/'.$folder.'/';
            }
        }

        $image = Image::make($mediaKey);
        $image->crop(intval($request->width),intval($request->height), intval($request->x), intval($request->y));
        if($request->version != 'profile_image'){
            $image->save(public_path('/uploads').$word.$path[0].'-'.$request->version.'.'.$path[1], 50);
            $data['version'] = $request->version;
        }else{
            $image->save(public_path('/uploads').$word.$path[0].'.'.$path[1], 50);
            $data['version'] = 'profile_image';
        }




        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $data,
        ]);
    }

}

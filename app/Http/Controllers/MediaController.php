<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Media;
use App\Model\Event;
use Intervention\Image\ImageManagerStatic as Image;
use Alexusmai\LaravelFileManager\Events\Download;


class MediaController extends Controller
{

    public function index()
    {
        return view('admin.media2.index');
    }

    public function crop_file_manager_image(Request $request){
        $path = $request->folder.$request->name;
        //dd($request->all());
        $image = Image::make(public_path('uploads').$path);
        $image->crop(intval($request->width),intval($request->height), intval($request->x), intval($request->y));
        $image->save(public_path('uploads').$path);

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $path,
        ]);

    }

    public function uploadSvg(Request $request, Media $media){
        $mediaKey = $request->svg;
        //dd($mediaKey);

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));
        //dd($folders);
        $path = explode(".",$id);

        $media->original_name = $id;
        $media->path = $folders.'/';
        $media->name = $path[0];
        $media->ext = '.'.$path[1];
        // $media->width = $image->width();
        // $media->height = $image->height();

        $media->save();

        return $media;
    }

    public function uploadVersionImage(Request $request, Media $media){
        $versions = json_decode($request->versions);
        $mediaKey = $request->image_upload;
        ///  test/companyAvatar.png

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders =substr($mediaKey, 0,strrpos($mediaKey, '/'));
        $path = explode(".",$id);
        //dd(public_path('/').$mediaKey);

        $image = Image::make(public_path('/').$mediaKey);

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
                    $image->save(public_path('/').$folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1], $value['q']);
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
            $media->ext = '.'.$content->guessClientExtension();
            $media->original_name = $name[1];
            $media->file_info = $content->getClientMimeType();
            $string = $path_name;
            $media->details = null;

            $string = explode('/', $string);
            array_pop($string);
            $string = implode('/', $string);
            $media->path = '/'.'uploads/'.$string.'/';


            $media->width = $size[0];
            $media->height = $size[1];
            $media->save();

    }



    public function crop_profile_image(Request $request)
    {
        //dd($request->all());
        $media = Media::find($request->media_id);
        //dd($media);
        if($media['details'] != null){
            //dd('has details');
            $arr = json_decode($media['details'], true);
            //dd($details);
            $details = [];
            if($arr != null || $arr != ''){
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

            //$details = json_encode($details);
            //dd($details);

            //Media::where('id', $request->media_id)->update(['details' => $details]);

            //find image with title+prof_image
            $name = explode('.', $media['original_name']);

            //replace first / from path
            $name1 = substr_replace($media['path'], "", 0, 1);
            //dd($name1);
            //dd($name1.$name[0].'-crop'.$media['ext']);
            if(file_exists($name1.$name[0].'-crop'.$media['ext'])){
                unlink($name1.$name[0].'-crop'.$media['ext']);
            }

            //save new crop image






        }else{
            $details = [];
            $details['x'] = $request->x;
            $details['y'] = $request->y;
            $details['width'] = $request->width;
            $details['height'] = $request->height;


            //save new image

        }

        $details = json_encode($details);

        Media::where('id', $request->media_id)->update(['details' => $details]);

        //dd(public_path($media['path'].$media['original_name'].$media['ext']));

        $image = Image::make(public_path($media['path'].$media['original_name']));

        $image->crop($request->width, $request->height, $request->x, $request->y);
        $name = explode('.', $media['original_name']);
        $image->save(public_path($media['path'].$name[0].'-crop'.$media['ext']), 80);
        //dd(public_path($media['path'].$name[0].'-crop'.$media['ext']));

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => asset($media['path'].$name[0].'-crop'.$media['ext']),
        ]);
    }

    public function crop_image(Request $request)
    {
        $media = Media::find($request->media_id);
        if($media['details'] != null){
            $details = json_decode($media['details'], true);

            $found = false;
            if(isset($details['img_align'])){
                foreach($details['img_align'] as $key => $value){
                    //dd($request->version);
                    if($key == $request->version){
                        $found = true;
                    }

                }
            }



            if($found){
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
            $arr = [];
            $arr['img_align'][$request->version]['x'] = $request->x;
            $arr['img_align'][$request->version]['y'] = $request->y;
            $arr['img_align'][$request->version]['width'] = $request->width;
            $arr['img_align'][$request->version]['height'] = $request->height;

            $details = json_encode($arr);

            Media::where('id', $request->media_id)->update(['details' => $details]);
        }



        $mediaKey = $request->path;
        //dd($request->all());
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
        //dd($mediaKey);
        $image = Image::make($mediaKey);
        $image->crop(intval($request->width),intval($request->height), intval($request->x), intval($request->y));
        if($request->version != 'profile_image'){
            $image->save(public_path('/uploads').$word.$path[0].'-'.$request->version.'.'.$path[1], 80);
            $data['version'] = $request->version;
        }else{
            $image->save(public_path('/uploads').$word.$path[0].'.'.$path[1], 80);
            $data['version'] = 'profile_image';
        }




        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $data,
        ]);
    }

    public function eventImage($id){
        $data['media'] = Media::find($id);
        //dd($data['media']);

        return view('layouts.media_versions', $data);
    }

    public function mediaImage(Request $request) {
        $data['medias'] = Media::where('original_name', $request->name)->first();


        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $data['medias']['id'],
        ]);
    }


}

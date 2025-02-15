<?php

namespace App\Http\Controllers;

use Alexusmai\LaravelFileManager\Events\Download;
use App\CMSFile;
use App\Jobs\SaveCMSFileWebp;
use App\Jobs\SaveImageWebp;
use App\Jobs\UploadImageConvertWebp;
use App\Jobs\UploadWebpImage;
use App\Model\Event;
use App\Model\Media;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class MediaController extends Controller
{
    public function index()
    {
        return view('admin.media2.index');
    }

    public function crop_file_manager_image(Request $request)
    {
        $path = $request->folder . $request->name;
        $image = Image::make(public_path('uploads') . $path);
        $image->crop(intval($request->width), intval($request->height), intval($request->x), intval($request->y));
        $image->save(public_path('uploads') . $path);

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $path,
        ]);
    }

    public function uploadSvg(Request $request, Media $media)
    {
        $mediaKey = $request->svg;
        //dd($mediaKey);

        $pos = strrpos($mediaKey, '/');
        $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

        $folders = substr($mediaKey, 0, strrpos($mediaKey, '/'));
        //dd($folders);
        $path = explode('.', $id);

        $media->original_name = $id;
        $media->path = $folders . '/';
        $media->name = $path[0];
        $media->ext = '.' . $path[1];
        // $media->width = $image->width();
        // $media->height = $image->height();

        $media->save();

        return $media;
    }

    public function uploadVersionImage(Request $request, Media $media)
    {
        //dd('test controller');
        // $versions = json_decode($request->versions);
        $mediaKey = $request->image_upload;

        //dd($mediaKey);

        if ($mediaKey != null) {
            $pos = strrpos($mediaKey, '/');
            $id = $pos === false ? $mediaKey : substr($mediaKey, $pos + 1);

            $folders = substr($mediaKey, 0, strrpos($mediaKey, '/'));
            $path = explode('.', $id);
            $image = Image::make(public_path('/') . $mediaKey);

            $media->original_name = $id;
            $media->path = $folders . '/';
            $media->name = $path[0];
            $media->ext = '.' . $path[1];
            $media->width = $image->width();
            $media->height = $image->height();
        } else {
            $media->original_name = null;
            $media->path = null;
            $media->name = null;
            $media->ext = null;
            $media->width = null;
            $media->height = null;
            $media->dpi = null;
            $media->details = null;
        }
        $media->save();

        // old code
        // foreach(get_image_versions() as $value){
        //     foreach($versions as $version){
        //         if($value['version'] == $version){

        //             if($image->width() > $image->height()){
        //                 $image->heighten($value['h'])->crop($value['w'], $value['h']);
        //             }elseif($image->width() < $image->height()){
        //                 $image->widen($value['w'])->crop($value['w'], $value['h']);
        //             }else{
        //                 $image->resize($value['w'], $value['h']);
        //             }

        //             $image->save(public_path('/').$folders.'/'.$path[0].'-'.$value['version'].'.'.$path[1], $value['q']);

        //             if ($image->mime == 'image/jpeg'){
        //                 $image1 = imagecreatefromjpeg(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']));

        //             }elseif($image->mime == 'image/gif'){
        //                 $image1 = imagecreatefromgif(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']));

        //             }elseif($image->mime == 'image/png'){
        //                 $image1 = imagecreatefrompng(public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']));
        //             }

        //             imagejpeg($image1, public_path($media['path'] .$media['name'].'-'.$value['version'] . $media['ext']), 40);
        //         }
        //     }
        // }

        return back()->withStatus(__('Image successfully uploaded.'))->withInput(['tab' => 'tabs-icons-text-2-tab']);
    }

    public function uploadProfileImage(Request $request, Media $media)
    {
        //parse old image
        $old_image = $media;
        //delete old image
        if ($old_image['name'] != null) {
            if (file_exists('uploads/profile_user/' . $old_image['original_name'])) {
                unlink('uploads/profile_user/' . $old_image['original_name']);
            }
        }

        $content = $request->file('photo');
        $name1 = explode('.', $content->getClientOriginalName());

        $path_name = $request->photo->store('profile_user', 'public');

        $name = explode('profile_user/', $path_name);
        //$size = getimagesize('uploads/'.$path_name);
        $media->name = $name1[0];
        $media->ext = '.' . $content->guessClientExtension();
        $media->original_name = $name[1];
        $media->file_info = $content->getClientMimeType();
        $string = $path_name;
        $media->details = null;

        $string = explode('/', $string);
        array_pop($string);
        $string = implode('/', $string);
        $media->path = '/' . 'uploads/' . $string . '/';

        // $media->width = $size[0];
        // $media->height = $size[1];
        $media->save();
    }

    public function crop_profile_image(Request $request)
    {
        $media = CMSFile::findOrFail($request->media_id);
        $media_parent = CMSFile::findOrFail($media->parent_id);

        $media->crop_data = [
            'width_offset' => $request->x,
            'height_offset' => $request->y,
            'crop_width' => $request->width,
            'crop_height' => $request->height,
        ];
        $media->save();

        $image = Image::make(public_path($media_parent->full_path));
        $image->crop($request->width, $request->height, $request->x, $request->y);
        $image->save(public_path($media->full_path), 60);

        dispatch((new SaveCMSFileWebp($media->id))->delay(now()->addSeconds(1)));

        return response()->json([
            'success' => __('Already image cropped.'),
            // 'data' => asset($media['path'] . $name[0] . '-crop' . $media['ext']),
            'profile_image' => CMSFile::findOrFail($request->media_id),
            'original_profile_image' => CMSFile::findOrFail($media->parent_id),
        ]);
    }

    public function crop_image(Request $request)
    {
        $media = Media::find($request->media_id);
        if ($media['details'] != null) {
            $details = $media['details'];

            $found = false;
            if (isset($details['img_align'])) {
                foreach ($details['img_align'] as $key => $value) {
                    //dd($request->version);
                    if ($key == $request->version) {
                        $found = true;
                    }
                }
            }

            if ($found) {
                $details['img_align'][$request->version]['x'] = $request->x;
                $details['img_align'][$request->version]['y'] = $request->y;
                $details['img_align'][$request->version]['width'] = $request->width;
                $details['img_align'][$request->version]['height'] = $request->height;
                $details['img_align'][$request->version]['slug'] = $request->version;
            } else {
                $details['img_align'][$request->version]['x'] = $request->x;
                $details['img_align'][$request->version]['y'] = $request->y;
                $details['img_align'][$request->version]['width'] = $request->width;
                $details['img_align'][$request->version]['height'] = $request->height;
                $details['img_align'][$request->version]['slug'] = $request->version;
            }

            $details = json_encode($details);

            Media::where('id', $request->media_id)->update(['details' => $details]);

        //dd($details);
        } else {
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

        $folders = substr($mediaKey, 0, strrpos($mediaKey, '/'));
        $path = explode('.', $id);

        $name = $path[0];
        $ext = $path[1];

        $folders = explode('/', $folders);
        //dd($folders);
        $word = '';
        foreach ($folders as $key => $folder) {
            //dd($folder);
            if ($key >= 4) {
                $word = $word . '/' . $folder . '/';
            }
        }

        $mediaKey = file_get_contents($mediaKey, false, stream_context_create([
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]));

        $image = Image::make($mediaKey);
        $image->crop(intval($request->width), intval($request->height), intval($request->x), intval($request->y));
        foreach (get_image_versions() as $key => $ver) {
            if ($ver['version'] == $request->version) {
                if ($image->width() > $image->height()) {
                    $image->heighten($ver['h']);
                } elseif ($image->width() < $image->height()) {
                    $image->widen($ver['w']);
                } else {
                    $image->resize($ver['w'], $ver['h']);
                }
            }
        }
        if ($request->version != 'profile_image') {
            $image->save(public_path('uploads/') . $word . $path[0] . '-' . $request->version . '.' . $path[1], 80);
            $data['version'] = $request->version;

            if ($image->mime == 'image/jpeg') {
                $image1 = imagecreatefromjpeg(public_path('uploads/') . $word . $path[0] . '-' . $request->version . '.' . $path[1]);
            } elseif ($image->mime == 'image/gif') {
                $image1 = imagecreatefromgif(public_path('uploads/') . $word . $path[0] . '-' . $request->version . '.' . $path[1]);
            } elseif ($image->mime == 'image/png') {
                $image1 = imagecreatefrompng(public_path('uploads/') . $word . $path[0] . '-' . $request->version . '.' . $path[1]);
            }
            //$image->save(public_path('uploads/').$word.$path[0].'.'.$path[1], 80);
            imagejpeg($image1, public_path('uploads/') . $word . $path[0] . '-' . $request->version . '.' . $path[1], 40);
        } else {
            $image->save(public_path('/uploads') . $word . $path[0] . '.' . $path[1], 80);
            $data['version'] = 'profile_image';
        }

        return response()->json([
            'success' => __('Already image cropped.'),
            'data' => $data,
        ]);
    }

    public function eventImage($id)
    {
        $data['media'] = Media::find($id);

        return view('layouts.media_versions', $data);
    }

    public function mediaImage(Request $request)
    {
        $data['medias'] = Media::where('original_name', $request->name)->first();
        if ($data['medias'] != null) {
            $id = $data['medias']['id'];
            $message = __('Already Fetch image.');
        } else {
            $id = null;
            $message = __('Image does not exist in datatable');
        }

        return response()->json([
            'success' => $message,
            'data' => $id,
        ]);
    }
}

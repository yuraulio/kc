<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class Media2Controller extends Controller
{
    public function index()
    {

        return view('admin.media2.index');
    }

    public function crop_image(Request $request)
    {
        
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

<?php

namespace Library;

class UploadHelperLib
{
    public function testUpload($request)
    {
        if ($request->file("uploadfile")->isValid()) {
            $img_name = $request->file("uploadfile")->getClientOriginalName();
            $img_path = public_path('uploads/'.$img_name);
            $dest_path = public_path('resizes/'.$img_name);

            $request->file("uploadfile")->move("uploads", $img_name);

            return ['success', $this->resizeCrop($img_path, $dest_path, 300, 200)];
        } else {
            return ['failure'];
        }
    }

    public function resizeImage($img_path, $dest_path, $width, $height)
    {
        $img = \Image::make($img_path)->resize($width, $height)->save($dest_path, 80);

        return 'resized';
    }

    public function resizeCrop($img_path, $dest_path, $width, $height)
    {
        $img = \Image::make($img_path)->fit($width, $height)->save($dest_path, 80);

        return 'resized_cropped';
    }

    /*
    public function makePng($img_path)
    {
        $img = \Image::make($img_path)->encode('png', 75);
    }
    */
}

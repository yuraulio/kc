<?php

namespace Library;

class ImageCropLib
{
    public function createCroppedImg($indata = array())
    {
        $imgPath = $this->localPathToUrl($indata['src']);

        if ($imgPath) {
            $imgName = $this->urlToFileName($indata['src']);
            if ($imgName) {
                $imgNameTime = time().'-'.$imgName;
                $destPath = base_path('public/crop/'.$imgNameTime);
                $destUrl = \URL::to('/crop/'.$imgNameTime);
                $img = \Image::make($imgPath)->crop($indata['width'], $indata['height'], $indata['x'], $indata['y'])->save($destPath, 80);
                return $destUrl;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function localPathToUrl($url = '', $inject_path = 'public')
    {
        $tmp_path = '';

        if ($url != '') {
            if (str_contains($url, \URL::to('/'))) {
                if ($inject_path) {
                    $tmp_path = str_replace(\URL::to('/'), base_path($inject_path), $url);
                } else {
                    $tmp_path = str_replace(\URL::to('/'), base_path(), $url);
                }
                return $tmp_path;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function urlToFileName($str = '')
    {
        if ($str) {
            $url_parts = explode("/", $str);
            $str_arr = explode(".", end($url_parts));
            $num = count($str_arr);
            if ($num >= 2) {
                return $str_arr[$num-2].'.'.$str_arr[$num-1];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

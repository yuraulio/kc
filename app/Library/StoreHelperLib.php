<?php

namespace Library;

use Config;
use PostRider\Store;

//use Library\BackendHelperLib;

class StoreHelperLib
{
    public function __construct()
    {}

    /**
     * Uploads files on store
     *
     */
    public function uploadFiles($store_id = 0, $request_obj)
    {
        if ($request_obj->hasFile('image') && $request_obj->file('image')->isValid()) {
            $this->storeFileCleanUp($store_id, 'image');
            $this->doFileUpload($store_id, 'image', $request_obj->file('image'));
        }
    }

    public function storeFileCleanUp($store_id, $file_field = '')
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.store_upload_options.settings.default_uploads');
        $store_dets = Store::where('id', $store_id)->first()->toArray();
        if ($store_dets[$file_field]) {
            if (file_exists($path_to_subfolder.$this->subFolderID($store_id).'/'.$store_dets[$file_field])) {
                unlink($path_to_subfolder.$this->subFolderID($store_id).'/'.$store_dets[$file_field]);
            }
            //perhaps empty cache
            Store::where('id', $store_id)->update([$file_field => '']);
        }
    }

    public function subFolderID($store_id = 0, $path_to_subfolder = 'uploads/stores/originals/')
    {
        $images_under_folder = Config::get('dpoptions.store_upload_options.settings.images_under_folder');
        if ($images_under_folder > 0) {


        $subfolder_id = intval(floor($store_id / $images_under_folder) + 1);


        return $subfolder_id;
        }
    }

    public function doFileUpload($store_id = 0, $file_field, $file)
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.store_upload_options.settings.default_uploads');

        $media["original_name"] = $file->getClientOriginalName();
        $media["file_info"] = $file->getClientMimeType();
        $media["size"] = $file->getClientSize()/1024; // this converts it to kB
        $media["path"] = $this->subFolderID($store_id);

        $name_slug_ext = $this->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

        $media["name"] = $name_slug_ext["name"];
        $media["ext"] = '.'.$name_slug_ext["ext"];
        $media["name_ext"] = $name_slug_ext["name_ext"];

        $file->move($path_to_subfolder.$media["path"].'/', $media["name_ext"]);
        Store::where('id', $store_id)->update([$file_field => $media["name_ext"]]);
        //dd($media);
    }

    public function prepareUniqueFilename($orig_name = '', $path_to_subfolder = 'uploads/originals/1/')
    {
        $path_parts = pathinfo($orig_name);
        $res['ext'] = $path_parts['extension'];
        $res['slug'] = str_slug($path_parts['filename'], '-');

        $end = 0;
        $filename = $res['slug'];
        $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
        $res['iter'] = 1;

        while ($end == 0) {
            if (file_exists($check_filepath)) {
                $filename = $res['slug'].'-'.str_random(5);
                $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
                $res['iter']++;
            } else {
                $end = 1;
                break;
            }
        }

        $res['name'] = $filename;
        $res['name_ext'] = $res['name'].'.'.$res['ext'];

        return $res;
    }

    public function imgUrl($store_id = 0, $img_str = '')
    {
        if (($store_id != 0) && (strlen($img_str))) {
            return \URL::to("/store-img").'/'.$this->subFolderID($store_id).'/'.$img_str;
        } else {
            return '';
        }
    }
}

<?php

use App\Model\Slug;
use App\Model\Media;
use Illuminate\Support\Str;
use App\Model\Menu;

function get_split_image_path($path)
    {
        $part = [];

        $pos = strrpos($path, '/');
        $id = $pos === false ? $path : substr($path, $pos + 1);
        $folder =substr($path, 0,strrpos($path, '/'));
        $path = explode(".",$id);

        $part['folder'] = $folder;
        $part['filename'] = $path[0];
        $part['ext'] = $path[1];

        return $part;
    }

if(!function_exists('get_image_versions')){

    function get_image_versions($ver = 'versions')
    {
        $versions = isset(config('image_versions')[$ver]) ? config('image_versions')[$ver] : [];
        return $versions;
    }

}

if (!function_exists('get_templates')) {

    function get_templates($model = 'pages')
    {
        $templates = isset(config('templates')[$model]) ? config('templates')[$model] : [];
        return $templates;
    }
}

if (!function_exists('check_for_slug')) {

    function check_for_slug($slug)
    {

        $slugConv = Str::slug($slug);
        if(!Slug::where('slug',$slugConv)->first()){
            return $slugConv;
        }

        $index = 0;
        while(Slug::where('slug',$slugConv)->first()){
            $index += 1;
            $slugConv = Str::slug($slug . '-' . $index);
        }

        return $slugConv;

    }
}

if (!function_exists('get_status_by_slug')){

    function get_status_by_slug($slug){

        $slug = Slug::where('slug',$slug)->first();

        if($slug){
            return $slug->slugable->published;
        }

        return false;

    }

}

if (!function_exists('get_processor_config')){
    function get_processor_config($processor_id){
        $available_processors = config('processors')['processors'];
        $processor_config = [];
        if (!empty($available_processors)) {
            foreach ($available_processors as $key => $row) {

                if (intval($key) == intval($processor_id)) {
                    $processor_config = $row;
                    break;
                }
            }
        }
        return $processor_config;
    }
}

if (!function_exists('cdn')){
    function cdn($asset) {
        // Verify if CDN URLs are present in the config file
        if (!Config::get('cdn_setup.cdn')) {
            return asset($asset);
        }

        // Get file name incl extension and CDN URLs
        $cdns = Config::get('cdn_setup.cdn');
        $assetName = basename($asset);

        // Remove query string
        $assetName = explode("?", $assetName);
        $assetName = $assetName[0];

        // Select the CDN URL based on the extension
        foreach ($cdns as $cdn => $types) {
            if (preg_match('/^.*\.(' . $types . ')$/i', $assetName)) {
                return cdnPath($cdn, $asset);
            }
        }

        // In case of no match use the last in the array
        end($cdns);
        return cdnPath(key($cdns), $asset);
    }
}

if (!function_exists('cdnPath')){
    function cdnPath($cdn, $asset) {
        $parseRes = parse_url($asset);
        if ($parseRes) {
            if (isset($parseRes['query'])) {
                return  "//" . rtrim($cdn, "/") . "/" . ltrim($parseRes['path'].'?'.$parseRes['query'], "/");
            } else {
                return  "//" . rtrim($cdn, "/") . "/" . ltrim($parseRes['path'], "/");
            }
        } else {
            return  "//" . rtrim($cdn, "/") . "/" . ltrim($asset, "/");
        }
    }
}


if (!function_exists('get_image')){
    function get_image($media, $version = null) {
        if(!$version){
            return isset($media['original_name']) ? $media['path'] . $media['original_name']  : '';
        }

        return isset($media['name']) ? $media['path']  . $media['name'] . '-' . $version . $media['ext'] : '';
    }
}

if (!function_exists('get_header')){
    function get_header() {
        $menus = Menu::where('name', 'Header')->get()->toArray();
        $result = array();
        foreach ($menus as $key => $element) {



            $model = app($element['menuable_type']);
            //dd($model::with('slugable')->find($element['menuable_id']));

            $element['data'] = $model::with('slugable')->find($element['menuable_id']);
            $result[$element['name']][] = $element;
            //dd($element);

        }
        return $result;
    }
}

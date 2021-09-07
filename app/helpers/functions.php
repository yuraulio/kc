<?php

use App\Model\Slug;
use App\Model\Option;
use App\Model\Media;
use Illuminate\Support\Str;
use App\Model\Menu;
use App\Model\Exam;

function get_social_media(){
    $social_media = Option::where('name', 'social_media')->get();
    $social_media = json_decode($social_media[0]['settings'], true);

    return $social_media;

}

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

        if(isset($slug) && (get_class($slug->slugable) == 'App\\Model\\Pages' || get_class($slug->slugable) == 'App\\Model\\Event')){
            return $slug->slugable->published;
        }

        return true;

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

if (!function_exists('get_profile_image')){
    function get_profile_image($media) {



        if(isset($media['original_name']) && $media['original_name']!=''){

            $name = explode('.', $media['original_name']);
            $path = ltrim($media['path'] . $name[0] . '-crop.'. $name[1], $media['path'][0]);


            if(file_exists($path)){

                return $media['path'] . $name[0] . '-crop.'. $name[1];
            }else{
                return $media['path'] . $media['original_name'];
            }

        }


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
            $result['menu'][$element['name']][] = $element;

            if($element['menuable_id'] == 143){
                $result['elearning_card'] = $element;
            }

            //dd($element);

        }
        return $result;

    }
}

if(!function_exists('total_graduate')){
    function total_graduate() {

        $sum = 0;
        $exams = Exam::with('results')->get()->toArray();
        foreach($exams as $key => $item){
            $success_percent = $item['q_limit'];
            foreach($item['results'] as $res){
                $total_score = $res['total_score'];
                $score = $res['score'];
                $percent = ($score/$total_score) * 100;

                if($percent >= $success_percent){
                    $sum++;
                }
            }
        }

        return $sum;
    }
}

if(!function_exists('group_by')){
    function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }
}

if(!function_exists('unique_multidim_array')){
    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}

if(!function_exists('formatBytes')){
    function formatBytes($bytes) {
        if ($bytes > 0) {
            $i = floor(log($bytes) / log(1024));
            $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
        } else {
            return 0;
        }
    }
}



<?php

use App\Model\Slug;
use Illuminate\Support\Str;

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

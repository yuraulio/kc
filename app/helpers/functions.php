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

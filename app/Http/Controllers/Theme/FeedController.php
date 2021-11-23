<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sitemap;
use URL;
use App\Model\Slug;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->baseUrl = $base_url = URL::to('/').'/';
        
    }

    public function index($feedType = null)
    {
        return $this->generateSitemap();
    }

    public function generateSitemap()
    {

    
        $custom = [
            ['loc' => $this->baseUrl, 'lastmod' => '', 'priority' => '', 'changefreq' => ''],
        ];

        if (!empty($custom)) {
            foreach ($custom as $key => $row) {
                Sitemap::addTag($row['loc'], $row['lastmod'], $row['priority'], $row['changefreq']);
            }
        }

        $slugs = Slug::all();

        foreach($slugs as $slug){
            
            if(!$slug->slugable){
                continue;
            }else if(get_class($slug->slugable) == 'App\Model\Event' && !$slug->slugable->category->first()) {
                continue;
            }

            if(!get_status_by_slug($slug->slug)){
                continue;
            }
            Sitemap::addTag($this->baseUrl.$slug->slug, $slug->slugable->updated_at, 'daily', '0.8');

            
        }
        
        return Sitemap::render();
        //echo Sitemap::render();
    }
}

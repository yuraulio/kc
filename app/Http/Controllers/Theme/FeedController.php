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
            
        }
        
        return Sitemap::render();
        //echo Sitemap::render();
    }
}

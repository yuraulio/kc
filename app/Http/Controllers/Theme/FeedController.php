<?php

namespace App\Http\Controllers\Theme;

use URL;
use Sitemap;
use App\Model\Slug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use BinshopsBlog\Models\BinshopsLanguage;
use BinshopsBlog\Models\BinshopsPostTranslation;

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

        //$avoidModels = ['App\Model\Type','App\Model\Partner'];
        $nonAvoidModels = ['App\Model\Instructor','App\Model\Event','App\Model\Category'];
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
            }else if(!in_array(get_class($slug->slugable),$nonAvoidModels)){
                continue;
            
            }else if(get_class($slug->slugable) == 'App\Model\Event' && (!$slug->slugable->category->first() || $slug->slugable->status != 0)) {
                continue;
            }

            if(!get_status_by_slug($slug->slug)){
                continue;
            }
            Sitemap::addTag($this->baseUrl.$slug->slug, $slug->slugable->updated_at, 'daily', '0.8');


        }

        $posts = BinshopsPostTranslation::whereLangId(BinshopsLanguage::whereLocale('en')->pluck('id'))->get();

        foreach($posts as $post){

            if(!$post->slug) {
                continue;
            }

            if(!get_status_by_slug($post->slug)){
                continue;
            }
            Sitemap::addTag($this->baseUrl . 'en/blog/' . $post->slug, $post->updated_at, 'daily', '0.8');


        }

        
        echo Sitemap::render();
        //return Sitemap::render();
        //echo Sitemap::render();
    }
}

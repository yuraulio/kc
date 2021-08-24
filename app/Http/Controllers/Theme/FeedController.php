<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __construct(LanguageHelperLib $langHelp)
    {
        $this->baseUrl = $base_url = URL::to('/').'/';
        $this->langHelp = $langHelp;
    }

    public function index($feedType = null)
    {
        switch ($feedType) {
            case 'sitemap':
                return $this->generateSitemap();
                break;
            default:
                return $this->generateSitemap();
                break;
        }
    }

    public function generateSitemap()
    {
        $website_id = $this->langHelp->defWebsite;

        //dd(SlugLookup::count());
        // Content (post, pages, galleries, videos)
        /*
        echo '<pre>';
        SlugLookup::where('type', 0)->orderBy('updated_at','desc')->groupBy('link_id')->chunk(200, function($entries)
        {
            foreach ($entries as $row)
            {
                print_r($row->toArray());
            }
        });
        echo '</pre>';
        */

        // Content (categories and tags)
        /*
        echo '<pre>';
        $query = SlugLookup::where('type', 1)->orderBy('updated_at','desc')->groupBy('link_id')->whereHas('allowedCatType', function ($query) {
            $query->whereIn('type', [0,1]);
        });

        $query->chunk(200, function($entries) {
            foreach ($entries as $row)
            {
                print_r($row->toArray());
            }
        });
        echo '</pre>';
        */
        $custom = [
            ['loc' => $this->baseUrl, 'lastmod' => '', 'priority' => '', 'changefreq' => ''],
        ];

        if (!empty($custom)) {
            foreach ($custom as $key => $row) {
                Sitemap::addTag($row['loc'], $row['lastmod'], $row['priority'], $row['changefreq']);
            }
        }

        //print_r($custom);
        /*
        $staticPages = Config::get('dpoptions.static_pages.settings');
        if ($staticPages) {
            if (isset($staticPages[$website_id])) {
                foreach ($staticPages[$website_id] as $key => $row) {
                    Sitemap::addTag($this->baseUrl.$row['loc'], $row['lastmod'], $row['priority'], $row['changefreq']);
                }
            }
        }
        */

        //print_r($staticPages); topics 1, qnas 16, organisers 5,
        //1 Topics, 15 Featured, 5 Organisers, 16 QnAs, 9 Location, 12 Event type, 22 event cat
        $exCategories = [1,5,9,12,15,16,22];

        $topics = Category::where('parent_id', '=', 1)->pluck('id')->toArray();
        //2,3,4
        $qnas = Category::where('parent_id', '=', 16)->pluck('id')->toArray();
        //17,18,19,20,21
        $organisers = Category::where('parent_id', '=', 5)->pluck('id')->toArray();
        //6,7,8
        $result = [];

        $result = array_merge($result, $exCategories);
        $result = array_merge($result, $topics);
        $result = array_merge($result, $qnas);
        $result = array_merge($result, $organisers);

        //$result = $topics + $qnas + $organisers + $exCategories;

        //$result = array_merge($result, [$topics, $qnas, $organisers, $exCategories]);

        //$result = array_merge($result, $organisers);

       // $result = array_merge($topics, $exCategories);

        //dd($result);




        Category::whereIn('type', [0,1])->where('status', 1)->whereNotIn('id', $result)->select('id','slug','updated_at')->chunk(200, function($entries) {
            foreach ($entries as $row)
            {
                Sitemap::addTag($this->baseUrl.$row->slug, $row->updated_at, 'daily', '0.8');
            }
        });

        Content::whereIn('type', [1])->where('status', 1)->select('id','slug','updated_at')->chunk(200, function($entries) {
            foreach ($entries as $row)
            {
                Sitemap::addTag($this->baseUrl.$row->slug, $row->updated_at, 'daily', '0.8');
            }
        });

        $contentTypes = Config::get('dpoptions.content_types');

        if ($contentTypes) {
            foreach ($contentTypes as $abbr => $row) {
                $settings = $row['settings'];
                if (isset($settings['on_sitemap']) && $settings['on_sitemap'] && isset($settings['uri_part'])) {
                    Content::whereIn('type', [$row['id']])->where('status', 1)->select('id','slug','updated_at')->chunk(200, function($entries) use ($settings) {
                        foreach ($entries as $row)
                        {
                            Sitemap::addTag($this->baseUrl.$settings['uri_part'].$row->slug, $row->updated_at, 'daily', '0.8');
                        }
                    });
                }
            }
        }

        return Sitemap::render();
        //echo Sitemap::render();
    }
}

<?php

namespace Library;

use Config;
use URL;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;

use PostRider\Category;
use PostRider\Content;
use PostRider\SlugLookup;

use Library\FrontendHelperLib;
use Library\LanguageHelperLib;
use PostRider\Option;

class MetaGeneratorLib
{
    public function __construct(FrontendHelperLib $frontHelp, LanguageHelperLib $langHelp)
    {
        $this->def_site_name = 'KnowCrunch - Learn. Transform. Thrive.';
        $this->def_generator = '';
        $this->def_author = 'knowcrunch.com';
        $this->def_title = '';
        $this->def_content = '';
        $this->def_description = '';
        $this->def_keywords = '';
        $this->def_image = ''; // url path to the default project image

        $this->def_fb_locale = '';
        $this->def_fb_type = '';
        $this->def_fb_app_id = '378574919191027';
        $this->def_fb_admins = '';

        $this->def_tw_card = '';
        $this->def_tw_site = '';
        $this->def_tw_creator = '';
        $this->def_tw_domain = '';

        $websiteMetaDefaults = Config::get('dpoptions.website_details');

        if ($websiteMetaDefaults) {
            $this->static_defaults = $websiteMetaDefaults['settings']['meta_defaults'];
            //$this->static_pages_meta = $websiteMetaDefaults['settings']['websites'];
            $this->static_pages_meta = [];
        } else {
            $this->static_defaults = [];
            $this->static_pages_meta = [];
        }

        if ($this->static_defaults) {
            $this->def_site_name = $this->static_defaults['site_name'];
            $this->def_generator = $this->static_defaults['generator'];
            $this->def_author = $this->static_defaults['author'];
            $this->def_title = $this->static_defaults['title'];
            $this->def_content = $this->static_defaults['content'];
            $this->def_description = $this->static_defaults['description'];
            $this->def_keywords = $this->static_defaults['keywords'];
            $this->def_image = $this->static_defaults['image'];

            $this->def_fb_locale = $this->static_defaults['fb_locale'];
            $this->def_fb_type = $this->static_defaults['fb_type'];
            $this->def_fb_app_id = $this->static_defaults['fb_app_id'];
            $this->def_fb_admins = $this->static_defaults['fb_admins'];

            $this->def_tw_card = $this->static_defaults['tw_card'];
            $this->def_tw_site = $this->static_defaults['tw_site'];
            $this->def_tw_creator = $this->static_defaults['tw_creator'];
            $this->def_tw_domain = $this->static_defaults['tw_domain'];
        }

        $this->meta = [
            "site_name" => $this->def_site_name,
            "generator" => $this->def_generator,
            "author" => $this->def_author,
            "title" => $this->def_title,
            "content" => $this->def_content,
            "description" => $this->def_description,
            "keywords" => $this->def_keywords,
            "image" => $this->def_image,
            "fb_locale" => $this->def_fb_locale,
            "fb_type" => $this->def_fb_type,
            "fb_app_id" => $this->def_fb_app_id,
            "fb_admins" => $this->def_fb_admins,
            "tw_card" => $this->def_tw_card,
            "tw_site" => $this->def_tw_site,
            "tw_creator" => $this->def_tw_creator,
            "tw_domain" => $this->def_tw_domain
        ];

        //we also have to define the list of favicons
        $this->frontHelp = $frontHelp;
        $this->langHelp = $langHelp;
    }

    public function perWebsiteDefaults($lang = null, $website_id = null)
    {
        $lang = (is_null($lang) ? $_ENV['LANG'] : $lang);
        $website_id = (is_null($website_id) ? $_ENV['WEBSITE'] : $website_id);

        $websiteMeta = Config::get('dpoptions.websites');

        if (isset($websiteMeta['settings']['website_details'])) {
            $website_details = $websiteMeta['settings']['website_details'];
            if (isset($website_details[$website_id]) && isset($website_details[$website_id]['langs']) && isset($website_details[$website_id]['langs'][$lang])) {
                $meta_defaults = $website_details[$website_id]['langs'][$lang]['meta_defaults'];
                foreach ($meta_defaults as $field => $value) {
                    if ($value) {
                        $tmp = 'def_'.$field;
                        if (isset($this->{$tmp})) {
                            $this->{$tmp} = $value;
                        }
                        if (isset($this->meta[$tmp])) {
                            $this->meta[$tmp] = $value;
                        }
                    }
                }
            }
        }
    }

    public function create($lang = 'el', $meta_type = null, $data = array(), $adata = array())
    {

        if (is_object($data)) {
            $data = $data->toArray();
        }

        if (is_object($adata)) {
            $adata = $adata->toArray();
        }

        //dd($data);

        $meta = array();

        if (empty($data)) {
            switch ($meta_type) {
                case "home":
                    $meta['header_code'] = $this->prepare_header_home_meta($lang, $data);
                    break;
                case "news-stream":
                    $meta['header_code'] = $this->prepare_header_news_stream_meta($lang, $data);
                    break;
                case "static-page":
                    $meta['header_code'] = $this->prepare_header_static_pages_meta($lang, $data);
                    break;
                default:
                    $meta['header_code'] = $this->prepare_header_default_meta($lang, $data);
                    break;
            }
        } else {
            switch ($meta_type) {
                case "post":
                    $meta['header_code'] = $this->prepare_header_post_meta($lang, $data);
                    break;
                case "blog":
                    $meta['header_code'] = $this->prepare_header_blog_meta($lang, $data, $adata);
                    break;
                case "page":
                    $meta['header_code'] = $this->prepare_header_page_meta($lang, $data);
                    break;
                case "category":
                    $meta['header_code'] = $this->prepare_header_category_meta($lang, $data);
                    break;
                case "static-page":
                    $meta['header_code'] = $this->prepare_header_static_pages_meta($lang, $data);
                    break;
                default:
                    $meta['header_code'] = $this->prepare_header_default_meta($lang, $data);
                    break;
            }
        }

        //other operations
        $meta['body_code'] = '';

        return $meta;
    }

    public function prepare_header_default_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = $this->def_title;
        $meta['description'] = $this->def_content;
        $meta['keywords'] = $this->def_keywords;
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        return $this->header_meta_markup($lang, null, $meta);
    }

    public function prepare_header_home_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = 'Knowcrunch';
        $meta['description'] = 'Knowcrunch';
        $meta['keywords'] = 'Knowcrunch';
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        return $this->header_meta_markup($lang, null, $meta);
    }

    public function prepare_header_news_stream_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = 'News - Knowcrunch';
        $meta['description'] = 'Knowcrunch';
        $meta['keywords'] = 'Knowcrunch';
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        return $this->header_meta_markup($lang, null, $meta);
    }

    public function prepare_header_static_pages_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = $this->def_title;
        $meta['description'] = $this->def_description;
        $meta['keywords'] = $this->def_keywords;
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        if (isset($data['static_meta'])) {
            if (isset($data['website_id'])) {
                $website_id = $data['website_id'];
            } else {
                $website_id = $this->langHelp->defWebsite();
            }

            $routedSlugMeta = $this->routedSlugMeta($data['static_meta']);

            if ($routedSlugMeta) {
                if (isset($routedSlugMeta['title'])) {
                    $meta['title'] = $routedSlugMeta['title'];
                }
                if (isset($routedSlugMeta['description'])) {
                    $meta['description'] = $routedSlugMeta['description'];
                }
                if (isset($routedSlugMeta['keywords'])) {
                    $meta['keywords'] = $routedSlugMeta['keywords'];
                }
                if (isset($routedSlugMeta['image'])) {
                    $meta['image'] = $routedSlugMeta['image'];
                }
            } elseif (isset($this->static_pages_meta[$website_id]) && isset($this->static_pages_meta[$website_id][$data['static_meta']])) {
                $staticMeta = $this->static_pages_meta[$website_id][$data['static_meta']];
                if ($staticMeta['title']) {
                    $meta['title'] = $staticMeta['title'];
                }
                if ($staticMeta['description']) {
                    $meta['description'] = $staticMeta['description'];
                }
                if ($staticMeta['keywords']) {
                    $meta['keywords'] = $staticMeta['keywords'];
                }
                if ($staticMeta['image']) {
                    $meta['image'] = $staticMeta['image'];
                }
            }
        }


        $meta['title'] = 'hello';
       $metaOptions = Option::where('id',38)->first();
       if($metaOptions){
        $metaOptions = json_decode($metaOptions->settings);
    //    dd( $metaOptions->get->home->meta->description);
        $meta['title'] = $metaOptions->get->home->meta->title;
        $meta['keywords'] = $metaOptions->get->home->meta->keywords;
        $meta['description'] = $metaOptions->get->home->meta->description;
        $meta['image'] = $metaOptions->get->home->meta->image;
       }
       
        return $this->header_meta_markup($lang, null, $meta);
    }

    public function routedSlugMeta($slug = null, $lang = null, $website = null)
    {
        $dpRoutesSlugs = Config::get('dproutes_slugs');
     //   dd($dpRoutesSlugs);
        $res = [];
        if (is_null($slug)) {
            $segment = '!undefined!';
        } else {
            $segment = $slug;
        }
        if (is_null($lang)) {
            $lang = $_ENV['LANG'];
        }
        if (is_null($website)) {
            $website = $_ENV['WEBSITE'];
        }

        if ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website][$segment]) && isset($dpRoutesSlugs[$website][$segment][$lang])) {
            if (is_array($dpRoutesSlugs[$website][$segment][$lang]) && isset($dpRoutesSlugs[$website][$segment][$lang]['meta'])) {
                $res = $dpRoutesSlugs[$website][$segment][$lang]['meta'];
            }
        } elseif ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website]["get#".$segment]) && isset($dpRoutesSlugs[$website]["get#".$segment][$lang])) {
            if (is_array($dpRoutesSlugs[$website]["get#".$segment][$lang]) && isset($dpRoutesSlugs[$website]["get#".$segment][$lang]['meta'])) {
                $res = $dpRoutesSlugs[$website]["get#".$segment][$lang]['meta'];
            }
        } elseif ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website]["post#".$segment]) && isset($dpRoutesSlugs[$website]["post#".$segment][$lang])) {
            if (is_array($dpRoutesSlugs[$website]["post#".$segment][$lang]) && isset($dpRoutesSlugs[$website]["post#".$segment][$lang]['meta'])) {
                $res = $dpRoutesSlugs[$website]["post#".$segment][$lang]['meta'];
            }
        } else {
            $res = [];
        }

        return $res;
    }

    public function prepare_header_post_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = $data['meta_title'];
        $meta['description'] = $data['meta_description'];
        $meta['keywords'] = $data['meta_keywords'];
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        if (!strlen($meta['title'])) {
            $meta['title'] = $data['title'];
        }

        if (isset($data['type']) && ($data['type'] !== 4)) {
            if (!strlen($meta['description'])) {
                $meta['description'] = $this->frontHelp->truncateOnSpace($data['body'], 300);
            }
        }

        if (isset($data['featured'])) {
            $meta['image'] = $this->frontHelp->pImg($data);
        } else {
            $meta['image'] = $this->def_image;
        }

        return $this->header_meta_markup($lang, "post", $meta);
    }

    public function prepare_header_blog_meta($lang = 'el', $data = array(), $adata = array())
    {
        $meta = array();
        $meta['title'] = $data['meta_title'];
        $meta['description'] = $data['meta_description'];
        $meta['keywords'] = $data['meta_keywords'];
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        if (!strlen($meta['title'])) {
            $meta['title'] = $data['title'];
        }

        if (isset($data['type']) && ($data['type'] !== 4)) {
            if (!strlen($meta['description'])) {
                $meta['description'] = $this->frontHelp->truncateOnSpace($data['body'], 300);
            }
        }

        if (isset($adata['columnist'])) {
            if (is_object($adata['columnist'])) {
                $adata['columnist'] = $adata['columnist']->toArray();
            }
        }

        if (isset($adata['columnist']) && strlen($adata['columnist']['image'])) {
            $meta['image'] = URL::to("/").'/columnist-img/default/'.$adata['columnist']['image'];
        } elseif (isset($data['featured'])) {
            $meta['image'] = $this->frontHelp->pImg($data);
        } else {
            $meta['image'] = $this->def_image;
        }

        return $this->header_meta_markup($lang, "blog", $meta);
    }

    public function prepare_header_page_meta($lang = 'el', $data = array())
    {
        $meta = array();
        $meta['title'] = $data['meta_title'];
        $meta['description'] = $data['meta_description'];
        $meta['keywords'] = $data['meta_keywords'];
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        if (!strlen($meta['title'])) {
            $meta['title'] = $data['title'];
        }

        if (!strlen($meta['description'])) {
            $meta['description'] = $this->frontHelp->truncateOnSpace($data['body'], 300);
        }

        if (isset($data['featured']) && !empty($data['featured']) && isset($data['featured']['media']) && !empty($data['featured']['media'])) {
            $meta['image'] = $this->frontHelp->pImg($data);
        } else {
            $meta['image'] = $this->def_image;
        }

        return $this->header_meta_markup($lang, "page", $meta);
    }

    public function prepare_header_category_meta($lang = 'el', $data = array(), $adata = array())
    {
        $meta = array();
        $meta['title'] = $data['meta_title'];
        $meta['description'] = $data['meta_description'];
        $meta['keywords'] = $data['meta_keywords'];
        $meta['url'] = $this->full_url();
        $meta['image'] = $this->def_image;

        if (!strlen($meta['title'])) {
            $meta['title'] = $data['name'];
        }

        if (!strlen($meta['description'])) {
            $meta['description'] = $this->frontHelp->truncateOnSpace($data['description'], 300);
        }

        if (isset($data['primary_image']) && (strlen($data['primary_image']) > 3)) {
            $meta['image'] = URL::to('/').'/'.$data['primary_image'];
        } else if (isset($data['secondary_image']) && (strlen($data['secondary_image']) > 3)) {
            $meta['image'] = URL::to('/').'/'.$data['secondary_image'];
        } else {
            $meta['image'] = $this->def_image;
        }

        return $this->header_meta_markup($lang, "category", $meta);
    }

    public function default_meta_values($meta = array())
    {
        $new_data = array();
        $new_data['meta']['url'] = $meta['url'];
        $new_data['meta']['canonical_url'] = $meta['url'];
        /*
        if (substr($meta['url'], -1) != "/") {
            $new_data['meta']['canonical_url'] = $meta['url'].'/';
        } else {
            $new_data['meta']['canonical_url'] = $meta['url'];
        }
        */
        $new_data['meta']['title'] = $meta['title'];
        $new_data['meta']['keywords'] = $meta['keywords'];
        $new_data['meta']['description'] = $meta['description'];
        $new_data['meta']['generator'] = $this->def_generator;
        $new_data['meta']['author'] = $this->def_author;

        return $new_data;
    }

    public function facebook_meta_values($meta = array())
    {
        $new_data = array();
        $new_data['fb']['app_id'] = $this->def_fb_app_id;
        $new_data['fb']['admins'] = $this->def_fb_admins;

        return $new_data;
    }

    public function open_graph_meta_values($meta = array())
    {
        $new_data = array();
        $new_data['og']['locale'] = $this->def_fb_locale;
        $new_data['og']['title'] = $meta['title'];
        $new_data['og']['type'] = 'website';
        $new_data['og']['url'] = $this->full_url();
        $new_data['og']['image'] = $meta['image'];
        $new_data['og']['site_name'] = $this->def_site_name;
        $new_data['og']['description'] = $meta['description'];

        //this means we have product meta
        if (isset($meta['price']))
        {
            $new_data['og']['type'] = 'product';
        }

        return $new_data;
    }

    public function twitter_meta_values($meta = array())
    {
        $new_data = array();
        $new_data['twitter']['card'] = "summary";
        $new_data['twitter']['site'] = $this->def_tw_site;
        $new_data['twitter']['title'] = $meta['title'];
        $new_data['twitter']['description'] = $meta['description'];
        $new_data['twitter']['creator'] = $this->def_tw_creator;
        $new_data['twitter']['image']['src'] = $meta['image'];
        $new_data['twitter']['domain'] = $this->def_tw_domain;

        //this means we also have product meta
        if (isset($meta['price']))
        {
            $new_data['twitter']['card'] = "product";

            $new_data['twitter']['data1'] = $meta['currency_symbol'].$meta['price'];
            $new_data['twitter']['label1'] = "Price";
            $new_data['twitter']['data2'] = $meta['location'];
            $new_data['twitter']['label2'] = "Location";
        }

        return $new_data;
    }

    public function header_meta_markup($lang = 'el', $meta_type = null, $meta = array())
    {
        $data = array();
        $html = '<!--The Meta-->';

        if (is_null($meta_type))
        {
            //load default simple tpl
            $data['type'] = NULL;
            $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

            $html .= $this->load_meta("default", $lang, $data);
            $html .= $this->load_meta("facebook", $lang, $data);
            $html .= $this->load_meta("twitter", $lang, $data);
        }
        else
        {
            switch ($meta_type)
            {
                case "post":
                    $data['type'] = "post";
                    $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

                    $html .= $this->load_meta("default", $lang, $data);
                    $html .= $this->load_meta("facebook", $lang, $data);
                    $html .= $this->load_meta("twitter", $lang, $data);
                    break;
                case "blog":
                    $data['type'] = "blog";
                    $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

                    $html .= $this->load_meta("default", $lang, $data);
                    $html .= $this->load_meta("facebook", $lang, $data);
                    $html .= $this->load_meta("twitter", $lang, $data);
                    break;
                case "page":
                    $data['type'] = "page";
                    $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

                    $html .= $this->load_meta("default", $lang, $data);
                    $html .= $this->load_meta("facebook", $lang, $data);
                    $html .= $this->load_meta("twitter", $lang, $data);
                    break;
                case "category":
                    $data['type'] = "category";
                    $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

                    $html .= $this->load_meta("default", $lang, $data);
                    $html .= $this->load_meta("facebook", $lang, $data);
                    $html .= $this->load_meta("twitter", $lang, $data);
                    break;
                default:
                    //load default simple tpl
                    $data['type'] = NULL;
                    $data = array_merge($data, $this->default_meta_values($meta), $this->facebook_meta_values($meta), $this->open_graph_meta_values($meta), $this->twitter_meta_values($meta));

                    $html .= $this->load_meta("default", $lang, $data);
                    $html .= $this->load_meta("facebook", $lang, $data);
                    $html .= $this->load_meta("twitter", $lang, $data);
                    break;
            }
        }

        $html .= '<!--End of Meta-->';

        return $html;
    }

    public function load_meta($provider = NULL, $lang = 'el', $data = array())
    {
        $mhtml = '';

        if (!is_null($provider)) {
            switch ($data['type']) {
                case "post":
                    $mhtml = view('meta_tpls.'.$provider.'.page_tpl', $data)->render();
                    break;
                case "blog":
                    $mhtml = view('meta_tpls.'.$provider.'.page_tpl', $data)->render();
                    break;
                case "page";
                    $mhtml = view('meta_tpls.'.$provider.'.page_tpl', $data)->render();
                    break;
                case "category";
                    $mhtml = view('meta_tpls.'.$provider.'.page_tpl', $data)->render();
                    break;
                default:
                    //$mhtml = view('meta_tpls.default.page_tpl', $data)->render();
                    $mhtml = view('meta_tpls.'.$provider.'.page_tpl', $data)->render();
                    break;
            }
        }

        return $mhtml;
    }

    public function full_url()
    {
        return \Request::url();
    }


    /*
     * New methods
     */
    public function generate($lang = null, $website_id = null, $content = array(), $adata = array())
    {
        $lang = (is_null($lang) ? $_ENV['LANG'] : $lang);
        $website_id = (is_null($website_id) ? $_ENV['WEBSITE'] : $website_id);
        $content = (is_object($content) ? $content->toArray() : $content);
        $adata = (is_object($adata) ? $adata->toArray() : $adata);

        $this->perWebsiteDefaults($lang, $website_id);
        $meta = $this->meta;
        $meta['url'] = $this->full_url();

        if ($content) {
            //var_dump($content);
            // try to extract meta from content
            if (isset($content['meta_title']) && $content['meta_title']) {
                $meta['title'] = $content['meta_title'];
            } elseif (isset($content['title']) && $content['title']) {
                $meta['title'] = $content['title'];
            } elseif (isset($content['name']) && $content['name']) {
                $meta['title'] = $content['name'];
            } elseif (isset($content['subtitle']) && $content['subtitle']) {
                $meta['title'] = $content['subtitle'];
            } else {
                $meta['title'] = $this->def_title;
            }

            if (isset($content['meta_keywords']) && $content['meta_keywords']) {
                $meta['keywords'] = $content['meta_keywords'];
            } else {
                $meta['keywords'] = $this->def_keywords;
            }

            if (isset($content['meta_description']) && $content['meta_description']) {
                $meta['description'] = $content['meta_description'];
            } elseif (isset($content['body']) && $content['body']) {
                $meta['description'] = $this->frontHelp->truncateOnSpace($content['body'], 300);
            } elseif (isset($content['description']) && $content['description']) {
                $meta['description'] = $this->frontHelp->truncateOnSpace($content['description'], 300);
            } elseif (isset($content['summary']) && $content['summary']) {
                $meta['description'] = $this->frontHelp->truncateOnSpace($content['summary'], 300);
            } else {
                $meta['description'] = $this->def_description;
            }

            if (isset($content['featured']) && $content['featured']) {
                $meta['image'] = $this->frontHelp->pImg($content);
            } elseif (isset($content['banner']) && $content['banner']) {
                $meta['image'] = $this->frontHelp->cImg($content, 'banner');
            } elseif (isset($content['all_media']) && $content['all_media']) {
                $meta['image'] = $this->frontHelp->cImg($content, 'all_media');
            } else {
                $meta['image'] = $this->def_image;
            }

            $res = $this->header_meta_markup($lang, null, $meta);
            //$res['header_code'] = $this->header_meta_markup($lang, null, $meta);
        } else {

            // try to extract meta from the current url, use the last uri part

            /*$segment = \Request::segment(1);
            $dpRoutes = Config::get('dproutes');*/

            $segments = \Request::segments();

            if (count($segments)==0) {
                $segment = \Request::segment(1);
            } else {
                $segment = $segments[count($segments)-1];
            }
            $dpRoutes = Config::get('dproutes');

            if ($segment && isset($dpRoutes['get']) && isset($dpRoutes['get'][$segment]) && isset($dpRoutes['get'][$segment]['meta']) && $dpRoutes['get'][$segment]['meta']) {
                if (isset($dpRoutes['get'][$segment]['meta']['title']) && $dpRoutes['get'][$segment]['meta']['title']) {
                    $meta['title'] = $dpRoutes['get'][$segment]['meta']['title'];
                }
                if (isset($dpRoutes['get'][$segment]['meta']['keywords']) && $dpRoutes['get'][$segment]['meta']['keywords']) {
                    $meta['keywords'] = $dpRoutes['get'][$segment]['meta']['keywords'];
                }
                if (isset($dpRoutes['get'][$segment]['meta']['description']) && $dpRoutes['get'][$segment]['meta']['description']) {
                    $meta['description'] = $dpRoutes['get'][$segment]['meta']['description'];
                }
                if (isset($dpRoutes['get'][$segment]['meta']['image']) && $dpRoutes['get'][$segment]['meta']['image']) {
                    $meta['image'] = $dpRoutes['get'][$segment]['meta']['image'];
                }

                $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
            } elseif ($segment && isset($dpRoutes['post']) && isset($dpRoutes['post'][$segment]) && isset($dpRoutes['post'][$segment]['meta']) && $dpRoutes['post'][$segment]['meta']) {
                if (isset($dpRoutes['post'][$segment]['meta']['title']) && $dpRoutes['post'][$segment]['meta']['title']) {
                    $meta['title'] = $dpRoutes['post'][$segment]['meta']['title'];
                }
                if (isset($dpRoutes['post'][$segment]['meta']['keywords']) && $dpRoutes['post'][$segment]['meta']['keywords']) {
                    $meta['keywords'] = $dpRoutes['post'][$segment]['meta']['keywords'];
                }
                if (isset($dpRoutes['post'][$segment]['meta']['description']) && $dpRoutes['post'][$segment]['meta']['description']) {
                    $meta['description'] = $dpRoutes['post'][$segment]['meta']['description'];
                }
                if (isset($dpRoutes['post'][$segment]['meta']['image']) && $dpRoutes['post'][$segment]['meta']['image']) {
                    $meta['image'] = $dpRoutes['post'][$segment]['meta']['image'];
                }

                $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
            } else {
                if ($segment) {
                    $dpRoutesSlugsInv = Config::get('dproutes_slugs_inverse');
                    if (!empty($dpRoutesSlugsInv) && isset($dpRoutesSlugsInv[$segment]) && !isset($dpRoutesSlugsInv[$segment]['static']) && isset($dpRoutesSlugsInv[$segment]['meta']) && $dpRoutesSlugsInv[$segment]['meta']) {

                        if (isset($dpRoutesSlugsInv[$segment]['meta']['title']) && $dpRoutesSlugsInv[$segment]['meta']['title']) {
                            $meta['title'] = $dpRoutesSlugsInv[$segment]['meta']['title'];
                        }
                        if (isset($dpRoutesSlugsInv[$segment]['meta']['keywords']) && $dpRoutesSlugsInv[$segment]['meta']['keywords']) {
                            $meta['keywords'] = $dpRoutesSlugsInv[$segment]['meta']['keywords'];
                        }
                        if (isset($dpRoutesSlugsInv[$segment]['meta']['description']) && $dpRoutesSlugsInv[$segment]['meta']['description']) {
                            $meta['description'] = $dpRoutesSlugsInv[$segment]['meta']['description'];
                        }
                        if (isset($dpRoutesSlugsInv[$segment]['meta']['image']) && $dpRoutesSlugsInv[$segment]['meta']['image']) {
                            $meta['image'] = $dpRoutesSlugsInv[$segment]['meta']['image'];
                        }

                        $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
                    } else {
                        $slug_lookup_dets = SlugLookup::slugBelongsTo($segment)->select('link_id','type')->first();
                        if (!empty($slug_lookup_dets)) {
                            switch ($slug_lookup_dets->type) {
                                case 0:
                                    $content = Content::select('id','lang','website_id','title','meta_title','meta_keywords','meta_description')->with('featured.media')->where('id', $slug_lookup_dets->link_id)->first();
                                    break;
                                case 1:
                                    $content = Category::select('id','lang','website_id','name','meta_title','meta_keywords','meta_description')->with('banner.media','allMedia.media')->where('id', $slug_lookup_dets->link_id)->first();
                                    break;
                                default:
                                    $content = null;
                                    break;
                            }

                            if (!is_null($content)) {
                                $lang = $content->lang;
                                $website = $content->website_id;
                                $res['header_code'] = $this->generate($lang, $website_id, $content);
                            } else {
                                $meta['title'] = $this->def_title;
                                $meta['keywords'] = $this->def_keywords;
                                $meta['description'] = $this->def_description;
                                $meta['image'] = $this->def_image;

                                $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
                            }
                        } else {
                            $meta['title'] = $this->def_title;
                            $meta['keywords'] = $this->def_keywords;
                            $meta['description'] = $this->def_description;
                            $meta['image'] = $this->def_image;

                            $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
                        }
                    }
                } else {
                    $meta['title'] = $this->def_title;
                    $meta['keywords'] = $this->def_keywords;
                    $meta['description'] = $this->def_description;
                    $meta['image'] = $this->def_image;

                    $res['header_code'] = $this->header_meta_markup($lang, null, $meta);
                }
            }
        }

        //$res['body_code'] = '';
        return $res;
    }
}

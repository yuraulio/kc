<?php

namespace Library;

use Config;
use File;

use PostRider\Option;
use PostRider\Content;
use Library\LanguageHelperLib;

class OptionsHelperLib
{
    public function __construct(LanguageHelperLib $langHelper)
    {
        $this->langHelp = $langHelper;
    }

    public function fetchOption()
    {
        return "option fetched";
    }

    public function optionTypes()
    {
        $otypes = array(
            /*'post_type'     => "Post Type",*/
            'banner'   => "Banner",
            'block'    => "Block",
            'image_versions' => "Image Versions",
            /*'meta_type'     => "Meta Type",*/
            'system'        => "System",
            'config'        => "Config",
            'setup'         => "Setup",
            );

        return $otypes;
    }

    public function optionGroups()
    {
        $groups = Option::SelectGroups()->get();
        $ogroups = ["no_group" => "no_group"];

        if (!empty($groups)) {
            foreach ($groups as $key => $value) {
                if (!isset($ogroups[$value['group']])) {
                    $ogroups[$value['group']] = $value['group'];
                }
            }
        }

        return $ogroups;
    }

    /**
     * Get all config options
     *
     * Creates a config file named dpoptions based on the defined options
     * that belong to this config. The various options are stored inside
     * a group => array("name" => )
     */
    public function exportToConfig()
    {
        $config_options = Option::ofType("config")->ConfigOrderBy()->get()->toArray();
        $custom_config = [];

        foreach ($config_options as $key => $row) {
            if (strlen($row['group']) && ($row['group'] !== "no_group")) {
                if (!isset($custom_config[$row['group']])) {
                    $custom_config[$row['group']] = [];
                    $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                } else {
                    if (!isset($custom_config[$row['group']][$row['name']])) {
                        $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                    }
                }
            } else {
                if (!isset($custom_config[$row['name']])) {
                    $custom_config[$row['name']] = $this->parseConfigOptionToArray($row);
                }
            }
        }

        $config_export = "<?php \n\nreturn ";
        $config_export .= var_export($custom_config, true);
        $config_export .= ";\n";

        $file =  base_path().'/config/dpoptions.php';
        $bytes_written = File::put($file, $config_export);

        if ($bytes_written === false) {
            die("Error writing to file");
        }

        // export banners

        $config_options = Option::ofType("banner")->ConfigOrderBy()->get()->toArray();
        $custom_config = [];

        foreach ($config_options as $key => $row) {
            if (strlen($row['group']) && ($row['group'] !== "no_group")) {
                if (!isset($custom_config[$row['group']])) {
                    $custom_config[$row['group']] = [];
                    $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                } else {
                    if (!isset($custom_config[$row['group']][$row['name']])) {
                        $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                    }
                }
            } else {
                if (!isset($custom_config[$row['name']])) {
                    $custom_config[$row['name']] = $this->parseConfigOptionToArray($row);
                }
            }
        }

        $config_export = "<?php \n\nreturn ";
        $config_export .= var_export($custom_config, true);
        $config_export .= ";\n";

        $file =  base_path().'/config/dpbanners.php';
        $bytes_written = File::put($file, $config_export);

        if ($bytes_written === false) {
            die("Error writing to file");
        }

        // export blocks

        $config_options = Option::ofType("block")->ConfigOrderBy()->get()->toArray();
        $custom_config = [];

        foreach ($config_options as $key => $row) {
            if (strlen($row['group']) && ($row['group'] !== "no_group")) {
                if (!isset($custom_config[$row['group']])) {
                    $custom_config[$row['group']] = [];
                    $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                } else {
                    if (!isset($custom_config[$row['group']][$row['name']])) {
                        $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                    }
                }
            } else {
                if (!isset($custom_config[$row['name']])) {
                    $custom_config[$row['name']] = $this->parseConfigOptionToArray($row);
                }
            }
        }

        $config_export = "<?php \n\nreturn ";
        $config_export .= var_export($custom_config, true);
        $config_export .= ";\n";

        $file =  base_path().'/config/dpblocks.php';
        $bytes_written = File::put($file, $config_export);

        if ($bytes_written === false) {
            die("Error writing to file");
        }

        // export project images

        $config_options = Option::ofType("image_versions")->ConfigOrderBy()->get()->toArray();
        $custom_config = [];

        foreach ($config_options as $key => $row) {
            if (strlen($row['group']) && ($row['group'] !== "no_group")) {
                if (!isset($custom_config[$row['group']])) {
                    $custom_config[$row['group']] = [];
                    $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                } else {
                    if (!isset($custom_config[$row['group']][$row['name']])) {
                        $custom_config[$row['group']][$row['name']] = $this->parseConfigOptionToArray($row);
                    }
                }
            } else {
                if (!isset($custom_config[$row['name']])) {
                    $custom_config[$row['name']] = $this->parseConfigOptionToArray($row);
                }
            }
        }

        $config_export = "<?php \n\nreturn ";
        $config_export .= var_export($custom_config, true);
        $config_export .= ";\n";

        $file =  base_path().'/config/dpimage_versions.php';
        $bytes_written = File::put($file, $config_export);

        if ($bytes_written === false) {
            die("Error writing to file");
        }

        $this->gererateDpRoutes();

        return $bytes_written;
        //return var_export($custom_config, true);
    }

    public function parseConfigOptionToArray($option = array())
    {
        $option_arr = [];
        $excluded = ["priority", "group", "type", "created_at", "updated_at", "name", "parent_id"];

        if (!empty($option)) {
            foreach ($option as $key => $value) {
                if (!in_array($key, $excluded)) {
                    if ($key == "settings") {
                        $option_arr[$key] = $this->decodeJsonStr($value);
                    } else {
                        $option_arr[$key] = $value;
                    }
                }
            }
        }

        return $option_arr;
    }

    public function decodeJsonStr($str = '')
    {
        if (is_array($str)) {
            return $str;
        } else {
            $settingsArr = json_decode(trim($str), true);
            if (json_last_error()) {
                $settingsArr = json_decode($this->fixDecodeError($str), true);
            }
            return $settingsArr;
        }
    }

    /**
     * Remove hidden chars
     */
    public function fixDecodeError($str)
    {
        /*
        for ($i = 0; $i <= 31; ++$i) {
            $str = str_replace(chr($i), "", $str);
        }
        $str = str_replace(chr(127), "", $str);

        if (0 === strpos(bin2hex($str), 'efbbbf')) {
           $str = substr($str, 3);
        }
        */


        /*
        if (0 === strpos(bin2hex($str), 'efbbbf')) {
            $str = substr($str, 3);
        }
        */

        $str = stripslashes($str);

        return $str;
    }

    public function contentStaticRoutes($content)
    {
        $dpoptions = Config::get('dpoptions.content_types');
        if ($dpoptions) {
            $dp_options_abbr = array_pluck($dpoptions, 'abbr', 'id');
            if (isset($dp_options_abbr[$content->type]) && ($dp_options_abbr[$content->type] == "static_page")) {
                $this->generateDpStaticRoutesSlugs();
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /*
     * Query all static page content and append it to dproutes slugs
     * this way will allow the developer to use the abbr as groupping element during page build
     */
    public function generateDpStaticRoutesSlugs()
    {
        $ctype = Option::select('id','abbr','name')->where('abbr','static_page')->first();
        $content = Content::where('type', $ctype->id)->where('status', 1)->select('id','type','abbr','slug','lang','website_id','group_id','view_tpl','title','meta_title','meta_keywords','meta_description')->orderBy('website_id', 'asc')->orderBy('lang', 'asc')->get();
        /*
        $route_slugs = $this->reloadModifiedRouteConfigs('config/dproutes_slugs.php');
        $groupped_export_inv = $this->reloadModifiedRouteConfigs('config/dproutes_slugs_inverse.php');
        */
        $route_slugs = Config::get('dproutes_slugs');
        $groupped_export_inv = Config::get('dproutes_slugs_inverse');

        if ($content) {
            //$staticTpls = $this->reloadModifiedRouteConfigs('config/dpoptions.php');
            $staticTpls = Config::get('dpoptions');

            if ($staticTpls && isset($staticTpls['static_templates']) && isset($staticTpls['static_templates']['settings']) && $staticTpls['static_templates']['settings']) {
                $staticTpls = $staticTpls['static_templates']['settings'];
            } else {
                $staticTpls = [];
            }
            foreach ($content as $key => $row) {
                $addFakeRoute = 1;
                if (isset($route_slugs[$row->website_id]) && isset($route_slugs[$row->website_id][$row->abbr]) && isset($route_slugs[$row->website_id][$row->abbr][$row->lang])) {
                    //$addFakeRoute = 0;
                }
                if (($addFakeRoute) && ($row->abbr)) {
                    if (isset($staticTpls[$row->view_tpl])) {
                        $view_tpl = $staticTpls[$row->view_tpl]['views']['frontend'];
                    } else {
                        $view_tpl = '';
                    }

                    $title = ((strlen($row->meta_title)) ? $row->meta_title : $row->title);

                    $route_slugs[$row->website_id][$row->abbr][$row->lang] = [
                        'slug' => $row->slug,
                        'view' => $view_tpl,
                        'static' => 1,
                        'meta' => [
                          'title' => $title,
                          'keywords' => $row->meta_keywords,
                          'description' => $row->meta_description,
                        ]
                    ];

                    $groupped_export_inv[$row->slug] = array(
                        'slug' => $row->slug,
                        'view' => $view_tpl,
                        'static' => 1,
                        'meta' => [
                          'title' => $title,
                          'keywords' => $row->meta_keywords,
                          'description' => $row->meta_description,
                        ],
                        'lastmod' => '',
                        'priority' => '',
                        'changefreq' => '',
                        'website' => $row->website_id,
                        'lang' => $row->lang,
                    );
                }
            }
        }

        if ($route_slugs) {
            $config_export = "<?php \n\nreturn ";
            $config_export .= var_export($route_slugs, true);
            $config_export .= ";\n";
        } else {
            $config_export = "<?php \n\nreturn ";
            $config_export .= var_export([], true);
            $config_export .= ";\n";
        }

        $file =  base_path().'/config/dproutes_slugs.php';
        $bytes_written = File::put($file, $config_export);

        if ($bytes_written === false) {
            die("Error writing to file");
        } else {
            if ($groupped_export_inv) {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export($groupped_export_inv, true);
                $config_export .= ";\n";
            } else {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export([], true);
                $config_export .= ";\n";
            }
            $file =  base_path().'/config/dproutes_slugs_inverse.php';
            $bytes_written = File::put($file, $config_export);
        }
    }

    /*
     * Since configs are cached, we need to load the actual php files and not the cached versions to append
     * the static page metas
     */
    public function reloadModifiedRouteConfigs($path_to_config = '')
    {
        if ($path_to_config) {
            $settings = include base_path($path_to_config);
            return $settings;
        } else {
            return [];
        }
    }

    /*
     * Gather content types and generate a routes config array
     *
     */
    public function gererateDpRoutes()
    {
        $custom_config = [];
        $route_lookup = Option::where(["type" => "config", "abbr" => "route_lookup"])->first();
        $slug_lookup = Option::where(["type" => "config", "abbr" => "slug_lookup"])->first();
        $langs = $this->langHelp->activeLangsFull();

        if ($route_lookup) {
            $route_lookup = $route_lookup->toArray();
            $tmp = $this->parseConfigOptionToArray($route_lookup);

            if (isset($tmp['settings']['get']) && !empty($tmp['settings']['get'])) {
                foreach ($tmp['settings']['get'] as $prefix => $route) {

                    if (strlen($route['uses']) > 3) {
                        $uses = $route['uses'];
                    } else {
                        $uses = "Theme\\HomeController@index";
                    }

                    $custom_config['get'][$prefix] = [
                        "prefix" => $route['prefix'],
                        "lang" => $route['lang'],
                        "website" => $route['website'],
                        "middleware" => (is_array($route['middleware']) ? $route['middleware'] : ""),
                        "uses" => $uses,
                        "group" => $route['group'],
                        "has_meta" => (isset($route['has_meta']) ? $route['has_meta'] : 0),
                        "name" => (isset($route['name']) ? $route['name'] : ""),
                    ];

                    if (isset($route['view'])) {
                        $custom_config['get'][$prefix]['view'] = $route['view'];
                    } else {
                        //$custom_config['get'][$prefix]['view'] = '';
                    }

                    if (isset($route['meta'])) {
                        $custom_config['get'][$prefix]['meta'] = $route['meta'];
                    } else {
                        //$custom_config['get'][$prefix]['meta'] = '';
                    }
                }
            }

            if (isset($tmp['settings']['post']) && !empty($tmp['settings']['post'])) {
                foreach ($tmp['settings']['post'] as $prefix => $route) {

                    if (strlen($route['uses']) > 3) {
                        $uses = $route['uses'];
                    } else {
                        $uses = "Theme\\HomeController@index";
                    }

                    $custom_config['post'][$prefix] = [
                        "prefix" => $route['prefix'],
                        "lang" => $route['lang'],
                        "website" => $route['website'],
                        "middleware" => (is_array($route['middleware']) ? $route['middleware'] : ""),
                        "uses" => $uses,
                        "group" => $route['group'],
                        "has_meta" => (isset($route['has_meta']) ? $route['has_meta'] : 0),
                        "name" => (isset($route['name']) ? $route['name'] : ""),
                    ];

                    if (isset($route['view'])) {
                        $custom_config['post'][$prefix]['view'] = $route['view'];
                    } else {
                        //$custom_config['post'][$prefix]['view'] = '';
                    }

                    if (isset($route['meta'])) {
                        $custom_config['post'][$prefix]['meta'] = $route['meta'];
                    } else {
                        //$custom_config['post'][$prefix]['meta'] = '';
                    }
                }
            }
        } else {
            foreach ($langs as $lang => $lrow) {
                $custom_config['get']['home-'.$lang] = [
                    "prefix" => 'home-'.$lang,
                    "lang" => $lang,
                    "website" => 1,
                    "middleware" => "",
                    "uses" => "Theme\\HomeController@index",
                    "group" => "home",
                    "has_meta" => 0,
                    "name" => "",
                ];
            }
        }

        if ($custom_config) {
            $config_export = "<?php \n\nreturn ";
            $config_export .= var_export($custom_config, true);
            $config_export .= ";\n";
        } else {
            $config_export = "<?php \n\nreturn ";
            $config_export .= var_export([], true);
            $config_export .= ";\n";
        }
        $file =  base_path().'/config/dproutes.php';
        $bytes_written = File::put($file, $config_export);

        $groupped_export = [];

        if ($slug_lookup) {
            $slug_lookup = $slug_lookup->toArray();
            $tmp = $this->parseConfigOptionToArray($slug_lookup);
            if ($tmp['settings'] && is_array($tmp['settings'])) {
                foreach ($tmp['settings'] as $website => $groups) {
                    $groupped_export[$website] = $groups;
                }
            }
        }

        $ctype = Option::select('id','abbr','name')->where('abbr','static_page')->first();
        $content = Content::where('type', $ctype->id)->where('status', 1)->select('id','type','abbr','slug','lang','website_id','group_id','view_tpl','title','meta_title','meta_keywords','meta_description')->orderBy('website_id', 'asc')->orderBy('lang', 'asc')->get();

        if ($content) {
            $staticTplsOpt = Option::where('abbr','static_templates')->first();

            if ($staticTplsOpt) {
                $staticTpls = $this->decodeJsonStr($staticTplsOpt->settings);
            } else {
                $staticTpls = [];
            }

            foreach ($content as $key => $row) {
                $addFakeRoute = 1;
                if (isset($route_slugs[$row->website_id]) && isset($route_slugs[$row->website_id][$row->abbr]) && isset($route_slugs[$row->website_id][$row->abbr][$row->lang])) {
                    //$addFakeRoute = 0;
                }
                if (($addFakeRoute) && ($row->abbr)) {
                    if (isset($staticTpls[$row->view_tpl])) {
                        $view_tpl = $staticTpls[$row->view_tpl]['views']['frontend'];
                    } else {
                        $view_tpl = '';
                    }

                    $title = ((strlen($row->meta_title)) ? $row->meta_title : $row->title);

                    $groupped_export[$row->website_id][$row->abbr][$row->lang] = [
                        'slug' => $row->slug,
                        'view' => $view_tpl,
                        'static' => 1,
                        'meta' => [
                          'title' => $title,
                          'keywords' => $row->meta_keywords,
                          'description' => $row->meta_description,
                        ]
                    ];

                    /*
                    $groupped_export_inv[$row->slug] = array(
                        'slug' => $row->slug,
                        'view' => $view_tpl,
                        'static' => 1,
                        'meta' => [
                          'title' => $title,
                          'keywords' => $row->meta_keywords,
                          'description' => $row->meta_description,
                        ],
                        'lastmod' => '',
                        'priority' => '',
                        'changefreq' => '',
                        'website' => $row->website_id,
                        'lang' => $row->lang,
                    );
                    */
                }
            }
        }

        if ($groupped_export) {
            $groupped_export_inv = [];

            foreach ($groupped_export as $website => $groups) {
                foreach ($groups as $group => $details) {
                    foreach ($details as $lang => $slug) {
                        if (is_array($slug)) {
                            $extOpts = $slug;
                            if (isset($extOpts['slug'])) {
                                $slug = $extOpts['slug'];
                            } else {
                                $slug = $group.'-'.$lang;
                            }
                            foreach ($extOpts as $opt => $val) {
                                if ($opt == "meta") {
                                    $groupped_export_inv[$slug][$opt] = $val;
                                } else {
                                    $groupped_export_inv[$slug][$opt] = $val;
                                }
                            }
                            $groupped_export_inv[$slug]["website"] = $website;
                            $groupped_export_inv[$slug]["lang"] = $lang;
                        } else {
                            $groupped_export_inv[$slug] = [
                                "website" => $website,
                                "lang" => $lang,
                            ];
                        }
                    }
                }
            }

            if ($groupped_export_inv) {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export($groupped_export_inv, true);
                $config_export .= ";\n";
            } else {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export([], true);
                $config_export .= ";\n";
            }
            $file =  base_path().'/config/dproutes_slugs_inverse.php';
            $bytes_written = File::put($file, $config_export);

            if ($custom_config) {
                foreach ($custom_config as $type => $prefixes) {
                    foreach ($prefixes as $prefix => $route) {
                        if (isset($route['view']) || isset($route['meta'])) {
                            $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']]['slug'] = $route['prefix'];

                            if (isset($route['view'])) {
                                $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']]['view'] = $route['view'];
                            } else {
                                $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']]['view'] = "";
                            }

                            if (isset($route['meta'])) {
                                $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']]['meta'] = $route['meta'];
                            } else {
                                $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']]['meta'] = "";
                            }
                        } else {
                            $groupped_export[$route['website']][$type.'#'.$route['group']][$route['lang']] = $route['prefix'];
                        }
                    }
                }
            }

            if ($groupped_export) {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export($groupped_export, true);
                $config_export .= ";\n";
            } else {
                $config_export = "<?php \n\nreturn ";
                $config_export .= var_export([], true);
                $config_export .= ";\n";
            }
            $file =  base_path().'/config/dproutes_slugs.php';
            $bytes_written = File::put($file, $config_export);
        } else {
            $config_export = "<?php \n\nreturn ";
            $config_export .= var_export([], true);
            $config_export .= ";\n";

            $file =  base_path().'/config/dproutes_slugs_inverse.php';
            $bytes_written = File::put($file, $config_export);

            $file =  base_path().'/config/dproutes_slugs.php';
            $bytes_written = File::put($file, $config_export);
        }

        if ($bytes_written === false) {
            die("Error writing to file");
        }
    }

    /*
     * Organize and get the meta-able entries of the route and slug lookup options
     * @op_type : "routes", "rotues_slugs"
     * @has_meta : 1
     * returns
     * array()
     */
    public function getMetaRoutesSlugs($op_type = "routes", $has_meta = 1)
    {
        $res = [];
        $meta_def = [
            "title" => "",
            "keywords" => "",
            "description" => "",
            "image" => ""
        ];

        switch ($op_type) {
            case 'routes':
                $dproutes = $this->reloadModifiedRouteConfigs('config/dproutes.php');
                if ($dproutes) {
                    foreach ($dproutes as $operation => $routes) {
                        foreach ($routes as $route => $row) {
                            if (isset($row['has_meta']) && ($row['has_meta'] == $has_meta) && isset($row['name'])) {
                                if (!isset($row['meta'])) {
                                    $meta = $meta_def;
                                } else {
                                    $meta = array_merge($meta_def, $row['meta']);
                                }
                                $res[] = [
                                    "website" => $row['website'],
                                    "lang" => $row['lang'],
                                    "type" => "routes",
                                    "operation" => $operation,
                                    "route" => $row['prefix'],
                                    "name" => $row['name'],
                                    "meta" => $meta,
                                ];
                            }
                        }
                    }
                }
                break;
            case 'routes_slugs':
                $dproutes_slugs = $this->reloadModifiedRouteConfigs('config/dproutes_slugs.php');
                if ($dproutes_slugs) {
                    foreach ($dproutes_slugs as $website => $routes) {
                        foreach ($routes as $group => $langs) {
                            foreach ($langs as $lang => $row) {
                                if (isset($row['has_meta']) && ($row['has_meta'] == $has_meta) && isset($row['name'])) {
                                    if (!isset($row['meta'])) {
                                        $meta = $meta_def;
                                    } else {
                                        $meta = array_merge($meta_def, $row['meta']);
                                    }
                                    $res[] = [
                                        "website" => $website,
                                        "lang" => $lang,
                                        "type" => "routes_slugs",
                                        "operation" => "def",
                                        "route" => $group,
                                        "name" => $row['name'],
                                        "meta" => $meta,
                                    ];
                                }
                            }
                        }
                    }
                }
                break;
            default:
                # code...
                break;
        }

        return $res;
    }
}

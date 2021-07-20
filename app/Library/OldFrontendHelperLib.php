<?php

namespace Library;

use Illuminate\Support\Facades\Facade;

use Carbon;
use Config;
use Request;
use URL;

use PostRider\Category;
use PostRider\CategoryContent;
use PostRider\Content;
use PostRider\IntraContentLinks;
use PostRider\FeaturedMedia;
use PostRider\Media;
use PostRider\SlugLookup;
use PostRider\User;

use Library\CategoryHelperLib;
use Library\CroppingLib;
use Library\CustomFieldHelperLib;

use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

class OldFrontendHelperLib
{
    public function __construct(CategoryHelperLib $categoryHelper, CroppingLib $croppingLib, CustomFieldHelperLib $customFieldHelper)
    {
        $this->categoryHelper = $categoryHelper;
        $this->croppingLib = $croppingLib;
        $this->cFieldLib = $customFieldHelper;
    }

    /**
     * Used with data returned from CategoryContent model
     * returns the usable image (either alternative of featured image of the content)
     *
     */
    public function pImg($data = array(), $version = 'default')
    {
        $data = $this->castAsArray($data);

        $src = \URL::to("/portal-img").'/'.$version.'/';
        if (!empty($data)) {
            if (isset($data['alternative']) && !empty($data['alternative'])) {
                $img_path = $data['alternative']['path'];
                $img_name = $data['alternative']['name'].$data['alternative']['ext'];
                $src .= $img_path.'/'.$img_name;
                $src .= $this->imgAlignUrl($data['alternative'], $version);
            } elseif (isset($data['featured']) && !empty($data['featured']) && isset($data['featured'][0]) && isset($data['featured'][0]['media'])) {
                $img_path = $data['featured'][0]['media']['path'];
                $img_name = $data['featured'][0]['media']['name'].$data['featured'][0]['media']['ext'];
                $src .= $img_path.'/'.$img_name;
                $src .= $this->imgAlignUrl($data['featured'][0]['media'], $version);
            } else {
                if (isset($data['content']) && !empty($data['content']) && isset($data['content']['featured']) && !empty($data['content']['featured']) && isset($data['content']['featured'][0]) && isset($data['content']['featured'][0]['media'])) {
                    $img_path = $data['content']['featured'][0]['media']['path'];
                    $img_name = $data['content']['featured'][0]['media']['name'].$data['content']['featured'][0]['media']['ext'];
                    $src .= $img_path.'/'.$img_name;
                    $src .= $this->imgAlignUrl($data['content']['featured'][0]['media'], $version);
                } else {
                    $src = $this->imagePlaceholder();
                }
            }
        } else {
            $src = $this->imagePlaceholder();
        }

        return $src;
    }

    /*
    public function oImg($data = array())
    {
        $data = $this->castAsArray($data);

        $src = \URL::to("/offer-img").'/';
        if (!empty($data)) {
            if (isset($data['featured']) && !empty($data['featured']) && isset($data['featured'][0]) && isset($data['featured'][0]['offer_id'])) {
                $img_path = $data['featured'][0]['path'];
                $img_name = $data['featured'][0]['name'].$data['featured'][0]['ext'];
                $src .= $img_path.'/'.$img_name;
            } else {
                $src = $this->imagePlaceholder();
            }
        } else {
            $src = $this->imagePlaceholder();
        }

        return $src;
    }
    */

    public function imgAlignUrl($media, $version = '')
    {
        $routedVersions = $this->croppingLib->routesToProjectVersions();
        $media = $this->castAsArray($media);

        if (isset($routedVersions[$version])) {
            $projectVersion = $routedVersions[$version]['version'];
            if (isset($media['details']) && strlen($media['details'])) {
                $media_details = json_decode($media['details'], true);
                if (isset($media_details['img_align']) && isset($media_details['img_align'][$projectVersion])) {
                    $cr_dets = $media_details['img_align'][$projectVersion];
                    //decide wich version to use
                    //dd($media_details['img_align'][$projectVersion]);
                    return '?crop='.$cr_dets['width'].','.$cr_dets['height'].','.$cr_dets['x'].','.$cr_dets['y'];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function catImg($data = array(), $version = 'default')
    {
        $data = $this->castAsArray($data);
        $cat_image = $this->categoryHelper->imgUrl($data['id'], $data['image']);

        if ($cat_image) {
            $src = $cat_image;
        } else {
            $src = $this->imagePlaceholder();
        }

        return $src;
    }

    public function catBannerImg($data = array(), $version = 'default')
    {
        $data = $this->castAsArray($data);
        $cat_image = $this->categoryHelper->imgUrl($data['id'], $data['banner_image']);

        if ($cat_image) {
            $src = $cat_image;
        } else {
            $src = $this->imagePlaceholder();
        }

        return $src;
    }

    public function cImg($data = array(), $version = 'banner')
    {
        $data = $this->castAsArray($data);

        $src = \URL::to("/portal-img").'/original/';
        if (!empty($data)) {
            switch ($version) {
                case 'banner':
                    if (isset($data['banner']) && !empty($data['banner']) && !empty($data['banner']['media'])) {
                        $media = $data['banner']['media'];
                        $img_path = $media['path'];
                        $img_name = $media['name'].$media['ext'];
                        $src .= $img_path.'/'.$img_name;
                        $src .= $this->imgAlignUrl($media, null);
                    } else {
                        $src = $this->catImg($data, $version);
                    }
                    break;
                case 'all_media':
                    if (isset($data['all_media']) && !empty($data['all_media']) && isset($data['all_media'][0]) && isset($data['all_media'][0]['media'])) {
                        $media = $data['all_media'][0]['media'];
                        $img_path = $media['path'];
                        $img_name = $media['name'].$media['ext'];
                        $src .= $img_path.'/'.$img_name;
                        $src .= $this->imgAlignUrl($media, null);
                    } else {
                        $src = $this->catImg($data, $version);
                    }
                    break;
                default:
                    if (isset($data['banner']) && !empty($data['banner']) && !empty($data['banner']['media'])) {
                        $media = $data['banner']['media'];
                        $img_path = $media['path'];
                        $img_name = $media['name'].$media['ext'];
                        $src .= $img_path.'/'.$img_name;
                        $src .= $this->imgAlignUrl($media, null);
                    } elseif (isset($data['all_media']) && !empty($data['all_media']) && isset($data['all_media'][0]) && isset($data['all_media'][0]['media'])) {
                        $media = $data['all_media'][0]['media'];
                        $img_path = $media['path'];
                        $img_name = $media['name'].$media['ext'];
                        $src .= $img_path.'/'.$img_name;
                        $src .= $this->imgAlignUrl($media, null);
                    } else {
                        $src = $this->catImg($data, $version);
                    }
                    break;
            }
        } else {
            $src = $this->imagePlaceholder();
        }

        return $src;
    }

    /**
     * A default placeholder image
     */
    public function imagePlaceholder($version = '')
    {
        //img_placeholder
        $src = '/theme/assets/img/img_placeholder.jpg';
        //$src = \URL::to("/portal-img").'/'.$version.'/';

        return $src;
    }

    public function oDate($data = array(), $version = 'expires_at', $format = '')
    {
        $data = $this->castAsArray($data);

        if (isset($data[$version])) {
            if ($data[$version]) {
                return Carbon::parse($data[$version])->format('d/m/Y H:i');
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function oPrice($data = array(), $version = 'actual_price', $format = '')
    {
        $data = $this->castAsArray($data);

        if (isset($data[$version])) {
            return number_format(floatval($data[$version]), 2, '.', ',');
        } else {
            return '';
        }
    }

    public function pSlug($data = array(), $path = '', $only_slug = 0)
    {
        $data = $this->castAsArray($data);

        if (!$only_slug) {
            $only_slug = $this->isInshapePost($data);
        }

        if (!empty($data)) {
            if ($only_slug) {
                if (isset($data['content']) && isset($data['content']['slug']) && strlen($data['content']['slug'])) {
                    $slug = $path.'/'.$data['content']['slug'];
                } elseif (isset($data['slug']) && strlen($data['slug'])) {
                    $slug = $path.'/'.$data['slug'];
                } else {
                    $slug = '';
                }
            } else {
                if (isset($data['ext_url']) && strlen($data['ext_url']) && $data['type'] !== 2) {
                    $slug = $data['ext_url'];
                } else {
                    if (isset($data['content']) && !empty($data['content']) && strlen($data['content']['ext_url']) && ($data['content']['type'] !== 2)) {
                        $slug = $data['content']['ext_url'];
                    } elseif (isset($data['content']) && isset($data['content']['slug']) && strlen($data['content']['slug'])) {
                        $slug = $path.'/'.$data['content']['slug'];
                    } elseif (isset($data['slug']) && strlen($data['slug'])) {
                        $slug = $path.'/'.$data['slug'];
                    } else {
                        $slug = '';
                    }
                }
            }
        } else {
            $slug = '';
        }

        return $slug;
    }

    public function pRoute($slug = null, $lang = null, $website = null, $optype = "def")
    {
        $res = '';
        $base_url = URL::to('/');
        $uri = Request::path();
        if (is_null($slug)) {
            //$segment = $request->segment(1);
            $segment = Request::segment(1);
        } else {
            $segment = $slug;
        }
        if (is_null($lang)) {
            $lang = $_ENV['LANG'];
        }
        if (is_null($website)) {
            $website = $_ENV['WEBSITE'];
        }

        switch ($optype) {
            case "def":
                $dpRoutesSlugs = Config::get('dproutes_slugs');
                if ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website][$segment]) && isset($dpRoutesSlugs[$website][$segment][$lang])) {
                    if (is_array($dpRoutesSlugs[$website][$segment][$lang])) {
                        if (isset($dpRoutesSlugs[$website][$segment][$lang]['slug'])) {
                            $res = $dpRoutesSlugs[$website][$segment][$lang]['slug'];
                        } else {
                            $res = $segment;
                        }
                    } else {
                        $res = $dpRoutesSlugs[$website][$segment][$lang];
                    }
                } else {
                    $res = $segment;
                }
                break;
            case "get":
                $dpRoutesSlugs = Config::get('dproutes_slugs');
                if ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website]["get#".$segment]) && isset($dpRoutesSlugs[$website]["get#".$segment][$lang])) {
                    if (is_array($dpRoutesSlugs[$website]["get#".$segment][$lang])) {
                        if (isset($dpRoutesSlugs[$website]["get#".$segment][$lang]['slug'])) {
                            $res = $dpRoutesSlugs[$website]["get#".$segment][$lang]['slug'];
                        } else {
                            $res = $segment;
                        }
                    } else {
                        $res = $dpRoutesSlugs[$website]["get#".$segment][$lang];
                    }
                } else {
                    $res = $segment;
                }
                break;
            case "post":
                $dpRoutesSlugs = Config::get('dproutes_slugs');
                if ($dpRoutesSlugs && isset($dpRoutesSlugs[$website]) && isset($dpRoutesSlugs[$website]["post#".$segment]) && isset($dpRoutesSlugs[$website]["post#".$segment][$lang])) {
                    if (is_array($dpRoutesSlugs[$website]["post#".$segment][$lang])) {
                        if (isset($dpRoutesSlugs[$website]["post#".$segment][$lang]['slug'])) {
                            $res = $dpRoutesSlugs[$website]["post#".$segment][$lang]['slug'];
                        } else {
                            $res = $segment;
                        }
                    } else {
                        $res = $dpRoutesSlugs[$website]["post#".$segment][$lang];
                    }
                } else {
                    $res = $segment;
                }
                break;
            case "dbq":
                //pending
                break;
            default:
                $res = '!undefined!';
                break;
        }

        return $res;
    }

    public function pTarget($data = array(), $overwrite = '')
    {
        $data = $this->castAsArray($data);

        if ($overwrite) {
            return $overwrite;
        } else {
            if (!empty($data)) {
                if (isset($data['ext_url']) && strlen($data['ext_url'])) {
                    $target = $data['target'];
                } else {
                    if (isset($data['content']) && !empty($data['content']) && strlen($data['content']['ext_url'])) {
                        $target = $data['content']['target'];
                    } else {
                        $target = '_self';
                    }
                }
            } else {
                $target = '_self';
            }
        }

        return $target;
    }

    public function pField($data = array(), $field = 'title')
    {
        $data = $this->castAsArray($data);

        if (!empty($data)) {
            if (isset($data[$field]) && strlen($data[$field])) {
                $pfield = $data[$field];
            } else {
                if (isset($data['content']) && !empty($data['content']) && strlen($data['content'][$field])) {
                    $pfield = $data['content'][$field];
                } elseif (isset($data[$field])) {
                    $pfield = $data[$field];
                } else {
                    $pfield = '';
                }
            }
        } else {
            $pfield = '';
        }

        return $pfield;
    }

    public function truncateOnSpace($str = '', $des_width = 150, $suffix = "...")
    {
        $string = strip_tags($str);
        $parts = preg_split('/([\s\n\r]+)/', $string, NULL, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);
        $length = 0;
        $append_suffix = 0;

        for ($last_part = 0; $last_part < $parts_count; ++$last_part)
        {
            $length += mb_strlen($parts[$last_part]);

            if ($length > $des_width)
            {
                $append_suffix = 1;
                break;
            }
        }

        $ret_str = implode(array_slice($parts, 0, $last_part));

        if ($append_suffix)
        {
            $ret_str .= $suffix;
        }

        return $ret_str;
    }

    public function excludingIds($data = array())
    {
        $excluding_ids = [];

        if (!empty($data)) {
            foreach ($data as $crow) {
                if (!empty($crow)) {
                    foreach ($crow as $key => $row) {
                        if (isset($row['content_id']) && !in_array($row['content_id'], $excluding_ids)) {
                            $excluding_ids[] = $row['content_id'];
                        }
                    }
                }
            }
        }
        //dd($excluding_ids);
        return $excluding_ids;
    }

    public function excludingIdsOfCats($data = array(), $merge_with = array())
    {
        $excluding_ids = [];

        if (!empty($data)) {
            foreach ($data as $crow) {
                if (isset($crow['id'])) {
                    $excluding_ids[] = $crow['id'];
                }
            }
        }
        //dd($excluding_ids);
        if (!empty($merge_with)) {
            if (empty($excluding_ids)) {
                return $merge_with;
            } else {
                return array_merge($merge_with, $excluding_ids);
            }
        }
        return $excluding_ids;
    }

    public function excludingIdsOfContent($data = array())
    {
        $excluding_ids = [];

        if (!empty($data)) {
            foreach ($data as $crow) {
                if (!empty($crow)) {
                    foreach ($crow as $key => $row) {
                        if (isset($row['id']) && !in_array($row['id'], $excluding_ids)) {
                            $excluding_ids[] = $row['id'];
                        }
                    }
                }
            }
        }
        //dd($excluding_ids);
        return $excluding_ids;
    }

    public function contentDate($inp_date = '', $lang = 'el', $version = 1)
    {
        $day = date("d", strtotime($inp_date));
        $month = date("n", strtotime($inp_date));
        $year = date("Y", strtotime($inp_date));
        $hour = date("H", strtotime($inp_date));
        $min = date("i", strtotime($inp_date));

        $indate = $day . '&nbsp;' . $this->monthsRep("el", $month, $version) . '&nbsp;' . $hour.':'.$min;

        return $indate;
    }

    public function contentDateBlog($inp_date = '', $lang = 'el', $version = 1)
    {
        $day = date("d", strtotime($inp_date));
        $month = date("n", strtotime($inp_date));
        $year = date("Y", strtotime($inp_date));
        $hour = date("H", strtotime($inp_date));
        $min = date("i", strtotime($inp_date));

        $indate = $day . '&nbsp;' . $this->monthsRep("el", $month, $version);

        return $indate;
    }

    public function contentTime($inp_date = '', $lang = 'el', $version = 1)
    {
        //$day = date("d", strtotime($inp_date));
        //$month = date("n", strtotime($inp_date));
        //$year = date("Y", strtotime($inp_date));
        $hour = date("H", strtotime($inp_date));
        $min = date("i", strtotime($inp_date));

        $indate = $hour.':'.$min;

        return $indate;
    }

    public function monthsRep($lang = 'el', $month, $version = 0)
    {
        $months = array();
        $month = intval($month);

        $months['el'][0][1] = 'Ιανουάριο';
        $months['el'][0][2] = 'Φεβρουάριο';
        $months['el'][0][3] = 'Μάρτιο';
        $months['el'][0][4] = 'Απρίλιο';
        $months['el'][0][5] = 'Μάιο';
        $months['el'][0][6] = 'Ιούνιο';
        $months['el'][0][7] = 'Ιούλιο';
        $months['el'][0][8] = 'Αύγουστο';
        $months['el'][0][9] = 'Σεπτέμβριο';
        $months['el'][0][10] = 'Οκτώβριο';
        $months['el'][0][11] = 'Νοέμβριο';
        $months['el'][0][12] = 'Δεκέμβριο';

        $months['el'][1][1] = 'Ιανουαρίου';
        $months['el'][1][2] = 'Φεβρουαρίου';
        $months['el'][1][3] = 'Μαρτίου';
        $months['el'][1][4] = 'Απριλίου';
        $months['el'][1][5] = 'Μαΐου';
        $months['el'][1][6] = 'Ιουνίου';
        $months['el'][1][7] = 'Ιουλίου';
        $months['el'][1][8] = 'Αυγούστου';
        $months['el'][1][9] = 'Σεπτεμβρίου';
        $months['el'][1][10] = 'Οκτωβρίου';
        $months['el'][1][11] = 'Νοεμβρίου';
        $months['el'][1][12] = 'Δεκεμβρίου';

        $months['en'][0][1] = 'January';
        $months['en'][0][2] = 'February';
        $months['en'][0][3] = 'March';
        $months['en'][0][4] = 'April';
        $months['en'][0][5] = 'May';
        $months['en'][0][6] = 'June';
        $months['en'][0][7] = 'July';
        $months['en'][0][8] = 'August';
        $months['en'][0][9] = 'September';
        $months['en'][0][10] = 'October';
        $months['en'][0][11] = 'November';
        $months['en'][0][12] = 'December';

        $months['en'][1][1] = 'January';
        $months['en'][1][2] = 'February';
        $months['en'][1][3] = 'March';
        $months['en'][1][4] = 'April';
        $months['en'][1][5] = 'May';
        $months['en'][1][6] = 'June';
        $months['en'][1][7] = 'July';
        $months['en'][1][8] = 'August';
        $months['en'][1][9] = 'September';
        $months['en'][1][10] = 'October';
        $months['en'][1][11] = 'November';
        $months['en'][1][12] = 'December';

        return $months[$lang][$version][$month];
    }

    public function daysRep($lang = 'el', $day, $version = 0)
    {
        $days = array();
        $day = intval($day);

        $days['el'][0][1] = 'Δευτέρα';
        $days['el'][0][2] = 'Τρίτη';
        $days['el'][0][3] = 'Τετάρτη';
        $days['el'][0][4] = 'Πέμπτη';
        $days['el'][0][5] = 'Παρασκευή';
        $days['el'][0][6] = 'Σάββατο';
        $days['el'][0][7] = 'Κυριακή';

        $days['el'][1][1] = 'Δευτέρα';
        $days['el'][1][2] = 'Τρίτη';
        $days['el'][1][3] = 'Τετάρτη';
        $days['el'][1][4] = 'Πέμπτη';
        $days['el'][1][5] = 'Παρασκευή';
        $days['el'][1][6] = 'Σάββατο';
        $days['el'][1][7] = 'Κυριακή';

        $days['en'][0][1] = 'Monday';
        $days['en'][0][2] = 'Tuesday';
        $days['en'][0][3] = 'Wednesday';
        $days['en'][0][4] = 'Thursday';
        $days['en'][0][5] = 'Friday';
        $days['en'][0][6] = 'Saturday';
        $days['en'][0][7] = 'Sunday';

        $days['en'][1][1] = 'Monday';
        $days['en'][1][2] = 'Tuesday';
        $days['en'][1][3] = 'Wednesday';
        $days['en'][1][4] = 'Thursday';
        $days['en'][1][5] = 'Friday';
        $days['en'][1][6] = 'Saturday';
        $days['en'][1][7] = 'Sunday';

        return $days[$lang][$version][$day];
    }

    public function loadMoreContent($list = array(), $indata = array())
    {
        if (!isset($indata['overwrite_slug'])) {
            $indata['overwrite_slug'] = 0;
        }

        $nlist = [];
        if (!empty($list)) {
            foreach ($list as $key => $row) {
                $nlist[$key] = [
                    'id' => $row->id,
                    'slug' => $this->pSlug($row, '', $indata['overwrite_slug']),
                    'target' => $this->pTarget($row),
                    'title' => $this->pField($row, 'title'),
                    'header' => $this->pField($row, 'header'),
                    'src' => $this->pImg($row,  $indata['img_version']),
                    'published_at' => $this->contentDate($row['published_at']),
                    'excerpt' => $this->pField($row, 'excerpt'),
                    'pSubCat' => $this->firstCatOfDepth($row, 1),
                    'truncated_body' => $this->truncateOnSpace($this->pField($row, 'body'), 200),
                ];
            }
        }

        return $nlist;
    }


    /**
     * Section Icon @2x
     * Takes the img name with extension and returns the @2x verion
     */
    public function icon2x($img_name = '')
    {
        $img_parts = explode(".", $img_name);
        $img_ext = end($img_parts);
        $parts = explode(".".$img_ext, $img_name);
        $new_img = $parts[0].'@2x.'.$img_ext;

        return $new_img;
    }

    /*
     * Extract gallery images from content
     *
     *
     */
    public function galleryImgs($data = '')
    {
        $imgs = [];

        if (!empty($data)) {
            $html = new \Htmldom();

            if (isset($data[0])) {
                $html->str_get_html($data[0]['content']['body']);
            } else {
                $html->str_get_html($data['content']['body']);
            }

            $images = [];
            $captions = [];

            foreach ($html->find('img[data-dpc-media-id]') as $e) {
                $images[] = $e->attr['data-dpc-media-id'];

                $next_sibling = $e->next_sibling();

                if (!is_null($next_sibling) && ($next_sibling->tag == "figcaption")) {
                    $captions[] = $next_sibling->plaintext;
                } else {
                    $captions[] = '';
                }
            }

            /*
            foreach ($html->find('[class=dpcimg]') as $e) {
                $next_sibling = $e->next_sibling();

                if (!is_null($next_sibling) && ($next_sibling->tag == "blockquote")) {
                    //$captions[] = $next_sibling->innertext;
                    $captions[] = $next_sibling->plaintext;
                } elseif ($e->tag == "blockquote") {
                    $captions[] = $e->plaintext;
                } else {
                    $inner_block = $e->find('blockquote');
                    $captions[] = '';
                }
            }
            */

            $html->save();
            $html->clear();

            if (!empty($images)) {
                foreach ($images as $key => $media_id) {
                    $imgs[$key] = Media::where('id','=',$media_id)->select('id','path','name','ext')->with('langdetails')->first()->toArray();
                    if (isset($captions[$key])) {
                        $imgs[$key]['caption'] = $captions[$key];
                    } else {
                        $imgs[$key]['caption'] = '';
                    }
                }
            }
        }

        return $imgs;
    }

    public function galleryImgsv2($data = '')
    {
        $imgs = [];
        $title = '';
        $slug = '';
        $summary = '';

        if (!empty($data)) {
            $html = new \Htmldom();

            if (isset($data[0])) {
                $html->str_get_html($data[0]['content']['body']);
                $title = $data[0]['content']['title'];
                $slug = $data[0]['content']['slug'];
            } else {
                $html->str_get_html($data['content']['body']);
                $title = $data['content']['title'];
                $slug = $data['content']['slug'];
            }

            $images = [];
            $captions = [];

            foreach ($html->find('img[data-dpc-media-id]') as $e) {
                $images[] = $e->attr['data-dpc-media-id'];

                $next_sibling = $e->next_sibling();

                if (!is_null($next_sibling) && ($next_sibling->tag == "figcaption")) {
                    $captions[] = $next_sibling->plaintext;
                } else {
                    $captions[] = '';
                }
            }

            foreach ($html->find('blockquote') as $e) {
                $summary .= $e->innertext;
            }

            /*
            foreach ($html->find('[class=dpcimg]') as $e) {
                $next_sibling = $e->next_sibling();

                if (!is_null($next_sibling) && ($next_sibling->tag == "blockquote")) {
                    //$captions[] = $next_sibling->innertext;
                    $captions[] = $next_sibling->plaintext;
                } elseif ($e->tag == "blockquote") {
                    $captions[] = $e->plaintext;
                } else {
                    $inner_block = $e->find('blockquote');
                    $captions[] = '';
                }
            }
            */

            $html->save();
            $html->clear();

            if (!empty($images)) {
                foreach ($images as $key => $media_id) {
                    $imgs[$key] = Media::where('id','=',$media_id)->select('id','path','name','ext')->with('langdetails')->first()->toArray();
                    if (isset($captions[$key])) {
                        $imgs[$key]['caption'] = $captions[$key];
                    } else {
                        $imgs[$key]['caption'] = '';
                    }
                }
            }
        }

        return [
            "title" => $title,
            "slug" => $slug,
            "imgs" => $imgs,
            "summary" => $summary,
        ];
    }

    /*
     * Convert inline content galleries to new form
     * and add the featured media taxs
     */
    public function convertGalleries()
    {
        $res = [];
        $galleries = Content::filterContent([
            'take' => 50,
            'status' => 1,
            'type' => 4, // gallery type id
            'sort' => 'published_at',
            'sort_direction' => 'desc',
            'excluding_ids' => [],
            'on_categories' => []
        ])->with('categories','featured.media')->get()->toArray();

        if (!empty($galleries)) {
            foreach ($galleries as $key => $row)
            {
                $tmp['content'] = $row;
                $res[$key] = $this->galleryImgsv2($tmp);
            }
        }

        if (!empty($res)) {
            foreach ($res as $key => $row) {
                $gallery_content = $galleries[$key];
                if (!empty($row['imgs'])) {
                    foreach ($row['imgs'] as $ikey => $irow) {
                        FeaturedMedia::create([
                            "content_id" => $gallery_content["id"],
                            "media_id"  => $irow["id"],
                            "type" => 1,
                            "details" => [
                                "caption" => $irow["caption"]
                            ]
                        ]);
                    }
                }
            }
        }
    }

    public function fetchTimeline($data = array(), $adata = array())
    {
        $defaults = [
            'excluding_ids' => [],
        ];

        $adata = array_merge($defaults, $adata);

        $timeline = Content::filterContent([
            'take' => 8,
            'status' => 1,
            'type' => 1,
            'lang' => $data['lang'],
            'sort' => 'published_at',
            'sort_direction' => 'desc',
            'excluding_ids' => $adata['excluding_ids'],
        ])->with('categories','tags','author','featured.media')->get()->toArray();

        return $timeline;
    }

    public function mediaCategories($data = array())
    {
        $mediaCategories[0] = [
            'slug' => 'latest-news',
            'name' => 'Ειδήσεις',
            'ids' => [1,2,3,5,6]
        ];

        $cat_ids = [
            1 => 4, //lifestyle
            2 => 7, //sports
            3 => 156, //paraxena
        ];

        foreach ($cat_ids as $key => $cat_id) {
            $tmp = Category::where('id',$cat_id)->select('id','name','slug')->first();
            $mediaCategories[$key] = [
                'slug' => $tmp->slug,
                'name' => $tmp->name,
                'ids' => [$tmp->id]
            ];
        }

        return $mediaCategories;
    }

    public function firstCatOfDepth($data = array(), $depth = 1)
    {
        $data = $this->castAsArray($data);
        $cat = '';

        if (isset($data['categories']) && !empty($data['categories'])) {
            foreach ($data['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat = $row['name'];
                    break;
                }
            }
        } elseif (isset($data['content']) && isset($data['content']['categories']) && !empty($data['content']['categories'])) {
            foreach ($data['content']['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat = $row['name'];
                    break;
                }
            }
        } else
        {}

        return $cat;

    }

    public function firstCatOfDepthSlug($data = array(), $depth = 1)
    {
        $data = $this->castAsArray($data);
        $cat = '';

        if (isset($data['categories']) && !empty($data['categories'])) {
            foreach ($data['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat = $row['slug'];
                    break;
                }
            }
        } elseif (isset($data['content']) && isset($data['content']['categories']) && !empty($data['content']['categories'])) {
            foreach ($data['content']['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat = $row['slug'];
                    break;
                }
            }
        } else
        {}

        return $cat;

    }

    public function fistCatId($data = array(), $depth = 0)
    {
        $data = $this->castAsArray($data);
        $cat_id = 'def';

        if (isset($data['categories']) && !empty($data['categories'])) {
            foreach ($data['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat_id = $row['id'];
                    break;
                }
            }
        } elseif (isset($data['content']) && isset($data['content']['categories']) && !empty($data['content']['categories'])) {
            foreach ($data['content']['categories'] as $key => $row) {
                if ($row['depth'] == $depth) {
                    $cat_id = $row['id'];
                    break;
                }
            }
        } else
        {}

        return $cat_id;
    }

    /*
     * Get the sports category submenu tree - flattened
     */
    public function sportsMenu()
    {
        $sportsMenuTree = Category::where('id', 7)->first()->getDescendants()->where('status',1)->where('type',0)->where('on_sports_menu',1)->toHierarchy();
        //$sportsMenu = $this->categoryHelper->flattenTree($sportsMenuTree, [], [0]);
        //dd($sportsMenuTree);
        return $sportsMenuTree;
    }

    public function teamLogos()
    {
        //set to 771 for live (601 local)
        $teamLogosTree = Category::where('id', 771)->first()->getDescendants()->where('status',1)->where('type',0)->toHierarchy();
        //dd($teamLogosTree);
        return $teamLogosTree;
    }

    /*
     * Check the sections of this post, look for inshape category
     */
    public function isInshapePost($data = array())
    {
        if (isset($data['content']) && !empty($data['content']) && isset($data['content']['categories']) && !empty($data['content']['categories'])) {
            foreach ($data['content']['categories'] as $row) {
                $tmp = $this->castAsArray($row);
                if ($tmp['id'] == 17) {
                    return 1;
                    break;
                }
            }
        } elseif (isset($data['categories']) && !empty($data['categories'])) {
            foreach ($data['categories'] as $row) {
                $tmp = $this->castAsArray($row);
                if ($tmp['id'] == 17) {
                    return 1;
                    break;
                }
            }
        } else {
            return 0;
        }
    }

    public function randomColumnists($data = array())
    {
        $defaults = [
            'take' => 3,
            'fetch_article' => 0
        ];

        $data = array_merge($defaults, $data);

        //$columnists = User::has('columnist')->with("columnist")->random()->select('id','first_name','last_name','username','email')->take($data['take'])->get();
        /*
        $columnists = User::has('columnist')->with([
            'columnist',
            'content' => function ($query) {
                $query->where('status', 1);
                $query->where('type', 5);
                $query->where('lang','el');
                $query->take(1);
                $query->orderBy('published_at', 'desc');
            }
        ])->random()->select('id','first_name','last_name','username','email')->take($data['take'])->get();
        */

        $columnists = User::has('columnist')->with([
            'columnist',
        ])->random()->select('id','first_name','last_name','username','email')->take($data['take'])->get()->toArray();

        if (!empty($columnists)) {
            foreach ($columnists as $key => $row) {
                $columnists[$key]['content'] = Content::where('author_id', $row['id'])->where('status', 1)->where('type', 5)->where('lang','el')->orderBy('published_at', 'desc')->first();
            }
        }

        //dd($columnists);

        if (is_null($columnists)) {
            return [];
        } else {
            /*
            $columnists = $columnists->toArray();

            if ($data['fetch_article'] == 1) {
                foreach ($columnists as $key => $row) {
                    $tmp = Content::select('id','title','slug','status','published_at','author_id')->with('author','featured.media')->filterContent([
                        'status' => 1,
                        'type' => 5,
                        'author_id' => 'any',
                        'search_term' => '',
                        'on_categories' => [],
                        'on_tags' => [],
                        'take' => 1,
                        'skip' => 0,
                        'sort' => 'published_at',
                        'sort_direction' => 'desc',
                        'lang' => 'el',
                    ])->get();

                    if (!is_null($tmp)) {
                        foreach ($tmp as $blog) {
                            $columnists[$key]['blog'] = $blog->toArray();
                        }
                    } else {
                        $columnists[$key]['blog'] = [];
                    }
                }
            }
            return $columnists;
            */

            //return $columnists->toArray();
            return $columnists;
        }
    }

    public function latestColumnists($data = array())
    {
        $defaults = [
            'take' => 3,
            'fetch_article' => 0
        ];

        $data = array_merge($defaults, $data);

        $columns = Content::where('type', 5)->where('status', 1)->where('lang','el')->orderBy('published_at', 'desc')->take($data['take'])->get()->toArray();
        $columnists = array();

        if (!empty($columns)) {
            foreach ($columns as $key => $row) {
                $columns[$key]['user'] = User::where('id',$row['author_id'])->with(['columnist'])->select('id','first_name','last_name','username','email')->get()->toArray();
            }
        }

        if (!empty($columns)) {
            foreach ($columns as $key => $row) {
                $columnists[$key] = $row['user'][0];
                $columnists[$key]['content'] = $row;
            }
        }

        //dd($columnists);

        if (is_null($columnists)) {
            return [];
        } else {
            return $columnists;
        }
    }

    public function castAsArray($indata)
    {
        if (is_object($indata)) {
            return $indata->toArray();
        } else {
            return $indata;
        }
    }

    public function setPolls()
    {
        $poll = Content::where('type', 8)->where('status', 1)->orderBy('published_at', 'desc')->first();

        if (is_null($poll)) {
            return '';
        } else {
            return $poll->body;
        }
    }

    public function activeSplash()
    {
        //$content = Content::where('type',9)->where('status',1)->select('id','excerpt')->orderBy('activate_at','desc')->orderBy('published_at','desc')->first();
        $content = Content::where('type',9)->where('status',1)->orderBy('activate_at','desc')->orderBy('published_at','desc')->with('featured','featured.media')->first();
        if ($content) {
            return $this->splashSettings($content->toArray());
        } else {
            return '';
        }
    }

    public function splashSettings($content)
    {
        $splashenabled = 0;
        $enablefrequency = 0;
        $displayfrequency = "1 days";
        $autohidetimer = 15;
        $tmp = json_decode($content['excerpt'], true);

        if (json_last_error() === JSON_ERROR_NONE) {
            if ($displayfrequency == 0) {
                $splashenabled = 0;
                $enablefrequency = 0;
                $displayfrequency = "1 days";
                $autohidetimer = 15;
            } else {
                $enablefrequency = 1;
                $displayfrequency = $tmp['displayfrequency'];
                $autohidetimer = intval($tmp['autohidetimer']);
                $splashenabled = 1;
            }
        }

        $splashSet = [
            'splashenabled' => intval($splashenabled),
            'splashpageurl' => \URL::to('/splash'),
            'enablefrequency' => intval($enablefrequency),
            'displayfrequency' => $displayfrequency,
            'defineheader' => $this->splashHeader(),
            //'definehtml' => $this->splashTplv2($content),
            'definehtml' => '',
            'cookiename' => ["tvOneSplashPageCookie".$content['id'], "path=/"],
            'autohidetimer' => intval($autohidetimer),
        ];

        $html = '<div id="splashSrc" style="display: none;">';
        $html .= $this->splashTplv2($content);
        $html .= '</div>';

        $html .= '<link href="'.\URL::to('/theme/assets/css/splash.css').'" rel="stylesheet" media="all">';
        $html .= '<script>';
        $html .= 'var splashSet = '.json_encode($splashSet, 128).';';
        $html .= '</script>';
        $html .= '<script src="'.\URL::to('/theme/assets/js/splashpage.js').'"></script>';

        return $html;
    }

    public function splashHeader()
    {
        $html = '<div class="splashHeader">';
        $html .= '<a href="javascript:splashpage.closeit()" title="Μετάβαση στο TVOneNews">';
        $html .= '<i class="fa fa-times"></i> <span>Μετάβαση στο TVOneNews</span></a>';
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }

    public function splashTpl($content = array())
    {
        $default_scripts = "<script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-35250446-6', 'auto');
            ga('send', 'pageview');
        </script>";

        $html = '<!DOCTYPE html><html lang="en"><head>';
        $html .= '<base href="'.\URL::to('/').'/" target="_self" />';
        $html .= '<meta charset="utf-8">';
        $html .= '<title></title>';
        $html .= '<meta name="description" content="">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<link href="'.\URL::to('/theme/assets/css/splash.css').'" rel="stylesheet" media="all">';
        $html .= '<style>';
        if (!empty($content['featured'])) {
            $image = $content['featured'][0]['media'];
            $html .= '.splash-img {
                width: '.$image['width'].'px;
                height: '.$image['height'].'px;
                background-size: cover;
                background-image: url('.\URL::to('/uploads/originals/'.$image['path'].'/'.$image['name'].$image['ext']).');
            }';
        }
        $html .= '</style>';
        if ($content['header_scripts']) {
            $html .= $content['header_scripts'];
        } else {
            $html .= $default_scripts;
        }
        $html .= '</head>';
        $html .= '<body>';

        $html .= '<div class="spalshArea">';
        $html .= '<div class="spalshRow">';
        $html .= '<div class="splashCont" id="ad_'.$content['id'].'">';
        $html .= '<a href="'.$content['ext_url'].'" target="_blank" title="'.$content['title'].'">';
        if (!empty($content['featured'])) {
            $image = $content['featured'][0]['media'];
            //$html .= '<div class="splash-img"></div>';
            $html .= '<img src="'.\URL::to('/uploads/originals/'.$image['path'].'/'.$image['name'].$image['ext']).'" alt="'.$content['title'].'" />';
        }
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</body></html>';

        return $html;
    }

    public function splashTplv2($content = array())
    {
        $html = '';
        if ($content['header_scripts']) {
            $html .= $content['header_scripts'];
        }

        $html .= '<div class="spalshArea">';
        $html .= '<div class="spalshRow">';
        $html .= '<div class="splashCont" id="ad_'.$content['id'].'">';
        $html .= '<a href="'.$content['ext_url'].'" target="_blank" title="'.$content['title'].'" onClick="splashpage.closeit()">';
        if (!empty($content['featured'])) {
            $image = $content['featured'][0]['media'];
            $html .= '<img src="'.\URL::to('/uploads/originals/'.$image['path'].'/'.$image['name'].$image['ext']).'" alt="'.$content['title'].'" />';
        }
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /*
    public function generateSplash($content_id = 0)
    {
        $content = Content::where('id', $content_id)->with('featured','featured.media')->first()->toArray();
        if (!empty($content)) {
            return $this->splashTpl($content);
        } else {
            return 'Nothis noda zero';
        }
    }
    */

    public function breadcrumbCats($offer, $on_category = false)
    {
        if (!$on_category) {
            $offer = $this->castAsArray($offer);
        } else {
            $category = $this->castAsArray($offer);
            $offer['categories'] = [0 => ["id" => $category['id']]];
        }

        //dd($offer);
        if (!empty($offer['categories'])) {
            //category node, get ancestors
            $mainCat = Category::where('id',$offer['categories'][0]['id'])->first();
            if ($mainCat) {
                $ancestores = $mainCat->getAncestorsAndSelf();
                $parentAsLeaf = [];
                foreach ($ancestores as $key => $row) {
                    if ($row->act_as_leaf) {
                        $parentAsLeaf = $row;
                        break;
                    }
                }

                return [
                    "mainCat" => $mainCat,
                    "ancestores" => $ancestores,
                    "num" => count($ancestores),
                    "parentAsLeaf" => $parentAsLeaf,
                ];
            } else {
                return [
                    "mainCat" => [],
                    "ancestores" => [],
                    "num" => 0,
                    "parentAsLeaf" => [],
                ];
            }
        } else {
            return [
                "mainCat" => [],
                "ancestores" => [],
                "num" => 0,
                "parentAsLeaf" => [],
            ];
        }
    }

    /*
     * Fetch the banner by abbr and lang
     * @returns an array of banners
     */
    public function fetchBanner($abbr = '', $lang = '', $take = "any", $adata = array())
    {
        $res = [];
        $banners = Content::select('id','title','abbr','priority','ext_url','target','caption','subtitle')->with('featured.media')->filterContent([
            "abbr" => $abbr,
            "lang" => $lang,
            "take" => $take,
            //"status" => 1,
            'sort' => 'priority',
            'sort_direction' => 'asc',
        ])->get();

        if ($banners) {
            $banners = $banners->toArray();
            foreach ($banners as $key => $row) {
                $res[$key] = $row;
                if (isset($row['featured']) && $row['featured']) {
                    $media = $row['featured'][0]['media'];
                    $res[$key]["media"] = $row['featured'][0]['media'];
                    $res[$key]["media"]['align_suffix'] = '';
                    $res[$key]["media"]['relative_path'] = $media['path'].'/'.$media['name'].$media['ext'];
                    unset($res[$key]["media"]["details"]);
                    $align_suffix = '';
                    if (isset($media['details']) && strlen($media['details'])) {
                        $media_details = json_decode($media['details'], true);
                        if (isset($media_details['img_align']) && isset($media_details['img_align'][$abbr])) {
                            $cr_dets = $media_details['img_align'][$abbr];
                            $align_suffix = '?crop='.$cr_dets['width'].','.$cr_dets['height'].','.$cr_dets['x'].','.$cr_dets['y'];
                            $res[$key]["media"]['align_suffix'] = $align_suffix;
                        }
                    }
                    //$res[$key]['details'] = $media['details'];
                    $res[$key]['image'] = URL::to('/banner-img').'/'.$row['abbr'].'/'.$media['path'].'/'.$media['name'].$media['ext'].$align_suffix;
                }
                unset($res[$key]['featured']);
            }
        }

        return $res;
    }

    /*
     * Fetch the block by abbr and lang
     * @returns a block
     */
    public function fetchBlock($abbr = '', $lang = '', $take = "any", $adata = array())
    {
        $res = [];
        $block = Content::select('id','title','abbr','priority','ext_url','target','body')->with('featured.media')->filterContent([
            "abbr" => $abbr,
            "lang" => $lang,
            "take" => $take,
            "status" => 1,
            'sort' => 'priority',
            'sort_direction' => 'asc',
        ])->first();

        if ($block) {
            $block = $block->toArray();
            if (isset($block['featured']) && $block['featured']) {
                $media = $block['featured'][0]['media'];
                $align_suffix = '';
                if (isset($media['details']) && strlen($media['details'])) {
                    $media_details = json_decode($media['details'], true);
                    if (isset($media_details['img_align']) && isset($media_details['img_align'][$abbr])) {
                        $cr_dets = $media_details['img_align'][$abbr];
                        $align_suffix = '?crop='.$cr_dets['width'].','.$cr_dets['height'].','.$cr_dets['x'].','.$cr_dets['y'];
                    }
                }
                $block['featured'] = $media;
                $block['featured']['image'] = $media['path'].'/'.$media['name'].$media['ext'].$align_suffix;
            }
            //unset($res[$key]['featured']);
            return $block;
        } else {
            return [];
        }
    }

    /*
     * Check if url exists is preview session
     * Return a status array if it does
     */
    public function checkPreviewSession($id = 0, $defStatus = 1, $type = "content")
    {
        $previewSession = session()->get('preview_session', []);
        $currentDateTime = Carbon::now()->toDateTimeString();

        if ($previewSession) {
            if (isset($previewSession[$type])) {
                foreach ($previewSession[$type] as $id => $row) {
                    if ($currentDateTime > $row['expires_at']) {
                        unset($previewSession[$type][$id]);
                    }
                }
                session()->put('preview_session', $previewSession);

                if (isset($previewSession[$type][$id])) {
                   return [0,1,2];
                } else {
                    return [$defStatus];
                }
            } else {
                return [$defStatus];
            }
        } else {
            return [$defStatus];
        }
    }

    /*
     * Accepts either an eloquent model collection or array.
     * Appends the allowed link content, organized into content type abbr subgroups
     * Appends the custom fields of the content and its primary linked posts
     */
    public function contentParse($content = null, $adata = array())
    {
        $defaults = [
            "content_id" => 0,
            "sort" => 'intra_content_links.created_at',
            "desc" => 'desc',
            "content" => [
                "include_types" => [],
                "exclude_types" => [],
                "status" => "any"
            ]
        ];

        $data = array_merge($defaults, $adata);
        $custTypes = Config::get('dpoptions.content_types');
        $custTypesAbbr = array_pluck(Config::get('dpoptions.content_types'), "abbr", "id");
        $custTypesAbbrFlipped = array_pluck(Config::get('dpoptions.content_types'), "id", "abbr");

        if ($content) {
            if (isset($content[0])) {
                foreach ($content as $ckey => $crow) {
                    $res = [];
                    $include_types = [];
                    $exclude_types = [];

                    if (isset($custTypesAbbr[$crow->type])) {
                        $typeAbbr = $custTypesAbbr[$crow->type];
                        if (isset($custTypes[$typeAbbr])) {
                            $typeSettings = $custTypes[$typeAbbr]['settings'];
                            if (isset($typeSettings['allowed_content_links'])) {
                                if (!empty($typeSettings['allowed_content_links']['including'])) {
                                    foreach ($typeSettings['allowed_content_links']['including'] as $value) {
                                        if (is_numeric($value)) {
                                            $include_types[] = $value;
                                        } else {
                                            if (isset($custTypesAbbrFlipped[$value])) {
                                                $include_types[] = $custTypesAbbrFlipped[$value];
                                            }
                                        }
                                    }
                                } else {
                                    // all content types are allowed
                                }
                                if (!empty($typeSettings['allowed_content_links']['excluding'])) {
                                    foreach ($typeSettings['allowed_content_links']['excluding'] as $value) {
                                        if (is_numeric($value)) {
                                            $exclude_types[] = $value;
                                        } else {
                                            if (isset($custTypesAbbrFlipped[$value])) {
                                                $exclude_types[] = $custTypesAbbrFlipped[$value];
                                            }
                                        }
                                    }
                                } else {
                                    // all content types are allowed
                                }
                            }
                            $intraLinked = IntraContentLinks::getLinkedOf([
                                'content_id' => $crow->id,
                                'content' => [
                                    "include_types" => $include_types,
                                    "exclude_types" => $exclude_types,
                                    "status" => $data['content']['status']
                                ]
                            ])->get();

                            if ($intraLinked) {
                                $tmp = $this->cFieldLib->contentCustomFields(array_pluck($intraLinked, 'linked'));
                                foreach ($tmp as $tkey => $trow) {
                                    if (isset($custTypesAbbr[$trow->type])) {
                                        $res[$custTypesAbbr[$trow->type]][] = $trow;
                                    } else {
                                        $res['unknown'][] = $trow;
                                    }
                                }
                            }
                            /*
                            $intraLinked = IntraContentLinks::getAssociatedOf([
                                'content_id' => $crow->id,
                                'content' => [
                                    "include_types" => $include_types,
                                    "exclude_types" => $exclude_types,
                                    "status" => $data['content']['status']
                                ]
                            ])->get();

                            if ($intraLinked) {
                                $tmp = $this->cFieldLib->contentCustomFields(array_pluck($intraLinked, 'linked'));
                                foreach ($tmp as $tkey => $trow) {
                                    $res[$typeAbbr][] = $trow;
                                }
                            }
                            */
                            $content[$ckey] = $this->cFieldLib->contentCustomFields($crow);
                            $content[$ckey]['linked_per_type'] = $res;
                        } else {
                            // do not append linked content
                        }
                    }
                }
            } else {
                $content = $this->cFieldLib->contentCustomFields($content);
                $res = [];
                $include_types = [];
                $exclude_types = [];

                if (isset($custTypesAbbr[$content->type])) {
                    $typeAbbr = $custTypesAbbr[$content->type];
                    if (isset($custTypes[$typeAbbr])) {
                        $typeSettings = $custTypes[$typeAbbr]['settings'];
                        if (isset($typeSettings['allowed_content_links'])) {
                            if (!empty($typeSettings['allowed_content_links']['including'])) {
                                foreach ($typeSettings['allowed_content_links']['including'] as $value) {
                                    if (is_numeric($value)) {
                                        $include_types[] = $value;
                                    } else {
                                        if (isset($custTypesAbbrFlipped[$value])) {
                                            $include_types[] = $custTypesAbbrFlipped[$value];
                                        }
                                    }
                                }
                            } else {
                                // all content types are allowed
                            }
                            if (!empty($typeSettings['allowed_content_links']['excluding'])) {
                                foreach ($typeSettings['allowed_content_links']['excluding'] as $value) {
                                    if (is_numeric($value)) {
                                        $exclude_types[] = $value;
                                    } else {
                                        if (isset($custTypesAbbrFlipped[$value])) {
                                            $exclude_types[] = $custTypesAbbrFlipped[$value];
                                        }
                                    }
                                }
                            } else {
                                // all content types are allowed
                            }
                        }
                        $intraLinked = IntraContentLinks::getLinkedOf([
                            'content_id' => $content->id,
                            'content' => [
                                "include_types" => $include_types,
                                "exclude_types" => $exclude_types,
                                "status" => $data['content']['status']
                            ]
                        ])->get();

                        if ($intraLinked) {
                            $tmp = $this->cFieldLib->contentCustomFields(array_pluck($intraLinked, 'linked'));
                            foreach ($tmp as $tkey => $trow) {
                                if (isset($custTypesAbbr[$trow->type])) {
                                    $res[$custTypesAbbr[$trow->type]][] = $trow;
                                } else {
                                    $res['unknown'][] = $trow;
                                }
                            }
                        }

                        $content['linked_per_type'] = $res;
                    } else {
                        // do not append linked content
                    }
                }
            }
        }

        return $content;
    }
}

<?php

namespace Library;

use Config;
use Image;
use Mail;
use Session;
use Illuminate\Support\Facades\Facade;
use Cartalyst\Sentinel\Sentinel;

use PostRider\CategoryContent;
use PostRider\Content;
use PostRider\Columnist;
use PostRider\CustomFields;
use PostRider\FeaturedMedia;
use PostRider\Media;
use PostRider\Option;
use PostRider\SlugLookup;
use PostRider\User;

//use Library\EmailNotificationsLib;
use Library\LanguageHelperLib;

class BackendHelperLib
{
    public function __construct(LanguageHelperLib $langHelper)
    {
        $this->langHelp = $langHelper;
    }

    /*
    public function __construct(Sentinel $auth)
    {
        $this->auth = $auth;
    }
    */
    /*
    public function __construct(EmailNotificationsLib $emailNotify)
    {
        $this->emailNotify = $emailNotify;
    }
    */

    /*
     * Cleans the slug and check if its unique
     * Lookup table types: 0 : content, 1 : category / tag
     * Accepts an array containing the following:
     * @id
     * @slug_str
     * @type
     *
     * returns a unique slug
     */
    public function checkSlug($data = array())
    {
        $res = $data;
        $res['iter'] = 0;

        if (strlen($data['slug_str']) == 0) {
            $res['new_slug_str'] = '';
        } else {
            $slug_check = str_slug($data['slug_str']);

            if (isset($data['force_this_slug']) && ($data['force_this_slug'] == 1)) {
                $res['new_slug_str'] = $slug_check;
            } else {
                switch ($data['type']) {
                    case 'content':
                        $slug_type = 0;
                        $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_check)->select('id')->get()->toArray();
                        break;
                    case 'category':
                        $slug_type = 1;
                        $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_check)->select('id')->get()->toArray();
                        break;
                    case 'tag':
                        $slug_type = 1;
                        $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_check)->select('id')->get()->toArray();
                        break;
                    case 'columnist':
                        $slug_type = 2;
                        $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_check)->select('id')->get()->toArray();
                        break;
                    default:
                        $slug_type = 0;
                        $existing_version = [];
                        break;
                }

                $res['iter'] = 1;

                if (empty($existing_version)) {
                    $res['new_slug_str'] = $slug_check;
                } else {
                    $end = 0;
                    while ($end == 0) {
                        if (empty($existing_version)) {
                            $end = 1;
                            break;
                        } else {
                            $slug_new = str_slug($slug_check.'-'.str_random(5));
                            $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_new)->select('id')->get()->toArray();
                            $res['iter']++;
                        }
                    }

                    $res['new_slug_str'] = $slug_new;
                }
            }
        }

        return $res;
    }

    /*
     * returns a slug or empty str to be used on new slug creation
     */
    public function returnUnsedSlug($data = array())
    {
        if (isset($data['force_this_slug']) && ($data['force_this_slug'] == 1)) {
            $res = $this->forceSlugUsage($data);
        } else {
            $res = $this->checkSlug($data);
        }

        return $res['new_slug_str'];
    }

    /*
     * Forces a slug to a specific content by adding a suffix to
     * already used versions of it
     */
    public function forceSlugUsage($data = array())
    {
        $res = $data;
        $res['iter'] = 0;

        if (strlen($data['slug_str']) == 0) {
            $res['new_slug_str'] = '';
        } else {
            $slug_check = str_slug($data['slug_str']);
            $existing_version = SlugLookup::findSlugExludingCurrentId($data['id'], "any", $slug_check)->select('id','slug')->get()->toArray();

            if (!empty($existing_version)) {
                foreach ($existing_version as $key => $row) {
                    SlugLookup::where('id',$row['id'])->update([
                        'slug' => str_slug($row['slug'].'-'.$row['id'].str_random(4))
                    ]);
                    $res['iter']++;
                }
            }
            $res['new_slug_str'] = $slug_check;
        }

        return $res;
    }

    /*
     * Creates a new entry in the slug lookup table. It is assumed that the slug used
     * has already been validated by the returnUnsedSlug() method
     *
     * if the slug_str is not empty
     * and the slug does not exist
     * or is was used by the same content in a past entry
     * Accepts an array containing the following:
     *
     * @id
     * @slug_str
     * @type
     *
     */
    public function addSlugToLookup($data = array())
    {
        if (strlen($data['slug_str']) == 0) {
            return 0;
        } else {
            switch ($data['type']) {
                case 'content':
                    $slug_type = 0;
                    $used_in_the_past = SlugLookup::findPreviousSlug($data['id'], $slug_type, $data['slug_str'])->select('id','slug')->get()->toArray();
                    break;
                case 'category':
                    $slug_type = 1;
                    $used_in_the_past = SlugLookup::findPreviousSlug($data['id'], $slug_type, $data['slug_str'])->select('id','slug')->get()->toArray();
                    break;
                case 'tag':
                    $slug_type = 1;
                    $used_in_the_past = SlugLookup::findPreviousSlug($data['id'], $slug_type, $data['slug_str'])->select('id','slug')->get()->toArray();
                    break;
                case 'columnist':
                    $slug_type = 2;
                    $used_in_the_past = SlugLookup::findPreviousSlug($data['id'], $slug_type, $data['slug_str'])->select('id','slug')->get()->toArray();
                    break;
                default:
                    $slug_type = 0;
                    $used_in_the_past = [];
                    break;
            }

            if (empty($used_in_the_past)) {
                $createArr = [
                    "link_id" => $data['id'],
                    "type" => $slug_type,
                    "slug" => $data['slug_str']
                ];

                if (isset($data['lang'])) {
                    $createArr['lang'] = $data['lang'];
                }

                if (isset($data['website_id'])) {
                    $createArr['website_id'] = $data['website_id'];
                }
                SlugLookup::create($createArr);
                return 1;
            } else {
                foreach ($used_in_the_past as $key => $row) {
                    SlugLookup::where("id", $row['id'])->update(['updated_at' => date('Y-m-d H:i:s')]);
                }
                return 0;
            }
        }
    }

    /*
     * Completely removes a slug and its history from the slug lookup table
     *
     */
    public function removeSlugs($data = array())
    {
        if (isset($data['link_id']) && isset($data['type'])) {
            return SlugLookup::where('link_id',$data['link_id'])->where('type',$data['type'])->delete();
        } else {
            return false;
        }
    }

    /*
     * Gets a utf 8 formatted string and removes the following chars
     * We can not use the e() laravel method cause it uses htmlenties encoding all greek chars
     *
     * returns a cleaner str
     */
    public function cleanStr($str = '')
    {
        $ret_str = $str;

        $replace_with = array(
            0 => array("char" => '\"', "html" => '&quot;'),
            1 => array("char" => '\'', "html" => '&#x27;'),
            2 => array("char" => '\!', "html" => '&#33;'),
            3 => array("char" => '\<', "html" => '&lt;'),
            4 => array("char" => '\>', "html" => '&gt;'),
            5 => array("char" => '\/', "html" => '&#x2F;'),
        );

        foreach ($replace_with as $row) {
            $ret_str = mb_ereg_replace("[".$row["char"]."]", $row["html"], $ret_str);
        }

        //return mb_ereg_replace("[\"\'\!\<\>\/]", , $str);
       return $ret_str;
    }

    /*
     * @request: A request object
     * @url_str: A filter str
     * @defaults: A defaults array with filter value pairs for this query
     * Accepts a request object and / or a url str
     * If the request object is not empty it takes precedence over the url str
     * Creates the filter value pairs
     * Then it compares the extracted results with the defaults filters
     *
     * returns either an array containing filter => value pairs by crosschecking with the defaults array or an empty array
     */
    public function extractFilters($request, $url_str = '', $defaults = array())
    {
        $filters = [];

        if (!empty($request->all())) {
            $filters = $request->all();

			$type = explode('type:', $url_str);
			if (count($type) > 1) {
				$type = explode('!', $type[1])[0];
				$filters['type'] = $type;
			}
        } elseif (strlen($url_str)) {
            $args = explode('!', $url_str);
            foreach ($args as $key => $row) {
                $filter_pair = explode(':', $row);
                if (count($filter_pair) == 2) {
                    $filters[$filter_pair[0]] = $filter_pair[1];
                }
            }
        } else {}

        if (empty($filters)) {
            return $defaults;
        } else {
            if (empty($defaults)) {
                return [];
            } else {
                foreach ($defaults as $key => $row) {
                    if (isset($filters[$key])) {
                        $defaults[$key] = $filters[$key];
                    }
                } //dd($defaults);
                return $defaults;
            }
        }
    }

    /*
     * Remove not needed fields from the content results query
     * Improves response time on large sets
     */
    public function filterContentResults($results = array())
    {
        $res = $results;
        $city = 'No city';
        if (!empty($results)) {
            foreach ($results as $key => $row) {
                //dd('row = '. $row);
                if (isset($row['categories']) && !empty($row['categories'])) {
                    $tmp = [];
                    foreach ($row['categories'] as $ckey => $crow) {
						if (isset($_POST['type'])) {
							if($crow['parent_id'] == 45 && $_POST['type'] == 33) {
                                
								$tmp[] = [
									'id' => $crow['id'],
									'depth' => $crow['depth'],
									'type' => $crow['type'],
									'lang' => $crow['lang'],
									'name' => $crow['name'],
									'slug' => $crow['slug'],
								];
                                
							}else if($crow['parent_id'] == 9 && $_POST['type'] == 33){             
                                
                                $city = $crow['name'];
							}
							else if ($_POST['type'] != 33) {
								$tmp[$ckey] = [
									'id' => $crow['id'],
									'depth' => $crow['depth'],
									'type' => $crow['type'],
									'lang' => $crow['lang'],
									'name' => $crow['name'],
									'slug' => $crow['slug'],
								];
							}

                            /*if ($_POST['type'] == 35) {
                                $tmp[] = [
                                    'id' => $crow['id'],
                                    'depth' => $crow['depth'],
                                    'type' => $crow['type'],
                                    'lang' => $crow['lang'],
                                    'name' => $crow['name'],
                                    'slug' => $crow['slug'],
                                ];
                            }*/

                            if($_POST['type'] == 33){
                                $res[$key]['city'] = $city;
                            }
        
                            $res[$key]['categories'] = $tmp;

						}
						else {
							$tmp[$ckey] = [
								'id' => $crow['id'],
								'depth' => $crow['depth'],
								'type' => $crow['type'],
								'lang' => $crow['lang'],
								'name' => $crow['name'],
								'slug' => $crow['slug'],
							];
						}
                    }

                    
                }

                if (isset($row['tags']) && !empty($row['tags'])) {
                    $tmp = [];
                    foreach ($row['tags'] as $ckey => $crow) {
                        $tmp[$ckey] = [
                            'id' => $crow['id'],
                            'depth' => $crow['depth'],
                            'type' => $crow['type'],
                            'lang' => $crow['lang'],
                            'name' => $crow['name'],
                            'slug' => $crow['slug'],
                        ];
                    }
                    $res[$key]['tags'] = $tmp;
                }

                $tmp = ['id' => 0, 'shown_as' => ''];

                if (isset($row['author']) && !empty($row['author'])) {
                    if (($row['author']['first_name']) && ($row['author']['last_name'])) {
                        $tmp['shown_as'] = $row['author']['first_name'] . ' ' . $row['author']['last_name'];
                    } elseif ($row['author']['username']) {
                        $tmp['shown_as'] = $row['author']['username'];
                    } else {
                        $tmp['shown_as'] = $row['author']['email'];
                    }

                    $tmp['id'] = $row['author']['id'];
                }

                $res[$key]['author'] = $tmp;
                $tmp = ['id' => 0, 'shown_as' => ''];

                if (isset($row['creator']) && !empty($row['creator'])) {
                    if (($row['creator']['first_name']) && ($row['creator']['last_name'])) {
                        $tmp['shown_as'] = $row['creator']['first_name'] . ' ' . $row['creator']['last_name'];
                    } elseif ($row['creator']['username']) {
                        $tmp['shown_as'] = $row['creator']['username'];
                    } else {
                        $tmp['shown_as'] = $row['creator']['email'];
                    }

                    $tmp['id'] = $row['creator']['id'];
                }

                $res[$key]['creator'] = $tmp;

                $tmp = ['is_set' => 0];

                if (isset($row['featured'][0]) && !empty($row['featured'][0])) {
                    //if (isset($row['featured']['media']) && !empty($row['featured']['media'])) {
                        $media = $row['featured'][0]['media'];
                        $align_suffix = '';
                        $rel_path = '';
                        if ($row['abbr']) {
                            $align_suffix = '';
                            $bannerVersions = Config::get('dpbanners.banner_versions.settings');
                            if ($bannerVersions && isset($bannerVersions[$row['abbr']])) {
                                if (isset($media['details']) && strlen($media['details'])) {
                                    $media_details = json_decode($media['details'], true);
                                    if (isset($media_details['img_align']) && isset($media_details['img_align'][$row['abbr']])) {
                                        $cr_dets = $media_details['img_align'][$row['abbr']];
                                        $align_suffix = '?crop='.$cr_dets['width'].','.$cr_dets['height'].','.$cr_dets['x'].','.$cr_dets['y'];
                                    }
                                }
                            }
                        }
                        $rel_path = $row['abbr'].'/'.$media['path'].'/'.$media['name'].$media['ext'].$align_suffix;
                        $tmp = [
                            'id'    => $media['id'],
                            'path'  => $media['path'],
                            'name'  => $media['name'],
                            'ext'   => $media['ext'],
                            'is_set'=> 1,
                            'align_suffix' => $align_suffix,
                            'rel_path' => $rel_path
                        ];
                    //}
                }

                $res[$key]['featured'] = $tmp;
            }
        }

        return $res;
    }

    /*
     * Returns an array of users of specific roles
     */
    public function usersOfRoles($roles = array('super_admin', 'administrator', 'manager', 'author', 'collaborator'))
    {
        //Sentinel::inRole($role)
        $exclude_roles = [];
        $users = User::select('id','first_name','last_name','username','email')->with("roles")->usersOfRoles($roles)->get()->toArray();

        if (!empty($users)) {
            foreach ($users as $key => $row) {
                if (($row['first_name']) && ($row['last_name'])) {
                    $authors[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
                } elseif ($row['username']) {
                    $authors[$row['id']] = $row['username'];
                } else {
                    $authors[$row['id']] = $row['email'];
                }
            }
        }

        return $authors;
    }

    /*
     * Returns an array of columnists of specific roles
     */
    public function getColumnists()
    {
        //Sentinel::inRole($role)
        $exclude_roles = [];
        $users = User::has('columnist')->select('id','first_name','last_name','username','email')->get()->toArray();

        if (!empty($users)) {
            foreach ($users as $key => $row) {
                if (($row['first_name']) && ($row['last_name'])) {
                    $authors[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
                } elseif ($row['username']) {
                    $authors[$row['id']] = $row['username'];
                } else {
                    $authors[$row['id']] = $row['email'];
                }
            }
        }

        return $authors;
    }

    /*
     * Check if the logged in user is an admin or manager
     * and fetch all users else fetch only self
     */
    public function contentAuthors()
    {
        if (\Sentinel::inRole('super_admin') || \Sentinel::inRole('administrator') || \Sentinel::inRole('manager')) {
            $users = User::select('id','first_name','last_name','username','email')->with("roles")->usersOfRoles(['super_admin', 'administrator', 'manager', 'author', 'collaborator'])->get()->toArray();
        } else {
            $users[] = \Sentinel::getUser()->toArray();
        }

        if (!empty($users)) {
            foreach ($users as $key => $row) {
                if (($row['first_name']) && ($row['last_name'])) {
                    $authors[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
                } elseif ($row['username']) {
                    $authors[$row['id']] = $row['username'];
                } else {
                    $authors[$row['id']] = $row['email'];
                }
            }
        }

        return $authors;
    }

    /*
     * Check if the logged in user is an admin or manager
     * and fetch all users else fetch only self
     */
    public function offerAuthors()
    {
        if (\Sentinel::inRole('super_admin') || \Sentinel::inRole('administrator') || \Sentinel::inRole('manager')) {
            $users = User::select('id','first_name','last_name','username','email')->with("roles")->usersOfRoles(['super_admin', 'administrator', 'manager', 'author', 'collaborator'])->get()->toArray();
        } else {
            $users[] = \Sentinel::getUser()->toArray();
        }

        if (!empty($users)) {
            foreach ($users as $key => $row) {
                if (($row['first_name']) && ($row['last_name'])) {
                    $authors[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
                } elseif ($row['username']) {
                    $authors[$row['id']] = $row['username'];
                } else {
                    $authors[$row['id']] = $row['email'];
                }
            }
        }

        return $authors;
    }

    /*
     * Set a featured image for a content, mainly for posts
     * Also, create content if it does not exist
     *
     */
    public function setFeatured($input = array())
    {
        $res = ['set' => false, 'status' => 1];

        $input['content_group_id'] = ((isset($input['content_group_id'])) ? $input['content_group_id'] : 0);
        $input['content_lang'] = ((isset($input['content_lang'])) ? $input['content_lang'] : $this->langHelp->defLang);
        $input['content_website_id'] = ((isset($input['content_website_id'])) ? $input['content_website_id'] : $this->langHelp->defWebsite);
        $input['content_type'] = ((isset($input['content_type'])) ? $input['content_type'] : 1);
        $input['scope'] = ((isset($input['scope'])) ? $input['scope'] : 'post');
        $input['content_abbr'] = ((isset($input['content_abbr'])) ? $input['content_abbr'] : '');

        if ($input['op_type'] == "add") {
            if ($input['content_id']) {
                $content = Content::where('id', $input['content_id'])->select('id','group_id','lang','website_id')->first();
                if (is_null($content)) {
                    $content = Content::create([
                        'creator_id' => \Sentinel::getUser()->id,
                        'author_id' => \Sentinel::getUser()->id,
                        'type' => $input['content_type'],
                        'group_id' => $input['content_group_id'],
                        'lang' => $input['content_lang'],
                        'website_id' => $input['content_website_id'],
                        'abbr' => $input['content_abbr'],
                    ]);
                    $content = $this->langHelp->setContentGroupId($content, ['group_id' => $input['content_group_id']]);
                }
            } else {
                $content = Content::create([
                    'creator_id' => \Sentinel::getUser()->id,
                    'author_id' => \Sentinel::getUser()->id,
                    'type' => $input['content_type'],
                    'group_id' => $input['content_group_id'],
                    'lang' => $input['content_lang'],
                    'website_id' => $input['content_website_id'],
                    'abbr' => $input['content_abbr'],
                ]);
                $content = $this->langHelp->setContentGroupId($content, ['group_id' => $input['content_group_id']]);
            }

            $featuredMedia = FeaturedMedia::updateOrCreate(['content_id' => $content->id], ['media_id' => $input['media_id'], 'type' => 0]);
            $res = $this->getFeaturedMedia([
                "media_id" => $input['media_id'],
                "content_id" => $content->id,
                "group_id" => $content->group_id,
                "lang" => $input['content_lang'],
                "website_id" => $input['content_website_id'],
                "type" => $input['content_type'],
                "scope" => $input['scope']
            ]);
        } elseif ($input['op_type'] == "add_existing") {
            $featuredMedia = FeaturedMedia::updateOrCreate(['content_id' => $input['content_id']], ['media_id' => $input['media_id'], 'type' => 0]);
            $res['set'] = true;
        } elseif ($input['op_type'] == "delete_cust_media") {
            $custField = CustomFields::where('c_field_id', '=', $input['cust_field_id'])->where('content_id', '=', $input['content_id'])->first();
            if ($custField) {
                $custField->update(['value' => '']);
            }
            $res['content_id'] = $input['content_id'];
            $res['set'] = false;
        } else {
            FeaturedMedia::where('media_id', '=', $input['media_id'])->where('content_id', '=', $input['content_id'])->where('type', '=', 0)->delete();
            $res['content_id'] = $input['content_id'];
            $res['set'] = false;
        }

        return $res;
    }

    /*
     *
     *
     */
    public function getFeaturedMedia($indata = array())
    {
        $res = [
            'set' => false,
            'status' => 1,
            'media_id' => $indata['media_id'],
            'content_id' => $indata['content_id'],
            'group_id' => $indata['group_id'],
            'lang' => $indata['lang'],
            'website_id' => $indata['website_id'],
            'type' => $indata['type'],
            'multi_lang' => $this->langHelp->generateContentLangVersions($indata['content_id'], $indata['website_id']),
        ];

        $defaults = [
            "media_id" => $indata['media_id'],
            "content_id" => $indata['content_id'],
            "scope" => "post",
        ];

        $data = array_merge($defaults, $indata);

        $media = Media::select('id','path','name','ext','details')->findOrFail($data['media_id'])->toArray();
        $res['media_id'] = $media['id'];
        $res['media'] = $media;
        $res['set'] = true;
        if ($data['scope'] == "banner") {
            $content = Content::where('id', $data['content_id'])->select('id','type','abbr')->first();
            if ($content) {
                if ($content->type == 1) {
                    $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
                } else {
                    //$typeDets = Option::where('avvr', $content->type)->select('id','abbr')->first();
                    $bannerVersions = Config::get('dpbanners.banner_versions.settings');
                    if ($bannerVersions && isset($bannerVersions[$content->abbr])) {
                        $res['abbr'] = $content->abbr;
                        $align_suffix = '';
                        if (isset($media['details']) && strlen($media['details'])) {
                            $media_details = json_decode($media['details'], true);
                            if (isset($media_details['img_align']) && isset($media_details['img_align'][$content->abbr])) {
                                $cr_dets = $media_details['img_align'][$content->abbr];
                                $align_suffix = '?crop='.$cr_dets['width'].','.$cr_dets['height'].','.$cr_dets['x'].','.$cr_dets['y'];
                            }
                        }
                        $res['thumb_url'] = \URL::to("/").'/featured-banner-img/'.$content->abbr.'/'.$media['path'].'/'.$media['name'].$media['ext'].$align_suffix;
                    } else {
                        $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
                    }
                }
            } else {
                $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
            }
        } else {
            $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
        }

        return $res;
    }

    /*
     * Set an alternative image for a partial section content
     *
     */
    public function setAlternative($input = array())
    {
        $res = ['set' => false, 'status' => 1];

        if (!isset($input['content_type'])) {
            $input['content_type'] = 0;
        }

        if ($input['op_type'] == "add") {
            CategoryContent::where('type','=',2)->where('content_id','=',$input['content_id'])->where('category_id','=',$input['section_id'])->update(['alternative_media_id' => $input['media_id']]);
            $media = Media::select('id','path','name','ext')->findOrFail($input['media_id'])->toArray();
            $res['set'] = true;
            $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
            $res['media_id'] = $media['id'];
            $res['content_id'] = $input['content_id'];
        } else {
            CategoryContent::where('type','=',2)->where('content_id','=',$input['content_id'])->where('category_id','=',$input['section_id'])->update(['alternative_media_id' => 0, 'alternative_versions' => '']);
            $res['content_id'] = $input['content_id'];
        }

        return $res;
    }


    /**
     * A placeholder method that will be replaced in the feature with an actual websites table
     * for now it returns an array with a single element, the website name
     *
     */
    public function getWebsites()
    {
        return [0 => 'TV ONE'];
    }

    /**
     * A placeholder method that will be replaced in the feature with an actual websites table
     * for now it returns a webstore id
     *
     */
    public function getCurrentWebsiteID()
    {

    }

    /**
     * Query the session for the "layout_toggles" key
     * and crosscheck its value with a given key
     *
     * return true or false
     */
    public function isSectionOpen($section_key)
    {
        $layout_toggles = \Session::get('layout_toggles');
        $defaults = [
            "feed_sidebar" => "closed",
            "main_sidebar" => "closed",
        ];

        if (is_null($layout_toggles) || empty($layout_toggles)) {
            if (isset($defaults[$section_key])) {
                return ($defaults[$section_key] == "open") ? true : false;
            } else {
                return false;
            }
        } else {
            if (isset($layout_toggles[$section_key])) {
                return ($layout_toggles[$section_key] == "open") ? true : false;
            } else {
                if (isset($defaults[$section_key])) {
                    return ($defaults[$section_key] == "open") ? true : false;
                } else {
                    return false;
                }
            }
        }
    }

    /*
     * Columnists
     *
     */
    public function addUpdateColumnistFields($user_id = 0, $data = array())
    {
        if (($user_id != 0) && (!empty($data))) {
            if (strlen($data['slug'])) {
                $columnist = Columnist::where('user_id', $user_id)->first();
                $data['user_id'] = $user_id;

                if (is_null($columnist)) {
                    // create
                    $data['slug'] = $this->returnUnsedSlug([
                        'id' => 0,
                        'type' => 'columnist',
                        'slug_str' => $data['slug'],
                        'force_this_slug' => 0
                    ]);

                    $columnist = Columnist::create($data);

                    $this->addSlugToLookup(['id' => $user_id, 'type' => 'columnist', 'slug_str' => $data['slug'], 'lang' => 'el', 'website' => 1]);
                } else {
                    // update
                    $data['slug'] = $this->returnUnsedSlug([
                        'id' => $columnist->user_id,
                        'type' => 'columnist',
                        'slug_str' => $data['slug'],
                        'force_this_slug' => 0
                    ]);

                    $columnist = Columnist::where('user_id', $user_id)->update($data);

                    $this->addSlugToLookup(['id' => $user_id, 'type' => 'columnist', 'slug_str' => $data['slug'], 'lang' => 'el', 'website' => 1]);
                }

                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function prepareUniqueFilename($orig_name = '', $path_to_subfolder = 'uploads/originals/1/')
    {
        $path_parts = pathinfo($orig_name);

        if (isset($path_parts['extension'])) {
            $res['ext'] = $path_parts['extension'];
            $res['slug'] = str_slug($path_parts['filename'], '-');
        } else {
            $res['ext'] = 'jpg';
            $res['slug'] = str_slug(microtime()).'-'.str_random(5);
        }

        $end = 0;
        $filename = $res['slug'];
        $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
        $res['iter'] = 1;

        while ($end == 0) {
            if (file_exists($check_filepath)) {
                $filename = $res['slug'].'-'.str_random(5);
                $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
                $res['iter']++;
            } else {
                $end = 1;
                break;
            }
        }

        $res['name'] = $filename;
        $res['name_ext'] = $res['name'].'.'.$res['ext'];

        return $res;
    }

    public function createDir($dir, $permision = 0755, $recursive = true)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $permision, $recursive);
        } else {
            return true;
        }
    }

    public function extractMeta($data = array())
    {
        $res = $data;
        $res['tags'] = [];
        $res['status'] = 0;

        if (strlen($data['url']) > 3) {
            $sites_html = @file_get_contents($data['url']);
            if ($sites_html) {
                $html = new \DOMDocument();
                @$html->loadHTML($sites_html);

                foreach ($html->getElementsByTagName('meta') as $meta) {
                    if ($meta->getAttribute('name')) {
                        $res['tags'][$meta->getAttribute('name')] = $meta->getAttribute('content');
                    } elseif ($meta->getAttribute('property')) {
                        $res['tags'][$meta->getAttribute('property')] = $meta->getAttribute('content');
                    } else {
                        $res['tags']['unspecified'][] = $meta->getAttribute('content');
                    }
                }
                $res['status'] = 1;
            }
        }

        $res['filteredTags'] = $this->filterMeta($res);

        //echo $meta_og_img;
        //$res['tags'] = get_meta_tags($data['url']); // only meta with name are parsed

        return $res;
    }

    public function filterMeta($data = array())
    {
        $res = [
            'title' => '',
            'image' => '',
            'description' => '',
            'keywords' => '',
            'price' => '',
        ];

        if (!empty($data['tags'])) {
            if (isset($data['tags']['og:title']) && $data['tags']['og:title']) {
                $res['title'] = $data['tags']['og:title'];
            } elseif (isset($data['tags']['twitter:title']) && $data['tags']['twitter:title']) {
                $res['title'] = $data['tags']['twitter:title'];
            } elseif (isset($data['tags']['title']) && $data['tags']['title']) {
                $res['title'] = $data['tags']['title'];
            } else {

            }

            if (isset($data['tags']['og:image']) && $data['tags']['og:image']) {
                $res['image'] = strtok($data['tags']['og:image'], '?');
            } elseif (isset($data['tags']['twitter:image']) && $data['tags']['twitter:image']) {
                $res['image'] = strtok($data['tags']['twitter:image'], '?');
            } else {

            }

            if (isset($data['tags']['og:description']) && $data['tags']['og:description']) {
                $res['description'] = $data['tags']['og:description'];
            } elseif (isset($data['tags']['twitter:description']) && $data['tags']['twitter:description']) {
                $res['description'] = $data['tags']['twitter:description'];
            } elseif (isset($data['tags']['description']) && $data['tags']['description']) {
                $res['description'] = $data['tags']['description'];
            } else {

            }

            if (isset($data['tags']['keywords']) && $data['tags']['keywords']) {
                $res['keywords'] = $data['tags']['keywords'];
            } else {

            }

            if (isset($data['tags']['product:sale_price:amount']) && $data['tags']['product:sale_price:amount']) {
                $res['price'] = floatval($data['tags']['product:sale_price:amount']);
            } elseif (isset($data['tags']['product:price:amount']) && $data['tags']['product:price:amount']) {
                $res['price'] = floatval($data['tags']['product:price:amount']);
            } else {
                $twitter_pr_fields = ['data1', 'label1', 'data2', 'label2'];
                $twitter_prices = [];
                foreach ($twitter_pr_fields as $key => $value) {
                    if (isset($data['tags'][$value]) && is_float($data['tags'][$value])) {
                        $twitter_prices[] = floatval($data['tags'][$value]);
                    }
                }

                if (empty($twitter_prices)) {

                } else {
                    $res['price'] = array_values(arsort($twitter_prices))[0];
                }
            }
        }

        return $res;
    }

    public function assignMeta($data = array())
    {
        if ($data['offer_id']) {
            $offer = Offer::findOrFail($data['offer_id']);
            $data['offer']['slug'] = $this->returnUnsedSlug(['id' => $data['offer_id'], 'type' => 'offer', 'slug_str' => $data['filteredTags']['title']]).'-'.$data['offer_id'];
            $data['offer']['title'] = $data['filteredTags']['title'];
            $data['offer']['actual_price'] = $data['filteredTags']['price'];
            $data['offer']['description'] = $data['filteredTags']['description'];
            $data['offer']['meta_keywords'] = $data['filteredTags']['keywords'];
            $offer->update($data['offer']);
            $this->addSlugToLookup(['id' => $offer->id, 'type' => 'offer', 'slug_str' => $offer->slug]);
        } else {
            $data['offer']['slug'] = $this->returnUnsedSlug(['id' => 0, 'type' => 'offer', 'slug_str' => $data['filteredTags']['title']]);
            $data['offer']['title'] = $data['filteredTags']['title'];
            $data['offer']['actual_price'] = $data['filteredTags']['price'];
            $data['offer']['description'] = $data['filteredTags']['description'];
            $data['offer']['meta_keywords'] = $data['filteredTags']['keywords'];
            $data['offer']['creator_id'] = \Sentinel::getUser()->id;
            $data['offer']['type'] = 1;
            $data['offer']['lang'] = 'el';
            $data['offer']['account_id'] = Session::get('account.id');
            $offer = Offer::create($data['offer']);
            Offer::where('id', $offer->id)->update(['slug' => $offer->slug.'-'.$offer->id]);
            $this->addSlugToLookup(['id' => $offer->id, 'type' => 'offer', 'slug_str' => $offer->slug.'-'.$offer->id]);
            $offer = Offer::where('id', $offer->id)->first();
            $this->informAdminsAboutNewOffer($offer->id);
        }

        $res = [
            'status' => 1,
            'offer' => [
                'id' => $offer->id,
                'slug' => $offer->slug,
                'title' => $data['filteredTags']['title'],
                'price' => $data['filteredTags']['price'],
                'description' => $data['filteredTags']['description'],
                'keywords' => $data['filteredTags']['keywords'],
                'updated_at' => $offer->updated_at,
                'created_at' => $offer->created_at,
                'published_at' => $offer->published_at,
                'status' => $offer->status,
                'has_expired_at' => $offer->has_expired_at
            ]
        ];

        if ($data['filteredTags']['image']) {
            $path_to_subfolder = base_path('public/');
            $path_to_subfolder .= 'uploads/offers/originals/';

            $media['account_id'] = session()->get('account.id');
            $media['offer_id'] = $offer->id;

            //get the image and upload it to a temp folder with a unix timestamp
            $getFileUrl = $this->getFileFromUrl($data['filteredTags']['image']);

            if ($getFileUrl['status'] == 1) {
                $image = Image::make($getFileUrl['path']);
                $urlParts = pathinfo($getFileUrl['path']);
            } else {
                $image = Image::make($data['filteredTags']['image']);
                $urlParts = pathinfo($data['filteredTags']['image']);
            }

            //->save('/path/saveAsImageName.jpg');
            $media["original_name"] = $urlParts['basename'];
            $media["file_info"] = $image->mime();
            $media["size"] = $image->filesize()/1024; // this converts it to kB

            $existing = OfferMedia::where("offer_id", $media['offer_id'])->where("type", 0)->first();

            if ($existing) {
                $existing->update($media);
                $db_media = $existing;
            } else {
                $db_media = OfferMedia::create($media);
            }

            $media["id"] = $db_media->id;
            $media["path"] = intval(floor($media["id"] / 1000) + 1);

            $name_slug_ext = $this->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

            $media["name"] = $name_slug_ext["name"];
            $media["ext"] = '.'.$name_slug_ext["ext"];
            $media["name_ext"] = $name_slug_ext["name_ext"];

            //may need absolute path
            $image->save($path_to_subfolder.$media["path"].'/'.$media["name_ext"]);

            if (substr_count($media["file_info"], 'image')) {
                $media["type"] = 0; // this means that this is an image
                //$media['dpi'] = $this->getImageDPI($path_to_subfolder.$media["path"].'/', $media["name"]);
                $media['width'] = Image::make($path_to_subfolder.$media["path"].'/'.$media["name_ext"])->width();
                $media['height'] = Image::make($path_to_subfolder.$media["path"].'/'.$media["name_ext"])->height();
            } else {
                $media["type"] = 1; // this means that this is a file
            }

            $db_media = OfferMedia::findOrFail($media["id"])->update($media);

            if ($getFileUrl['status'] == 1) {
                unlink($getFileUrl['path']);
                rmdir($getFileUrl['dir']);
            }

            $res = array_merge($res, [
                'media_id' => $media["id"],
                "media" => $media,
                "thumb_url" => '/offer-img/'.$media['path'].'/'.$media['name'].$media['ext'],
                "set" => 1,
            ]);
            return $res;
        } else {
            return $res;
        }
    }

    public function getFileFromUrl($url = '')
    {
        $res['status'] = 0;
        $res['time'] = time();
        $path_to_subfolder = base_path('public/uploads/tmp/'.$res['time'].'/');
        $res['dir'] = $path_to_subfolder;
        $this->createDir($path_to_subfolder);

        if ($url) {
            $urlParts = pathinfo($url);
            if (isset($urlParts['extension'])) {
                $res['ext'] = $urlParts['extension'];
            } else {
                $res['ext'] = 'jpg';
            }
            $res['name'] = $urlParts['filename'];
            if (!$res['ext']) {
                $res['ext'] = 'jpg';
            }
            $res['tmp_name'] = $res['name'].'.'.$res['ext'];
            $res['name_ext'] = $res['name'].'.'.$res['ext'];
            $res['url'] = $url;
            $res['path'] = $path_to_subfolder.$res['tmp_name'];

            $file = fopen ($url, 'rb');

            if ($file) {
                $newf = fopen($res['path'], 'wb');
                if ($newf) {
                    while(!feof($file)) {
                        fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                    }
                }
            }

            if ($file) {
                fclose($file);
            }

            if ($newf) {
                fclose($newf);
            }

            $res['status'] = 1;
        }

        return $res;
    }

    public function getFileExt($str = '')
    {
        if ($str) {
            $img_parts = explode(".", $str);
            if ($img_parts) {
                return end($img_parts);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function informAdminsAboutNewOffer($offer_id = 0)
    {
        //return $this->emailNotify->newOfferAdded($offer_id);
    }
}

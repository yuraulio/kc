<?php

namespace Library;

use Config;
use PostRider\Category;
use PostRider\CategoryMedia;
use PostRider\Media;

use Library\BackendHelperLib;

class CategoryHelperLib
{
    //public $flatten_arr = array();

    public function __construct(BackendHelperLib $backendHelper)
    {
        $this->backendHelper = $backendHelper;
    }

    public function ulTree($tree = null)
    {
        $ul_list = '';

        if ($tree) {
            foreach ($tree as $node) {
               $ul_list .= $this->renderNode($node);
            }
        }

        return $ul_list;
    }

    public function renderNode($node = array())
    {
        if ($node->isLeaf()) {
            return '<li>'.'<a href="'.route('editCategory', ['id' => $node->id]).'" title="'.$node->name.'">'.$node->name.'</a>'.'</li>';
        } else {
            $html = '<li>'.'<a href="'.route('editCategory', ['id' => $node->id]).'">'.$node->name.'</a>';
            $html .= '<ul>';

            foreach ($node->children as $child) {
                $html .= $this->renderNode($child);
            }

            $html .= '</ul>';
            $html .= '</li>';
        }

        return $html;
    }


    public function ulTreev2($tree = null)
    {
        $ul_list = '';

        if ($tree) {
            foreach ($tree as $node) {
               $ul_list .= $this->renderNodev2($node);
            }
        }

        return '<ul>'.$ul_list.'</ul>';
    }

    public function renderNodev2($node = array())
    {
        if ($node->isLeaf()) {
            $html = '<li data-dp-section-id="'.$node->id.'" data-dp-section-type="'.$node->type.'" class="section_li">';
            $html .= '<span class="section_name section_type'.$node->type.'" title="'.$node->name.'">'.$node->name.'</span>';
            $html .= '</li>';
            return $html;
         } else {
            $html = '<li data-dp-section-id="'.$node->id.'" data-dp-section-type="'.$node->type.'" class="section_li">';
            $html .= '<i class="fa fa-plus-square-o"></i>';
            $html .= '<span class="section_name section_type'.$node->type.'" title="'.$node->name.'">'.$node->name.'</span>';

            $html .= '<ul>';

            foreach ($node->children as $child) {
                $html .= $this->renderNodev2($child);
            }

            $html .= '</ul>';
            $html .= '</li>';
        }

        return $html;
    }

    public function createNode($ndata = array(), $additional = array())
    {
        unset($ndata['image'], $ndata['banner_image']);
        if ($ndata['parent_id']) {
            $parent = Category::where('id', '=', $ndata['parent_id'])->first();
            $ndata['slug'] = $this->backendHelper->returnUnsedSlug([
                'id' => 0,
                'type' => 'category',
                'slug_str' => $ndata['slug'],
                'force_this_slug' => (isset($ndata['force_this_slug']) ? $ndata['force_this_slug'] : 0)
            ]);

            if ($ndata['type'] == 2) {
                //get its parent tree, not only its parent..
                $ndata['slug'] = $ndata['slug'].'-section-partial';
            }
            $child = Category::create($ndata);
            $child->makeChildOf($parent);
            $response = $child;
            $this->backendHelper->addSlugToLookup(['id' => $response->id, 'type' => 'category', 'slug_str' => $response->slug, 'lang' => $ndata['lang'], 'website_id' => $response->website_id]);
        } else {
            unset($ndata['parent_id']);
            $ndata['slug'] = $this->backendHelper->returnUnsedSlug([
                'id' => 0,
                'type' => 'category',
                'slug_str' => $ndata['slug'],
                'force_this_slug' => (isset($ndata['force_this_slug']) ? $ndata['force_this_slug'] : 0)
            ]);

            if ($ndata['type'] == 2) {
                $ndata['slug'] = $ndata['slug'].'-section-partial';
            }
            $root = Category::create($ndata);
            $response = $root;
            $this->backendHelper->addSlugToLookup(['id' => $response->id, 'type' => 'category', 'slug_str' => $response->slug, 'lang' => $ndata['lang'], 'website_id' => $response->website_id]);
        }

        if (isset($ndata['group_id'])) {
            if (!$ndata['group_id']) {
                Category::where('id', '=', $response->id)->update(['group_id' => $response->id]);
                $response->group_id = $response->id;
            }
        } else {
            Category::where('id', '=', $response->id)->update(['group_id' => $response->id]);
            $response->group_id = $response->id;
        }
        $this->updateIdPath($response->id);

        return $response;
    }

    public function updateNode($node_id = 0, $ndata = array(), $additional = array())
    {
        $updDescIdPath = 1;
        $category = Category::findOrFail($node_id);
        //echo '<pre>';
        $ndata['slug'] = $this->backendHelper->returnUnsedSlug([
            'id' => $node_id,
            'type' => 'category',
            'slug_str' => $ndata['slug'],
            'force_this_slug' => (isset($ndata['force_this_slug']) ? $ndata['force_this_slug'] : 0)
        ]);

        unset($ndata['image'], $ndata['banner_image']);

        //dd($ndata);

        if ($category->parent_id == $ndata['parent_id']) {
            //all is well, update the details
            unset($ndata['parent_id']);
            $category->update($ndata);
            //echo '1<br/>';
            $updDescIdPath = 0;
        } elseif (($category->parent_id == 0) && ($ndata['parent_id'] != 0)) {
            //root becomes child
            $parent = Category::where('id', '=', $ndata['parent_id'])->first();
            unset($ndata['parent_id']);
            $category->update($ndata);
            $child = Category::where('id', '=', $node_id)->first();
            $child->makeChildOf($parent);
            //echo '2<br/>';
        } elseif (($category->parent_id != 0) && ($ndata['parent_id'] == 0)) {
            //child becomes root
            $child = Category::where('id', '=', $node_id)->first();
            $child->makeRoot();
            unset($ndata['parent_id']);
            $category->update($ndata);
            //echo '3<br/>';
        } else {
            //all other options
            $parent = Category::where('id', '=', $ndata['parent_id'])->first();
            unset($ndata['parent_id']);
            $category->update($ndata);
            $child = Category::where('id', '=', $node_id)->first();
            $child->makeChildOf($parent);
            //echo '4<br/>';
        }

        $this->backendHelper->addSlugToLookup(['id' => $node_id, 'type' => 'category', 'slug_str' => $ndata['slug'], 'lang' => $ndata['lang']]);
        $this->updateIdPath($node_id);
        if ($updDescIdPath) {
            $this->updateDescendantIdPath($node_id);
        }
        //echo '</pre>';

        //return Category::isValidNestedSet();
        //echo Category::isValidNestedSet();
        return $node_id;
    }

    /**
     * Updates the id path of a node
     * @ node id (category id)
     * returns null
     */
    public function updateIdPath($node_id = 0)
    {
        $id_path = [];
        $node = Category::where("id", $node_id)->first();
        if ($node) {
            $ancestors = $node->getAncestorsAndSelf(['id']);
            if ($ancestors) {
                $id_path = array_pluck($ancestors, 'id');
            }
        }
        if ($id_path) {
            $node->update(['id_path' => '/'.implode('/', $id_path).'/']);
        }
    }

    /**
     * Updates the id path of all node descendants
     * run only when a parent id has changed
     * @ node id (category id)
     * returns null
     */
    public function updateDescendantIdPath($parent_node_id = 0)
    {
        $desc_ids = [];
        $node = Category::where("id", $parent_node_id)->first();

        if ($node) {
            $descendants = $node->getDescendants(['id']);
            if ($descendants) {
                $desc_ids = array_pluck($descendants, 'id');
            }
        }
        if ($desc_ids) {
            foreach ($desc_ids as $node_id) {
                $this->updateIdPath($node_id);
            }
        }
    }

    /**
     * Uploads files on category nodes methods
     *
     */
    public function uploadFiles($category_id = 0, $request_obj)
    {
        if ($request_obj->hasFile('image') && $request_obj->file('image')->isValid()) {
            $this->categoryFileCleanUp($category_id, 'image');
            $this->doFileUpload($category_id, 'image', $request_obj->file('image'));
        }

        if ($request_obj->hasFile('banner_image') && $request_obj->file('banner_image')->isValid()) {
            $this->categoryFileCleanUp($category_id, 'banner_image');
            $this->doFileUpload($category_id, 'banner_image', $request_obj->file('banner_image'));
        }
    }

    public function doFileUpload($category_id = 0, $file_field, $file)
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.category_upload_options.settings.default_uploads');

        $media["original_name"] = $file->getClientOriginalName();
        $media["file_info"] = $file->getClientMimeType();
        $media["size"] = $file->getClientSize()/1024; // this converts it to kB
        $media["path"] = $this->subFolderID($category_id);

        $name_slug_ext = $this->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

        $media["name"] = $name_slug_ext["name"];
        $media["ext"] = '.'.$name_slug_ext["ext"];
        $media["name_ext"] = $name_slug_ext["name_ext"];

        $file->move($path_to_subfolder.$media["path"].'/', $media["name_ext"]);
        Category::where('id', $category_id)->update([$file_field => $media["name_ext"]]);
        //dd($media);
    }

    public function subFolderID($category_id = 0, $path_to_subfolder = 'uploads/categories/originals/')
    {
        $images_under_folder = \Config::get('dpoptions.category_upload_options.settings.images_under_folder');
        if ($images_under_folder > 0) {
        $subfolder_id = intval(floor($category_id / $images_under_folder) + 1);
        return $subfolder_id;
         }
    }

    public function prepareUniqueFilename($orig_name = '', $path_to_subfolder = 'uploads/originals/1/')
    {
        $path_parts = pathinfo($orig_name);
        $res['ext'] = $path_parts['extension'];
        $res['slug'] = str_slug($path_parts['filename'], '-');

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

    public function categoryFileCleanUp($category_id, $file_field = '')
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.category_upload_options.settings.default_uploads');
        $cat_dets = Category::where('id', $category_id)->first()->toArray();
        if ($cat_dets[$file_field]) {
            if (file_exists($path_to_subfolder.$this->subFolderID($category_id).'/'.$cat_dets[$file_field])) {
                unlink($path_to_subfolder.$this->subFolderID($category_id).'/'.$cat_dets[$file_field]);
            }
            //perhaps empty cache
            Category::where('id', $category_id)->update([$file_field => '']);
        }
    }

    public function imgUrl($category_id = 0, $img_str = '')
    {
        if (($category_id != 0) && (strlen($img_str))) {
            return \URL::to("/category-img").'/'.$this->subFolderID($category_id).'/'.$img_str;
        } else {
            return '';
        }
    }
    /**
     * Accepts the catergory tree collection and separator char
     *
     * returns an array indexed by the category id and valued by the sepdarator char
     * multipplied by the category depth
     */

    public function createCustomTree($collection, $sep = ' ', $additional = array())
    {
        $flat_tree = $this->flattenTree($collection);

        //dd($flat_tree);

        if (isset($additional['def_mess'])) {
            $ret_arr = [0 => $additional['def_mess']];
        } else {
            $ret_arr = [0 => 'Root Category'];
        }

        if (!empty($flat_tree)) {
            foreach ($flat_tree as $key => $row) {
                $ret_arr[$row['id']] = $this->depthStr($row['depth'], $sep).$row['name'];
            }
        }

        return $ret_arr;
    }

    /**
     * Accepts the category tree collection in array form
     *
     * returns the resulting one dimensions tree array for use in selection
     * and foreach functions
     */
    public function createContentCatTree($collection, $sep = ' ', $additional = array())
    {
        $flat_tree = $this->flattenTree($collection);
        $ret_arr = array();

        if (!empty($flat_tree)) {
            foreach ($flat_tree as $key => $row) {
                $ret_arr[$row['id']] = $this->depthStr($row['depth'], $sep).$row['name'];
            }
        }

        return $ret_arr;
    }

    /**
     * Accepts the category tree collection in array form
     *
     * returns the resulting one dimensions tree array for use lists, tables
     * and foreach functions
     */
    public function createFullContentCatTree($collection, $sep = ' ', $additional = array())
    {
        $flat_tree = $this->flattenTree($collection);
        $ret_arr = array();

        if (isset($additional['mark_leaves']) && ($additional['mark_leaves'] == 1)) {
            $markLeaves = 1;
            $roots = Category::ofType(0)->where("lang", "=", $additional['lang'])->where("depth", "=", 0)->get();
            foreach ($roots as $key => $root) {
                $tmpLeaves[$root->id] = $root->leaves()->select('id')->get()->toArray();
            }
            foreach ($tmpLeaves as $root_id => $leaves) {
                if (empty($leaves)) {
                    $leaveLike[] = $root_id;
                } else {
                    foreach ($leaves as $leaf) {
                        $leaveLike[] = $leaf['id'];
                    }
                }
            }
        } else {
            $markLeaves = 0;
            $leaveLike = array();
        }

        //var_dump($leaveLike);

        if (!empty($flat_tree)) {
            $idx = 0;
            foreach ($flat_tree as $key => $row) {
                $ret_arr[$idx] = $row;
                $ret_arr[$idx]['pos'] = $idx;
                $ret_arr[$idx]['depth_name'] = $this->depthStr($row['depth'], $sep).$row['name'];
                if ($markLeaves) {
                    if (in_array($row['id'], $leaveLike)) {
                        $ret_arr[$idx]['is_leaf'] = 1;
                    } else {
                        $ret_arr[$idx]['is_leaf'] = 0;
                    }
                }
                $idx++;
            }

            if (!empty($additional)) {
                if (isset($additional['allowed'])) {
                    foreach ($ret_arr as $key => $row) {
                        foreach ($row as $rkey => $value) {
                            if (!in_array($rkey, $additional['allowed'])) {
                                unset($ret_arr[$key][$rkey]);
                            }
                        }
                    }
                }
            }
        }

        return $ret_arr;
    }

    /**
     * Accepts the category tree collection in array form
     *
     * returns the resulting one dimensions tree array for use lists, tables
     * and foreach functions with the immediate parent prefixing its children
     */
    public function createFullContentCatTreev2($collection, $sep = ' ', $additional = array())
    {
        $flat_tree = $this->flattenTree($collection);
        $ret_arr = array();

        if (!empty($flat_tree)) {
            $idx = 0;
            $parent = [];
            foreach ($flat_tree as $key => $row) {
                if ($row['depth'] == 0) {
                    $parent = $row;
                }

                $ret_arr[$idx] = $row;
                $ret_arr[$idx]['pos'] = $idx;
                if ($row['id'] != $parent['id']) {
                    $ret_arr[$idx]['depth_name'] = $this->depthStr($row['depth'], $sep).$row['name'];
                    $ret_arr[$idx]['name'] = $parent['name'].' - '.$row['name'];
                }
                $idx++;
            }

            if (!empty($additional)) {
                if (isset($additional['allowed'])) {
                    foreach ($ret_arr as $key => $row) {
                        foreach ($row as $rkey => $value) {
                            if (!in_array($rkey, $additional['allowed'])) {
                                unset($ret_arr[$key][$rkey]);
                            }
                        }
                    }
                }
            }
        }

        return $ret_arr;
    }

    public function flattenTree($collection, $flatten_arr = array(), $allowed_types = array())
    {
        //$flatten_arr = array();

        if (!empty($collection)) {
            foreach ($collection as $row) {
                if (empty($allowed_types)) {
                    /*
                    $flatten_arr[$row['id']] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'depth' => $row['depth'],
                        'slug' => $row['slug'],
                        'trending' => $row['trending'],
                        'parent_id' => $row['parent_id'],
                        'description' => $row['description'],
                        'image' => $row['image'],
                        'banner_image' => $row['banner_image'],
                        'status' => $row['status'],
                        'act_as_leaf' => $row['act_as_leaf'],
                        'abbr' => $row['abbr'],
                        'id_path' => $row['id_path']
                    ];
                    */
                    $flatten_arr[$row['id']] = $row;
                    if (isset($row['children']) && !empty($row['children'])) {
                        //$flatten_arr = array_merge($flatten_arr, $this->flattenTree($row['children']));
                        $flatten_arr = $this->flattenTree($row['children'], $flatten_arr, $allowed_types);
                    }
                } else {
                    if (in_array($row['type'], $allowed_types)) {
                        /*
                        $flatten_arr[$row['id']] = [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'depth' => $row['depth'],
                            'slug' => $row['slug'],
                            'trending' => $row['trending'],
                            'parent_id' => $row['parent_id'],
                            'description' => $row['description'],
                            'image' => $row['image'],
                            'banner_image' => $row['banner_image'],
                            'abbr' => $row['abbr'],
                            'id_path' => $row['id_path']
                        ];
                        */
                        $flatten_arr[$row['id']] = $row;
                        if (isset($row['children']) && !empty($row['children'])) {
                            //$flatten_arr = array_merge($flatten_arr, $this->flattenTree($row['children']));
                            $flatten_arr = $this->flattenTree($row['children'], $flatten_arr, $allowed_types);
                        }
                    }
                }
            }
        }

        return $flatten_arr;
    }

    public function depthStr($depth = 0, $sep = ' ')
    {
        $str = '';
        for ($i = 1; $i <= $depth; $i++) {
            $str .= $sep;
        }
        return $str;
    }

    /**
     * Accepts the results of the pivot table between category and content (the taxonomy row)
     *
     * returns an array of category ids with descenting order
     */
    public function contentCats($cats)
    {
        $res = [];
        if (!empty($cats)) {
            foreach ($cats as $key => $row) {
                $res[] = $row['category_id'];
            }
        }

        return $res;
    }

    /**
     * Accepts the results of the pivot table between category and content (the taxonomy row)
     *
     * returns an array keyed bycategory ids and valued by priorit
     */
    public function contentCatPriority($cats)
    {
        $res = [];
        if (!empty($cats)) {
            foreach ($cats as $key => $row) {
                $res[$row['category_id']] = $row['priority'];
            }
        }

        return $res;
    }

    /**
     * Accepts a collection of categories
     * Returns an array organized into parents and children
     *      parents : an array keyed by the category id (in the collections correct order)
     *      children : array keyed by the parent category id with values of an array of all
     *                 of its descendants keyed by their corresponting cat ids
     */
    public function organizeToRootChildren($collection, $root_depth = null)
    {
        $res = ["roots" => [], "children" => []];
        $flattened = $this->flattenTree($collection);

        if (!empty($flattened)) {
            if (is_null($root_depth)) {
                $root_depth = $flattened[key($flattened)]['depth'];
            }
            $curr_root = 0;
            foreach ($flattened as $key => $row) {
                if ($row['depth'] == $root_depth) {
                    $res['roots'][$row['id']] = $row;
                    $curr_root = $row['id'];
                } else {
                    if (!isset($res['children'][$curr_root])) {
                        $res['children'][$curr_root] = [];
                    }
                    $res['children'][$curr_root][$row['id']] = $row;
                }
            }
        }

        return $res;
    }

    /**
     * The following method is directly linked to ContentHelper categorySelection method
     * Based on the currently select content type it will filter the available categories related to it
     *
     *  returns the category tree
     */
    public function filterCategoriesPerContent($data = array())
    {
        $contentTypes = Config::get('dpoptions.content_types');
        $contentTypesAbbr = array_pluck($contentTypes, "abbr", "abbr");
        $including_ids = [];
        $excluding_ids = [];
        if (!isset($data['of_type'])) {
            $data['of_type'] = [0,1,2];
        }

        if (isset($data['scope'])) {
            if (isset($contentTypesAbbr[$data['scope']])) {
                if (isset($contentTypes[$data['scope']])) {
                    $contentDets = $contentTypes[$data['scope']]['settings'];
                    if (isset($contentDets['categories'])) {
                        $contentCats = $contentDets['categories'];
                        if (!isset($contentCats['including'])) {
                            $contentCats['including'] = array();
                        }
                        if (!isset($contentCats['excluding'])) {
                            $contentCats['excluding'] = array();
                        }
                        if (empty($contentCats['including']) && empty($contentCats['excluding'])) {
                            $allCats = true;
                        } elseif (!empty($contentCats['including']) && empty($contentCats['excluding'])) {
                            //get root and children using the root abbr of including
                            $including_ids = [];
                            $excluding_ids = [];
                            foreach ($contentCats['including'] as $key => $value) {
                                $node = Category::where('slug', $value)->where("lang", "=", $data['lang'])->first();
                                if ($node) {
                                    $catSet = $node->getDescendantsAndSelf();
                                    if ($catSet) {
                                        $catSetIds = array_pluck($catSet, 'id');
                                        foreach ($catSetIds as $key => $catId) {
                                            $including_ids[] = $catId;
                                        }
                                    }
                                }
                            }
                            $allCats = false;
                        } elseif (empty($contentCats['including']) && !empty($contentCats['excluding'])) {
                            //get root and children using the root abbr of excluding
                            $including_ids = [];
                            $excluding_ids = [];
                            foreach ($contentCats['excluding'] as $key => $value) {
                                $node = Category::where('slug', $value)->where("lang", "=", $data['lang'])->first();
                                if ($node) {
                                    $catSet = $node->getDescendantsAndSelf();
                                    if ($catSet) {
                                        $catSetIds = array_pluck($catSet, 'id');
                                        foreach ($catSetIds as $key => $catId) {
                                            $excluding_ids[] = $catId;
                                        }
                                    }
                                }
                            }
                            $allCats = false;
                        } else {
                            //get root and children using the root abbr of including
                            //get root and children using the root abbr of excluding
                            $including_ids = [];
                            foreach ($contentCats['including'] as $key => $value) {
                                $node = Category::where('slug', $value)->where("lang", "=", $data['lang'])->first();
                                if ($node) {
                                    $catSet = $node->getDescendantsAndSelf();
                                    if ($catSet) {
                                        $catSetIds = array_pluck($catSet, 'id');
                                        foreach ($catSetIds as $key => $catId) {
                                            $including_ids[] = $catId;
                                        }
                                    }
                                }
                            }
                            $excluding_ids = [];
                            foreach ($contentCats['excluding'] as $key => $value) {
                                $node = Category::where('slug', $value)->where("lang", "=", $data['lang'])->first();
                                if ($node) {
                                    $catSet = $node->getDescendantsAndSelf();
                                    if ($catSet) {
                                        $catSetIds = array_pluck($catSet, 'id');
                                        foreach ($catSetIds as $key => $catId) {
                                            $excluding_ids[] = $catId;
                                        }
                                    }
                                }
                            }
                            $allCats = false;
                        }
                    } else {
                        $allCats = true;
                    }
                } else {
                    $allCats = true;
                }
            } else {
                $allCats = true;
            }
        } else {
            $allCats = true;
        }

        if ($allCats) {
            //$catTree = Category::ofType($data['of_type'])->where("lang", "=", $data['lang'])->get()->toHierarchy();
            $catTree = $this->custTree([
                'type' => $data['of_type'],
                'lang' => $data['lang']
            ]);
        } else {
            $including_ids = array_unique($including_ids);
            $excluding_ids = array_unique($excluding_ids);
            /*
            $query = Category::ofType($data['of_type'])->where("lang", "=", $data['lang']);
            if (!empty($including_ids)) {
                $query->whereIn('id', $including_ids);
            }
            if (!empty($excluding_ids)) {
                $query->whereNotIn('id', $excluding_ids);
            }
            $catTree = $query->get()->toHierarchy();
            */
            $catTree = $this->custTree([
                'type' => $data['of_type'],
                'lang' => $data['lang'],
                'including' => $including_ids,
                'excluding' => $excluding_ids
            ]);
        }

        return $catTree;
    }

    /* Periklis for knowcrunch */
     public function filterBlockCategoriesPerContent($data = array())
    {
        $contentTypes = Config::get('dpoptions.content_types');
        $contentTypesAbbr = array_pluck($contentTypes, "abbr", "abbr");
        $including_ids = [];
        $excluding_ids = [];
        if (!isset($data['of_type'])) {
            $data['of_type'] = [0,1,2];
        }

        $blockrootCat = $data['blockrootCat'];

	        $node = Category::where('slug', $blockrootCat)->where("lang", "=", $data['lang'])->first();
	        if ($node) {
	            $catSet = $node->getDescendantsAndSelf();
	            if ($catSet) {
	                $catSetIds = array_pluck($catSet, 'id');
	                foreach ($catSetIds as $key => $catId) {
	                    $including_ids[] = $catId;
	                }
	            }
	        }

            $including_ids = array_unique($including_ids);
            $excluding_ids = array_unique($excluding_ids);

            $catTree = $this->custTree([
                'type' => $data['of_type'],
                'lang' => $data['lang'],
                'including' => $including_ids,
                'excluding' => $excluding_ids
            ]);

        return $catTree;
    }

    public function setBanner($input = array())
    {
        $res = ['set' => false, 'status' => 1];

        if (!isset($input['content_type'])) {
            $input['content_type'] = 0;
        }

        if ($input['op_type'] == "add") {

            if ($input['category_id']) {
                $category = Category::where('id', $input['category_id'])->first();
                if ($category) {
                    //all is well
                } else {
                    $category = Category::create(['type' => 0, 'lang' => $input['lang']]);
                }
            } else {
                $category = Category::create(['type' => 0, 'lang' => $input['lang']]);
            }

            // group id is 0, define it
            if (!$input['group_id']) {
                $category->update(['group_id' => $category->id]);
            } else {
                $category->update(['group_id' => $input['group_id']]);
            }

            //$categoryMedia = CategoryMedia::updateOrCreate(['category_id' => $category->id], ['media_id' => $input['media_id'], 'type' => $input['set_as_type']]);
            $categoryMedia = CategoryMedia::where('category_id', $category->id)->where('type', $input['set_as_type'])->first();
            if ($categoryMedia) {
                $categoryMedia->update(['media_id' => $input['media_id']]);
            } else {
                $categoryMedia = CategoryMedia::create(['category_id' => $category->id, 'media_id' => $input['media_id'], 'type' => $input['set_as_type']]);
            }
            $media = Media::select('id','path','name','ext')->findOrFail($input['media_id'])->toArray();
            $res['set'] = true;
            $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
            $res['media_id'] = $media['id'];
            $res['category_id'] = $category->id;
            $res['group_id'] = $category->group_id;
        } elseif ($input['op_type'] == "delete_media") {
            $categoryMedia = CategoryMedia::select('id')->where('category_id', $input['category_id'])->where('media_id', $input['media_id'])->where('type', $input['type'])->first();
            if ($categoryMedia) {
                CategoryMedia::where('id', $categoryMedia->id)->delete();
                $res['set'] = true;
                $res['delete'] = true;
            } else {
                $res['delete'] = false;
            }
        } elseif ($input['op_type'] == "fetch_media") {
            $categoryMedia = CategoryMedia::where('category_id', $input['category_id'])->where('type', $input['type'])->get();

            if ($categoryMedia) {
                foreach ($categoryMedia as $key => $row) {
                    $media = Media::select('id','path','name','ext')->findOrFail($row->media_id)->toArray();
                    $res['set'] = true;
                    $res['thumb_url'] = \URL::to("/").'/featured_image/'.$media['path'].'/'.$media['name'].$media['ext'];
                    $res['media_id'] = $media['id'];
                    break;
                }
            } else {
                $res['set'] = false;
            }
            $res['category_id'] = $input['category_id'];
        } else {}

        return $res;
    }

    public function custTree($options = array())
    {
        $defaults = [
            "lang" => 'el',
            "website_id" => 1,
            "type" => [0],
            "status" => "any",
            "including" => [],
            "excluding" => [],
            "depth" => [],
            "id_path" => '',
            "take" => 0,
            "skip" => 0,
            "op_type" => 'default'
        ];

        $data = array_merge($defaults, $options);

        $query = Category::whereIn('type', $data['type']);
        $query->where('lang', $data['lang']);
        $query->where('website_id', $data['website_id']);

        if (!empty($data['including'])) {
            $query->whereIn('id', $data['including']);
        }

        if (!empty($data['excluding'])) {
            $query->whereNotIn('id', $data['excluding']);
        }

        if (($data['status'] != "any") && (is_array($data['status']))) {
            $query->whereIn('status', $data['status']);
        }

        $query->select('id','parent_id','depth','id_path','name','slug','description');

        switch ($data['op_type']) {
            case 'case1':
                # code...
                break;
            default:
                $query->orderBy('lang','asc');
                $query->orderBy('priority', 'asc');
                $query->orderBy('parent_id', 'asc');
                $query->orderBy('name','asc');
                break;
        }

        $res = $query->get();

        if ($res) {
            $res = $res->toArray();
        }

        return $this->traverseTree($res, 'id', 'parent_id', 0);
    }

    public function traverseTree($cats = array(), $cat_id = 'id', $cat_parent_id = 'parent_id', $root_id = 0)
    {
        $res = [];
        $retdata = array();
        $retdata['sorted_list_arr'] = array();
        $retdata['allcatbyid'] = array();

        if (empty($cats)) {
            return array();
        } else {
            $operation = "def";
            foreach ($cats as $key => $row) {
                $cat_pars[$row[$cat_parent_id]][] = $row;
                $retdata['allcatbyid'][$row[$cat_id]] = $row;
            }

            if (isset($cat_pars[$root_id])) {
                $tree_data['tree'] = $this->createTree($cat_pars, $cat_pars[$root_id]);
            } elseif (isset($cat_pars[0])) {
                $tree_data['tree'] = $this->createTree($cat_pars, $cat_pars[0]);
            } else {
                $operation = "no_parents";

                foreach ($cat_pars as $parent_cat_id => $children) {
                    if (count($children) > 1) {
                        $operation = "def";
                        break;
                    }
                }

                if ($operation == "def") {
                    reset($cat_pars);
                    $first_key = key($cat_pars);
                    $tree_data['tree'] = $this->createTree($cat_pars, $cat_pars[$first_key]);
                } else {}
            }

            switch ($operation) {
                case "def":
                    $retdata['sorted_list_arr'] = $this->parseTree($tree_data['tree']);
                    break;
                case "no_parents":
                    foreach ($cats as $key => $row) {
                        $retdata['sorted_list_arr'][$key] = $row[$cat_id];
                    }
                    break;
            }

            if (!empty($retdata['sorted_list_arr'])) {
                foreach ($retdata['sorted_list_arr'] as $tkey => $tcat_id) {
                    if (isset($retdata['allcatbyid'][$tcat_id])) {
                        $res[] = $retdata['allcatbyid'][$tcat_id];
                    }
                }
            }

            return $res;
        }
    }

    public function createTree(&$cat_pars, $parent, $cat_id = 'id', $cat_parent_id = 'parent_id')
    {
        $tree = array();
        foreach ($parent as $key => $row) {
            if (isset($cat_pars[$row[$cat_id]])) {
                $row['children'] = $this->createTree($cat_pars, $cat_pars[$row[$cat_id]], $cat_id, $cat_parent_id);
            }
            $tree[] = $row;
        }
        return $tree;
    }

    public function parseTree($node, $cat_id = 'id', $cat_parent_id = 'parent_id')
    {
        $results_arr = array();
        foreach ($node as $key => $row) {
            $results_arr[] = $row[$cat_id];
            if (isset($row['children'])) {
                $results_arr = array_merge($results_arr, $this->parseTree($row['children'], $cat_id, $cat_parent_id));
            }
        }
        return $results_arr;
    }
}

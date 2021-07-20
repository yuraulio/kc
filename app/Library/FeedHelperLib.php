<?php

namespace Library;

use Config;
use File;

use Illuminate\Support\Facades\Facade;
use Cartalyst\Sentinel\Sentinel;

use PostRider\CategoryContent;
use PostRider\Content;
use PostRider\Media;
use PostRider\MediaDetails;

use Library\BackendHelperLib;
use Library\MediaUploaderLib;

class FeedHelperLib
{
    public function __construct(BackendHelperLib $backendHelper, MediaUploaderLib $mediaUploader)
    {
        $this->backendHelper = $backendHelper;
        $this->mediaUploader = $mediaUploader;
    }

    public function getInshapeLatestPosts($limit = 10)
    {
        $base = 'http://inshape.com.cy/';
        $partial = 'feed/json/Rn9u3AtzHWZWFmpcbhZgLirTshXskIG0nv88YkNaw0QVKbvnj6wZ33p3O93MR5im/1/post/1/'.$limit;
        $json_response = file_get_contents($base.$partial);

        if (strlen($json_response) > 9) {
            $response = json_decode($json_response, true);
        } else {
            $response = array();
        }

        return $this->parseInshapeResponse($response);
    }

    public function parseInshapeResponse($response = array())
    {
        $post_list = [];
        if (!empty($response)) {
            if (isset($response['post_list']) && isset($response['post_list']['list'])) {
                $idx = count($response['post_list']['list']) - 1;
                foreach ($response['post_list']['list'] as $key => $row) {
                    $post_list[$idx] = $row;
                    $post_list[$idx]['content'] = $this->getSinglePost($row['post_id']);
                    $idx--;
                }
            }
        }
        return $post_list;
    }

    public function getSinglePost($post_id = 0)
    {
        $base = 'http://inshape.com.cy/';
        $partial = 'feed/json/Rn9u3AtzHWZWFmpcbhZgLirTshXskIG0nv88YkNaw0QVKbvnj6wZ33p3O93MR5im/3/post/'.$post_id;

        if ($post_id) {
            $json_response = file_get_contents($base.$partial);

            if (strlen($json_response) > 9) {
                $response = json_decode($json_response, true);
            } else {
                $response = array();
            }
        }

        if (!empty($response)) {
            return $response['post']['list'][0];
        } else {
            return [];
        }
    }

    public function insertOrUpdatePostList($post_list = array())
    {
        if (!empty($post_list)) {
            foreach ($post_list as $key => $row) {
                if (!empty($row)) {
                    //dd($row);
                    //check if the slug exists in our db
                    $content = Content::where('ext_url', $row['url'])->first();
                    //dd($content);
                    if (!empty($row['content'])) {
                        if (is_null($content)) {
                            //create
                            //set featured
                            $content = Content::create([
                                'title' => $row['title'],
                                'slug' => $this->backendHelper->returnUnsedSlug([
                                    'slug_str' => $row['slug'],
                                    'type' => 'content',
                                    'id' => 0,
                                ]),
                                'excerpt' => $row['description'],
                                'target' => '_self',
                                'ext_url' => $row['url'],
                                'body' => $row['content']['description'],
                                'type' => 1,
                                'lang' => 'el',
                                'status' => 1,
                                'creator_id' => 1,
                                'author_id' => 1,
                                'published_at' => $row['updated_at'],
                                'meta_title' => $row['content']['meta_title'],
                                'meta_description' => $row['content']['meta_description'],
                                'meta_keywords' => $row['content']['meta_keywords'],
                            ]);

                            $attachToInshape = CategoryContent::create([
                                'content_id' => $content->id,
                                'category_id' => 17,
                                'type' => 0,
                                'priority' => 0,
                            ]);

                            $this->backendHelper->addSlugToLookup(['id' => $content->id, 'type' => 'content', 'slug_str' => $content->slug, 'lang' => $content->lang]);

                            //dd($attachToInshape);
                            $this->setInshapeFeatured($content, $row);
                        } else {
                            //update ???
                        }
                    }
                }
            }
        }
    }

    public function setInshapeFeatured($content, $post = array())
    {
        if (isset($post['thumbnail']['src'])) {
            $img_from_url = $this->mediaUploader->getFileFromUrl($post['thumbnail']['src']);
            //var_dump($img_from_url);

            $path_to_subfolder = base_path('public/');
            $path_to_subfolder .= Config::get('dpoptions.media_upload_options.settings.default_uploads');

            $media["original_name"] = $img_from_url['tmp_name'];
            $media["file_info"] = $this->mediaUploader->getFileInfo($img_from_url['path']);

            $media["size"] = filesize($img_from_url['path'])/1024; // this converts it to kB

            $db_media = Media::create($media);

            $media["id"] = $db_media->id;
            $media["path"] = $this->mediaUploader->subFolderID($media["id"]);
            $media["name"] = $img_from_url["name"];
            $media["ext"] = '.'.$img_from_url["ext"];
            $media["name_ext"] = $img_from_url["name_ext"];
            $media["type"] = 0;
            $img_dets = getimagesize($img_from_url['path']);
            $media['width'] = $img_dets[0];
            $media['height'] = $img_dets[1];
            //var_dump($media);

            //$move_status = move_uploaded_file($img_from_url['path'], $path_to_subfolder.$media["path"].'/'.$media["name_ext"]);
            $move_status = File::move($img_from_url['path'], $path_to_subfolder.$media["path"].'/'.$media["name_ext"]);

            $db_media = Media::findOrFail($media["id"])->update($media);
            MediaDetails::create(['media_id' => $media["id"], 'lang' => $content->lang, 'title' => $media["original_name"]]);

            $createRes = $this->mediaUploader->createVersions($path_to_subfolder.$media["path"].'/'.$media["name_ext"], $media);

            $featuredRes = $this->backendHelper->setFeatured([
                'op_type' => 'add_existing',
                'content_id' => $content->id,
                'media_id' => $media["id"]
            ]);

            //dd($featuredRes);
            //return $featuredRes;
            return 1;
        } else {
            return 0;
        }
    }
}

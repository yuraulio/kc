<?php

namespace Library;

use Config;

use PostRider\Media;
use PostRider\MediaDetails;

use Library\BackendHelperLib;
use Library\CategoryHelperLib;
use Library\CustomFieldHelperLib;
use Library\FrontendHelperLib;
use Library\LanguageHelperLib;
use Sentinel;

class MediaUploaderLib
{
    public function __construct(BackendHelperLib $backendHelper, FrontendHelperLib $frontHelper, CustomFieldHelperLib $customFieldHelper, CategoryHelperLib $categoryHelper, LanguageHelperLib $langHelper)
    {
        $this->backendHelper = $backendHelper;
        $this->frontHelper = $frontHelper;
        $this->cFieldLib = $customFieldHelper;
        $this->categoryHelper = $categoryHelper;
        $this->langHelp = $langHelper;
    }

    public function handleFileUpload($request, $input_field = "dp_fileupload", $adata = array())
    {
        $all_input = $request->all();
        unset($all_input[$input_field]);
        $adata = array_merge($adata, $all_input);

        if (is_array($request->file($input_field))) {
            foreach ($request->file($input_field) as $key => $row) {
                $files[] = $this->uploadFile($row, $adata);
            }
            return $files;
        } else {
            return $this->uploadFile($request->file($input_field), $adata);
        }
    }

    /**
     * File upload handler. Gets called when the dropzone media upload methods are used
     *
     */
    public function uploadFile($file, $adata = array())
    {
        
        if ($file->isValid()) {
            $path_to_subfolder = base_path('public/');
            $path_to_subfolder .= Config::get('dpoptions.media_upload_options.settings.default_uploads');

            $media["original_name"] = $file->getClientOriginalName();
            $media["file_info"] = $file->getClientMimeType();
            $media["size"] = $file->getClientSize()/1024; // this converts it to kB

            $db_media = Media::create($media);

            $media["id"] = $db_media->id;
            $media["path"] = $this->subFolderID($media["id"]);

            $name_slug_ext = $this->backendHelper->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

            $media["name"] = $name_slug_ext["name"];
            $media["ext"] = '.'.$name_slug_ext["ext"];
            $media["name_ext"] = $name_slug_ext["name_ext"];

            $file->move($path_to_subfolder.$media["path"].'/', $media["name_ext"]);

            if (substr_count($media["file_info"], 'image')) {
                if (isset($adata['advert']) && ($adata['advert'] == 1)) {
                    $media["type"] = 9; // this means that this is an advert image / banner
                } else {
                    $media["type"] = 0; // this means that this is an image
                }
                //$media['dpi'] = $this->getImageDPI($path_to_subfolder.$media["path"].'/', $media["name"]);
                $media['width'] = \Image::make($path_to_subfolder.$media["path"].'/'.$media["name_ext"])->width();
                $media['height'] = \Image::make($path_to_subfolder.$media["path"].'/'.$media["name_ext"])->height();
                $createRes = $this->createVersions($path_to_subfolder.$media["path"].'/'.$media["name_ext"], $media);
                $res = ['media_id' => $media["id"], "media" => $media, "errors" => $createRes["errors"], "urls" => $createRes["urls"]];
            } else {
                $media["type"] = 1; // this means that this is a file
                $createRes = $this->fileVersions($path_to_subfolder.$media["path"].'/'.$media["name_ext"], $media);
                $res = ['media_id' => $media["id"], "media" => $media, "errors" => $createRes["errors"], "urls" => $createRes["urls"]];
            }

            $db_media = Media::findOrFail($media["id"])->update($media);
            MediaDetails::create(['media_id' => $media["id"], 'lang' => $adata["lang"], 'title' => $media["original_name"]]);

            if (!isset($adata['content_type'])) {
                $adata['content_type'] = 0;
            }

            $adata['content_group_id'] = ((isset($adata['content_group_id'])) ? $adata['content_group_id'] : 0);
            $adata['content_lang'] = ((isset($adata['content_lang'])) ? $adata['content_lang'] : $this->langHelp->defLang);
            $adata['content_website_id'] = ((isset($adata['content_website_id'])) ? $adata['content_website_id'] : $this->langHelp->defWebsite);
            $adata['content_type'] = ((isset($adata['content_type'])) ? $adata['content_type'] : 0);
            $adata['content_abbr'] = ((isset($adata['content_abbr'])) ? $adata['content_abbr'] : '');
            $adata['scope'] = ((isset($adata['scope'])) ? $adata['scope'] : 'post');

            if (isset($adata['set_featured']) && $adata['set_featured'] && empty($createRes["errors"])) {
                $featuredRes = $this->backendHelper->setFeatured([
                    'op_type' => 'add',
                    'content_id' => $adata['content_id'],
                    'content_group_id' => $adata['content_group_id'],
                    'content_lang' => $adata['content_lang'],
                    'content_website_id' => $adata['content_website_id'],
                    'content_type' => $adata['content_type'],
                    'content_abbr' => $adata['content_abbr'],
                    'media_id' => $media["id"],
                    'scope' => $adata['scope']
                ]);
                $res = array_merge($res, $featuredRes);
                return $res;
            } elseif (isset($adata['set_alternative']) && $adata['set_alternative'] && empty($createRes["errors"])) {
                $featuredRes = $this->backendHelper->setAlternative([
                    'op_type' => 'add',
                    'content_id' => $adata['content_id'],
                    'content_type' => $adata['content_type'],
                    'section_id' => $adata['section_id'],
                    'media_id' => $media["id"]
                ]);
                $res = array_merge($res, $featuredRes);
                return $res;
            } elseif (isset($adata['cust_field_media']) && $adata['cust_field_media'] && empty($createRes["errors"])) {
                $featuredRes = $this->cFieldLib->setCustFieldMedia([
                    'op_type' => 'add',
                    'content_id' => $adata['content_id'],
                    'content_group_id' => $adata['content_group_id'],
                    'content_lang' => $adata['content_lang'],
                    'content_website_id' => $adata['content_website_id'],
                    'content_type' => $adata['content_type'],
                    'media_id' => $media["id"],
                    'cust_field_id' => $adata['cust_field_id'],
                    'scope' => $adata['scope']
                ]);
                $res = array_merge($res, $featuredRes);
                return $res;
            } elseif (isset($adata['set_category_media']) && $adata['set_category_media'] && isset($adata['set_category_type']) && isset($adata['group_id']) && isset($adata['lang']) && empty($createRes["errors"])) {
                $featuredRes = $this->categoryHelper->setBanner([
                    'op_type' => 'add',
                    'category_id' => $adata['category_id'],
                    'group_id' => $adata['group_id'],
                    'lang' => $adata['lang'],
                    'content_type' => $adata['content_type'],
                    'media_id' => $media["id"],
                    'set_as_type' => $adata['set_category_type']
                ]);
                $res = array_merge($res, $featuredRes);
                return $res;
            } else {

                if (isset($adata['student_avatar'])) {
                    if (isset($adata['stid']) && $adata['stid'] != '') {
                        $student = Sentinel::getUser();
                        if($student->avatar > 0) {
                            $oldavatar = Media::find($student->avatar);
                            $oldavatardetails = MediaDetails::where('media_id', '=', $student->avatar)->first();

                            $oldavatar->delete();
                            $oldavatardetails->delete();
                        }
                        $student->avatar = $media["id"];
                        $student->save();
                        //$media["id"]
                    }
                    //$adata['content_type'] = 0;
                }
                return $res;


            }
        } else {
            return ['failure'];
        }
    }

    /**
     * Add video handler. Gets called via the add / edit video content
     *
     */
    public function addVideo($vdata = array(), $adata = array())
    {
        $vimg = [];
        if (isset($vdata['images']) && !empty($vdata['images'])) {

            foreach ($vdata['images'] as $key => $row) {
                $vimg['url'] = $row['url'];
                $vimg['size'] = $row['size'];
                $vimg['width'] = $row['width'];
                $vimg['height'] = $row['height'];
                break;
            }

            $img_from_url = $this->getFileFromUrl($vimg['url']);

            if ($img_from_url['status']) {
                $path_to_subfolder = base_path('public/');
                $path_to_subfolder .= Config::get('dpoptions.media_upload_options.settings.default_uploads');

                //$media["original_name"] = str_slug($this->frontHelper->truncateOnSpace($vdata['title']), 200).'.'.$img_from_url['ext'];
                $media["original_name"] = str_slug($vdata['title']).'.'.$img_from_url['ext'];
                $media["file_info"] = $this->getFileInfo($img_from_url['path']);
                $media["size"] = $vimg['size']/1024; // this converts it to kB

                $db_media = Media::create($media);

                $media["id"] = $db_media->id;
                $media["path"] = $this->subFolderID($media["id"]);

                $name_slug_ext = $this->backendHelper->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

                $media["name"] = $name_slug_ext["name"];
                $media["ext"] = '.'.$name_slug_ext["ext"];
                $media["name_ext"] = $name_slug_ext["name_ext"];

                //$move_status = move_uploaded_file($img_from_url['path'], $path_to_subfolder.$media["path"].'/'.$media["name_ext"]);
                $move_status = \File::move($img_from_url['path'], $path_to_subfolder.$media["path"].'/'.$media["name_ext"]);

                $media["type"] = 2; // this means that this is a video
                $media["details"] = json_encode($vdata);
                $media["video_provider"] = $vdata['provider_name'];
                $media["video_url"] = $vdata['url'];
                $media['width'] = $vimg['width'];
                $media['height'] = $vimg['height'];

                $db_media = Media::findOrFail($media["id"])->update($media);
                MediaDetails::create(['media_id' => $media["id"], 'lang' => $adata["lang"], 'title' => $media["original_name"]]);

                $createRes = $this->createVersions($path_to_subfolder.$media["path"].'/'.$media["name_ext"], $media);

                $res = ['media_id' => $media["id"],
                    "media" => $media,
                    "errors" => $createRes["errors"],
                    "urls" => $createRes["urls"],
                    //"src" => $img_from_url['path'],
                    //"dest" => $path_to_subfolder.$media["path"].'/'.$media["name_ext"],
                    ///"move_status" => $move_status,
                ];

                $featuredRes = $this->backendHelper->setFeatured(['op_type' => 'add', 'content_id' => $adata['content_id'], 'media_id' => $media["id"]]);
                $res = array_merge($res, $featuredRes);

                return $res;
            }
        } else {
            return ['failure'];
        }
    }

    /**
     * Extract file extension
     *
     */
    public function getFileExt($str = '')
    {
        if ($str) {
            $img_parts = explode(".", $str);
            return end($img_parts);
        } else {
            return 'tmp';
        }
    }

    /*
     * Use file info to get the mime type
     */
    public function getFileInfo($path_to_file = '')
    {
        $finfo = finfo_open();
        $fileinfo = finfo_file($finfo, $path_to_file, FILEINFO_MIME);
        finfo_close($finfo);

        return $fileinfo;
    }

    /**
     * Get file form url
     *
     */
    public function getFileFromUrl($url = '')
    {
        $path_to_subfolder = base_path('public/uploads/tmp/');
        $this->backendHelper->createDir($path_to_subfolder);
        //$name_slug_ext = $this->backendHelper->prepareUniqueFilename($url, 'uploads/originals/1/');
        $res['status'] = 0;

        if ($url) {
            $res['ext'] = $this->getFileExt($url);
            $res['name'] = time().'-'.str_random(5);
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

    public function createVersions($srcPath = '', $media = array())
    {
        $img_versions = Config::get('dpoptions.image_versions');
        $errors = array();
        $warnings = array();
        $urls = array();

        if (!empty($img_versions)) {
            foreach ($img_versions as $version_name => $options) {
                if (!empty($options['settings'])) {
                    $version = $options['settings'];
                    $path_to_subfolder = base_path('public/').$version['path'].$media["path"].'/';

                    if ($this->backendHelper->createDir($path_to_subfolder)) {
                        $destPath = $path_to_subfolder.$media['name'].$media['ext'];
                        $urls[$version_name] = asset($version['path'].$media["path"].'/'.$media['name'].$media['ext']);

                        if ($version["crop_and_center"]) {
                            if ($media['width'] > $media['height']) {
                                //landscape, resize by height
                                \Image::make($srcPath)->heighten($version['height'])->crop($version['width'], $version['height'])->save($destPath, $version['quality']);
                            } elseif ($media['width'] < $media['height']) {
                                //portrait, resize by width
                                \Image::make($srcPath)->widen($version['width'])->crop($version['width'], $version['height'])->save($destPath, $version['quality']);
                            } else {
                                //square, all is well
                                \Image::make($srcPath)->resize($version['width'], $version['height'])->save($destPath, $version['quality']);
                            }
                        } else {
                            \Image::make($srcPath)->widen($version['width'], function ($constraint) {
                                $constraint->upsize();
                            })->save($destPath, $version['quality']);
                        }
                    }
                } else {
                    $errors[] = $version_name.' has no image version settings';
                }
            }
        } else {
            $errors[] = 'No image versions';
        }

        return ["errors" => $errors, "urls" => $urls, "warnings" => $warnings];
    }

    public function fileVersions($srcPath = '', $media = array())
    {
        $errors = array();
        $warnings = array();
        $urls = array();
        $file_versions = Config::get('dpoptions.file_versions');
        $cleanExt = explode(".", $media["ext"])[1];
        $path_to_subfolder = Config::get('dpoptions.media_upload_options.settings.default_uploads');

        if (isset($file_versions['settings'][$cleanExt])) {
            $version = $file_versions['settings'][$cleanExt];
            $urls[$cleanExt] = asset($path_to_subfolder.$media['path'].'/'.$media['name_ext']);
            $urls["icon"] = $version["icon"];
            $urls["image"] = $version["image"];
            $urls["thumb"] = asset($version["image"]);
        } else {
            $warnings[] = $cleanExt.' has no file version settings';
            $version = $file_versions['settings']["generic"];
            $urls["generic"] = asset($path_to_subfolder.$media['path'].'/'.$media['name_ext']);
            $urls["icon"] = $version["icon"];
            $urls["image"] = $version["image"];
            $urls["thumb"] = asset($version["image"]);
        }

        return ["errors" => $errors, "urls" => $urls, "warnings" => $warnings];
    }

    public function subFolderID($media_id = 0, $path_to_subfolder = 'uploads/originals/')
    {
        $images_under_folder = Config::get('dpoptions.media_upload_options.settings.images_under_folder');
        $subfolder_id = intval(floor($media_id / $images_under_folder) + 1);
        $this->backendHelper->createDir($path_to_subfolder.$subfolder_id);
        return $subfolder_id;
    }

    public function getImageDPI($path_to_file = '')
    {
        $image_res_y = 0;
        $image_res_x = 0;

        if (extension_loaded('imagick') || class_exists("Imagick"))
        {
            $resource = new Imagick($path_to_file);
            //$imageResolution = $resource->getImageResolution();
            //$imageUnits = $resource->getImageUnits();
            $imageIdentity = $resource->identifyImage();

            switch ($imageIdentity['units'])
            {
                case 'PixelsPerInch':
                    $image_res_y = $imageIdentity['resolution']['y'];
                    $image_res_x = $imageIdentity['resolution']['x'];
                    break;
                case 'PixelsPerCentimeter':
                    $image_res_y = intval(round($imageIdentity['resolution']['y'] * 2.54, 2));
                    $image_res_x = intval(round($imageIdentity['resolution']['x'] * 2.54, 2));
                    break;
                default:
                    $image_res_y = $imageIdentity['resolution']['y'];
                    $image_res_x = $imageIdentity['resolution']['x'];
                    break;
            }
        }

        $imageIdentity['dpi'] = $image_res_y;

        return $imageIdentity['dpi'];
    }

    public function handleFolder($request)
    {
        $indata = $request->all();

        switch ($indata['op_type']) {
            case 'create':
                $res = ['status' => $this->createFolder($indata), 'op_type' => 'create'];
                break;
            case 'rename':
                $res = ['status' => $this->renameFolder($indata), 'op_type' => 'rename'];
                break;
            case 'selected':
                $res = ['status' => 0, 'op_type' => 'selected'];
                break;
            default:
                $res = ['status' => 0, 'op_type' => 'default'];
                break;
        }

        return $res;
    }

    public function createFolder($data = array())
    {
        $paths = $this->setupPaths($data);
        $new_dir_clean = $paths['parent_dir'].'/'.$paths['new_folder_name'];

        return [mkdir($new_dir_clean, 0775, true), $new_dir_clean];
    }

    public function renameFolder($data = array())
    {
        $paths = $this->setupPaths($data);
        $old_dir = $paths['parent_dir'].'/'.$data['folder_name'];

        return rename($old_dir, $paths['new_dir']);
    }

    public function setupPaths($data = array())
    {
        $dir_path_arr = explode("/", $data['dir_path']);
        $dir_path = '';

        if (!empty($dir_path_arr)) {
            foreach ($dir_path_arr as $key => $row) {
                if ($key > 0) {
                    $dir_path .= str_replace("\n", " ", trim($row))."/";
                }
            }
        }

        $new_dir = base_path().'/public/project_uploads/'.$dir_path;

        return [
            "new_dir" => $new_dir,
            "new_folder_name" => basename($new_dir),
            "parent_dir" => dirname($new_dir)
        ];
    }

    public function php_file_tree($directory, $return_link, $extensions = array())
    {
        // Generates a valid XHTML list of all directories, sub-directories, and files in $directory
        // Remove trailing slash
        $code = '';
        if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
        $code .= $this->php_file_tree_dir($directory, $return_link, $extensions);
        return '<ul><li>Project'.$code.'</li></ul>';
    }

    public function php_file_tree_dir($directory, $return_link, $extensions = array(), $first_call = true)
    {
        // Recursive function called by php_file_tree() to list directories/files

        // Get and sort directories/files
        if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
        natcasesort($file);
        // Make directories first
        $files = $dirs = array();
        foreach($file as $this_file) {
            if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
        }
        $file = array_merge($dirs, $files);

        // Filter unwanted extensions
        if( !empty($extensions) ) {
            foreach( array_keys($file) as $key ) {
                if( !is_dir("$directory/$file[$key]") ) {
                    $ext = substr($file[$key], strrpos($file[$key], ".") + 1);
                    if( !in_array($ext, $extensions) ) unset($file[$key]);
                }
            }
        }

        if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
            $php_file_tree = "<ul";
            if( $first_call ) { $php_file_tree .= ""; $first_call = false; }
            $php_file_tree .= ">";
            foreach( $file as $this_file ) {
                if( $this_file != "." && $this_file != ".." ) {
                    if( is_dir("$directory/$this_file") ) {
                        // Directory
                        $php_file_tree .= "<li>" . htmlspecialchars($this_file);
                        $php_file_tree .= $this->php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false);
                        $php_file_tree .= "</li>";
                    } else {
                        // File
                        // Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
                        $ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1);
                        $link = str_replace("[link]", "$directory/" . urlencode($this_file), $return_link);
                        $php_file_tree .= "<li>" . htmlspecialchars($this_file) . "</li>";
                    }
                }
            }
            $php_file_tree .= "</ul>";
        } else {
            $php_file_tree = "";
        }
        return $php_file_tree;
    }

    public function php4_scandir($dir)
    {
        $dh  = opendir($dir);
        while( false !== ($filename = readdir($dh)) ) {
            $files[] = $filename;
        }
        sort($files);
        return($files);
    }

    public function convertPHPSizeToBytes($sSize)
    {
        if (is_numeric($sSize)) {
           return $sSize;
        }

        $sSuffix = substr($sSize, -1);
        $iValue = substr($sSize, 0, -1);

        switch (strtoupper($sSuffix)) {
            case 'P':
                $iValue *= 1024;
            case 'T':
                $iValue *= 1024;
            case 'G':
                $iValue *= 1024;
            case 'M':
                $iValue *= 1024;
            case 'K':
                $iValue *= 1024;
                break;
        }

        return $iValue;
    }

    public function getMaximumFileUploadSize($normalize = 1)
    {
        return min($this->convertPHPSizeToBytes(ini_get('post_max_size')),
            $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')),
            $this->convertPHPSizeToBytes(ini_get('memory_limit'))
        )/$normalize;
    }

    /*
    *   Accepts a media entry array with details
    *   returns an array of the media with tis details organized
    *   by lang
    */
    public function organizeMediaDetailsPerLang($media = array())
    {
        if ($media) {
            $details = array();
            if (isset($media['langdetails']) && !empty($media['langdetails'])) {
                foreach ($media['langdetails'] as $key => $row)
                {
                    $details[$row['lang']] = $row;
                }
            }

            $media['langdetails'] = $details;
        }

        return $media;
    }

    /*
    *   Accepts a media entry array with details per lang, the required field and the lang
    *   returns the value of the field, if it exists
    */
    public function parseMediaDetails($media = array(), $field = '', $lang = '')
    {
        if (!isset($media['langdetails']) || empty($media['langdetails'])) {
            return '';
        } else {
            if (!isset($media['langdetails'][$lang])) {
                return '';
            } else {
                if (!isset($media['langdetails'][$lang][$field])) {
                    return '';
                } else {
                    return $media['langdetails'][$lang][$field];
                }
            }
        }
    }

    /*
     * Version return align details
     *
     */
    public function getAlignVersion($version_slug = '', $media_details = array())
    {
        if (!empty($media_details) && !empty($media_details['details'])  && isset($media_details['details']['img_align']) && strlen($version_slug)) {
            if (isset($media_details['details']['img_align'][$version_slug])) {
                return $media_details['details']['img_align'][$version_slug];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

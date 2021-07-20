<?php

namespace Library;

use URL;
use Config;
use Request;
use Sentinel;

use PostRider\Content;
use PostRider\CustomFields;
use PostRider\Option;
use PostRider\Media;

//use Library\FrontendHelperLib;
use Library\LanguageHelperLib;
use Library\OptionsHelperLib;

class CustomFieldHelperLib
{
    public function __construct(/*FrontendHelperLib $frontHelp, */LanguageHelperLib $langHelper, OptionsHelperLib $optHelp)
    {
        //$this->frontHelp = $frontHelp;
        $this->langHelp = $langHelper;
        $this->optHelp = $optHelp;
    }

    /*
     * Each Content (post) will have multiple entries on the content_custom_fields tax
     * each entry will be the custom field and its value
     *
     */
    public function contentCustomFields($content = null)
    {
        $res = [];
        if ($content) {
            if (isset($content[0])) {
                foreach ($content as $ckey => $crow) {
                    $res = [];
                    if (isset($crow->customFields)) {
                        foreach ($crow->customFields as $key => $row) {
                            if (isset($res[$row['name']])) {
                                if ($this->is_numeric_array($res[$row['name']])) {
                                    $res[$row['name']][] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                                   
                                } else {
                                    $tmp = $res[$row['name']];
                                    unset($res[$row['name']]);
                                    $res[$row['name']] = [$tmp];
                        
                                    $res[$row['name']][] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                                }
                            } else {
                                $res[$row['name']] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                            }
                        }

                        $crow['c_fields'] = $res;
                        $content[$ckey] = $crow;
                        $content[$ckey] = $this->parseContent($content[$ckey], "body", $crow['c_fields']);
                    }
                }
            } else {
                if (isset($content->customFields)) {
                    foreach ($content->customFields as $key => $row) {

                        if (isset($res[$row['name']])) {
                            if ($this->is_numeric_array($res[$row['name']])) {
                                $res[$row['name']][] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                            } else {
                             
                                $tmp = $res[$row['name']];
                                unset($res[$row['name']]);
                                $res[$row['name']] = [$tmp];
                                $res[$row['name']][] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                            }
                        } else {
                            $res[$row['name']] = ["value" => $row->value, "class" => "", "tax_id" => $row->id, "content_id" => $row->content_id];
                        }
                    }
                    $content['c_fields'] = $res;
                    $content = $this->parseContent($content, "body", $content['c_fields']);
                }
            }
        }

        return $content;
    }

    /*
     * parse the content and check if nodes have the "dpParseSection". If this is present
     * then we need to handle it and replace its content accordingly
     */
    public function parseContent($content, $db_field = "body", $c_fields = array()) {
        if (Request::is('admin/*')) {
            return $content;
        } else {
             $domHtml = new \Htmldom();
            if (is_object($content)) {
                @$domHtml->load($content->{$db_field});
                $content->{$db_field} = $this->lookForElem($domHtml, $c_fields, $content);
            } else {
                @$domHtml->load($content[$db_field]);
                $content[$db_field] = $this->lookForElem($domHtml, $c_fields, $content);
            }
            $domHtml->clear();
            unset($domHtml);
            return $content;
        }
    }

    public function lookForElem($domHtml, $c_fields = array(), $content) {
        $domElems = $domHtml->find('div[class=dpParseSection]');
        if ($domElems) {
            foreach ($domElems as $key => $e) {
                // is this is custom field or something else, check the data-dp-section attr
                // we assume name and abbreviation are the same
                switch ($e->{'data-dp-section-is'}) {
                    case 'cf_field':
                        $e->innertext = $this->parseContentCustomFields($e, $c_fields, $content);
                        break;
                    default:
                        $e->innertext = '';
                        break;
                }
            }
        }

        $html = $domHtml->save();
        $domHtml->clear();
        unset($domHtml);
        return $html;
    }

    public function parseContentCustomFields($elem, $c_fields, $content)
    {
        $abbr = $elem->{'data-dp-cfield-abbr'};
        if (isset($c_fields[$abbr])) {
            if ($abbr == "multi_dropzone") {
                $html = $this->parseMultiDropzone($abbr, $elem, $c_fields, $content);
            } else {
                $html = '';
            }
        }
        return $html;
    }

    public function parseMultiDropzone($abbr, $elem, $c_fields, $content)
    {
        $html = '';
        $galleryVersion = $elem->{'data-dp-cfield-gallery-version'};
        if ($galleryVersion != "default") {
            $gallerySelection = Config::get('dpoptions.custom_field.multi_dropzone.settings.gallery_selection');
            $cfieldInit = Config::get('dpoptions.custom_field.multi_dropzone');

            if ($gallerySelection['show']) {
                if (isset($gallerySelection['versions'][$galleryVersion])) {
                    $version = $gallerySelection['versions'][$galleryVersion];
                    if (isset($version['views']) && isset($version['views']['frontend'])) {
                        $html = view($version['views']['frontend'], ["cfield" => $c_fields[$abbr], "cfieldInit" => $cfieldInit, "content" => $content])->render();
                    }
                }
            }
        }
        return $html;
    }

    public function attachCustField($content, $customField, $priority = 0, $value = '')
    {
        $cfieldSettings = $this->optHelp->decodeJsonStr($customField['settings']);
        $existingCustField = CustomFields::where("content_id", $content->id)->where("c_field_id", $customField['id'])->where('priority', $priority)->first();

        if ($existingCustField) {
            if (isset($cfieldSettings['type'])) {
                switch ($cfieldSettings['type']) {
                    case 'multiDropzone':
                        $multiFiles = $existingCustField->value;
                        /*
                        echo '<pre>';
                        print_r(unserialize($value));
                        echo '</pre>';
                        echo '<hr/><pre>';
                        print_r($multiFiles);
                        echo '</pre>';
                        */
                        $multiFiles["media"][] = unserialize($value);
                        /*
                        echo '<hr/><pre>';
                        print_r($multiFiles);
                        echo '</pre>';
                        */
                        //$multiFiles[count($multiFiles)] = unserialize($value);
                        $existingCustField->update(['value' => serialize($multiFiles)]);
                        break;
                    default:
                        $existingCustField->update(['value' => $value]);
                        break;
                }
            } else {
                $existingCustField->update(['value' => $value]);
            }
        } else {
            if (isset($cfieldSettings['type'])) {
                switch ($cfieldSettings['type']) {
                    case 'multiDropzone':
                        $multiFiles = ["media" => [0 => unserialize($value)], "settings" => []];
                        $existingCustField = CustomFields::create([
                            "content_id" => $content->id,
                            "c_field_id" => $customField['id'],
                            "c_field_name" => $customField['abbr'],
                            "priority" => $priority,
                            "value" => serialize($multiFiles),
                        ]);
                        break;
                    default:
                        $existingCustField = CustomFields::create([
                            "content_id" => $content->id,
                            "c_field_id" => $customField['id'],
                            "c_field_name" => $customField['abbr'],
                            "priority" => $priority,
                            "value" => $value,
                        ]);
                        break;
                }
            } else {
                $existingCustField = CustomFields::create([
                    "content_id" => $content->id,
                    "c_field_id" => $customField['id'],
                    "c_field_name" => $customField['abbr'],
                    "priority" => $priority,
                    "value" => $value,
                ]);
            }
        }
        return $existingCustField->id;
    }

    //<img alt="featured image" src="http://webcms_prd/featured_image/1/lamborghini-aventador-lp-700-4-special-edition-1920x1080-OHEGT.jpg" class="dp_featured_img second_dropzone_click">
    public function setCField($content = null, $cfieldName = '', $priority = null)
    {
        if ($content) {
            if (isset($content['c_fields'])) {
                ///dd($content['c_fields']);
                if (isset($content['c_fields'][$cfieldName])) {
                    if (is_null($priority)) {
                        if ($this->is_numeric_array($content['c_fields'][$cfieldName])) {
                            //return $this->handleSerialized($content['c_fields'][$cfieldName][0]['value']);
                            return $content['c_fields'][$cfieldName][0]['value'];
                        } else {
                            //return $this->handleSerialized($content['c_fields'][$cfieldName]['value']);
                            return $content['c_fields'][$cfieldName]['value'];
                        }
                    } else {
                        if (isset($content['c_fields'][$cfieldName][$priority])) {
                            //return $this->handleSerialized($content['c_fields'][$cfieldName][$priority]['value']);
                            return $content['c_fields'][$cfieldName][$priority]['value'];
                        } else {
                            return '';
                        }
                    }
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

    public function setCustFieldMedia($data = array())
    {
        $res = ['set' => false, 'status' => 1];
        $customField = Option::where('id', $data['cust_field_id'])->first()->toArray();
        $cfieldSettings = $this->optHelp->decodeJsonStr($customField['settings']);

        if ($data['content_id']) {
            // all is well
            $content = Content::where('id', $data['content_id'])->select('id','group_id','lang','website_id','type')->first();
            if (isset($cfieldSettings['type'])) {
                switch ($cfieldSettings['type']) {
                    case 'multiDropzone':
                        break;
                    default:
                        $existingCustField = CustomFields::where("content_id", $content->id)->where("c_field_id", $customField['id'])->first();
                        if ($existingCustField) {
                            $value = $existingCustField->value;
                        }
                        break;
                }
            } else {
                $existingCustField = CustomFields::where("content_id", $content->id)->where("c_field_id", $customField['id'])->first();
                if ($existingCustField) {
                    //$value = $this->handleSerialized($existingCustField->value);
                    $value = $existingCustField->value;
                }
            }
        } else {
            $data['content_group_id'] = ((isset($data['content_group_id'])) ? $data['content_group_id'] : 0);
            $data['content_lang'] = ((isset($data['content_lang'])) ? $data['content_lang'] : $this->langHelp->defLang);
            $data['content_website_id'] = ((isset($data['content_website_id'])) ? $data['content_website_id'] : $this->langHelp->defWebsite);

            $content = Content::create([
                'creator_id' => Sentinel::getUser()->id,
                'author_id' => Sentinel::getUser()->id,
                'type' => $data['content_type'],
                'group_id' => $data['content_group_id'],
                'lang' => $data['content_lang'],
                'website_id' => $data['content_website_id'],
                //'abbr' => $data['content_abbr'],
            ]);
            $content = $this->langHelp->setContentGroupId($content, ['group_id' => $data['content_group_id']]);
        }

        $media = Media::where('id', $data['media_id'])->first();
        $value["media_id"] = $media->id;
        $value["media_type"] = $media->type;
        $value["media_ext"] = $media->ext;
        $value["media_name"] = $media->name;
        $value["relative_path"] = $media->path.'/'.$media->name.$media->ext;

        $res['set'] = true;
        if ($media->type == 0) {
            $res['thumb_url'] = URL::to("/").'/featured_image/'.$media->path.'/'.$media->name.$media->ext;
        } else {
            $file_versions = Config::get('dpoptions.file_versions');
            $cleanExt = explode(".", $media->ext)[1];
            if (isset($file_versions['settings'][$cleanExt])) {
                $res['thumb_url'] = asset($file_versions['settings'][$cleanExt]["image"]);
            } else {
                $res['thumb_url'] = asset($file_versions['settings']["generic"]["image"]);
            }
        }

        $res['media'] = $media;

        $this->attachCustField($content, $customField, 0, serialize($value));

        $res['media_id'] = $data['media_id'];
        $res['content_id'] = $content->id;
        $res['group_id'] = $content->group_id;
        $res['lang'] = $content->lang;
        $res['website_id'] = $content->website_id;
        $res['type'] = $content->type;
        $res['multi_lang'] = $this->langHelp->generateContentLangVersions($content->id, $content->website_id);
        $res['cfield']["id"] = $customField['id'];
        if (isset($cfieldSettings['type'])) {
            $res['cfield']["type"] = $cfieldSettings['type'];
        }

        return $res;
    }

    public function is_numeric_array($arr)
    {
        return $arr === array() || range(0, count($arr)-1) === array_keys($arr);
    }

    /*
    public function handleSerialized($str = '')
    {
        $data = @unserialize($str);
        if ($data !== false) {
            return $data;
        } else {
            return $str;
        }
    }
    */

    /*
     * List all page blade views under views.pages excluding partials
     * Usable in a dropdown select on edit page view
     */
    public function listPageBlades()
    {
        $pages['default'] = "Default";
        $allPages = [];
        $iterator = new \DirectoryIterator(resource_path('views/pages'));
        foreach ($iterator as $fileinfo) {
            if (!$fileinfo->isDot() && !$fileinfo->isDir()) {
                $allPages[] = $fileinfo->getBasename('.blade.php');
                //$tmpTitle = title_case(implode(" ",explode("_", $fileinfo->getBasename('.blade.php'))));
                //$pages[$fileinfo->getBasename('.blade.php')] = $tmpTitle;
            }
        }

        if ($allPages) {
            foreach ($allPages as $value) {
                if ($value != "default") {
                    $tmpTitle = title_case(implode(" ",explode("_", $value)));
                    $pages[$value] = $tmpTitle;
                }

            }
        }

        return $pages;
    }
}

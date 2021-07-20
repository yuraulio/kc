<?php

namespace Library;

use Config;

use PostRider\Category;
use PostRider\Content;
use PostRider\Option;

class LanguageHelperLib
{
    public $defLang = "el";
    public $defLanguage = "Greek";
    public $defWebsite = 1;

    public function __construct()
    {
        $this->defLang = $this->defLang();
        $this->defLanguage = $this->defLanguage();
        $this->defWebsite = $this->defWebsite();
    }

    /*
     * Returns the default language short code as defined in the laguages option default setting
     * If not defined returns the defautl lang code defined in the env settings
     */
    public function defLang()
    {
        $defLang = Config::get('dpoptions.languages.settings.def_lang');
        if ($defLang) {
            return $defLang;
        } else {
            return env('DEF_LANG');
        }
    }

    /*
     * Returns the default language title as defined in the laguages option default setting
     * If not defined returns the defautl language title defined in the env settings
     */
    public function defLanguage()
    {
        $defLang = Config::get('dpoptions.languages.settings.def_lang');
        if ($defLang) {
            $allLangs = Config::get('dpoptions.languages.settings.all_langs');
            if (isset($allLangs[$defLang])) {
                return $allLangs[$defLang]['title'];
            } else {
                return env('DEF_LANGUAGE');
            }
        } else {
            return env('DEF_LANGUAGE');
        }
    }

    /*
     * Accepts a string (ideally a valid lang code)
     * @returns a valid lang code
     */
    public function toValidLang($str = '')
    {
        $allLangs = Config::get('dpoptions.languages.settings.all_langs');
        $activeLangs = Config::get('dpoptions.languages.settings.active_langs');
        if ($allLangs && isset($allLangs[$str])) {
            if (in_array($str, $activeLangs)) {
                return $allLangs[$str]['txt'];
            } else {
                return $this->defLang;
            }
        } else {
            return $this->defLang;
        }
    }

    /*
     * Accepts content identifier (content, category), and an id
     * @returns an array on per lang versions
     *
     */
    public function langVersions($type = '', $id = 0)
    {
        $tmpVersions = [];
        switch ($type) {
            case 'category':
                $tmp = Category::where('id', $id)->select('id','group_id')->first();
                if ($tmp && $tmp->group_id) {
                    $tmpVersions = Category::where('group_id', $tmp->group_id)->select('id','lang','name')->get();
                }
                break;
            case 'content':
                $tmp = Content::where('id', $id)->select('id','group_id')->first();
                if ($tmp && $tmp->group_id) {
                    $tmpVersions = Content::where('group_id', $tmp->group_id)->select('id','lang','title')->get();
                }
                break;
            default:
                $tmpVersions = [];
                break;
        }

        if ($tmpVersions) {
            foreach ($tmpVersions as $key => $row) {
                $versions[$row['lang']] = $row;
            }
            return $versions;
        } else {
            return [];
        }
    }

    public function activeLangsFull()
    {
        $versions = [];
        $allLangs = Config::get('dpoptions.languages.settings.all_langs');
        $activeLangs = Config::get('dpoptions.languages.settings.active_langs');
        if ($allLangs && $activeLangs) {
            foreach ($activeLangs as $lang) {
                if (isset($allLangs[$lang])) {
                    $versions[$lang] = $allLangs[$lang];
                }
            }
            return $versions;
        } else {
            return $versions;
        }
    }

    /**
     * Based on the group_id value... we are missing the setCategoryGroupId
     *
     */
    public function setContentGroupId($content, $data = array())
    {
        if (isset($data['group_id'])) {
            if (!$data['group_id']) {
                Content::where('id', '=', $content->id)->update(['group_id' => $content->id]);
                $content->group_id = $content->id;
            }
        } else {
            Content::where('id', '=', $content->id)->update(['group_id' => $content->id]);
            $content->group_id = $content->id;
        }
        return $content;
    }

    public function generateContentLangVersions($content_id = 0, $website_id = 1)
    {
        $data['website_id'] = $website_id;
        $data['content'] = Content::where('id', $content_id)->select('id','group_id','type')->first();
        if ($data['content']->group_id) {
            $custType = Option::select('id','abbr','name')->where('id', $data['content']->type)->first();
            $data['custTypeID'] = $custType->id;
            $data['custType'] = Config::get('dpoptions.content_types.'.$custType->abbr.'.settings');
            $data['custType']['abbr'] = Config::get('dpoptions.content_types.'.$custType->abbr.'.abbr');
        } else {

        }
        $data['langVersions'] = $this->langVersions("content", $content_id);
        $html = view('admin.lang_versions.content.edit', $data)->render();
        return $html;
    }

    /*
     * Returns the default website id as defined in the websites option default setting
     * If not defined returns the defautl website id defined in the env settings
     */
    public function defWebsite()
    {
        $defLang = Config::get('dpoptions.websites.settings.def_website');
        if ($defLang) {
            return $defLang;
        } else {
            return env('DEF_WEBSITE');
        }
    }
}

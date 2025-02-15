<?php

namespace App\Library;

use App\Model\Admin\Admin;
use App\Model\Slug;
use Carbon\Carbon;

class PageVariables
{
    protected static $variables = [
        'course_hours',
        'launch_date',
        'course_inclass_dates',
        'course_inclass_days',
        'course_certificate_type',
        'course_language',
        'course_delivery',
        'course_elearning_expiration',
    ];

    public static function parseText($text, $page, $dynamic_page_data = null)
    {
        if ($page) {
            // author
            if (strpos($text, '@author') !== false) {
                $admin = Admin::find($page->user_id);
                $admin_name = ucfirst($admin->firstname) . ' ' . ucfirst($admin->lastname);
                $text = str_replace('@author', $admin_name, $text);
            }
            // course details
            if (strpos($text, '@course_details') !== false) {
                if ($dynamic_page_data) {
                    $course_details = view('new_web.components.course_details_component', ['dynamic_page_data' => $dynamic_page_data])->render();

                    $text = str_replace('@course_details', $course_details, $text);
                }
            }

            // page title
            if (strpos($text, '@page_title') !== false) {
                $page_title = $page->title;
                $text = str_replace('@page_title', $page_title, $text);
            }
        }

        if ($dynamic_page_data) {
            $text = self::replaceVariables($text, $page, $dynamic_page_data);
        }

        return $text;
    }

    protected static function replaceVariables($text, $page, $dynamicPageData)
    {
        if (!empty($dynamicPageData['info'])) {
            if (!empty($dynamicPageData['info']['hours'])) {
                $text = str_replace('{{course_hours}}', $dynamicPageData['info']['hours']['hour'], $text);
            }
            if (!empty($dynamicPageData['info']['inclass'])) {
                if (!empty($dynamicPageData['info']['inclass']['dates'])) {
                    $text = str_replace('{{course_inclass_dates}}', $dynamicPageData['info']['inclass']['dates']['text'], $text);
                }
                if (!empty($dynamicPageData['info']['inclass']['days'])) {
                    $text = str_replace('{{course_inclass_days}}', $dynamicPageData['info']['inclass']['days']['text'], $text);
                }
            }
            if (!empty($dynamicPageData['info']['certificate']) && !empty($dynamicPageData['info']['certificate']['type'])) {
                $text = str_replace('{{course_certificate_type}}', $dynamicPageData['info']['certificate']['type'], $text);
            }
            if (!empty($dynamicPageData['info']['language']) && !empty($dynamicPageData['info']['language']['text'])) {
                $text = str_replace('{{course_language}}', $dynamicPageData['info']['language']['text'], $text);
            }
            if (!empty($dynamicPageData['info']['delivery_info']) && !empty($dynamicPageData['info']['delivery_info']['text'])) {
                $text = str_replace('{{course_delivery}}', $dynamicPageData['info']['delivery_info']['text'], $text);
            }
            if (!empty($dynamicPageData['info']['elearning']) && !empty($dynamicPageData['info']['elearning']['expiration'])) {
                $text = str_replace('{{course_elearning_expiration}}', $dynamicPageData['info']['elearning']['expiration'], $text);
            }
        }
        if (!empty($dynamicPageData['event']) && $dynamicPageData['event']->launch_date) {
            $text = str_replace('{{launch_date}}', Carbon::parse($dynamicPageData['event']->launch_date)->format('d/m/Y'), $text);
        }
        foreach (self::$variables as $k) {
            $text = str_replace('{{' . $k . '}}', '', $text);
        }

        return $text;
    }
}

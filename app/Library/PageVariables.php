<?php

namespace App\Library;

use App\Model\Admin\Admin;
use App\Model\Slug;

class PageVariables
{
    public static function parseText($text, $page, $dynamic_page_data = null)
    {
        // author
        if (strpos($text, "@author") !== false) {
            $admin = Admin::find($page->user_id);
            $admin_name = ucfirst($admin->firstname) . " " . ucfirst($admin->lastname);
            $text = str_replace("@author", $admin_name, $text);
        }

        // course details
        if (strpos($text, "@course_details") !== false) {
            if ($dynamic_page_data) {
                $course_details = view('new_web.components.course_details_component', ['dynamic_page_data' => $dynamic_page_data])->render();

                $text = str_replace("@course_details", $course_details, $text);
            }
        }

        // page title
        if (strpos($text, "@page_title") !== false) {
            $page_title = $page->title;
            $text = str_replace("@page_title", $page_title, $text);
        }

        return $text;
    }
}

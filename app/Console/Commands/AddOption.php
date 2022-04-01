<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Option;
class AddOption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'option:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Option::create([
            'type' => 'config',
            'title' => 'Deree IDs',
            'name' => 'deree_ids',
            'status' => true,
            'abbr' => 'deree_codes',
            'settings' => json_encode([]),

        ]);

        Option::create([
            'type' => 'config',
            'title' => 'Website Details',
            'name' => 'website_details',
            'status' => true,
            'abbr' => 'website_details',
            'settings' => json_encode("{\r\n    \"domain\": \"knowcrunch.com\",\r\n    \"title\": \"Knowcrunch\",\r\n    \"keywords\": \"\",\r\n    \"description\": \"\",\r\n    \"admin_email\": \"info@knowcrunch.com\",\r\n    \"website_email\": \"info@knowcrunch.com\",\r\n    \"hosting\": {\r\n        \"start\": \"01\/03\/2017\",\r\n        \"end\": \"28\/02\/2018\"\r\n    },\r\n    \"license\": {\r\n        \"start\": \"\",\r\n        \"end\": \"\"\r\n    },\r\n    \"support\": {\r\n        \"start\": \"01\/03\/2017\",\r\n        \"end\": \"28\/02\/2018\",\r\n        \"cost\": \"\"\r\n    },\r\n    \"route_modes\": [\"multi_lang\", \"multi_domain\"],\r\n    \"routing_mode\": \"multi_lang\",\r\n    \"meta_defaults\": {\r\n        \"site_name\": \"Knowcrunch professional training. Learn, Transform, Thrive.\",\r\n        \"generator\": \"Darkpony CMS\",\r\n        \"author\": \"Knowcrunch\",\r\n        \"title\": \"Knowcrunch professional training. Learn, Transform, Thrive.\",\r\n        \"keywords\": \"training, \u03b5\u03ba\u03c0\u03b1\u03af\u03b4\u03b5\u03c5\u03c3\u03b7, seminars, \u03c3\u03b5\u03bc\u03b9\u03bd\u03ac\u03c1\u03b9\u03b1, events, courses, diplomas, certificates, business, marketing, digital marketing, \u03b5\u03ba\u03c0\u03b1\u03af\u03b4\u03b5\u03c5\u03c3\u03b7 \u03c3\u03c4\u03b5\u03bb\u03b5\u03c7\u03ce\u03bd, executive training, knowcrunch\",\r\n        \"description\": \"Knowcrunch is offering high level professional training and specialized educational courses dedicated to fostering knowledge to others.\",\r\n        \"content\": \"\",\r\n        \"image\": \"https:\/\/knowcrunch.com\/uploads\/knowcrunch-training-education-courses.jpg\",\r\n        \"fb_locale\": \"\",\r\n        \"fb_type\": \"\",\r\n        \"fb_app_id\": \"961275423898153\",\r\n        \"fb_admins\": \"\",\r\n        \"tw_card\": \"\",\r\n        \"tw_site\": \"\",\r\n        \"tw_creator\": \"\",\r\n        \"tw_domain\": \"\"\r\n    }\r\n}"),
            'value' => 1532,
        ]);

        $arr=[];
        $arr['facebook']['title'] = 'Facebook';
        $arr['facebook']['url'] ='https://www.facebook.com/Knowcrunch';
        $arr['facebook']['target'] = '_blank';
        $arr['twitter']['title'] = 'Twitter';
        $arr['twitter']['url'] = 'https://twitter.com/knowcrunch';
        $arr['twitter']['target'] = '_blank';
        $arr['googleplus']['title'] = 'Google Plus';
        $arr['googleplus']['url'] = '';
        $arr['googleplus']['target'] = '_blank';
        $arr['instagram']['title'] = 'Instagram';
        $arr['instagram']['url'] = 'https://www.instagram.com/knowcrunch/';
        $arr['instagram']['target'] = '_blank';
        $arr['medium']['title'] = 'Medium';
        $arr['medium']['url'] = '';
        $arr['medium']['target'] = '_blank';
        $arr['pinterest']['title'] = 'Pinterest';
        $arr['pinterest']['url'] = '';
        $arr['pinterest']['target'] = "_blank";
        $arr['behance']['title'] = 'behance';
        $arr['behance']['url'] = 'https://www.behance.com';
        $arr['behance']['target'] = '_blank';
        $arr['linkedin']['title'] = 'Linkedin';
        $arr['linkedin']['url'] = 'https://www.linkedin.com/company/knowcrunch';
        $arr['linkedin']['target'] = '_blank';
        $arr['youtube']['title'] = 'youtube';
        $arr['youtube']['url'] = 'https://www.youtube.com//channel/UCU5p3dauJLrdMpuLwB_mX1A';
        $arr['youtube']['target'] = '_blank';

        Option::create([
            'type' => 'config',
            'title' => 'Social Media',
            'name' => 'social_media',
            'status' => true,
            'abbr' => 'social_media',
            'settings' => json_encode($arr)
            //'settings' => json_encode("{\r\n    \"1\": {\r\n        \"facebook\": {\r\n            \"title\": \"Facebook\",\r\n            \"url\": \"https:\/\/www.facebook.com\/Knowcrunch\/\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"twitter\": {\r\n            \"title\": \"Twitter\",\r\n            \"url\": \"https:\/\/twitter.com\/knowcrunch\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"googleplus\": {\r\n            \"title\": \"Google Plus\",\r\n            \"url\": \"\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"instagram\": {\r\n            \"title\": \"Instagram\",\r\n            \"url\": \"https:\/\/www.instagram.com\/knowcrunch\/\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"medium\": {\r\n            \"title\": \"Medium\",\r\n            \"url\": \"\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"pinterest\": {\r\n            \"title\": \"Pinterest\",\r\n            \"url\": \"\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"behance\": {\r\n            \"title\": \"Behance\",\r\n            \"url\": \"https:\/\/www.behance.com\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"linkedin\": {\r\n            \"title\": \"LinkedIn\",\r\n            \"url\": \"https:\/\/www.linkedin.com\/company\/knowcrunch\",\r\n            \"target\": \"_blank\"\r\n        },\r\n        \"youtube\": {\r\n            \"title\": \"Youtube\",\r\n            \"url\": \"https:\/\/www.youtube.com\/channel\/UCU5p3dauJLrdMpuLwB_mX1A\",\r\n            \"target\": \"_blank\"\r\n        }\r\n    }\r\n}"),
        ]);

        return 0;
    }
}

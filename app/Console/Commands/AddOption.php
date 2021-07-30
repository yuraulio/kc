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
        /*Option::create([
            'type' => 'config',
            'title' => 'Deree IDs',
            'name' => 'deree_ids',
            'status' => true,
            'abbr' => 'deree-codes',
            'settings' => json_encode([]),

        ]);*/

        Option::create([
            'type' => 'config',
            'title' => 'Website Details',
            'name' => 'website_details',
            'status' => true,
            'abbr' => 'website_details',
            'settings' => json_encode("{\r\n    \"domain\": \"knowcrunch.com\",\r\n    \"title\": \"KnowCrunch\",\r\n    \"keywords\": \"\",\r\n    \"description\": \"\",\r\n    \"admin_email\": \"info@knowcrunch.com\",\r\n    \"website_email\": \"info@knowcrunch.com\",\r\n    \"hosting\": {\r\n        \"start\": \"01\/03\/2017\",\r\n        \"end\": \"28\/02\/2018\"\r\n    },\r\n    \"license\": {\r\n        \"start\": \"\",\r\n        \"end\": \"\"\r\n    },\r\n    \"support\": {\r\n        \"start\": \"01\/03\/2017\",\r\n        \"end\": \"28\/02\/2018\",\r\n        \"cost\": \"\"\r\n    },\r\n    \"route_modes\": [\"multi_lang\", \"multi_domain\"],\r\n    \"routing_mode\": \"multi_lang\",\r\n    \"meta_defaults\": {\r\n        \"site_name\": \"KnowCrunch professional training. Learn, Transform, Thrive.\",\r\n        \"generator\": \"Darkpony CMS\",\r\n        \"author\": \"KnowCrunch\",\r\n        \"title\": \"KnowCrunch professional training. Learn, Transform, Thrive.\",\r\n        \"keywords\": \"training, \u03b5\u03ba\u03c0\u03b1\u03af\u03b4\u03b5\u03c5\u03c3\u03b7, seminars, \u03c3\u03b5\u03bc\u03b9\u03bd\u03ac\u03c1\u03b9\u03b1, events, courses, diplomas, certificates, business, marketing, digital marketing, \u03b5\u03ba\u03c0\u03b1\u03af\u03b4\u03b5\u03c5\u03c3\u03b7 \u03c3\u03c4\u03b5\u03bb\u03b5\u03c7\u03ce\u03bd, executive training, knowcrunch\",\r\n        \"description\": \"KnowCrunch is offering high level professional training and specialized educational courses dedicated to fostering knowledge to others.\",\r\n        \"content\": \"\",\r\n        \"image\": \"https:\/\/knowcrunch.com\/uploads\/knowcrunch-training-education-courses.jpg\",\r\n        \"fb_locale\": \"\",\r\n        \"fb_type\": \"\",\r\n        \"fb_app_id\": \"961275423898153\",\r\n        \"fb_admins\": \"\",\r\n        \"tw_card\": \"\",\r\n        \"tw_site\": \"\",\r\n        \"tw_creator\": \"\",\r\n        \"tw_domain\": \"\"\r\n    }\r\n}"),
            'value' => 1532,
        ]);

        return 0;
    }
}

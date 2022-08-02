<?php

namespace Database\Seeders;

use App\Model\Admin\PageType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_page_types')->truncate();

        $pageType = new PageType();
        $pageType->id = 2;
        $pageType->title = 'Blog';
        $pageType->description = "Blog (for blog pages)";
        $pageType->save();

        $pageType = new PageType();
        $pageType->id = 3;
        $pageType->title = 'Course page';
        $pageType->description = "Course page (for course pages)";
        $pageType->save();

        $pageType = new PageType();
        $pageType->id = 4;
        $pageType->title = 'Trainer page';
        $pageType->description = "Trainer page (for instructor pages)";
        $pageType->save();

        $pageType = new PageType();
        $pageType->id = 5;
        $pageType->title = 'General';
        $pageType->description = "General (for normal pages)";
        $pageType->save();

        $pageType = new PageType();
        $pageType->id = 6;
        $pageType->title = 'Knowledge';
        $pageType->description = "Knowledge (for knowledge pages)";
        $pageType->save();

        $pageType = new PageType();
        $pageType->id = 7;
        $pageType->title = 'City page';
        $pageType->description = "City page (for event city pages)";
        $pageType->save();
    }
}

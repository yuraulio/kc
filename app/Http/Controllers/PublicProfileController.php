<?php

namespace App\Http\Controllers;

use App\Model\Admin\Template;
use App\Model\User;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        if (!$user->is_public_profile_enabled) {
            abort(404);
        }

        // We're pulling in the "Instructor profile template" to use the menu defined there for this page
        $page = Template::whereTitle('Public Profile Template')->first();
        // Template rows (the menu IDs were not saved initially, so I manually added them and am saving the result here in case it gets lost).
        // [{"id":"9db00416-35c2-4819-a455-d11a45bebbc5","order":1,"description":"","removable":false,"disable_color":true,"tab":"Meta","columns":[{"id":"ef4e3e48-c4f6-40d4-9a54-384d9e89b83c","order":0,"component":"meta","active":true,"template":{"version":7,"title":"Meta","icon":"dripicons-rocket","key":"meta_component","one_column":true,"changable":false,"removable":false,"disable_color":true,"tab":"Meta","simple_view":true,"inputs":[{"type":"text","key":"meta_slug","label":"Slug","size":"col-lg-12","main":true},{"type":"text","key":"meta_title","label":"Meta Title","size":"col-lg-12","main":true},{"type":"textarea","key":"meta_description","label":"Meta Description","main":true},{"type":"image","key":"meta_image","label":"Shareable image","main":true},{"type":"text","key":"meta_author","label":"Meta Author","size":"col-lg-12","main":true},{"type":"text","key":"meta_type","label":"Meta Type","size":"col-lg-12","main":true},{"type":"textarea","key":"meta_schema","label":"Meta Schema","size":"col-lg-12","main":true,"simple_view":false}]},"width":6}],"color":"white"},{"id":"c3df4eaf-3daa-4c1f-95da-5ded0cb7b8af","order":2,"description":"","collapsed":true,"removable":false,"disable_color":true,"tab":"Content","columns":[{"id":"267f9095-218a-4abb-be36-98860b1092d1","order":0,"component":"menus","active":true,"template":{"version":3,"title":"Menus","icon":"dripicons-menu","key":"menu_component","one_column":true,"changable":false,"removable":false,"disable_color":true,"tab":"Content","simple_view":false,"inputs":[{"type":"multidropdown","key":"main_menu_desktop","label":"Main menu desktop","route":"getMenus","value":{"name":"Main menu","slug":"main-menu","custom_class":"Main menu","id":1},"size":"col-lg-6"},{"type":"multidropdown","key":"main_menu_mobile","label":"Main menu mobile","route":"getMenus","value":{"name":"Main menu","slug":"main-menu","custom_class":"Main menu","id":1},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_1_desktop","label":"Footer menu 1 deshtop","route":"getMenus","value":{"name":"About","slug":"about","custom_class":"About","id":2},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_1_mobile","label":"Footer menu 1 mobile","route":"getMenus","value":{"name":"About","slug":"about","custom_class":"About","id":2},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_2_desktop","label":"Footer menu 2 desktop","route":"getMenus","value":{"name":"Education","slug":"education","custom_class":"Education"},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_2_mobile","label":"Footer menu 2 mobile","route":"getMenus","value":{"name":"Education","slug":"education","custom_class":"Education"},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_3_desktop","label":"Footer menu 3 desktop","route":"getMenus","value":{"name":"Students","slug":"students","custom_class":"Students","id":4},"size":"col-lg-6"},{"type":"multidropdown","key":"footer_menu_3_mobile","label":"Footer menu 3 mobile","route":"getMenus","value":{"name":"Students","slug":"students","custom_class":"Students","id":1},"size":"col-lg-6"},{"type":"multidropdown","key":"account_menu","label":"Account menu","route":"getMenus","value":{"name":"Account","slug":"account","custom_class":"Account"},"size":"col-lg-12"}]},"width":6}],"color":"white"},{"id":"75b73fe4-6422-4b14-9279-42034d65517b","width":"content","order":3,"description":"","collapsed":false,"color":"white","tab":"Content","tabs_tab":null,"columns":[{"id":"2b9f3071-9871-4c36-bdc6-41f8cdf123a8","order":0,"component":"public_profile","active":true,"template":{"version":2,"title":"Public profile","icon":"dripicons-user","key":"public_profile_component","tab":"Content","width":"content","simple_view":false,"mobile":true,"inputs":[]},"width":6}]}]

        $dynamicPageData = [
            'user' => $user,
        ];

        return view('new_web.page', [
            'content' => json_decode($page->content ? $page->content : $page->rows),
            'page_id' => $page->id,
            'comments' => $page->comments ? $page->comments->take(500) : null,
            'page' => $page,
            'dynamic_page_data' => $dynamicPageData,
            'thankyouData' => null,
            'renderFbChat' => true,
        ]);
    }
}

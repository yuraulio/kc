<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'name',
        'menuable_id',
        'menuable_type'
    ];

    public function menuable()
    {
        return $this->morphTo();
    }

    public static function getMenuElementsToDisplayInFront($inputs){
        $emptyElementMenu = [
            'name' => "",
            'title' => "",
            'mobile' => "",
        ];
        $mainMenuDesktop = $emptyElementMenu;
        $mainMenuMobile = $emptyElementMenu;
        $footerMenu1Desktop = $emptyElementMenu;
        $footerMenu1Mobile = $emptyElementMenu;
        $footerMenu2Desktop = $emptyElementMenu;
        $footerMenu2Mobile = $emptyElementMenu;
        $footerMenu3Desktop = $emptyElementMenu;
        $footerMenu3Mobile = $emptyElementMenu;
        $accountMenu = $emptyElementMenu;
    
        $allMenus = Cache::remember('menus', 60*60, function(){
            return Menu::all();
        });
        $menus = [];
        foreach ($inputs as $input){
            $menus[$input->key] = $input->value ?? "";
            if(!isset($input->value->id))
                continue;
            foreach($allMenus as $menu){
                if($input->value && $menu->id == $input->value->id){
                    switch($menu->id){
                        case $menus['main_menu_desktop']->id:
                            $mainMenuDesktop = $menu;
                            break;
                        case $menus['main_menu_mobile']->id:
                            $mainMenuMobile = $menu;
                            break;
                        case $menus['footer_menu_1_desktop']->id:
                            $footerMenu1Desktop = $menu;
                            break;
                        case $menus['footer_menu_1_mobile']->id:
                            $footerMenu1Mobile = $menu;
                            break;
                        case $menus['footer_menu_2_desktop']->id:
                            $footerMenu2Desktop = $menu;
                            break;
                        case $menus['footer_menu_2_mobile']->id:
                            $footerMenu2Mobile = $menu;
                            break;
                        case $menus['footer_menu_3_desktop']->id:
                            $footerMenu3Desktop = $menu;
                            break;
                        case $menus['footer_menu_3_mobile']->id:
                            $footerMenu3Mobile = $menu;
                            break;
                        case $menus['account_menu']->id:
                            $accountMenu = $menu;
                            break;
                    }
                }
            }
        }

        return [
            $mainMenuDesktop,
            $mainMenuMobile,
            $footerMenu1Desktop,
            $footerMenu1Mobile,
            $footerMenu2Desktop,
            $footerMenu2Mobile,
            $footerMenu3Desktop,
            $footerMenu3Mobile,
            $accountMenu
        ];
    }
}

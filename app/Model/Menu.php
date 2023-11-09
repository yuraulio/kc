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
    
        $allMenus = Cache::remember('CodexShaper-menus', 60*60, function(){
            return \CodexShaper\Menu\Models\Menu::all();
        });
        $menus = [];
        $menus = [];
        foreach ($inputs as $input){
            $menus[$input->key] = $input->value ?? "";
        }
        foreach ($inputs as $input){
            foreach($allMenus as $menu){
                if(!isset($input->value->id))
                    continue;
                $menu_formated = [
                    'name' => $menu->name ?? "",
                    'title' => $menu->custom_class ?? "",
                    'mobile' => $menu->url ?? "",
                ];
                if($input->value && $menu->id == $input->value->id){
                    if($menu->id == ($menus['main_menu_desktop']->id ?? 0)){
                        $mainMenuDesktop = $menu_formated;
                    }
                    if($menu->id == ($menus['main_menu_mobile']->id ?? 0)){
                        $mainMenuMobile = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_1_desktop']->id ?? 0)){
                        $footerMenu1Desktop = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_1_mobile']->id ?? 0)){
                        $footerMenu1Mobile = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_2_desktop']->id ?? 0)){
                        $footerMenu2Desktop = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_2_mobile']->id ?? 0)){
                        $footerMenu2Mobile = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_3_desktop']->id ?? 0)){
                        $footerMenu3Desktop = $menu_formated;
                    }
                    if($menu->id == ($menus['footer_menu_3_mobile']->id ?? 0)){
                        $footerMenu3Mobile = $menu_formated;
                    }
                    if($menu->id == ($menus['account_menu']->id ?? 0)){
                        $accountMenu = $menu_formated;
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

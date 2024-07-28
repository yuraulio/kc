@php
    $menus = [];
    foreach ($column->template->inputs as $input){
        $menus[$input->key] = $input->value ?? "";
    }
@endphp

{{-- get menus --}}
@php

    list(
        $mainMenuDesktop,
        $mainMenuMobile,
        $footerMenu1Desktop,
        $footerMenu1Mobile,
        $footerMenu2Desktop,
        $footerMenu2Mobile,
        $footerMenu3Desktop,
        $footerMenu3Mobile,
        $accountMenu
    ) = App\Model\Menu::getMenuElementsToDisplayInFront($column->template->inputs);
@endphp

{{-- main menu --}}
@section('main_menu_desktop')
    @menu($mainMenuDesktop["name"])
@endsection
@section('main_menu_mobile')
    @menu($mainMenuMobile["name"])
@endsection

{{-- footer menu 1 --}}
@section('footer_menu_1_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $footerMenu1Desktop["title"] }}</h4>
@endsection
@section('footer_menu_1_desktop')
    @menu($footerMenu1Desktop["name"])
@endsection
@section('footer_menu_1_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $footerMenu1Mobile["title"] }}</h4>
@endsection
@section('footer_menu_1_mobile')
    @menu($footerMenu1Mobile["name"])
@endsection

{{-- footer menu 2 --}}
@section('footer_menu_2_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $footerMenu2Desktop["title"] }}</h4>
@endsection
@section('footer_menu_2_desktop')
    @menu($footerMenu2Desktop["name"])
@endsection
@section('footer_menu_2_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $footerMenu2Mobile["title"] }}</h4>
@endsection
@section('footer_menu_2_mobile')
    @menu($footerMenu2Mobile["name"])
@endsection

{{-- footer menu 3 --}}
@section('footer_menu_3_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $footerMenu3Desktop["title"] }}</h4>
@endsection
@section('footer_menu_3_desktop')
    @menu($footerMenu3Desktop["name"])
@endsection
@section('footer_menu_3_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $footerMenu3Mobile["title"] }}</h4>
@endsection
@section('footer_menu_3_mobile')
    @menu($footerMenu3Mobile["name"])
@endsection

{{-- account menu --}}
@section('account_menu')
    @menu($accountMenu["name"])
@endsection

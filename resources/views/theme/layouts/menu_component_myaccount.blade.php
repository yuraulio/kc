{{-- get menus --}}
@php

    $mainMenuDesktop = get_menu(1 );
    $mainMenuMobile = get_menu(1 );
    $footerMenu1Desktop = get_menu(2 );
    $footerMenu1Mobile = get_menu(2 );
    $footerMenu2Desktop = get_menu(3 );
    $footerMenu2Mobile = get_menu(3 );
    $footerMenu3Desktop = get_menu(4 );
    $footerMenu3Mobile = get_menu(4 );

    $accountMenu = get_menu(8 );
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

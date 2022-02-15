@php
    $menus = [];
    foreach ($column->template->inputs as $input){
        $menus[$input->key] = $input->value ?? "";
    }
@endphp

{{-- main menu --}}
@section('main_menu_desktop')
    @menu($menus['main_menu_desktop']->slug ?? "")
@endsection
@section('main_menu_mobile')
    @menu($menus['main_menu_mobile']->slug ?? "")
@endsection

{{-- footer menu 1 --}}
@section('footer_menu_1_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $menus['footer_menu_1_desktop']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_1_desktop')
    @menu($menus['footer_menu_1_desktop']->slug ?? "")
@endsection
@section('footer_menu_1_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $menus['footer_menu_1_mobile']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_1_mobile')
    @menu($menus['footer_menu_1_mobile']->slug ?? "")
@endsection

{{-- footer menu 2 --}}
@section('footer_menu_2_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $menus['footer_menu_2_desktop']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_2_desktop')
    @menu($menus['footer_menu_2_desktop']->slug ?? "")
@endsection
@section('footer_menu_2_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $menus['footer_menu_2_mobile']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_2_mobile')
    @menu($menus['footer_menu_2_mobile']->slug ?? "")
@endsection

{{-- footer menu 2 --}}
@section('footer_menu_3_title_desktop')
    <h4 class="footer-title menu-desktop">{{ $menus['footer_menu_3_desktop']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_3_desktop')
    @menu($menus['footer_menu_3_desktop']->slug ?? "")
@endsection
@section('footer_menu_3_title_mobile')
    <h4 class="footer-title menu-mobile">{{ $menus['footer_menu_3_mobile']->custom_class ?? "" }}</h4>
@endsection
@section('footer_menu_3_mobile')
    @menu($menus['footer_menu_3_mobile']->slug ?? "")
@endsection
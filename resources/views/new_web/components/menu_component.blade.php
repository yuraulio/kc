@php
    $menus = [];
    foreach ($column->template->inputs as $input){
        $menus[$input->key] = $input->value ?? "";
    }
@endphp

@section('main-menu')
    @menu($menus['main_menu']->slug ?? "")
@endsection

@section('footer-menu-1-title')
    <h4 class="footer-title">{{ $menus['footer_menu_1']->name ?? "" }}</h4>
@endsection
@section('footer-menu-1')
    @menu($menus['footer_menu_1']->slug ?? "")
@endsection

@section('footer-menu-2-title')
    <h4 class="footer-title">{{ $menus['footer_menu_2']->name ?? "" }}</h4>
@endsection
@section('footer-menu-2')
    @menu($menus['footer_menu_2']->slug ?? "")
@endsection

@section('footer-menu-3-title')
    <h4 class="footer-title">{{ $menus['footer_menu_3']->name ?? "" }}</h4>
@endsection
@section('footer-menu-3')
    @menu($menus['footer_menu_3']->slug ?? "")
@endsection
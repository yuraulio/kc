@php
	use App\Model\Admin\Page;
@endphp

<ul class="level_root {{ $settings['levels']['root']['style'] }}">
	
    @foreach ($menuItems as $menu)
		{{-- I am using "middleware" for data if menu should be shown on mobile --}}
		@php
			$parentMenu = (new Page)->getMenu($menu->menu_id ?? "");
			if (!$menu->middleware) {
				$menu->custom_class = $menu->custom_class . " hide-on-mobile";
			}
			if ($parentMenu["mobile"] != "1") {
				$menu->custom_class = $menu->custom_class . " hide-on-mobile";
			}
		@endphp
		@include(
			'menu::menus.recursive', 
			[
				'menu'=>$menu,
				'settings'=>$settings,
				'i' => 0
			])
    @endforeach
</ul>
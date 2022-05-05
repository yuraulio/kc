@php
    if (!filter_var($menu->url, FILTER_VALIDATE_URL)) { 
        $menu->url = env("APP_URL") . $menu->url;
    } 
@endphp

<li data-id="{{$menu->id}}" class="menu_item">
    <a class="{{$menu->custom_class}}" href="{{ $menu->url }}">
        <span class="menu-icon"></span>{!! $menu->icon !!}
        <span class="menu-title">{{ $menu->title }}</span>
    </a>
    @if (count($menu->children) > 0)
        <ul class="
        level_{{ ++$i }}
        {{ menu_lebel_show($settings['levels'], 'level_'.$i) }}
        {{ menu_lebel_position($settings['levels'], 'level_'.$i) }}">
            @foreach($menu->children as $menu)
                @include(
                    'menu::menus.recursive',
                    [
                        'menu'=>$menu,
                        'settings'=>$settings,
                        'i' => $i
                    ])
            @endforeach
        </ul>
    @endif
</li>

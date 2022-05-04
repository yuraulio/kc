@php
    $tabs = [];
    foreach ($column->template->inputs as $input){
        $tabs[$input->key] = [
            "value" => $input->value ?? "",
            "tabs" => $input->tabs ?? "",
        ];
    }

    $event = $dynamic_page_data["event"] ?? null;
    $is_event_paid = $dynamic_page_data["is_event_paid"] ?? null;
    $estatus = $event->status ?? null;

    function checkTabContent($tab, $dynamic_page_data, $tabs) {
        foreach ($tabs["tabs"]["value"] as $tab_content) {
            if ($tab_content->tabs_tab == $tab) {

                if ($tab == "faq") {
                    $tab = "questions";
                }
                if ($tab == "overview") {
                    return true;
                }
                if (isset($dynamic_page_data["sections"][$tab])) {
                    if ($dynamic_page_data["sections"][$tab]->first()->visible) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }
        return false;
    }

@endphp

<div class="section-course-tabs">
    <div class="content-wrapper">
        <div class="tabs-wrapper fixed-tab-controls">
            <div class="tab-controls">
                <div class="container tabs-container">

                            <a href="#" class="mobile-tabs-menu">Overview</a>
                            <ul class="clearfix tab-controls-list">
                                @foreach ($tabs["tabs"]["tabs"] as $index=>$tab)
                                    @if (checkTabContent($tab, $dynamic_page_data, $tabs))
                                        <li><a href="#{{Illuminate\Support\Str::slug($tab)}}" class="{{$index == 0 ? "active" : "" }}">{{$tab}}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                            
                            @if ($event)
                                @if($event->view_tpl == "elearning_free")
                                    @if($is_event_paid==0 && !Auth::user())
                                        <a href="{{ route('cart.add-item', [ $event->id,'free', 8 ]) }}" class="btn btn--lg btn--primary hidden-sm go-to-href">ENROLL FOR FREE</a>
                                    @elseif($is_event_paid==0 && Auth::user())
                                        <a href="{{ route('enrollForFree',  $event->id) }}" class="btn btn--lg btn--primary hidden-sm go-to-href">ENROLL FOR FREE</a>
                                    @endif
                                @else
                                @if($estatus == 0 && !$is_event_paid)
                                        <a href="#seats" class="btn btn--lg btn--primary go-to-href">ENROLL NOW</a>
                                    @elseif($estatus != 3 && $estatus != 1 && !$is_event_paid)
                                        <a href="#seats" class="btn btn--lg btn--primary go-to-href go-to-href soldout">SOLD OUT</a>
                                    @endif
                                @endif
                            @endif
                
                </div>
            </div>

            <div class="tabs-content">
                @foreach ($tabs["tabs"]["tabs"] as $index => $tab)
                    <div id="{{Illuminate\Support\Str::slug($tab)}}" class="p-0 tab-content-wrapper {{ $index == 0 ? 'active-tab' : '' }}">
                        @foreach ($tabs["tabs"]["value"] as $data)
                            @if ($data->tabs_tab == $tab)
                                @include("new_web.layouts.rows")
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
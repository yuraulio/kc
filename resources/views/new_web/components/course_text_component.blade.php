@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $event = $dynamic_page_data["event"] ?? null;

    $title = '' ;
    $body = '' ;
    if(isset($sections['overview'])){
        $title = $sections['overview']->first()->title ?? null;
        $body = $sections['overview']->first()->description ?? null;
    }
@endphp

<div class="" itemprop="abstract">
    <h2 class="tab-title">{{$title}}</h2>
    <h3 > {!!$body!!} </h3>
    {!! $event->body !!}
</div>

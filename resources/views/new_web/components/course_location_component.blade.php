@php
    $sections = $dynamic_page_data["sections"] ?? null;
    $topics = $dynamic_page_data["topics"] ?? null;
    $venues = $dynamic_page_data["venues"] ?? null;
    $title = '';
    $body = '';
    if(isset($sections['location'])){
        $title = $sections['location']->first()->title ?? null;
        $body = $sections['location']->first()->description ?? null;
    }
@endphp

@if(count($venues))
               
<div class="course-full-text mt-5 mb-5">
<h2 class="text-align-center text-xs-left tab-title">{!!$title!!}</h2>
<h3>{!!$body!!}</h3>
@foreach($venues as $vkey => $venue)

<div class="location-text">
    <h3>{!! $venue['name'] !!} </h3>
    <p>{!! $venue['address'] !!}<br/><br/><br/>{!!$venue['direction_description']!!}</p>
</div>
<div class="location-map-wrapper">
    <iframe  src="https://maps.google.com/maps?q={!! $venue['name'] !!}&z=17&output=embed" width="1144" height="556" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
</div>
@endforeach
</div>

@endif
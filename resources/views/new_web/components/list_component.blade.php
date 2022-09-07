@php
    $list = [];
    foreach ($column->template->inputs as $input){
        $list[$input->key] = $input->value ?? "";
    }

    $source = $list["list_source"]->title;
@endphp

@if($source == "Blog")
    @include("new_web.components.lists.blog_list_component")
@elseif($source == "Knowledge")
    @include("new_web.components.lists.knowledge_list_component")
@elseif($source == "Courses")
    @include("new_web.components.lists.events_list_component")
@elseif($source == "Instructors")
    @include("new_web.components.lists.instructors_list_component")
@elseif($source == "City")
    @include("new_web.components.lists.city_list_component")
@elseif($source == "Homepage - in class events" || $source == "Homepage - elearning events")
    @include("new_web.components.lists.homepage_events_component")
@endif
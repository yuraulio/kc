@php
    $html = [];
    foreach ($column->template->inputs as $input){
        $html[$input->key] = $input->value ?? "";
    }
@endphp

{!! $html["html_embed"] ?? "" !!}
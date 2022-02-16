@php
    $html = [];
    foreach ($column->template->inputs as $input){
        $html[$input->key] = $input->value ?? "";
    }
@endphp

<div class="pb-4 pt-4">
    {!! $html["html_embed"] ?? "" !!}
</div>

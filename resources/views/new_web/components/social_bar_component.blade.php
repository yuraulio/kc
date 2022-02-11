@php
    $socials = [];
    foreach ($column->template->inputs as $input){
        $socials[$input->key] = $input->value ?? "";
    }
@endphp

<div class="">
    @include("global.social", ['summary' => $socials["social_summary"] ?? "", 'title' => $socials["social_title"] ?? ""])
</div>
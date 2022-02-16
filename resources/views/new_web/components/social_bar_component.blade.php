@php
    $socials = [];
    foreach ($column->template->inputs as $input){
        $socials[$input->key] = $input->value ?? "";
    }
@endphp

<div class="pb-4 pt-4">
    @include("global.social", ['summary' => $socials["social_summary"] ?? "", 'title' => $socials["social_title"] ?? ""])
</div>
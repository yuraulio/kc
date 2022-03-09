@php
    use App\Library\PageVariables;

    $text = [];
    foreach ($column->template->inputs as $input){
        $text[$input->key] = $input->value;
    }

    $display_text = $text["rich_text_box_title"] ?? "";
@endphp

{{-- <div class="blog_body_content content-text-area pb-4 pt-4 m-0"> --}}
<div class="mt-5 mb-5 m-0">
    {!! PageVariables::parseText($display_text, $page, $dynamic_page_data ?? null) !!}
</div>
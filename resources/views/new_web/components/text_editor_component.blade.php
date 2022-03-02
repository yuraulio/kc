@php
    use App\Library\PageVariables;

    $text = [];
    foreach ($column->template->inputs as $input){
        $text[$input->key] = $input->value;
    }

    $display_text = $text["rich_text_box_title"] ?? "";

    $collection = collect(json_decode(json_encode($content), true)));
    dd($collection->pluck('meta_title'));
    dd(array_flatten(json_decode(json_encode($content), true)));
@endphp

<div class=" pb-4 pt-4 m-0">
    {!! PageVariables::parseText($display_text, $page, $dynamic_page_data ?? null) !!}
</div>
@php
    use App\Library\PageVariables;

    if ($column->template->dynamic) {
        if ($dynamic_page_data && isset($dynamic_page_data["event"])){
            $sections = $dynamic_page_data["sections"] ?? null;
            $event = $dynamic_page_data["event"] ?? null;

            $title = '' ;
            $editor_text = '' ;
            if(isset($sections['overview'])){
                $title = $sections['overview']->first()->title ?? null;
                $editor_text = $event->body ?? null;
                $editor_text = PageVariables::parseText($editor_text, $page, $dynamic_page_data ?? null);
            }
        } else {
            $title = $dynamic_page_data["title"];
            $subtitle = $dynamic_page_data["content"]["header"];
            $editor_text = $dynamic_page_data["content"]['body'];
            $editor_text = PageVariables::parseText($editor_text, $page, $dynamic_page_data ?? null);
        }

    } else {
        $text = [];
        foreach ($column->template->inputs as $input){
            $text[$input->key] = $input->value ?? "";
        }

        $display_text = $text["rich_text_box_title"] ?? "";
        $editor_text = PageVariables::parseText($display_text, $page, $dynamic_page_data ?? null);
    }
@endphp

<div class="mt-5 mb-5 m-0 text-editor-blockquote">
    @if (isset($title))
        <h2 class="tab-title">{{$title}}</h2>
    @endif
    @if (isset($subtitle))
        <h5>{{ $subtitle }}</h5>
    @endif
    {!! $editor_text !!}
</div>
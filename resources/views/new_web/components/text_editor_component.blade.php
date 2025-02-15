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
            $title = $dynamic_page_data["title"] ?? null;
            $subtitle = $dynamic_page_data["content"]["header"] ?? null;
            $company = $dynamic_page_data["content"]['company'] ?? null;
            $companyUrl = $dynamic_page_data["content"]['ext_url'] ?? null;
            $editor_text = $dynamic_page_data["content"]['body'] ?? null;
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

<div class="mt-5 mb-5 m-0 cms-rich-text-editor text-editor-blockquote {{ $page->slug == "about" ? " about-static-page " : "" }}">
    @if (isset($title) && !isset($dynamic_page_data['event']))
        <h1 class="tab-title">{{ PageVariables::parseText($title, $page, $dynamic_page_data ?? null) }}</h1>
    @endif
    @if (isset($subtitle))
        <h2 style="font-size: 30px;">
            {{ PageVariables::parseText($subtitle, $page, $dynamic_page_data ?? null) }}@if (isset($company)), <a target="_blank" title="{{ $company }}" href="{{ $companyUrl }}"> {{ $company }}</a>@endif
        </h2>
    @endif
    {!! $editor_text !!}
</div>

@if (($column->template->dynamic && $dynamic_page_data && isset($dynamic_page_data["event"])))
    <script type="application/ld+json">
        {!! json_encode($event->schemadata()) !!}
   </script>
@endif

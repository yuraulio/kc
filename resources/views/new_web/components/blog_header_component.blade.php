<div class="p-0 pb-1 pt-1 cms-page-header cms-rich-text-editor">
    @foreach ($column->template->inputs as $input)
        @if($input->key == "blog_header_title")
           {!! $input->value ?? "" !!}
        @endif
    @endforeach
</div>

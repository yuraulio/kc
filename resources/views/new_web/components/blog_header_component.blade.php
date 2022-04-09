<div class="p-0 pb-4 pt-4 container blogx-container">
    @foreach ($column->template->inputs as $input)
        @if($input->key == "blog_header_title")
           {!! $input->value ?? "" !!}
        @endif
    @endforeach
</div>

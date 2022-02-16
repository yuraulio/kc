<div class="pb-4 pt-4">
    @foreach ($column->template->inputs as $input)
        @if($input->key == "blog_header_title")
            <h1 class='blog_title'>{{$input->value ?? ""}}</h1>
        @elseif($input->key == "blog_header_subtitle")
            <h5 class='blog_subtitle mb-0'>{{$input->value ?? ""}}</h5>
        @endif
    @endforeach
</div>
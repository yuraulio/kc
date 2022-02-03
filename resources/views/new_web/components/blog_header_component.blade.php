<div class="">
    @foreach ($column->template->inputs as $input)
        @if($input->key == "blog_header_title")
            <h1 class='blog_title' style="margin-top: 20px;">{{$input->value}}</h1>
        @elseif($input->key == "blog_header_subtitle")
            <h5 class='blog_subtitle'>{{$input->value}}</h5>
        @endif
    @endforeach
</div>
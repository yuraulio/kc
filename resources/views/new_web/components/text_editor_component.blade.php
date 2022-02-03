<div class="blog_body_content content-text-area">
    @foreach ($column->template->inputs as $input)
        {!! $input->value ?? "" !!}
    @endforeach
</div>
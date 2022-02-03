<div class="blog_body_content">
    @foreach ($column->template->inputs as $input)
        {!! $input->value ?? "" !!}
    @endforeach
</div>
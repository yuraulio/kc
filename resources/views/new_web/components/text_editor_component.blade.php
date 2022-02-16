<div class="blog_body_content content-text-area pb-4 pt-4">
    @foreach ($column->template->inputs as $input)
        {!! $input->value ?? "" !!}
    @endforeach
</div>
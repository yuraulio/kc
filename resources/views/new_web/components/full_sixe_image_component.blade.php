@foreach ($column->template->inputs as $input)
    <img src="{{ $input->value }}" class="center" alt="">
@endforeach
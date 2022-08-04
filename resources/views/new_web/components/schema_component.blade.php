
@php
    $meta = [];
    foreach ($column->template->inputs as $input){
        $meta[$input->key] = $input->value ?? "";
    }
@endphp

@section('header')

    @if(isset($meta['meta_schema']) && $meta['meta_schema'])
        <script type="application/ld+json">
            {!! $meta['schema_schema'] !!}
        </script>
    @endif

@endsection


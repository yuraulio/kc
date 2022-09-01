
@php
    $schema = [];
    foreach ($column->template->inputs as $input){
        $schema[$input->key] = $input->value ?? "";
    }
@endphp

@if(isset($schema['meta_schema']) && $schema['meta_schema'])
    <script type="application/ld+json">
        {!! $schema['schema_schema'] !!}
    </script>
@endif


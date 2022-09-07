
@php
    $schema = [];
    foreach ($column->template->inputs as $input){
        $schema[$input->key] = $input->value ?? "";
    }

@endphp

@if(isset($schema['schema_schema']) && $schema['schema_schema'])
    <script type="application/ld+json">
        {!! $schema['schema_schema'] !!}
    </script>
@endif


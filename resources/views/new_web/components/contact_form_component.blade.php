@php
    $form = [];
    foreach ($column->template->inputs as $input){
        $form[$input->key] = $input->value ?? "";
    }

    $type = $form["form_type"]->id ?? 1;
    $overlap = $form['top_overlap'] ?? true;
    $overlap_class = $overlap ? 'form-overlap' : 'form-default';
@endphp

@if ($type == 1)
    <!-- contact form -->
    @include("new_web.components.forms.contact")
@elseif ($type == 2)
    <!-- corporate training form -->
    @include("new_web.components.forms.corporate_training")
@elseif ($type == 3)
    <!-- become an instructor form -->
    @include("new_web.components.forms.become_an_instructor")
@elseif ($type == 4)
    <!-- giveaway form -->
    @include("new_web.components.forms.giveaway")
@endif
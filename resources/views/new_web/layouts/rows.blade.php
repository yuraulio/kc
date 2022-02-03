
@if(isset($data->width) && $data->width == "full")
    <div class="container-fluid">
        <div class="row  mb-5">
            @foreach ($data->columns as $column)
                <div class="col-md-{{ 12 / count($data->columns) }}">
                    @includeIf("new_web.components." . $column->template->key)
                </div>
            @endforeach
        </div>
    </div>
@elseif(isset($data->width) && $data->width == "blog")
    <div class="container blogx-container">
        <div class="row mb-5">
            @foreach ($data->columns as $column)
                <div class="col-md-{{ 12 / count($data->columns) }}">
                    @includeIf("new_web.components." . $column->template->key)
                </div>
            @endforeach
        </div>
    </div>
@elseif(isset($data->width) && $data->width == "content")
    <div class="container">
        <div class="row mb-5">
            @foreach ($data->columns as $column)
                <div class="col-md-{{ 12 / count($data->columns) }}">
                    @includeIf("new_web.components." . $column->template->key)
                </div>
            @endforeach
        </div>
    </div>
@else
    @foreach ($data->columns as $column)
        @includeIf("new_web.components." . $column->template->key)
    @endforeach
@endif

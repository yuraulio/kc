@if(isset($data->width) && $data->width == "full")
    <div class="background-{{$data->color}}">
        <div class="container-fluid">
            <div class="row">
                @foreach ($data->columns as $column)
                    <div class="col-md-{{ isset($column->width) ? ($column->width * 2) : (12 / count($data->columns)) }}">
                        @includeIf("new_web.components." . $column->template->key)
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@elseif(isset($data->width) && $data->width == "blog")
    <div class="background-{{$data->color}}">
        <div class="container blogx-container">
            <div class="row">
                @foreach ($data->columns as $column)
                    <div class="col-md-{{ isset($column->width) ? ($column->width * 2) : (12 / count($data->columns)) }}">
                        @includeIf("new_web.components." . $column->template->key)
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@elseif(isset($data->width) && $data->width == "content")
    <div class="background-{{$data->color}}">
        <div class="container ps-4 pe-4">
            <div class="row">
                @foreach ($data->columns as $column)
                    <div class="col-md-{{ isset($column->width) ? ($column->width * 2) : (12 / count($data->columns)) }}">
                        @includeIf("new_web.components." . $column->template->key)
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    @foreach ($data->columns as $column)
        @includeIf("new_web.components." . $column->template->key)
    @endforeach
@endif

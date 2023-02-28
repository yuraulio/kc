@if(isset($data->width) && $data->width == "full")
    @if($showHero)
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
    @else
    <div class="container blogx-container">
        <div class="row">
            @foreach ($data->columns as $column)
                @foreach((array)$column->template->inputs as $input)
                    @if($input->key == 'hero_title')
                        {!! $input->value !!}
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    @endif
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
@elseif(isset($data->width) && $data->width == "content" && !$showHero)
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
@elseif(!$showHero)
    @foreach ($data->columns as $column)
        @includeIf("new_web.components." . $column->template->key)
    @endforeach
@endif

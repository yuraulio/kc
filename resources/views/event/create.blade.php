@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Event') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Events Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('events.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="d-none form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority') }}">

                                    @include('alerts.feedback', ['field' => 'priority'])
                                </div>
                                <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">

                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="published" id="input-published">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'published'])

                                </div>

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>

                                @include('admin.city.event.index')
                                @include('admin.partner.event.index')

                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select multiple name="type_id[]" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}" required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}" >{{ $type->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>

                                <div class="form-group{{ $errors->has('delivery') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Delivery') }}</label>
                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" >
                                            @foreach ($delivery as $delivery)
                                                <option value="{{ $delivery->id }}" >{{ $delivery->name }}</option>
                                            @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'delivery'])
                                </div>

                                <div id="exp_input" class="form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
                                </div>



                                <div class="form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-delivery">{{ __('Access to files until') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control datepicker" id="input-release_date_files" name="release_date_files" placeholder="Select date" type="text" value="">
                                    </div>
                                    @include('alerts.feedback', ['field' => 'release_date_files'])
                                </div>



                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                            <option value="">-</option>
                                            <option value="4">{{ __('My Account Only') }}</option>
                                            <option value="2">{{ __('Soldout') }}</option>
                                            <option value="3">{{ __('Completed') }}</option>
                                            <option value="0">{{ __('Open') }}</option>
                                            <option value="1">{{ __('Close') }}</option>
                                            {{--<option value="5">{{ __('Waiting') }}</option>--}}

                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>
                                @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                                <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-htmlTitle">{{ __('HTML title') }}</label>
                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('HTML title') }}" value="{{ old('Short title') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'htmlTitle'])
                                </div>

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>

                                <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>

                                <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="text" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>
                                <div class="form-group{{ $errors->has('view_tpl') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-view_tpl">{{ __('View tpl') }}</label>
                                    <select name="view_tpl"  class="form-control" placeholder="{{ __('View tpl') }}">
                                        @foreach (get_templates('events') as $key => $template)
                                            <option value="{{ $template }}" {{ $template == old('template') ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'view_tpl'])
                                </div>

                                <?php //dd($instructors[10][0]); ?>

                                <div class="form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-syllabus">{{ __('Syllabus Manager') }}</label>
                                    <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus" class="form-control" placeholder="{{ __('Syllabus Manager') }}">
                                        <option value=""></option>

                                        @foreach ($instructors as $key => $instructor)
                                            @if($instructors[$key][0]->medias != null)
                                            <option ext="{{$instructors[$key][0]->medias['ext']}}" original_name="{{$instructors[$key][0]->medias['original_name']}}" name="{{$instructors[$key][0]->medias['name']}}" path="{{$instructors[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @else
                                            <option path="null" name="null" ext="null" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @endif

                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'syllabus'])
                                </div>

                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$user->id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$user->id}}">

                                    @include('alerts.feedback', ['field' => 'ext_url'])

                                    {{--@include('admin.upload.upload', ['event' => ( isset($event) && $event->medias != null) ? $event->medias : null])--}}






                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection


@push('js')

<script>

    instructors = @json($instructors);

    $(document).ready(function(){
        $("#input-syllabus").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions
            });
        });

        function formatOptions (state) {
        if (!state.id) {
            return state.text;
        }


        path = state.element.attributes['path'].value
        name = state.element.attributes['name'].value
        plus_name = '-instructors-small'
        ext = state.element.attributes['ext'].value

        var $state = $(
        '<span class="rounded-circle"><img class="avatar-sm rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'" /> ' + state.text + '</span>'
        );

        var $state1 = $(
        '<span class="avatar avatar-sm rounded-circle"><img class="rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'"/></span>'
        );

        return $state;
        }
</script>

<script>
    $( "#input-delivery" ).change(function() {
        if($(this).val() == 143){
            $('#exp_input').css('display', 'block')
        }else{
            $('#exp_input').css('display', 'none')
        }
    });
</script>

<script>

    $( "#select-image" ).click(function() {
        path = ''
        $.each( $('.fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = path +'/'+name+'.'+ext

        $('#image_upload').val(path)



        $('#img-upload').attr('src', path);

        $(".close").click();
    });



</script>


@endpush

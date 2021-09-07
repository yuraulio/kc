@extends('layouts.app', [
    'title' => __('Lesson Create'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('lessons.index') }}">{{ __('Lessons Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Lesson') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Lessons Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($topics); ?>
                    <div class="card-body">
                        <form method="post" action="{{ route('lessons.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Lesson information') }}</h6>
                            <div class="pl-lg-4">

                            <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                <label class="custom-toggle custom-published">
                                    <input name="status" id="input-status" type="checkbox">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                </label>
                                @include('alerts.feedback', ['field' => 'status'])
                            </div>


                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                {{--<div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-htmlTitle">{{ __('HTML Title') }}</label>
                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('HTML Title') }}" value="{{ old('htmlTitle') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'htmlTitle'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-control-label" for="input-topic_id">{{ __('Filters') }}</label>
                                            <div class="filter_col" data-column="9">
                                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="category">
                                                <option></option>
                                                </select>
                                            </div>
                                            <div class="filter_col" data-column="9">
                                                <input class="select2-css" type="text" placeholder="Search Topic" id="searchTopic" name="searchTopic">
                                            </div>

                                        </div>



                                        <div class="col-8">
                                            <label class="form-control-label" for="input-topic_id">{{ __('Topic') }}</label>
                                            <select multiple name="topic_id[]" id="input-topic_id" class="form-control topics" placeholder="{{ __('Topic') }}">
                                                @foreach ($topics as $topic)
                                                    <option data-category="{{$topic['category'][0]['name']}}" value="{{ $topic->id }}" >{{ $topic->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>


                                    @include('alerts.feedback', ['field' => 'topic_id'])
                                </div>

                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select name="type_id" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}">
                                        <option value="">-</option>
                                        @foreach ($types as $type)
                                            @if($type['id'] >= 150 && $type['id'] <= 161)
                                                <option value="{{ $type['id'] }}" @if(isset($lesson['type'][0]['id']) && $lesson['type'][0]['id'] == $type['id']) selected @endif>{{ $type['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>

                                <div class="form-group{{ $errors->has('bold') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-bold">{{ __('Bold') }}</label>
                                    <label class="custom-toggle custom-published toggle-bold">
                                        <input name="bold" id="input-bold" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="off" data-label-on="on"></span>
                                    </label>
                                    @include('alerts.feedback', ['field' => 'bold'])
                                </div>

                                {{--<div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
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
                                </div>--}}

                                <div class="form-group{{ $errors->has('vimeo_video') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-vimeo_video">{{ __('Vimeo Video') }}</label>
                                    <input type="text" name="vimeo_video" id="input-vimeo_video" class="form-control{{ $errors->has('vimeo_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Vimeo Video') }}" value="{{ old('vimeo_video') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'vimeo_video'])
                                </div>
                                <div class="form-group{{ $errors->has('vimeo_duration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-vimeo_duration">{{ __('Vimeo Duration') }}</label>
                                    <input type="text" name="vimeo_duration" id="input-vimeo_duration" class="form-control{{ $errors->has('vimeo_duration') ? ' is-invalid' : '' }}" placeholder="{{ __('Vimeo Duration') }}" value="{{ old('vimeo_duration') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'vimeo_duration'])
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary add-dynamic-link" type="button">Add Link</button>
                                    <div id="dynamic-link">


                                </div>
                                </div>
                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$user->id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$user->id}}">

                                    @include('alerts.feedback', ['field' => 'ext_url'])


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
    let topics = @json($topics);
    let uniqueTopics = []
    var selectedCategory = null

    function removeSpecial(s){
            s = s.replace(/ /g,'');
            s = s.replace(/&/g,'');
            s = s.replace(/amp;/g,'');
            return s
        }

    $(function() {

        $("#category").select2({
            placeholder: "By Category",
            allowClear: true
        });

        $.each(topics, function(key, value) {
            let categoryName = value.category[0].name
            if(uniqueTopics[categoryName] === undefined){
                uniqueTopics[categoryName] = categoryName
                $('#category').append(`<option value="${categoryName}">${categoryName}</option>`)
            }
        })

        //on select filter category Show or Hide topics
        $('#category').on('select2:select', function (e) {
            var data = e.params.data;
            selectedCategory = data.text
            $.each($('#input-topic_id option'), function(key, value) {
                if($(value).data('category') !== undefined){
                    if(removeSpecial($(value).data('category')) != removeSpecial(selectedCategory)){
                        $(value).hide()
                    }
                    else{
                        $(value).show()
                    }
                }

            })
        });

        $("#category").on("select2:unselecting", function (e) {
            $.each($('#input-topic_id option'), function(key, value) {
                $(value).show()
            })
            selectedCategory = null
        });


        $( "#searchTopic" ).keyup(function() {

            let word = $('#searchTopic').val().toLowerCase()
            if( word.length >= 3){
                $.each($('#input-topic_id option'), function(key, value) {
                    let name = $(value).text().toLowerCase()
                    if(name !== undefined){
                        if(selectedCategory == null){
                            if(name.includes(word)){
                                $(value).show()
                            }else{
                                $(value).hide()
                            }
                        }else{
                            if(name.includes(word) && (removeSpecial($(value).data('category')) == removeSpecial(selectedCategory))){
                                $(value).show()
                            }else{
                                $(value).hide()
                            }
                        }

                    }

                })

            }else if(word === ""){
                $.each($('#input-topic_id option'), function(key, value) {
                    if($(value).data('category') !== undefined){
                        if(selectedCategory == null){
                            $(value).show()
                        }else{
                            if(removeSpecial($(value).data('category')) != removeSpecial(selectedCategory)){
                                $(value).hide()
                            }
                            else{
                                $(value).show()
                            }
                        }

                    }

                })

            }
        });




    });



    $(document).on('click', '.add-dynamic-link', function() {

        count = $('.links').length + 1

        row = `
        <div class="lesson-links-admin">
            <input type="text" name="names[]" class="form-control names" placeholder="Enter Link Name" value="">
            <input type="text" name="links[]" class="form-control links" placeholder="Enter Link ${count}" value="">
            <button type="button" class="btn btn-danger remove-link">Remove</button>
        </div>
        `

        $('#dynamic-link').append(row)
    })

    $(document).on('click', '.remove-link', function() {
        $(this).parent().remove()

        $.each($('.links'), function(key, value) {
            key = key + 1
            $(value).attr('placeholder', 'Enter Link '+key)
        })
    })


</script>

@endpush

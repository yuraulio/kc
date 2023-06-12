@extends('layouts.app', [
    'title' => __('Lesson Edit'),
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
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Lesson') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="col-12 mt-2">
            @include('alerts.success')
            @include('alerts.errors')
        </div>
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
                    <div class="card-body">
                        <form method="post" action="{{ route('lessons.update', $lesson) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Lesson information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div style="margin:auto 0;" class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                        <label class="custom-toggle custom-published ">
                                            <input name="status" id="input-status" type="checkbox" @if($lesson->status) checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>

                                    <div style="margin:auto 40px;" class="form-group{{ $errors->has('bold') ? ' has-danger' : '' }}">
                                        <label style="margin-top: 1.5rem" class="form-control-label" for="input-bold">{{ __('Bold') }}</label>
                                        <label class="custom-toggle custom-published toggle-bold">
                                            <input name="bold" id="input-bold" type="checkbox" @if($lesson->bold) checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="off" data-label-on="on"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'bold'])
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Lesson Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $lesson->title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select name="type_id" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}">
                                        <option value="">-</option>
                                        @foreach ($types as $type)
                                            @if($type['id'] >= 150 && $type['id'] <= 161)
                                            <option @if(isset($lesson->type[0]['id']) && $lesson->type[0]['id'] == $type['id']) selected @endif
                                            value="{{ $type->id }}" > {{ $type->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>


                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-vimeo_video">{{ __('Vimeo Video') }}</label>
                                    <input type="text" name="vimeo_video" id="input-vimeo_video" class="form-control{{ $errors->has('vimeo_video') ? ' is-invalid' : '' }}" placeholder="{{ __('Vimeo Video') }}" value="{{ old('vimeo_video', $lesson->vimeo_video) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'vimeo_video'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-vimeo_duration">{{ __('Vimeo Duration') }}</label>
                                    <input type="text" name="vimeo_duration" id="input-vimeo_duration" class="form-control{{ $errors->has('vimeo_duration') ? ' is-invalid' : '' }}" placeholder="{{ __('Vimeo Duration') }}" value="{{ old('vimeo_duration', $lesson->vimeo_duration) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'vimeo_duration'])
                                </div>

                                <div class="form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                    <div class="row">
                                        <div class="col-12">
                                                <label class="form-control-label" for="input-topic_id">{{ __('Filters') }}</label>
                                                <div class="filter_col" data-column="9">
                                                    <select name="category" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="category">
                                                    <option></option>
                                                    </select>
                                                </div>
                                                <div class="filter_col" data-column="9">
                                                    <input class="select2-css" type="text" placeholder="Search Topic" id="searchTopic" name="searchTopic">
                                                </div>

                                            </div>

                                        <div class="col-12 topic-list">
                                            <label class="form-control-label" for="input-topic_id">{{ __('Topic') }}</label>
                                            <select multiple name="topic_id[]" id="input-topic_id" class="form-control topics" placeholder="{{ __('Topic') }}">

                                                @foreach ($topics as $topic)
                                                <?php $selected = false; ?>

                                                    @foreach($lesson->topic as $selected_topic)
                                                        @if($topic->id == $selected_topic['id'])
                                                            <?php $selected = true; ?>
                                                        @endif

                                                    @endforeach

                                                    @foreach($topic->category as $key => $tcategory)
                                                    <option <?= ($selected) ? 'selected' : ''; ?> @if($key>0) style="display:none" @endif data-categoryName="{{$tcategory->name}}" data-category="{{ $tcategory->id }}" value="{{ $topic->id }}" > {{ $topic->title }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @include('alerts.feedback', ['field' => 'topic_id'])
                                </div>


                                <div class="form-group">
                                    <button class="btn btn-primary add-dynamic-link" type="button">Add Link</button>
                                    <div id="dynamic-link">
                                        <?php //dd($lesson); ?>
                                        @if(!empty($lesson['links']))
                                            <?php
                                            $links = json_decode($lesson['links'], true);
                                            //dd($links);
                                             ?>
                                            @foreach($links as $key => $link)
                                            <div class="lesson-links-admin">
                                                <input type="text" name="names[]" class="form-control names" placeholder="Enter Link Name" value="{{$link['name']}}">
                                                <input type="text" name="links[]" class="form-control links" placeholder="Enter Link {{$key + 1}}" value="{{$link['link']}}">
                                                <button type="button" class="btn btn-danger remove-link">Remove</button>
                                            </div>
                                            @endforeach

                                        @endif

                                    </div>

                                </div>

                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$lesson->creator_id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$lesson->author_id}}">

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


            $.each(value.category, function(key1, value1) {

                let categoryName = value1.name

                if(uniqueTopics[categoryName] === undefined){
                    uniqueTopics[categoryName] = categoryName

                    let selected = '';

                    if(value1.id == "{{ $selectedCategory }}"){
                        selected = 'selected'
                    }

                    $('#category').append(`<option ${selected} value="${value1.id}">${categoryName}</option>`)
                }
            });



        })

        //on select filter category Show or Hide topics
        $('#category').on('select2:select', function (e) {
            var data = e.params.data;
            selectedCategory = data.text

            $.each($('#input-topic_id option'), function(key, value) {

                if($(value).data('categoryname') !== undefined){
                    if(removeSpecial($(value).data('categoryname')) != removeSpecial(selectedCategory)){
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
                            if(name.includes(word) && (removeSpecial($(value).data('categoryname')) == removeSpecial(selectedCategory))){
                                $(value).show()
                            }else{
                                $(value).hide()
                            }
                        }

                    }

                })

            }else if(word === ""){
                $.each($('#input-topic_id option'), function(key, value) {
                    if($(value).data('categoryname') !== undefined){
                        if(removeSpecial($(value).data('categoryname')) != removeSpecial(selectedCategory)){
                            $(value).hide()
                        }
                        else{
                            $(value).show()
                        }
                    }

                })

            }
        });


    })





    $(document).on('click', '.add-dynamic-link', function() {

        count = $('.links').length + 1

        row = `
        <div class="lesson-links-admin">
            <input type="text" name="names[]" class="form-control names" placeholder="Enter Link Name" value="">
            <input type="text" name="links[]" class="form-control links" placeholder="Enter Link ${count}" value="">
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

    let a = [];
    $( "#input-topic_id" ).change(function(e) {
        //alert( "Handler for .change() called." );

        let selected_value = $(this).find(":selected").val();
        let selected_cat = $(this).find(":selected").data("category");

        if(selected_cat == undefined){
            $( '.topics option' ).each(function() {
                $( this ).removeAttr("disabled")
                $(this).css("opacity", "1")

                let a = [];
            })
        }
        //alert('sdf'+selected_category)
        //console.log(selected_category)



        $( '.topics option' ).each(function(e) {
            let cat = $( this ).data('category')
            let value = $( this ).val()

            if(selected_cat == cat && selected_value != value){
                $(this).attr("disabled", "true")
                $(this).css("opacity", "0.4")
            }



            // if(a.find(e => a = selected_category)){
            //     alert(a)
            //     $(this).attr("disabled", "true")
            //     $(this).css("opacity", "0.4")
            // }

            // if(value != undefined){
            //     a.push(cat)
            // }
        });
    });

</script>

@endpush

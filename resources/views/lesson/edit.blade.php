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
                                <?php //dd($lesson); ?>

                            <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                <label class="custom-toggle custom-published">
                                    <input name="status" id="input-status" type="checkbox" @if($lesson->status) checked @endif>
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                </label>
                                @include('alerts.feedback', ['field' => 'status'])
                            </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $lesson->title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>
                                <?php //dd($topics[1]); ?>
                                <?php //dd($lesson->topic); ?>

                                <div class="form-group{{ $errors->has('topic_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-topic_id">{{ __('Topic') }}</label>
                                    <select multiple name="topic_id[]" id="input-topic_id" class="form-control topics" placeholder="{{ __('Topic') }}" required>
                                        <option value="">-</option>

                                        @foreach ($topics as $topic)
                                        <?php $selected = false; ?>

                                            @foreach($lesson->topic as $selected_topic)
                                                @if($topic->id == $selected_topic['id'])
                                                    <?php $selected = true; ?>
                                                @endif

                                            @endforeach

                                            <option <?= ($selected) ? 'selected' : ''; ?> data-category="{{ $topic->category[0]->id }}" value="{{ $topic->id }}" > {{ $topic->title }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'topic_id'])
                                </div>
                                <?php //dd(count($lesson->type) == 0); ?>
                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select name="type_id" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}">
                                        <option value="">-</option>
                                        @foreach ($types as $type)
                                            @if($type['id'] >= 150 && $type['id'] <= 161)

                                            <option <?php if(count($lesson->type) != 0){
                                                if($lesson->type[0]->id == $type->id){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>
                                            value="{{ $type->id }}" > {{ $type->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>

                                <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-htmlTitle">{{ __('HTML Title') }}</label>
                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('HTML Title') }}" value="{{ old('Short title', $lesson->htmlTitle) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'htmlTitle'])
                                </div>

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle', $lesson->subtitle) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>

                                <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $lesson->header) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>

                                <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" required autofocus>{{ old('summary', $lesson->summary) }}</textarea>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $lesson->body) }}</textarea>

                                    @include('alerts.feedback', ['field' => 'body'])
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
        console.log('select_value: ' + selected_value)
        console.log('select_cat: ' + selected_cat)

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

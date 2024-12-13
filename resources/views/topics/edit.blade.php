@extends('layouts.app', [
    'title' => __('Topic Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'topics-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('topics.index') }}">{{ __('Topics Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Topic') }}</li>
        @endcomponent
    @endcomponent
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Topics Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('topics.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="nav-wrapper tab-buttons">
                        <div class="text-right">
                            <button id="submit-btn" type="button" class="submit-btn btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                    </div>

                    <div id="mobile_menu" class="dropdown d-none">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item settings-btn active" data-toggle="tab"  href="#tabs-icons-text-1" role="tab" type="button">
                            <span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span>
                            <span class="btn-inner--text">Topic Info</span>
                        </button>
                        <button class="dropdown-item seo" data-toggle="tab"  href="#tabs-icons-text-2" role="tab" type="button">
                            <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                            <span class="btn-inner--text">Automated Emails</span>
                        </button>


                        </div>
                    </div>

                    <div class="nav-wrapper tab-buttons">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row event-tabs" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">

                                <button class="btn btn-icon btn-primary settings-btn active" data-toggle="tab"  href="#tabs-icons-text-1" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span>
                                    <span class="btn-inner--text">Topic Info</span>
                                </button>

                            </li>
                            <li class="nav-item">

                                <button class="btn btn-icon btn-primary settings-btn" data-toggle="tab"  href="#tabs-icons-text-2" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-email-83"></i></span>
                                    <span class="btn-inner--text">Automated Emails</span>
                                </button>

                            </li>
                        </ul>
                    </div>



                    <div class="card-body">


                        <form id="topic_edit_form" method="post" action="{{ route('topics.update', $topic) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input name="fromCategory" value="{{$fromCategory}}" hidden>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    {{--<h6 class="heading-small text-muted mb-4">{{ __('Topic information') }}</h6>--}}
                                    <div class="pl-lg-4">

                                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">


                                            <label class="custom-toggle custom-published">
                                                    <input type="checkbox" name="status" id="input-status" @if($topic['status'] == '1') checked @endif>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                                </label>
                                                @include('alerts.feedback', ['field' => 'status'])

                                        </div>

                                        <div class="d-none form-group{{ $errors->has('comment_status') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-comment_status">{{ __('Comment status') }}</label>
                                            <input type="text" name="comment_status" id="input-comment_status" class="form-control{{ $errors->has('comment_status') ? ' is-invalid' : '' }}" placeholder="{{ __('Comment status') }}" value="{{ old('comment_status', $topic->comment_status) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'comment_status'])
                                        </div>

                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                            <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $topic->title) }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }} topic-list">
                                            <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                            <select multiple name="category_id[]" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                                <option value=" ">-</option>
                                                @foreach ($categories as $category)
                                                    <option <?php if(count($topic->category) != 0){


                                                        if(in_array($category->id,$topic->category()->pluck('category_id')->toArray())){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?>
                                                    value="{{ $category->id }}" >{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'category_id'])
                                        </div>

                                        <div class="form-group{{ $errors->has('short_title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-short_title">{{ __('Short title') }}</label>
                                            <input type="text" name="short_title" id="input-short_title" class="form-control{{ $errors->has('short_title') ? ' is-invalid' : '' }}" placeholder="{{ __('short_title') }}" value="{{ old('short_title', $topic->short_title) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'short_title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('subtitle', $topic->subtitle) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'subtitle'])
                                        </div>

                                        <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                            <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $topic->header) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'header'])
                                        </div>

                                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                            {{--<textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" required autofocus>{{ old('summary', $topic->summary) }}</textarea>--}}

                                            <!-- anto's editor -->
                                            <input class="hidden" name="summary" value="{{ old('summary',$topic->summary) }}"/>
                                            <?php $data = $topic->summary?>
                                            @include('event.editor.editor', ['keyinput' => "input-summary", 'data'=> "$data", 'inputname' => "'summary'" ])
                                            <!-- anto's editor -->

                                            @include('alerts.feedback', ['field' => 'summary'])
                                        </div>

                                        <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                            {{--<textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $topic->body) }}</textarea>--}}

                                            <!-- anto's editor -->
                                            <input class="hidden" name="body" value="{{ old('body',$topic->body) }}"/>
                                            <?php $data = $topic->body?>
                                            @include('event.editor.editor', ['keyinput' => "input-body", 'data'=> "$data", 'inputname' => "'body'" ])
                                            <!-- anto's editor -->

                                            @include('alerts.feedback', ['field' => 'body'])
                                        </div>




                                            <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$topic->creator_id}}">
                                            <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$topic->author_id}}">

                                            @include('alerts.feedback', ['field' => 'ext_url'])


                                        {{--<div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>--}}
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    
                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-email_template">{{ __('Automated Email Template') }}</label>
                                            <select name="email_template[]" multiple id="input-email_template" class="form-control" placeholder="{{ __('Email Template') }}">
                                                <option value="">-</option>

                                                @foreach(automateEmailTemplates() as $key => $emailTemplate)

                                                    <option value="{{ $key }}" @if(strpos(old('email_template',$topic->email_template), $key) !== FALSE ) selected @endif >{{ $emailTemplate }}</option>

                                                @endforeach

                                            </select>

                                            @include('alerts.feedback', ['field' => 'email_template'])
                                        </div>
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
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>

<script>
    $( document ).ready(function() {

        mobileMenu()
        setActiveLabelMobileMenu()

        $(window).on('resize', function(){
            mobileMenu()
        })

        $(document).on('click', '#mobile_menu .dropdown-item', function() {
            let items = $('#mobile_menu').find('.dropdown-item')

            $.each(items, function(index, value) {
                $(value).removeClass('active')
            })

            $(this).addClass('active')
            setActiveLabelMobileMenu()

        })

        $('.submit-btn').on('click', function(){

            $('#topic_edit_form').submit();
        })

        function mobileMenu(){
            var mobileWidth = 680;

            if($(window).width() <= mobileWidth){

                $('#mobile_menu').removeClass('d-none')
                $('.nav-wrapper.tab-buttons').addClass('d-none')

                $('.tab-pane-mobile').removeClass('d-none');
                $('#tab_inside_tab').addClass('has_mobile_menu');

            }else{

                $('#mobile_menu').addClass('d-none')
                $('.nav-wrapper.tab-buttons').removeClass('d-none')

                $('.tab-pane-mobile').addClass('d-none');
                $('#tab_inside_tab').removeClass('has_mobile_menu');

            }




        }

        function setActiveLabelMobileMenu(){
            let items = $('#mobile_menu').find('.dropdown-item')
            $.each(items, function(index, value) {
                if($(value).hasClass('active')){
                    $('#dropdownMenuButton').text($(value).text())
                }
            })
        }
    });
</script>
@endpush

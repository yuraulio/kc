@extends('layouts.app', [
    'title' => __('Instructors Management'),
    'parentSection' => 'laravel',
    'elementName' => 'instructors-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('instructors.index') }}">{{ __('Instructors Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Instructor') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">

            <div class="col-xl-12 order-xl-1">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Edit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#metas" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Metas</a>
                        </li>

                    </ul>
                </div>
            <div>

            <div class="col-xl-12 order-xl-1">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <form method="post" action="{{ route('instructors.update', $instructor) }}" autocomplete="off"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <h6 class="heading-small text-muted mb-4">{{ __('Instructor information') }}</h6>
                                    <div class="pl-lg-4">


                                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">


                                            <label class="custom-toggle custom-published">
                                                <input type="checkbox" name="status" id="input-status" @if($instructor['status'] == '1') checked @endif>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                            </label>
                                                @include('alerts.feedback', ['field' => 'status'])

                                        </div>

                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                            <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $instructor->title) }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('short_title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-short_title">{{ __('Short title') }}</label>
                                            <input type="text" name="short_title" id="input-short_title" class="form-control{{ $errors->has('short_title') ? ' is-invalid' : '' }}" placeholder="{{ __('short_title') }}" value="{{ old('Short title', $instructor->short_title) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'short_title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle', $instructor->subtitle) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'subtitle'])
                                        </div>

                                        <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                            <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $instructor->header) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'header'])
                                        </div>

                                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                            <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" required autofocus>{{ old('summary', $instructor->summary) }}</textarea>

                                            @include('alerts.feedback', ['field' => 'summary'])
                                        </div>

                                        <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                            <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $instructor->body) }}</textarea>

                                            @include('alerts.feedback', ['field' => 'body'])
                                        </div>

                                        <div class="form-group{{ $errors->has('ext_url') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-ext_url">{{ __('Company') }}</label>
                                            <input type="text" name="company" id="input-ext_url" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" placeholder="{{ __('Company') }}" value="{{ old('company', $instructor->company) }}"autofocus>

                                            @include('alerts.feedback', ['field' => 'company'])
                                        </div>

                                        <div class="form-group{{ $errors->has('ext_url') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-ext_url">{{ __('External url') }}</label>
                                            <input type="text" name="ext_url" id="input-ext_url" class="form-control{{ $errors->has('ext_url') ? ' is-invalid' : '' }}" placeholder="{{ __('External url') }}" value="{{ old('ext_url', $instructor->ext_url) }}"autofocus>

                                            @include('alerts.feedback', ['field' => 'ext_url'])
                                        </div>

                                        <div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-user_id">{{ __('Assign User') }}</label>
                                            <select name="user_id" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-user_id" class="form-control" placeholder="{{ __('Instructor') }}">
                                                <option value=""></option>

                                                @foreach ($users as $key => $user)

                                                @if($instructor->user->first() != null && $instructor->user->first()['id'] == $user['id'])
                                                <option selected value="{{$user['id']}}">{{ $user['firstname'] }} {{ $user['lastname'] }}</option>
                                                @else
                                                <option value="{{$user['id']}}">{{ $user['firstname'] }} {{ $user['lastname'] }}</option>
                                                @endif

                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'user_id'])
                                        </div>

                                        <?php
                                            $social_media = json_decode($instructor['social_media'], true);
                                            //dd($social_media);
                                        ?>

                                        <div class="form-group{{ $errors->has('facebook') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-facebook">{{ __('Facebook') }}</label>
                                            <input type="text" name="facebook" id="input-facebook" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" placeholder="{{ __('Facebook link') }}" value="<?= (isset($social_media['facebook'])) ? $social_media['facebook'] : ''; ?>"autofocus>

                                            @include('alerts.feedback', ['field' => 'facebook'])
                                        </div>

                                        <div class="form-group{{ $errors->has('instagram') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-instagram">{{ __('Instagram') }}</label>
                                            <input type="text" name="instagram" id="input-instagram" class="form-control{{ $errors->has('instagram') ? ' is-invalid' : '' }}" placeholder="{{ __('Instagram link') }}" value="<?= isset($social_media['instagram']) ? $social_media['instagram'] : ''; ?>"autofocus>

                                            @include('alerts.feedback', ['field' => 'instagram'])
                                        </div>

                                        <div class="form-group{{ $errors->has('linkedin') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-linkedin">{{ __('Linkedin') }}</label>
                                            <input type="text" name="linkedin" id="input-linkedin" class="form-control{{ $errors->has('linkedin') ? ' is-invalid' : '' }}" placeholder="{{ __('Linkedin link') }}" value="<?= isset($social_media['linkedin']) ? $social_media['linkedin'] : ''; ?>"autofocus>

                                            @include('alerts.feedback', ['field' => 'linkedin'])
                                        </div>

                                        <div class="form-group{{ $errors->has('twitter') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-twitter">{{ __('Twitter') }}</label>
                                            <input type="text" name="twitter" id="input-twitter" class="form-control{{ $errors->has('twitter') ? ' is-invalid' : '' }}" placeholder="{{ __('Twitter link') }}" value="<?= isset($social_media['twitter']) ? $social_media['twitter'] : ''; ?>"autofocus>

                                            @include('alerts.feedback', ['field' => 'twitter'])
                                        </div>

                                        <div class="form-group{{ $errors->has('youtube') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-youtube">{{ __('Youtube') }}</label>
                                            <input type="text" name="youtube" id="input-youtube" class="form-control{{ $errors->has('youtube') ? ' is-invalid' : '' }}" placeholder="{{ __('Youtube link') }}" value="<?= isset($social_media['youtube']) ? $social_media['youtube'] : ''; ?>"autofocus>

                                            @include('alerts.feedback', ['field' => 'youtube'])
                                        </div>


                                        <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$instructor->creator_id}}">
                                        <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$instructor->author_id}}">


                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                @include('admin.upload.upload', ['event' => ( isset($instructor) && $instructor->medias != null) ? $instructor->medias : null, 'versions' => ['instructors-testimonials', 'instructors-small']])

                                @if($instructor->medias != null && $instructor->medias['name'] != '')
                                    <div id="version-btn" style="margin-bottom:20px" class="col">
                                        <a href="{{ route('media2.eventImage', $instructor->medias) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            @include('admin.metas.metas',['metas' => $metas])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div>
    <div>















        @include('layouts.footers.auth')
    </div>
@endsection

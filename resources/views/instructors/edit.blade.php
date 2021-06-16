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


<?php //dd($instructor->user()->first()); ?>

    <div class="container-fluid mt--6">

            <div class="col-xl-12 order-xl-1">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Edit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Image Version</a>
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
                                            <div class="status-label">
                                                <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                            </div>
                                            <div class="status-toogle">
                                                <label class="custom-toggle">
                                                    <input type="checkbox" name="status" id="input-status" <?= ($instructor['status'] == 1) ? 'checked' : ''; ?>>
                                                    <span class="custom-toggle-slider rounded-circle"></span>
                                                </label>
                                                @include('alerts.feedback', ['field' => 'status'])
                                            </div>
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

                                       
                                        <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$instructor->creator_id}}">
                                        <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$instructor->author_id}}">

                                        @include('alerts.feedback', ['field' => 'ext_url'])


                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            @include('admin.upload.upload', ['event' => ( isset($instructor) && $instructor->medias != null) ? $instructor->medias : null])
                            @include('event.image_versions', ['event' => $instructor->medias, 'versions1'=>['instructors-small', 'instructors-testimonials']])
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

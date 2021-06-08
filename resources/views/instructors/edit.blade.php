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
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Instructors Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('instructors.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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
                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$instructor->creator_id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$instructor->author_id}}">

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

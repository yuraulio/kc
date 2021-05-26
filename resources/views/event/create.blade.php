@extends('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
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
                                <div class="form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'priority'])
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

                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select name="type_id" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}">
                                        <option value="">-</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}" >{{ $type->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>

                                <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-published">{{ __('Published') }}</label>
                                    <select name="published" id="input-published" class="form-control" placeholder="{{ __('Published') }}" >
                                        <option value="">-</option>
                                            <option value="1">{{ __('Published') }}</option>
                                            <option value="0">{{ __('Unpublished') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'published'])
                                </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                            <option value="">-</option>
                                            <option value="3">{{ __('Soldout') }}</option>
                                            <option value="2">{{ __('Completed') }}</option>
                                            <option value="1">{{ __('Open') }}</option>
                                            <option value="0">{{ __('Close') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

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
                                    <input type="text" name="summary" id="input-summary" class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" value="{{ old('summary') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <input type="text" name="body" id="input-body" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" value="{{ old('body') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="number" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>
                                <div class="form-group{{ $errors->has('view_tpl') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-view_tpl">{{ __('View tpl') }}</label>
                                    <input type="text" name="view_tpl" id="input-view_tpl" class="form-control{{ $errors->has('view_tpl') ? ' is-invalid' : '' }}" placeholder="{{ __('View tpl') }}" value="{{ old('view_tpl') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'view_tpl'])
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

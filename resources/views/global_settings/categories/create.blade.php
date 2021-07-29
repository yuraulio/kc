@extends('layouts.app', [
    'title' => __('Role Management'),
    'parentSection' => 'laravel',
    'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('global.index') }}">{{ __('Categories Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Category') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Categories Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('global.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('category.store') }}" autocomplete="off">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Category information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}"  required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                    <textarea name="description" id="input-description" cols="30" rows="10" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description') }}"></textarea>

                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="text" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours') }}">

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlSelect1">{{ __('Select Dropbox Folder') }}</label>
                                    <select class="form-control" name="folder_name" id="folder_name">
                                        @foreach($folders as $folder)
                                            <option value="{{ $folder }}">{{ $folder }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'dropbox'])
                                </div>

                                <div class="form-group{{ $errors->has('show_homepage') ? ' has-danger' : '' }}">
                                    <div class="status-label">
                                        <label class="form-control-label" for="input-show_homepage">{{ __('Show Homepage') }}</label>
                                    </div>
                                    <div class="status-toogle">
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="show_homepage" id="input-show_homepage">
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'show_homepage'])
                                    </div>
                                </div>

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

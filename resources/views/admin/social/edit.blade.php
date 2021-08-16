@extends('layouts.app', [
    'title' => __('Social Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'social-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('social.index') }}">{{ __('Social Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="type">{{ __('Edit Social') }}</li>
        @endcomponent
    @endcomponent
<?php //dd($social); ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Social Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('social.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('social.update', $social) }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Social information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input disabled type="text" name="title" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $social['title']) }}"  required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-url">{{ __('Url') }}</label>
                                    <input type="text" name="url" id="input-url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" placeholder="{{ __('Url') }}" value="{{ old('url', $social['url']) }}"  required autofocus>

                                    @include('alerts.feedback', ['field' => 'url'])
                                </div>

                                <div class="form-group{{ $errors->has('target') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-target">{{ __('Target') }}</label>
                                    <input type="text" name="target" id="input-target" class="form-control{{ $errors->has('target') ? ' is-invalid' : '' }}" placeholder="{{ __('Target') }}" value="{{ old('target', $social['target']) }}"  required autofocus>

                                    @include('alerts.feedback', ['field' => 'target'])
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




@extends('layouts.app', [
    'title' => __('Section Management'),
    'parentSection' => 'laravel',
    'elementName' => 'section-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Examples') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">{{ __('Sections Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add section') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Section Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('section.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('section.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Section information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('section') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-section">{{ __('Section') }}</label>
                                    <input type="text" name="section" id="input-section" class="form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" placeholder="{{ __('Section') }}" value="{{ old('section') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'section'])
                                </div>

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                    <textarea name="description" id="input-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>

                                <input type="hidden" name="event_id" value="{{$event_id}}">


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

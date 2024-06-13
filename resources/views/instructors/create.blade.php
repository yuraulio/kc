@extends('layouts.app', [
    'title' => __('Instructor Management'),
    'parentSection' => 'laravel',
    'elementName' => 'instructors-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('instructors.index') }}">{{ __('Instructors Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Instructor') }}</li>
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
                        <form method="post" action="{{ route('instructors.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Instructor information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">

                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="status" id="input-status">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="inactive" data-label-on="active"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'status'])

                                </div>


                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('First name') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('First name') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                {{--<div class="form-group{{ $errors->has('short_title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-short_title">{{ __('Short title') }}</label>
                                    <input type="text" name="short_title" id="input-short_title" class="form-control{{ $errors->has('short_title') ? ' is-invalid' : '' }}" placeholder="{{ __('short_title') }}" value="{{ old('Short title') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'short_title'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('Last name') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('Last name') }}" value="{{ old('subtitle') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>

                                <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Job title') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Job title') }}" value="{{ old('header') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>

                                {{--<div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>

                                    <!-- anto's editor -->
                                    <input class="hidden" name="summary" value="{{ old('summary') }}"/>
                                    <?php $data = old('summary')?>
                                    @include('event.editor.editor', ['keyinput' => "input-summary", 'data'=> "$data", 'inputname' => "'summary'" ])
                                    <!-- anto's editor -->

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Description') }}</label>

                                    <!-- anto's editor -->
                                    <input class="hidden" name="body" value="{{ old('body') }}"/>
                                    <?php $data = old('body')?>
                                    @include('event.editor.editor', ['keyinput' => "input-body", 'data'=> "$data", 'inputname' => "'body'" ])
                                    <!-- anto's editor -->

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>

                                <div class="form-group{{ $errors->has('ext_url') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-ext_url">{{ __('Company url') }}</label>
                                    <input type="text" name="ext_url" id="input-ext_url" class="form-control{{ $errors->has('ext_url') ? ' is-invalid' : '' }}" placeholder="{{ __('Company url') }}" value="{{ old('ext_url') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'ext_url'])
                                </div>

                                <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-mobile">{{ __('Mobile') }}</label>
                                    <input type="number" name="mobile" id="input-mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile') }}"  autofocus>

                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>

                                <div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-user_id">{{ __('Assign User') }}</label>
                                    <select name="user_id" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-user_id" class="form-control" placeholder="{{ __('Instructor') }}">
                                        <option value=""></option>

                                        @foreach ($users as $key => $user)

                                            <option value="{{$user['id']}}">{{ $user['firstname'] }} {{ $user['lastname'] }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'user_id'])
                                </div>

                                {{--@include('admin.upload.upload', ['event' => ( isset($event) && $event->medias['path'] != null) ? $event : null])--}}

                                <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$user->id}}">
                                <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$user->id}}">


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
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>
@endpush

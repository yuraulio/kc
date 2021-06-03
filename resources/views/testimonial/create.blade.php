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

            <li class="breadcrumb-item"><a href="{{ route('testimonials.index') }}">{{ __('Testimonials Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Testimonial') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Testimonials Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('testimonials.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('testimonials.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Testimonial information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-lastname">{{ __('Last name') }}</label>
                                    <input type="text" name="lastname" id="input-lastname" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="{{ __('Last name') }}" value="{{ old('lastname') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'lastname'])
                                </div>

                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>

                                <div class="form-group{{ $errors->has('instructor_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-instructor_id">{{ __('Instructor') }}</label>
                                    <select name="instructor_id" id="input-instructor_id" class="form-control" placeholder="{{ __('Instructor') }}">
                                        <option value="">-</option>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->title }} {{ $instructor->subtitle }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'instructor_id'])
                                </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                            <option value="1">{{ __('Published') }}</option>
                                            <option value="0">{{ __('Unpublished') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('testimonial') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-testimonial">{{ __('Testimonial') }}</label>
                                    <input type="text" name="testimonial" id="input-testimonial" class="form-control{{ $errors->has('testimonial') ? ' is-invalid' : '' }}" placeholder="{{ __('Testimonial') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'testimonial'])
                                </div>

                                <div class="form-group{{ $errors->has('facebook') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-facebook">{{ __('Facebook link') }}</label>
                                    <input type="text" name="facebook" id="input-facebook" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" placeholder="{{ __('Facebook link') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'facebook'])
                                </div>

                                <div class="form-group{{ $errors->has('linkedin') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-linkedin">{{ __('Linkedin link') }}</label>
                                    <input type="text" name="linkedin" id="input-linkedin" class="form-control{{ $errors->has('linkedin') ? ' is-invalid' : '' }}" placeholder="{{ __('Linkedin link') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'linkedin'])
                                </div>

                                <div class="form-group{{ $errors->has('youtube') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-youtube">{{ __('Youtube link') }}</label>
                                    <input type="text" name="youtube" id="input-youtube" class="form-control{{ $errors->has('youtube') ? ' is-invalid' : '' }}" placeholder="{{ __('Youtube link') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'youtube'])
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

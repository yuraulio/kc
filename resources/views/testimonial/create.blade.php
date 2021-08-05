@extends('layouts.app', [
    'title' => __('Testimonial Management'),
    'parentSection' => 'laravel',
    'elementName' => 'testimonials-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
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

                            <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <div class="status-label">
                                        <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    </div>
                                    <div class="status-toogle">
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="status" id="input-status">
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>
                                </div>

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
                                    <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>
                                <?php //dd($instructors[10][0]); ?>

                                <div class="form-group{{ $errors->has('instructor_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-instructor_id">{{ __('Instructor') }}</label>
                                    <select name="instructor_id" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-instructor_id" class="form-control" placeholder="{{ __('Instructor') }}">
                                        <option value=""></option>

                                        @foreach ($instructors as $key => $instructor)

                                        @if($instructors[$key][0]->medias != null)
                                        <option ext="{{$instructors[$key][0]->medias['ext']}}" original_name="{{$instructors[$key][0]->medias['original_name']}}" name="{{$instructors[$key][0]->medias['name']}}" path="{{$instructors[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                        @else
                                        <option ext="null" original_name="null" name="null" path="null" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                        @endif

                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'instructor_id'])
                                </div>

                                <div class="form-group{{ $errors->has('testimonial') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-testimonial">{{ __('Testimonial') }}</label>
                                    <textarea name="testimonial" id="input-testimonial" class="ckeditor form-control{{ $errors->has('testimonial') ? ' is-invalid' : '' }}"></textarea>

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

                                {{--@include('admin.upload.upload', ['event' => ( isset($testimonial) && $testimonial->medias['path'] != null) ? $event : null])--}}


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

<script>

    instructors = @json($instructors);

    $(document).ready(function(){
        $("#input-instructor_id").select2({
        templateResult: formatOptions
        });
        });

        function formatOptions (state) {
            //console.log(state)
        if (!state.id) { return state.text; }
        //console.log(state.text)
        console.log(state.element)

        path = state.element.attributes['path'].value
        name = state.element.attributes['name'].value
        plus_name = '-instructors-small'
        ext = state.element.attributes['ext'].value

        var $state = $(
        '<span class="rounded-circle"><img class="avatar-sm rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'" /> ' + state.text + '</span>'
        );

        var $state1 = $(
        '<span class="avatar avatar-sm rounded-circle"><img class="rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'"/></span>'
        );
        return $state;
        }
</script>

@endpush

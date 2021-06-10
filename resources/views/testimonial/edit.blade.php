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
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Testimonial') }}</li>
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
                                <form method="post" action="{{ route('testimonials.update', $testimonial) }}" autocomplete="off"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <h6 class="heading-small text-muted mb-4">{{ __('Testimonial information') }}</h6>
                                    <div class="pl-lg-4">

                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $testimonial->name) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'name'])
                                        </div>

                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                            <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $testimonial->title) }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                            <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                                    <option <?= ($testimonial['status'] == 1) ? 'selected="selected"' : ''; ?> value="1">{{ __('Published') }}</option>
                                                    <option <?= ($testimonial['status'] == 0) ? 'selected="selected"' : ''; ?> value="0">{{ __('Unpublished') }}</option>
                                            </select>

                                            @include('alerts.feedback', ['field' => 'status'])
                                        </div>

                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                            <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                                <option value="">-</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                    <?php if(count($testimonial->category) != 0){
                                                        if($category->id == $testimonial->category[0]->id){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?>

                                                        >{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'category_id'])
                                        </div>

                                        <?php //dd($testimonial); ?>

                                        <div class="form-group{{ $errors->has('instructor_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-instructor_id">{{ __('Instructor') }}</label>
                                            <select name="instructor_id" id="input-instructor_id" class="form-control" placeholder="{{ __('Instructor') }}" required>
                                                <option value="">-</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                    <?php if(count($testimonial->instructors) != 0){
                                                        if($instructor->id == $testimonial->instructors[0]->id){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?>

                                                        >{{ $instructor->title }}</option>
                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'instructor_id'])
                                        </div>

                                        <div class="form-group{{ $errors->has('testimonial') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-testimonial">{{ __('Testimonial') }}</label>
                                            <input type="text" name="testimonial" id="input-testimonial" class="form-control{{ $errors->has('testimonial') ? ' is-invalid' : '' }}" placeholder="{{ __('Testimonial') }}" value="{{ old('testimonial', $testimonial->testimonial) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'testimonial'])
                                        </div>

                                        <?php
                                            $video = $testimonial['video_url'];
                                            $video = json_decode($video);
                                            $social = $testimonial['social_url'];
                                            $social = json_decode($social);
                                            if(isset($social->facebook)){
                                                $facebook = $social->facebook;
                                            }else{
                                                $facebook = null;
                                            }
                                            if(isset($social->linkedin)){
                                                $linkedin = $social->linkedin;
                                            }else{
                                                $linkedin = null;
                                            }

                                            if(isset($video->youtube)){
                                                $youtube = $social->youtube;
                                            }else{
                                                $youtube = null;
                                            }
                                        ?>

                                        <div class="form-group{{ $errors->has('facebook') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-facebook">{{ __('Facebook') }}</label>
                                            <input type="text" name="facebook" id="input-facebook" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" placeholder="{{ __('Facebook') }}" value="{{ old('facebook', $facebook) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'facebook'])
                                        </div>

                                        <div class="form-group{{ $errors->has('linkedin') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-linkedin">{{ __('Linkedin') }}</label>
                                            <input type="text" name="linkedin" id="input-linkedin" class="form-control{{ $errors->has('linkedin') ? ' is-invalid' : '' }}" placeholder="{{ __('Linkedin') }}" value="{{ old('linkedin', $linkedin) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'linkedin'])
                                        </div>

                                        <div class="form-group{{ $errors->has('youtube') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-youtube">{{ __('Youtube') }}</label>
                                            <input type="text" name="youtube" id="input-youtube" class="form-control{{ $errors->has('youtube') ? ' is-invalid' : '' }}" placeholder="{{ __('Youtube') }}" value="{{ old('youtube', $youtube) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'youtube'])
                                        </div>

                                        <?php //dd($testimonial->medias != null); ?>

                                        @include('admin.upload.upload', ['event' => ( isset($testimonial) && $testimonial->medias != null) ? $testimonial : null])


                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            @include('event.image_versions', ['event' => $testimonial->medias, 'versions1'=>['instructors-testimonials']])
                            </div>




                        </div>




                    </div>

                </div>
            </div>


        @include('layouts.footers.auth')
    </div>
@endsection

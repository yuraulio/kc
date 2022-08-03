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
                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
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

                                    <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                        <label class="custom-toggle custom-published">
                                            <input name="status" id="input-status" type="checkbox" @if($testimonial->status) checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>

                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $testimonial->name) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'name'])
                                        </div>

                                        <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-lastname">{{ __('Last Name') }}</label>
                                            <input type="text" name="lastname" id="input-lastname" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="{{ __('Last Name') }}" value="{{ old('lastname', $testimonial->lastname) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'lastname'])
                                        </div>

                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                            <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $testimonial->title) }}" required autofocus>

                                            @include('alerts.feedback', ['field' => 'title'])
                                        </div>



                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                            <select multiple name="category_id[]" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                                <option value="">-</option>
                                                @foreach ($categories as $category)

                                                    <option value="{{ $category->id }}"
                                                    <?php
                                                        $testimonialsIds = $testimonial->category->pluck('id')->toArray();
                                                        if(count($testimonial->category) != 0){
                                                            if(in_array($category->id,$testimonialsIds)){
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
                                        <div class="form-group{{ $errors->has('instructor_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-instructor_id">{{ __('Instructor') }}</label>
                                            <select name="instructor_id" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-instructor_id" class="form-control" placeholder="{{ __('Instructor') }}">
                                                <option value="">-</option>


                                                @foreach ($instructors as $key => $instructor)
                                                <?php //dd($key); ?>

                                                    <option
                                                    <?php if(count($testimonial->instructors) != 0){
                                                        if($key == $testimonial->instructors[0]['id']){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?>
                                                     ext="{{$instructors[$key][0]->medias['ext']}}" original_name="{{$instructors[$key][0]->medias['original_name']}}" name="{{$instructors[$key][0]->medias['name']}}" path="{{$instructors[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'instructor_id'])
                                        </div>

                                        <div class="form-group{{ $errors->has('testimonial') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-testimonial">{{ __('Testimonial') }}</label>
                                            {{--<textarea name="testimonial" id="input-testimonial" class="ckeditor form-control{{ $errors->has('testimonial') ? ' is-invalid' : '' }}">{{ old('testimonial', $testimonial->testimonial) }}</textarea>--}}

                                            <!-- anto's editor -->
                                            <input class="hidden" name="testimonial" value="{{ old('testimonial', $testimonial->testimonial) }}"/>
                                            <?php $data = $testimonial->testimonial?>
                                            @include('event.editor.editor', ['keyinput' => "input-testimonial", 'data'=> "$data", 'inputname' => "'testimonial'" ])
                                            <!-- anto's editor -->

                                            @include('alerts.feedback', ['field' => 'testimonial'])
                                        </div>

                                        <?php
                                            $video = $testimonial['video_url'];
                                            //$video = json_decode($video);
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

                                            if(isset($video)){
                                                $youtube = $video;

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
                                            <label class="form-control-label" for="input-youtube">{{ __('Video Testimonial') }}</label>
                                            <input type="text" name="youtube" id="input-youtube" class="form-control{{ $errors->has('youtube') ? ' is-invalid' : '' }}" placeholder="{{ __('Video Testimonial') }}" value="{{ old('youtube', $youtube) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'youtube'])
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            @include('admin.upload.upload', ['event' => ( isset($testimonial) && $testimonial->medias != null) ? $testimonial->medias : null, 'versions' => ['instructors-small']])
                            @if($testimonial->medias != null && $testimonial->medias['name'] != '')
                                <div id="version-btn" style="margin-bottom:20px" class="col">
                                    <a href="{{ route('media2.eventImage', $testimonial->medias) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
                                </div>
                            @endif
                        </div>




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


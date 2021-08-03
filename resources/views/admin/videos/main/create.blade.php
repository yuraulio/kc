@extends('layouts.app', [
'title' => __('Create video'),
'parentSection' => 'laravel',
'elementName' => 'video-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Create video') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('video.index') }}">{{ __('Video Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add video') }}</li>
        @endcomponent
    @endcomponent

    <?php //dd(isset($video->id)); ?>

    @if(!isset($video->id))
         <form id="form-video" method="post" action="{{ route('video.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('video.update', $video->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Video Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('video.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('Video information') }}</h6>


                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Title') }}</label>
                           <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title',$video->title) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'title'])
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Description') }}</label>
                           <input type="text" name="description" id="input-description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description',$video->description) }}" >
                           @include('alerts.feedback', ['field' => 'description'])
                        </div>

                        {{--<div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Url') }}</label>
                           <input type="text" name="url" id="input-url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" placeholder="{{ __('Url') }}" value="{{ old('url',$video->url) }}"  autofocus>
                           @include('alerts.feedback', ['field' => 'url'])
                        </div>--}}

                        <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Body') }}</label>
                           <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $video->body) }}</textarea>
                           @include('alerts.feedback', ['field' => 'body'])
                        </div>



                     </div>

                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="text-center">
                           <button id="submit-btn-video" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </form>



@stop

@push('js')
<script>

CKEDITOR.config.allowedContent = true;
   $( "#submit-btn-video" ).click(function() {
      $('#form-video').submit()
   });


</script>

@endpush



<?php $noEditablePages = ['home','cart']; ?>

@extends('layouts.app', [
'title' => __('Logo Management'),
'parentSection' => 'laravel',
'elementName' => 'logo-management'
])
@section('content')
@component('layouts.headers.auth')
@component('layouts.headers.breadcrumbs')
@slot('title')
{{ __('Examples') }}
@endslot
<li class="breadcrumb-item"><a href="{{ route('logos.index') }}">{{ __('Logos Management') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Add Logo') }}</li>
@endcomponent
@endcomponent
<div class="container-fluid mt--6">
    <div class="nav-wrapper" style="margin-top: 65px;">
        @if($logo->name)
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#page" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Logo</a>
            </li>

            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media</a>
            </li>

            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media_version" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media Version</a>
            </li>
        </ul>
        @endif
    </div>

<div class="tab-content" id="myTabContent">
   <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      @if(!$logo->name)
         <form method="post" action="{{ route('logos.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('logos.update',$logo->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Logo Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('logos.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('Logo information') }}</h6>


                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                           <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$logo->name) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('ext_url') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Url') }}</label>
                           <input type="text" name="ext_url" id="input-ext_url" class="form-control{{ $errors->has('ext_url') ? ' is-invalid' : '' }}" placeholder="{{ __('Url') }}" value="{{ old('ext_url',$logo->ext_url) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'ext_url'])
                        </div>

                        <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-type">{{ __('Type') }}</label>
                            <select name="type" id="input-type" class="form-control" placeholder="{{ __('Type') }}" required>
                                <option value="">-</option>
                                @if(!$logo->name)
                                <option value="logos" >Logo</option>
                                <option value="brands" >Brands</option>
                                @else
                                <option value="logos" <?= ($logo->type == 'logos') ? 'selected' : ''; ?>>Logos</option>
                                <option value="brands" <?= ($logo->type == 'brands') ? 'selected' : ''; ?>>Brands</option>
                                @endif


                            </select>

                            @include('alerts.feedback', ['field' => 'type'])
                        </div>


                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-summary">{{ __('Logo Summary') }}</label>
                           <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Logo summary') }}"  required autofocus>{{ old('summary',$logo->summary) }}</textarea>
                           @include('alerts.feedback', ['field' => 'summary'])
                        </div>


                     </div>

                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">

                     <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-category_id">{{ __('Status') }}</label>
                        <select name="status" id="input-category_id" class="form-control" placeholder="{{ __('Status') }}">

                        <option value="0" {{ 0 == old('status',$logo->status) ? 'selected' : '' }}> Unpublished </option>
                        <option value="1" {{ 1 == old('status',$logo->status) ? 'selected' : '' }}> Published </option>
                        </select>
                        @include('alerts.feedback', ['field' => 'template'])
                     </div>

                     <div class="pl-lg-4">
                        <div class="text-center">
                           <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>


   @if($logo->name)
      <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
         <div class="row">
            <div class="col-xl-12 order-xl-1">
               @include('admin.upload.upload', ['event' => ($media != null) ? $media : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']])
            </div>
         </div>
      </div>

      <div class="tab-pane fade" id="media_version" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
         <div class="row">
            <div class="col-xl-12 order-xl-1">
               @include('event.image_versions', ['event' => $media,'versions1'=> ['event-card', 'header-image', 'social-media-sharing']])
            </div>
         </div>
      </div>
   @endif

   @include('layouts.footers.auth')
</div>
@endsection

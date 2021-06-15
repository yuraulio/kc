<?php $noEditablePages = ['home','cart']; ?>

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
<li class="breadcrumb-item"><a href="{{ route('role.index') }}">{{ __('Role Management') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Add Role') }}</li>
@endcomponent
@endcomponent
<div class="container-fluid mt--6">
<div class="nav-wrapper" style="margin-top: 65px;">
   <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#page" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Page</a>
      </li>
      @if($page->title || in_array($page->template,$noEditablePages))
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#metas" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Metas</a>
      </li>
      @endif

      @if($page->template !== 'cart')
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media</a>
      </li>

      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media_version" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media Version</a>
      </li>

      @endif

   </ul>
</div>
<div class="tab-content" id="myTabContent">
   <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      @if(!$page->title)
         <form method="post" action="{{ route('pages.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('pages.update',$page->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Page Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('role.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('Page information') }}</h6>
                     
                    @if($page->template != 'cart')
                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                           <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title',$page->title) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'title'])
                        </div>
                        @if(!in_array($page->template,$noEditablePages))
                           @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                        @endif
                        <div class="form-group{{ $errors->has('permissions') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-permissions">{{ __('Page Editor') }}</label>
                           <textarea name="content" id="input-content"  class="ckeditor form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" placeholder="{{ __('Page editor') }}"  required autofocus>{{ old('content',$page->content) }}</textarea>
                           @include('alerts.feedback', ['field' => 'permissions'])
                        </div>
                       
                     </div>
                     @endif
                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     @if(!in_array($page->template,$noEditablePages))
                    
                     @include('admin.preview.preview',['slug' => isset($slug) ? $slug : null])

                     <div class="form-group{{ $errors->has('template') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-category_id">{{ __('Template') }}</label>
                        <select name="template" id="input-category_id" class="form-control" placeholder="{{ __('Template') }}">
                        @foreach ($templates as $key => $template)
                        <option value="{{ $template }}" {{ $template == old('template',$page->template) ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'template'])
                     </div>
                    
                     <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                        <label class="form-control-label" for="input-category_id">{{ __('Published') }}</label>
                        <select name="published" id="input-category_id" class="form-control" placeholder="{{ __('Published') }}">
                        
                        <option value="0" {{ 0 == old('published',$page->published) ? 'selected' : '' }}> Unpublished </option>
                        <option value="1" {{ 1 == old('published',$page->published) ? 'selected' : '' }}> Published </option>
                        </select>
                        @include('alerts.feedback', ['field' => 'template'])
                     </div>
                     @endif
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
   
   @if($page->title || in_array($page->template,$noEditablePages))
   <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            @include('admin.metas.metas',['metas' => $metas])
         </div>
      </div>
   </div>
   @endif

   <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
           
         </div>
      </div>
   </div>

   <div class="tab-pane fade" id="media_version" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            
         </div>
      </div>
   </div>

   @include('layouts.footers.auth')
</div>
@endsection
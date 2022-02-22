<?php $noEditablePages = ['home','cart']; ?>

@extends('layouts.app', [
'title' => __('Pages Management'),
'parentSection' => 'laravel',
'elementName' => 'pages-management'
])

@section('content')
@component('layouts.headers.auth')
@component('layouts.headers.breadcrumbs')
@slot('title')
{{ __('') }}
@endslot
<li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('Page Management') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Add Page') }}</li>
@endcomponent
@endcomponent
<div class="container-fluid mt--6">
   <div class="nav-wrapper" style="margin-top: 65px;">
      <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#page" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Page</a>
         </li>
         @if($page->name || in_array($page->template,$noEditablePages))
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab" href="#metas" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-world mr-2"></i>Seo</a>
         </li>
         @endif

         @if($page->template !== 'cart')
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Media</a>
         </li>

         @endif

      </ul>
   </div>
<div class="tab-content" id="myTabContent">
   <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      @if(!$page->name)
         <form method="post" id="page-form" action="{{ route('pages.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" id="page-form" action="{{ route('pages.update',$page->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row">
            <div class="col-xl-9 order-xl-1">
               @if($page->id == 4753 || $page->id == 4754)
                  <input hidden name="terms" id="terms-value" value="1" >
               @endif
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Page Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('pages.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('Page information') }}</h6>

                    @if($page->template != 'cart')
                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                           <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$page->name) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'name'])
                        </div>
                        

                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                           <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title',$page->title) }}"  autofocus>
                           @include('alerts.feedback', ['field' => 'title'])
                        </div>

                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-summary">{{ __('Page Subtitle') }}</label>
                           <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Page summary') }}"  required autofocus>{{ old('summary',$page->summary) }}</textarea>
                           {{--<input name="summary" id="input-summary"  class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Page Subtitle') }}"  value="{{ old('summary',$page->summary) }}" autofocus>--}}
                           @include('alerts.feedback', ['field' => 'summary'])
                        </div>

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
                           <button type="button" class="btn btn-success mt-4 submit-button">{{ __('Save') }}</button>
                        </div>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>

   @if($page->name || in_array($page->template,$noEditablePages))
   <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
         @if(!in_array($page->template,$noEditablePages))
                           @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                        @endif
            @include('admin.metas.metas',['metas' => $metas])
         </div>
      </div>
   </div>
   @endif
   @if($page->name && $page->template != 'cart')
   <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
         @include('admin.upload.upload', ['event' => ($media != null) ? $media : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']])
         </div>

      </div>
        @if($media != null && $media['name'] != '')
            <div id="version-btn" style="margin-bottom:20px" class="col">
                <a href="{{ route('media2.eventImage', $media) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
            </div>
        @endif
   </div>

   {{--<div class="tab-pane fade" id="media_version" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            @include('event.image_versions', ['event' => $media,'versions1'=> ['event-card', 'header-image', 'social-media-sharing']])
         </div>
      </div>
   </div>--}}
   @endif

   @include('layouts.footers.auth')
</div>
@endsection

@push('js')
<script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

<script>
    
   $(".submit-button").click(function(){
      
      @if($page->id == 4753 || $page->id == 4754)
         
         let user = "{{$page->id == 4753 ? 'instructors' : 'users'}}";

          Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update " + user + "' term?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
          }).then((result) => {
             console.log(result)
            if (result.value) {
               $('#terms-value').val(0)
               $("#page-form").submit();
            }else{
               $("#page-form").submit();
            }
            
          })

          
      @else
          $("#page-form").submit();
      @endif

   })

</script>

@endpush
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
{{ __('Examples') }}
@endslot
<li class="breadcrumb-item"><a href="{{ route('role.index') }}">{{ __('Page Management') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Add Page') }}</li>
@endcomponent
@endcomponent
<div class="container-fluid mt--6">
<div class="nav-wrapper" style="margin-top: 65px;">
   <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#page" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Page</a>
      </li>
      @if($page->name)
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#benefits" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Benefits</a>
      </li>
      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#metas" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-world mr-2"></i>Seo</a>
      </li>

      <li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media</a>
      </li>

      <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#images" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Corporate Brands</a>
      </li>

      {{--<li class="nav-item">
         <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media_version" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Media Version</a>
      </li>--}}

      @endif
   </ul>
</div>
<div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>
<div class="tab-content" id="myTabContent">
   <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      @if(!$page->name)
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
                     <div class="pl-lg-4">

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Name') }}</label>
                           <input type="text" name="name" id="input-title" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$page->name) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                           <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title',$page->title) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'title'])
                        </div>
                        <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-content">{{ __('Page Editor') }}</label>
                           {{--<textarea name="content" id="input-content"  class="ckeditor form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" placeholder="{{ __('Page editor') }}"  required autofocus>{{ old('content',$page->content) }}</textarea>--}}
                            <!-- anto's editor -->
                            <input class="hidden" name="content" value="{{ old('content',$page->content) }}"/>
                            <?php $data = $page->content?>
                            @include('event.editor.editor', ['keyinput' => "input-content", 'data'=> "$data", 'inputname' => "'content'" ])
                            <!-- anto's editor -->
                           @include('alerts.feedback', ['field' => 'content'])
                        </div>

                     </div>
                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
            <div class="card">
                  <div class="card-header">
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
   @if($page->name)
   <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])

            @include('admin.metas.metas',['metas' => $metas])
         </div>
      </div>
   </div>

   <div class="tab-pane fade" id="benefits" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            @include('admin.benefits.benefits',['model' => $page])
         </div>
      </div>
   </div>


   <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
         @include('admin.upload.upload', ['event' => ($media != null) ? $media : null])
         </div>
      </div>
   </div>
   @if($media != null && $media['name'] != '')
        <div id="version-btn" style="margin-bottom:20px" class="col">
            <a href="{{ route('media2.eventImage', $media) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
        </div>
    @endif

    <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">

    <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Corporate Brands') }}</h3>
                            </div>
                                <div class="col-4 text-right">
                                    <?php
                                    $arr = [];
                                    $arr['template'] = 'corporate_brands';
                                    ?>
                                    <a href="{{ route('logos.create', $arr) }}" class="btn btn-sm btn-primary">{{ __('Add') }} {{ __('Corporate Brands') }}</a>
                                </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table table-flush"  id="datatable-basic101">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Created') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $item)
                                    <tr>
                                        <td><a href="{{ route('logos.edit', $item) }}">{{ $item->name }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                        <?php //dd($user); ?>
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('logos.edit', $item) }}">{{ __('Edit') }}</a>
                                                    {{--<form action="{{ route('user.destroy', $user) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>--}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>

   {{--<div class="tab-pane fade" id="media_version" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
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
{{--<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>--}}
@endpush

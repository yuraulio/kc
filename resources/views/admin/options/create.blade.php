@extends('layouts.app', [
'title' => __('Create option'),
'parentSection' => 'laravel',
'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Create option') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">{{ __('option Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add option') }}</li>
        @endcomponent
    @endcomponent


    {{--@if(!$option->code_option)
         <form method="post" action="{{ route('option.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else--}}
         <form id="sbt-option" method="post" action="{{ route('option.update',$option->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      {{--@endif--}}
         @csrf
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Option Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('role.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('option information') }}</h6>

                   
                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <textarea rows="20" class="form-control" name="settings" id="settingsEditor">{!!$codes!!}</textarea>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="text-center">
                           <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </form>



@stop

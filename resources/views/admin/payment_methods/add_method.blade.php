@extends('layouts.app', [
'title' => __('Payment Method Management'),
'parentSection' => 'laravel',
'elementName' => 'payment-management'
])
@section('content')
@component('layouts.headers.auth')
@component('layouts.headers.breadcrumbs')
@slot('title')
{{ __('') }}
@endslot
<li class="breadcrumb-item"><a href="{{ route('payments.index') }}">{{ __('Payments Management') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Add Payment Method') }}</li>
@endcomponent
@endcomponent
<div class="container-fluid mt--6">
   <div class="nav-wrapper" style="margin-top: 65px;">
      <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#method" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Method</a>
         </li>
         @if($form_type == 'edit')
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#processor" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Processor</a>
         </li>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#test_processor" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Test Processor</a>
         </li>
         @endif
      </ul>
   </div>
   <div class="row">
      <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
               <div class="row align-items-center">
                  <div class="col-8">
                     <h3 class="mb-0">{{ __('Payments Management') }}</h3>
                  </div>
                  <div class="col-4 text-right">
                     <a href="{{ route('payments.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
						@if($form_type == 'create')
                  <form method="post" action="{{ route('payments.store') }}" autocomplete="off"
                     enctype="multipart/form-data">
						@else
						<form method="post" action="{{ route('payments.update',$method['id']) }}" autocomplete="off"
                     enctype="multipart/form-data">
						@endif
                     @csrf
							<div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade show active" id="method" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                        <h6 class="heading-small text-muted mb-4">{{ __('Payment information') }}</h6>
                        <div class="pl-lg-4">
                           <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                              <input type="text" name="method_name" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('method_name',$method['method_name']) }}" required autofocus>
                              @include('alerts.feedback', ['field' => 'title'])
                           </div>
                           <div class="form-group{{ $errors->has('processor') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="input-type">{{ __('Processor') }}</label>
                              <select name="processor_id" id="input-type" class="form-control" placeholder="{{ __('Processor') }}">
                                 <option value="">-</option>
                                 @foreach($availableProcessors as $key => $processor)
                                 <option value="{{$key}}" {{ $key == old('processor_id',$method['processor_id']) ? 'selected' : '' }}>{{$processor['name']}}</option>
                                 @endforeach
                              </select>
                              @include('alerts.feedback', ['field' => 'type'])
                           </div>
                           <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="input-published">{{ __('Status') }}</label>
                              <select name="status" id="input-published" class="form-control" placeholder="{{ __('Status') }}" >
                                 <option value="">-</option>
                                 <option value="0" {{ 0 == old('status',$method['status']) ? 'selected' : '' }} >{{ __('Inactive') }}</option>
                                 <option value="1" {{ 1 == old('status',$method['status']) ? 'selected' : '' }}>{{ __('Active') }}</option>
                                 <option value="2"{{ 2 == old('status',$method['status']) ? 'selected' : '' }}>{{ __('Test') }}</option>
                              </select>
                              @include('alerts.feedback', ['field' => 'status'])
                           </div>
                           <div class="form-group{{ $errors->has('footer') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="input-footer">{{ __('Footer') }}</label>
                              <input type="text" name="footer" id="input-footer" class="form-control{{ $errors->has('footer') ? ' is-invalid' : '' }}" placeholder="{{ __('Footer') }}" value="{{ old('footer',$method['footer']) }}" required autofocus>
                              @include('alerts.feedback', ['field' => 'footer'])
                           </div>
                           {{--<div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="input-status">{{ __('Type') }}</label>
                              <select name="type" id="input-status" class="form-control" placeholder="{{ __('Type') }}">
                                 <option value="">-</option>
                                 <option value="bank" {{ "bank" == old('type',$method['type']) ? 'selected' : '' }}>Bank</option>
                                 <option value="stripe" {{ "stripe" == old('type',$method['type']) ? 'selected' : '' }}>Stripe</option>
                              </select>
                              @include('alerts.feedback', ['field' => 'type'])
                           </div>--}}
                        </div>
                     </div>
                     @if($form_type == 'edit')
                     <div class="tab-pane fade" id="processor" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
								{!! $html !!}

                     </div>

                     <div class="tab-pane fade" id="test_processor" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
								{!! $html_test !!}

                     </div>
                     @endif
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

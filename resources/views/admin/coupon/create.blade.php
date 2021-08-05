@extends('layouts.app', [
'title' => __('Create coupon'),
'parentSection' => 'laravel',
'elementName' => 'coupons-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('coupons') }}">{{ __('Coupon Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Coupon') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
    @if(!$coupon->code_coupon)
         <form method="post" action="{{ route('coupon.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('coupon.update',$coupon->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Coupon Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('coupons') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('coupon information') }}</h6>


                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                           <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$coupon->code_coupon) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'name'])
                        </div>



                        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-price">{{ __('Price') }}</label>
                           <input type="number" name="price" id="input-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price',$coupon->price) }}"  autofocus>
                           @include('alerts.feedback', ['field' => 'price'])
                        </div>



                        <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                            <label class="custom-toggle">
                                <input name="published" type="checkbox" @if($coupon->status) checked @endif>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                            </label>
                        </div>






                        <label class="form-control-label" for="input-events">{{ __('Events') }}</label>
                        <div class="checkbox-overflow">

                            @foreach($events as $event)
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <input name='events[]' value="{{$event->id}}" type="checkbox" aria-label="Checkbox for following text input" @if(in_array($event->id, $event_coupons)) checked @endif>
                                    </div>
                                  </div>
                                  <input type="text" value="{{$event->title}}" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            @endforeach
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
</div>



@stop

@push('js')
<script>

    $(function() {
        $('#content_summary').redactor({
            clickToEdit: true,
            clickToCancel: { title: 'Cancel' }
        });
    });


</script>

@endpush

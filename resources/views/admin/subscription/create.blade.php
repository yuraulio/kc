@extends('layouts.app', [
'title' => __('Create Plan'),
'parentSection' => 'laravel',
'elementName' => 'plans-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('plans') }}">{{ __('Plan Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Plan') }}</li>
        @endcomponent
    @endcomponent

    <div class="container-fluid mt--6">
    @if(!$plan->name)
         <form method="post" action="{{ route('plan.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('plan.update',$plan->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0">{{ __('Plan Management') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="{{ route('role.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4">{{ __('Plan information') }}</h6>


                     <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                           <input type="text" name="name" id="input-name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$plan->name) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'name'])
                        </div>


                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Description') }}</label>
                           <textarea name="description" id="input-title" class="ckeditor form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" required autofocus>{{ old('description',$plan->description) }}</textarea>
                           @include('alerts.feedback', ['field' => 'description'])
                        </div>

                        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-price">{{ __('Price') }}</label>
                           <input type="number" name="price" id="input-price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('price',$plan->cost) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'price'])
                        </div>

                        <div class="form-group{{ $errors->has('interval') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-price">{{ __('Billing Period') }}</label>
                            <select class="form-control" id="interval" name="interval">
                                <option value="day">Daily</option>
                                <option value="week">Weekly</option>
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div>

                        <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                            <label class="custom-toggle">
                                <input name="published" type="checkbox" @if($plan->published) checked @endif>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                            </label>
                        </div>


                        <div class="input-group form-group{{ $errors->has('interval_count') ? ' has-danger' : '' }}">
                           {{--<label class="form-control-label" for="input-price">{{ __('Price') }}</label>--}}
                           <input class="form-control{{ $errors->has('interval_count') ? ' is-invalid' : '' }}" id="interval_count" name='interval_count' value="{{$plan->interval_count}}" type="number" min="1"><div class="input-group-append"><span class="input-group-text" id="interval_count_label"> Day </span></div>
                           @include('alerts.feedback', ['field' => 'interval_count'])
                        </div>

                        <div class="form-group{{ $errors->has('trial') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-trial">{{ __('Trial') }}</label>
                           <input type="number" name="trial" id="input-trial" class="form-control{{ $errors->has('trial') ? ' is-invalid' : '' }}" placeholder="{{ __('Trial') }}" value="{{ old('trial_days',isset($plan->trial_days) ? $plan->trial_days : 0) }}"  required autofocus>
                           @include('alerts.feedback', ['field' => 'trial'])
                        </div>

                        <label class="form-control-label" for="input-events">{{ __('Events') }}</label>
                        <div class="checkbox-overflow">

                            @foreach($events as $event)
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <input name='events[]' value="{{$event->id}}" type="checkbox" aria-label="Checkbox for following text input" @if(in_array($event->id, $event_plans)) checked @endif>
                                    </div>
                                  </div>
                                  <input type="text" value="{{$event->title}}" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <label class="form-control-label" for="input-title">{{ __('Categories') }}</label>
                        <div class="checkbox-overflow">

                            @foreach($categories as $category)
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <input name='categories[]' value="{{$category->id}}" type="checkbox" aria-label="Checkbox for following text input" @if(in_array($category->id, $category_plans)) checked @endif>
                                    </div>
                                  </div>
                                  <input type="text" value="{{$category->name}}" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <label class="form-control-label" for="input-title">{{ __('No Events') }}</label>
                        <div class="checkbox-overflow">

                            @foreach($noevents as $event)
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input name='noevents[]' value="{{$event->id}}" type="checkbox" @if(in_array($event->id, $event_noplans)) checked @endif>
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



    $(document).ready(function(){


        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }

        $("#interval_count_label").text(interval);

        if($("#interval_count").val() < 1){
            $("#interval_count").val(1);
        }

        if("{{ $plan->interval }}"){
            $("#interval").val("{{ $plan->interval }}");
        }


    })

    $("#interval").change(function(){
        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }

        $("#interval_count_label").text(interval);
    })

    $("#interval_count").keyup(function(){
        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval == 'year'){
            $("#interval_count").val(1);
        }

        if(interval == 'month'){

            if($("#interval_count").val() < 1){
                $("#interval_count").val(1);
            }else if($("#interval_count").val() > 12){
                $("#interval_count").val(12);
            }


        }

        if(interval == 'week'){

            if($("#interval_count").val() < 1){
                $("#interval_count").val(1);
            }else if($("#interval_count").val() > 52){
                $("#interval_count").val(52);
            }


        }


       interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }


        $("#interval_count_label").text(interval);

    })

</script>

@endpush

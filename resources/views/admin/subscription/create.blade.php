@extends('layouts.app', [
'title' => __('Create Plan'),
'parentSection' => 'laravel',
'elementName' => 'role-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('Create Plan') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('role.index') }}">{{ __('Plan Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Plan') }}</li>
        @endcomponent
    @endcomponent


    @if(!$plan->name)
         <form method="post" action="{{ route('pages.store') }}" autocomplete="off" enctype="multipart/form-data">
      @else
         <form method="post" action="{{ route('pages.update',$plan->id) }}" autocomplete="off" enctype="multipart/form-data">
         @method('put')
      @endif
         @csrf
         <div class="row">
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
                           <input type="text" name="description" id="input-title" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description',$plan->description) }}"  required autofocus>
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

                        <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                           {{--<label class="form-control-label" for="input-price">{{ __('Price') }}</label>--}}
                           <input class="form-control{{ $errors->has('interval_count') ? ' is-invalid' : '' }} col-6" id="interval_count" name='interval_count' value="{{$plan->interval_count}}" type="number" min="1"><span id="interval_count_label"> Day </span>
                           @include('alerts.feedback', ['field' => 'price'])
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
                                      <input name='categories[]' type="checkbox" aria-label="Checkbox for following text input" @if(in_array($category->id, $category_plans)) checked @endif>
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
                     
                  </div>
               </div>
            </div>
         </div>
      </form>



@stop

@push('js')
<script>

    $(function() {
        $('#content_summary').redactor({
            clickToEdit: true,
            clickToCancel: { title: 'Cancel' }
        });
    });

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

        console.log(interval);

        $("#interval_count_label").text(interval);

    })

</script>

@endpush
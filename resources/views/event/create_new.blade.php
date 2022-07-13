@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __('') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Add Event') }}</li>
        @endcomponent
    @endcomponent

    @include('admin.upload.upload_new', ['from' => 'event_info'])

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Events Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="event_create_form" method="post" action="{{ route('events.store_new') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="d-none form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority') }}">

                                    @include('alerts.feedback', ['field' => 'priority'])
                                </div>
                                <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">

                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="published" id="input-published">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'published'])

                                </div>



                            <div class="row">
                                <div class="form-group col-12">
                                    <h3 class="mb-0 title" for="input-status">{{ __('Course Status') }}</h3>
                                </div>
                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }} col-sm-12 col-md-6 col-lg-3">

                                    <select name="status" id="input-status" class="form-control " placeholder="{{ __('Please select the status of this course') }}" >
                                        <option selected disabled value="">Please select the status of this course</option>
                                        <option value="4">{{ __('My Account Only') }}</option>
                                        <option value="2">{{ __('Soldout') }}</option>
                                        <option value="3">{{ __('Completed') }}</option>
                                        <option value="0">{{ __('Open') }}</option>
                                        <option value="1">{{ __('Close') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>
                            </div>

                            <hr>
                            <!-- HOURS SECTION -->
                            <div class="row">
                                <div class="form-group col-12">
                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course hours') }}</h3>

                                        <span data-infowrapper="hours" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <span class="fa fa-hourglass"></span>
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('hours_icon_path')}}" id="hours_path" name="course[{{'hours'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('hours_icon_alt_text') }}" id="hours_alt_text" name="course[{{'hours'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }} col-sm-12 col-md-2 col-lg-2">
                                    <input type="text" id="input-hours" name="course[{{'hours'}}][{{'hour'}}]" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Course Hours') }}" value="{{ old('hours') }}" autofocus>
                                    {{--@include('alerts.feedback', ['field' => 'hours'])--}}
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-3">
                                    <input style="background:aliceblue;" type="text" name="course[{{'hours'}}][{{'text'}}]" id="input-hours-text" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('hours_text') }}" autofocus>
                                </div>


                            </div>
                            <div class="row">

                                <label class="form-control-label col-12" for="input-hours">{{ __('Visible on:') }}</label>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'hours'}}][{{'visible'}}][{{'landing'}}]" id="hours_landing" type="checkbox">
                                        <label class="custom-control-label" for="hours_landing">Course landing page (summary)</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'hours'}}][{{'visible'}}][{{'home'}}]" id="hours_home" type="checkbox">
                                        <label class="custom-control-label" for="hours_home">Course box in home page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'hours'}}][{{'visible'}}][{{'list'}}]" id="hours_list" type="checkbox">
                                        <label class="custom-control-label" for="hours_list">Course box in list page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'hours'}}][{{'visible'}}][{{'invoice'}}]" id="hourls_invoice" type="checkbox">
                                        <label class="custom-control-label" for="hourls_invoice">Invoice description</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'hours'}}][{{'visible'}}][{{'emails'}}]" id="hours_emails" type="checkbox">
                                        <label class="custom-control-label" for="hours_emails">Automated emails</label>
                                    </div>

                                </div>


                            </div>

                            <hr>

                            <!-- LANGUAGE -->

                            <div class="row">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                    <h3 class="mb-0 title">{{ __('Course language') }}</h3>

                                        <span data-infowrapper="language" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <i class="ni ni-world-2"></i>
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('language_icon_path') }}" id="language_path" name="course[{{'language'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('language_icon_alt_text') }}" id="language_alt_text" name="course[{{'language'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>



                                <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                    <input type="text" id="input-language" name="course[{{'language'}}][{{'text'}}]" class="form-control" value="{{ old('language')}} " placeholder="{{ __('Language') }}" autofocus>
                                </div>

                            </div>

                            <div class="row">
                                <label class="form-control-label col-12" for="input-hours">{{ __('Visible on:') }}</label>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'language'}}][{{'visible'}}][{{'landing'}}]" id="language_landing" type="checkbox">
                                        <label class="custom-control-label" for="language_landing">Course landing page (summary)</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'language'}}][{{'visible'}}][{{'home'}}]" id="language_home" type="checkbox">
                                        <label class="custom-control-label" for="language_home">Course box in home page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'language'}}][{{'visible'}}][{{'list'}}]" id="language_list" type="checkbox">
                                        <label class="custom-control-label" for="language_list">Course box in list page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'language'}}][{{'visible'}}][{{'invoice'}}]" id="language_invoice" type="checkbox">
                                        <label class="custom-control-label" for="language_invoice">Invoice description</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'language'}}][{{'visible'}}][{{'emails'}}]" id="language_emails" type="checkbox">
                                        <label class="custom-control-label" for="language_emails">Automated emails</label>
                                    </div>

                                </div>


                            </div>

                            <hr>

                            <!-- DELIVERY -->
                            <div class="row">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course Delivery') }}</h3>


                                    </div>
                                </div>


                                <div class="col-sm-12 col-md-6 col-lg-3 form-group{{ $errors->has('delivery') ? ' has-danger' : '' }}">


                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" required>
                                        <option disabled selected value="">Please select where this course takes place</option>
                                        @foreach ($delivery as $delivery)
                                            <option value="{{ $delivery->id }}" >{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'delivery'])
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-12 delivery_child_wrapper d-none">
                                    <div class="row">
                                        <div style="display:flex;" class="col-9 col-sm-12 col-md-6 col-lg-3 form-group{{ $errors->has('city_id') ? ' has-danger' : '' }} ">
                                            <select name="city_id" id="input-city_id" class="form-control" placeholder="{{ __('Please select the city of this course') }}" >
                                                <option selected disabled value="">Please select the city of this course</option>
                                                @foreach ($cities as $city)

                                                    <option value="{{$city->id}}"> {{ $city->name }} </option>

                                                @endforeach
                                            </select>

                                        </div>
                                        <span class="input-icon-wrapper-city col-2" data-infowrapper="inclass_city">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <i class="fa fa-plane-departure"></i>
                                                </span>

                                            </span>


                                        <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'alt_text'}}]">




                                    </div>
                                    <div class="row ">

                                        <div class="form-group col-sm-12 col-md-5 col-lg-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ old('inclass_dates') }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" placeholder="Dates(from/to)">
                                                <span data-infowrapper="inclass_dates" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                        {{--<span class="fa fa-calendar"></span>--}}
                                                        <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="">
                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_dates_icon_path') }}" id="inclass_dates_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_dates_icon_alt_text') }}" id="inclass_dates_alt_text" id="inclass_dates_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-7 col-lg-10">
                                            <div class="row">
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing" type="checkbox">
                                                        <label class="custom-control-label" for="input-delivery-landing">Course landing page (summary)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'home'}}]" id="input-delivery-home" type="checkbox">
                                                        <label class="custom-control-label" for="input-delivery-home">Course box in home page</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'list'}}]" id="input-delivery-list" type="checkbox">
                                                        <label class="custom-control-label" for="input-delivery-list">Course box in list page</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'invoice'}}]" id="input-delivery-invoice" type="checkbox">
                                                        <label class="custom-control-label" for="input-delivery-invoice">Invoice description</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'emails'}}]" id="input-delivery-emails" type="checkbox">
                                                        <label class="custom-control-label" for="input-delivery-emails">Automated emails</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">

                                        <div class="form-group col-sm-12 col-md-5 col-lg-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ old('inclass_day')}}" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'text'}}]" placeholder="Day" >
                                                <span data-infowrapper="inclass_day" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_day_icon_path') }}" id="inclass_day_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_day_icon_alt_text') }}" id="inclass_day_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-7 col-lg-10">
                                            <div class="row">
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'landing'}}]" id="input-day-landing" type="checkbox">
                                                        <label class="custom-control-label" for="input-day-landing">Course landing page (summary)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'home'}}]" id="input-day-home" type="checkbox">
                                                        <label class="custom-control-label" for="input-day-home">Course box in home page</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'list'}}]" id="input-day-list" type="checkbox">
                                                        <label class="custom-control-label" for="input-day-list">Course box in list page</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'invoice'}}]" id="input-day-invoice" type="checkbox">
                                                        <label class="custom-control-label" for="input-day-invoice">Invoice description</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'emails'}}]" id="input-day-emails" type="checkbox">
                                                        <label class="custom-control-label" for="input-day-emails">Automated emails</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>







                                    <div class="row">

                                        <div class="form-group col-sm-12 col-md-5 col-lg-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ old('times')}}" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" placeholder="Times(from/to)">
                                                <span data-infowrapper="inclass_times" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/time.svg" alt="">

                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_times_icon_path') }}" id="inclass_times_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_times_icon_alt_text') }}" id="inclass_times_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-7 col-lg-10">
                                            <div class="row">
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'landing'}}]" id="input-times-landing" type="checkbox">
                                                        <label class="custom-control-label" for="input-times-landing">Course landing page (summary)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'home'}}]" id="input-times-home" type="checkbox">
                                                        <label class="custom-control-label" for="input-times-home">Course box in home page</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'list'}}]" id="input-times-list" type="checkbox">
                                                        <label class="custom-control-label" for="input-times-list">Course box in list page</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'invoice'}}]" id="input-times-invoice" type="checkbox">
                                                        <label class="custom-control-label" for="input-times-invoice">Invoice description</label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-sm-2 col-md-6 col-lg-2">
                                                    <div class="custom-control custom-checkbox mb-3 visible-item">
                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'emails'}}]" id="input-times-emails" type="checkbox">
                                                        <label class="custom-control-label" for="input-times-emails">Automated emails</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-4">
                                            <label class="form-control-label" for="input-hours">{{ __('Absences Limit(%)') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="course[{{'delivery'}}][{{'inclass'}}][{{'absences'}}]" id="input-absences_limit" class="form-control{{ $errors->has('Absences Limit(%)') ? ' is-invalid' : '' }}" placeholder="{{ __('absences_limit') }}" value="{{ old('$course_inclass_absences') }}"autofocus>
                                                <span class="input-group-addon input-group-append">
                                                    <span class="btn btn-outline-primary input-icon"> <span class="fa fa-calendar d-none"></span></span>
                                                </span>
                                            </div>
                                            {{--@include('alerts.feedback', ['field' => 'absences_imit'])--}}
                                        </div>
                                    </div>




                                    <div class="row access-student-wrapper">

                                        <div class="form-group col-12">

                                            <div class="input-group">
                                                <h3 class="mb-0 title">{{ __('Course access') }}</h3>

                                                <span data-infowrapper="free_courses" class="input-group-addon input-group-append input-icon-wrapper">
                                                    <span class="btn btn-outline-primary input-icon">
                                                        <span class="fa fa-calendar"></span>

                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('$course_free_access_icon_path') }}" id="free_courses_path" name="course[{{'free_courses'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('$course_free_access_icon_alt_text') }}" id="free_courses_alt_text" name="course[{{'free_courses'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>

                                        <label class="form-control-label col-12" for="input-hours">{{ __('Add students to another course') }}</label>

                                        <div class="form-group col-12">
                                            <span class="toggle-btn-inline-text">Would you like to let students access an e-learning course for free?</span>
                                            <label id="access-student-toggle" class="custom-toggle">
                                                <input id="access-student" name="course[{{'free_courses'}}][{{'enabled'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                            </label>
                                        </div>


                                        @if(count($elearning_events) != 0)
                                        <div class="free-course-wrapper d-none">

                                            <div class="form-group col-12">
                                                <label class="form-control-label" for="exampleFormControlSelect3">Please select the courses you want to allow free access</label>
                                                <select multiple="" name="course[{{'free_courses'}}][{{'list'}}][]" class="form-control" id="free_course_list">
                                                @foreach($elearning_events as $elearning_event)
                                                    <option value="{{ $elearning_event['id'] }}">{{ $elearning_event['title'] }}</option>
                                                @endforeach
                                                </select>
                                                </div>

                                        </div>
                                        @endif

                                    </div>


                                </div>

                                <div id="exp_input" class="col-sm-12 col-md-6 col-lg-3 form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration') }}"autofocus>
                                        <span data-infowrapper="elearning" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                            <span class="btn btn-outline-primary input-icon">
                                                <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="">
                                            </span>
                                        </span>

                                        <input type="hidden" value="{{ old('elearning_icon_path') }}" id="elearning_path" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('elearning_icon_alt_text') }}" id="elearning_alt_text" id="elearning_alt_text" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>

                                    {{--@include('alerts.feedback', ['field' => 'expiration'])--}}
                                </div>
                            </div>

                            <div class="row elearning_visible_wrapper d-none">

                                <label class="form-control-label col-12" for="input-delivery">{{ __('Visible on:') }}</label>
                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'landing'}}]" id="input-elearning-landing" type="checkbox">
                                        <label class="custom-control-label" for="input-elearning-landing">Course landing page (summary)</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'home'}}]" id="input-elearning-home" type="checkbox">
                                        <label class="custom-control-label" for="input-elearning-home">Course box in home page</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'list'}}]" id="input-elearning-list" type="checkbox">
                                        <label class="custom-control-label" for="input-elearning-list">Course box in list page</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'invoice'}}]" id="input-elearning-invoice" type="checkbox">
                                        <label class="custom-control-label" for="input-elearning-invoice">Invoice description</label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'emails'}}]" id="input-elearning-emails" type="checkbox">
                                        <label class="custom-control-label" for="input-elearning-emails">Automated emails</label>
                                    </div>
                                </div>



                            </div>



                            <hr>


                            <div class="row course-partner-wrapper">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title" for="input-hours">{{ __('Course partners') }}</h3>

                                        <span data-infowrapper="partner" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('course_partner_icon_path') }}" id="partner_path" name="course[{{'partner'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_partner_icon_alt_text') }}" id="partner_alt_text" name="course[{{'partner'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have supporters/partners?</span>
                                    <label class="custom-toggle">
                                        <input id="partner-toggle" name="partner_enabled" type="checkbox" >
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-4 form-group{{ $errors->has('partner_id') ? ' has-danger' : '' }} course-partner-list d-none ">

                                    <select multiple name="partner_id[]" id="input-partner_id" class="form-control" placeholder="{{ __('Partner') }}">
                                        @foreach ($partners as $partner)
                                            <option value="{{ $partner->id }}" >{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>
                            </div>


                            <hr>

                            <div class="row course-dropbox-wrapper">


                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title" for="input-hours">{{ __('Course files') }}</h3>

                                        <span data-infowrapper="files" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </span>
                                        <input type="hidden" value="" id="files_path" name="course[{{'files'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="" id="files_alt_text" name="course[{{'files'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>


                                <div class="col-sm-12 col-md-6 col-lg-3 form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Access to files until') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control datepicker" id="input-release_date_file" name="release_date_files" placeholder="Select date" type="text">
                                    </div>
                                    @include('alerts.feedback', ['field' => 'release_date_files'])
                                </div>

                            </div>

                            <hr>

                            <div class="row course-manager-wrapper">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title" >{{ __('Course manager') }}</h3>

                                        <span data-infowrapper="manager" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">

                                                <span class="fa fa-calendar"></span>

                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('course_manager_icon_path') }}" id="manager_path" name="course[{{'manager'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_manager_icon_alt_text') }}" id="manager_alt_text" name="course[{{'manager'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have a visible manager?</span>
                                    <label class="custom-toggle">
                                        <input id="manager-toggle" name="manager-enabled" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                @if(count($instructors) != 0)
                                <div class="col-sm-12 col-md-6 col-lg-3 course-manager-list form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }} d-none">
                                    <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus1" class="form-control" placeholder="{{ __('Syllabus Manager') }}">
                                        <option value=""></option>


                                        @foreach ($instructors as $key => $instructor)

                                            <option

                                            @if($instructors[$key][0]->medias != null)
                                                ext="{{$instructors[$key][0]->medias['ext']}}" original_name="{{$instructors[$key][0]->medias['original_name']}}" name="{{$instructors[$key][0]->medias['name']}}" path="{{$instructors[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @else
                                            ext="null" original_name="null" name="null" path="null" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'syllabus'])
                                </div>
                                @endif
                            </div>

                            <hr>

                            <div class="row course-awards-wrapper">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course awards & badges') }}</h3>

                                        <span data-infowrapper="awards" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('course_awards_icon_path') }}" id="awards_path" name="course[{{'awards'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_awards_icon_alt_text') }}" id="awards_alt_text" name="course[{{'awards'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have some award?</span>
                                    <label class="custom-toggle">
                                        <input id="award-toggle" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-3 form-group award-text d-none">
                                    <input style="background:aliceblue;" id="input-award-text" type="text" name="course[{{'awards'}}][{{'text'}}]" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('awards') }}" autofocus>
                                </div>
                            </div>


                            <hr>

                            <div class="row course-certification-wrapper">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course certification') }}</h3>

                                        <span data-infowrapper="certificate" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt="">
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('course_certification_icon_path') }}" id="certificate_path" name="course[{{'certificate'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_certification_icon_alt_text') }}" id="certificate_alt_text" name="course[{{'certificate'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course offer a certification? </span>
                                    <label class="custom-toggle">
                                        <input id="certification-toggle" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                <div class="form-group col-12 course-certification-visible-wrapper d-none">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('fb_') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-hours">{{ __('Certificate Title') }}</label>

                                            <textarea type="text" name="course[{{'certificate'}}][{{'success_text'}}]" id="input-certificate_title" class="ckeditor form-control" autofocus>{{ old('certificate_title') }}</textarea>

                                            @include('alerts.feedback', ['field' => 'certificate_title'])
                                        </div>

                                        <div class="col-sm-12 col-md-6 form-group">
                                            <label class="form-control-label" for="input-hours">{{ __('Title of certification (in case of exams failure)') }}</label>

                                            <textarea type="text" name="course[{{'certificate'}}][{{'failure_text'}}]" id="input-certificate_text_failure" class="form-control ckeditor"  autofocus>{{old('certificate_failure')}}</textarea>

                                        </div>

                                        <div class="col-sm-12 col-md-6 form-group">
                                            <label class="form-control-label" for="input-hours">{{ __('Certificate type') }}</label>
                                            <input type="text" name="course[{{'certificate'}}][{{'type'}}]" id="input-certificate_type" class="form-control" placeholder="{{ __('alphanumeric text ') }}" value="{{old('certificate_type')}}" autofocus/>

                                        </div>
                                    </div>
                                        <div class="row">
                                            <label class="form-control-label col-12" for="input-hours">{{ __('Visible on:') }}</label>

                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="course[{{'certificate'}}][{{'visible'}}][{{'landing'}}]" id="input-certificate-landing" type="checkbox">
                                                    <label class="custom-control-label" for="input-certificate-landing">Course landing page (summary)</label>
                                                </div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="course[{{'certificate'}}][{{'visible'}}][{{'home'}}]" id="input-certificate-home" type="checkbox">
                                                    <label class="custom-control-label" for="input-certificate-home">Course box in home page</label>
                                                </div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="course[{{'certificate'}}][{{'visible'}}][{{'list'}}]" id="input-certificate-list" type="checkbox">
                                                    <label class="custom-control-label" for="input-certificate-list">Course box in list page</label>
                                                </div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="course[{{'certificate'}}][{{'visible'}}][{{'invoice'}}]" id="input-certificate-invoice" type="checkbox">
                                                    <label class="custom-control-label" for="input-certificate-invoice">Invoice description</label>
                                                </div>

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="course[{{'certificate'}}][{{'visible'}}][{{'emails'}}]" id="input-certificate-emails" type="checkbox">
                                                    <label class="custom-control-label" for="input-certificate-emails">Automated emails</label>
                                                </div>

                                            </div>


                                        </div>
                                </div>




                            </div>



                            <hr>

                            <div class="row course-student-wrapper">
                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course students') }}</h3>
                                        <span data-infowrapper="students" class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon">
                                                <span class="fa fa-users"></span>
                                                {{--<img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Group_User.1.svg" alt="">--}}
                                            </span>
                                        </span>
                                        <input type="hidden" value="{{ old('course_students_icon_path') }}" id="students_path" name="course[{{'students'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_students_icon_alt_text') }}" id="students_alt_text" name="course[{{'students'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>

                                <div calss="col-sm-12 col-md-6 col-lg-3">
                                    <div class="form-group col-auto">
                                        <label class="form-control-label" for="input-hours">{{ __('Student should start count from this number:') }}</label>
                                        <input type="text" name="course[{{'students'}}][{{'count_start'}}]" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('count_start') }}" autofocus>
                                    </div>

                                    <div class="form-group col-auto">
                                        <label class="form-control-label" for="input-hours">{{ __('Text after the number of students:') }}</label>
                                        <input style="background:aliceblue;" name="course[{{'students'}}][{{'text'}}]" type="text" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('count_text') }}" autofocus>
                                    </div>
                                </div>



                            </div>
                            <div class="row">

                                <label class="form-control-label col-12" for="input-hours">{{ __('Visible on:') }}</label>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'students'}}][{{'visible'}}][{{'landing'}}]" id="input-students-landing" type="checkbox">
                                        <label class="custom-control-label" for="input-students-landing">Course landing page (summary)</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'students'}}][{{'visible'}}][{{'home'}}]" id="input-students-home" type="checkbox">
                                        <label class="custom-control-label" for="input-students-home">Course box in home page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'students'}}][{{'visible'}}][{{'list'}}]" id="input-students-list" type="checkbox">
                                        <label class="custom-control-label" for="input-students-list">Course box in list page</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'students'}}][{{'visible'}}][{{'invoice'}}]" id="input-students-invoice" type="checkbox">
                                        <label class="custom-control-label" for="input-students-invoice">Invoice description</label>
                                    </div>

                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-2">

                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="course[{{'students'}}][{{'visible'}}][{{'emails'}}]" id="input-students-emails" type="checkbox">
                                        <label class="custom-control-label" for="input-students-emails">Automated emails</label>
                                    </div>

                                </div>


                            </div>

                            <hr>












                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>

                                {{--@include('admin.city.event.index')--}}
                                {{--@include('admin.partner.event.index')--}}

                                {{--<div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select multiple name="type_id[]" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}" required>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}" >{{ $type->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>--}}

                                {{--<div class="form-group{{ $errors->has('delivery') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Delivery') }}</label>
                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" >
                                            @foreach ($delivery as $delivery)

                                                <option value="{{ $delivery->id }}" >{{ $delivery->name }}</option>
                                            @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'delivery'])
                                </div>--}}

                                {{--<div id="exp_input" class="form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
                                </div>--}}



                                {{--<div class="form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-delivery">{{ __('Access to files until') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control datepicker" id="input-release_date_files" name="release_date_files" placeholder="Select date" type="text" value="">
                                    </div>
                                    @include('alerts.feedback', ['field' => 'release_date_files'])
                                </div>--}}


                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-title">{{ __('Η1 public title') }}</label>
                                    <input type="text" name="title" id="input-title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Η1 public title') }}" value="{{ old('title') }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'title'])
                                </div>
                                @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                                <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-htmlTitle">{{ __('Admin title') }}</label>
                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('Admin title') }}" value="{{ old('Short title') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'htmlTitle'])
                                </div>

                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-subtitle">{{ __('H2 subtitle') }}</label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('H2 subtitle') }}" value="{{ old('Subtitle') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                </div>

                                {{--<div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>--}}

                                {{--<div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>--}}

                                {{--<div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('view_tpl') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-view_tpl">{{ __('View tpl') }}</label>
                                    <select name="view_tpl"  class="form-control" placeholder="{{ __('View tpl') }}">
                                        @foreach (get_templates('events') as $key => $template)
                                            <option value="{{ $template }}" {{ $template == old('template') ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'view_tpl'])
                                </div>

                                <?php //dd($instructors[10][0]); ?>

                                {{--<div class="form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-syllabus">{{ __('Syllabus Manager') }}</label>
                                    <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus" class="form-control" placeholder="{{ __('Syllabus Manager') }}">
                                        <option value=""></option>

                                        @foreach ($instructors as $key => $instructor)
                                            @if($instructors[$key][0]->medias != null)
                                            <option ext="{{$instructors[$key][0]->medias['ext']}}" original_name="{{$instructors[$key][0]->medias['original_name']}}" name="{{$instructors[$key][0]->medias['name']}}" path="{{$instructors[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @else
                                            <option path="null" name="null" ext="null" value="{{$key}}">{{ $instructors[$key][0]['title'] }} {{ $instructors[$key][0]['subtitle'] }}</option>
                                            @endif

                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'syllabus'])
                                </div>--}}

                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$user->id}}">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$user->id}}">

                                    @include('alerts.feedback', ['field' => 'ext_url'])

                                    {{--@include('admin.upload.upload', ['event' => ( isset($event) && $event->medias != null) ? $event->medias : null])--}}


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
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>
@endsection


@push('js')

<script>

    instructors = @json($instructors);

    $(document).ready(function(){
        // $("#input-syllabus").select2({
        //     templateResult: formatOptions,
        //     templateSelection: formatOptions
        //     });
            $("#input-syllabus1").select2({
                templateResult: formatOptions,
                templateSelection: formatOptions,
                placeholder: "Please select the instructor/manager of this course",
            });
        });



        function formatOptions (state) {
        if (!state.id) {
            return state.text;
        }


        path = state.element.attributes['path'].value
        name = state.element.attributes['name'].value
        plus_name = '-instructors-small'
        ext = state.element.attributes['ext'].value

        var $state = $(
        '<span class="rounded-circle"><img class="avatar-sm rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'" /> ' + state.text + '</span>'
        );

        var $state1 = $(
        '<span class="avatar avatar-sm rounded-circle"><img class="rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'"/></span>'
        );

        return $state;
        }
</script>

<script>
    $( "#input-delivery" ).change(function() {
        if($(this).val() == 143){
            $('#exp_input').css('display', 'block')
        }else{
            $('#exp_input').css('display', 'none')
        }
    });
    $( "#input-delivery" ).change(function() {
        if($(this).val() == 139){
            $('.delivery_child_wrapper').removeClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
        }else{
            $('.delivery_child_wrapper').addClass('d-none')
            $('.elearning_visible_wrapper').removeClass('d-none')
        }
    });

    $("#partner-toggle").change(function() {
        let status = $(this).prop('checked')

        if(status){
            $('.course-partner-list').removeClass('d-none');

            $('#input-partner_id').val([])

        }else{
            $('.course-partner-list').addClass('d-none');

            $('#input-partner_id').val([])
        }
    });

    $(document).on('click', '#access-student', function() {
        let status = $(this).prop('checked')

        if(status){
            $('.free-course-wrapper').removeClass('d-none')
        }else{
            $('.free-course-wrapper').addClass('d-none')
            $('#free_course_list').val("")
        }
    })

    $('#manager-toggle').change(function() {
        let status = $(this).prop('checked')

        if(status){
            $('.course-manager-list').removeClass('d-none');
            $('#input-syllabus1').val('').trigger('change');


        }else{
            $('.course-manager-list').addClass('d-none');
            $('#input-syllabus1').val('').trigger('change');

        }
    });

    $('#certification-toggle').change(function() {
        let status = $(this).prop('checked');


        if(status){
            $('.course-certification-visible-wrapper').removeClass('d-none');



            $('#input-certificate_title').val('')
            CKEDITOR.instances['input-certificate_title'].setData('')

            CKEDITOR.instances['input-certificate_text_failure'].setData('')
            $('#input-certificate_text_failure').val('')


            $('#input-certificate_type').val('')
        }else{
            $('.course-certification-visible-wrapper').addClass('d-none');

            //$('#input-certificate_title').val("")

            CKEDITOR.instances['input-certificate_title'].setData('')
            CKEDITOR.instances['input-certificate_text_failure'].setData('')
            $('#input-certificate_title').val("")
            $('#input-certificate_text_failure').val("")
            $('#input-certificate_title').text("")
            $('#input-certificate_text_failure').text("")

            $('#input-certificate_type').val('')

        }
    });

    $('#award-toggle').change(function() {
        let status = $(this).prop('checked');

        if(status){

            $('.award-text').removeClass('d-none');
            $('#input-award-text').val('');

        }else{
            $('.award-text').addClass('d-none');
            $('#input-award-text').val('');
        }
    })
</script>

<script>

    $( "#select-image" ).click(function() {
        path = ''
        $.each( $('.fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = path +'/'+name+'.'+ext

        $('#image_upload').val(path)



        $('#img-upload').attr('src', path);

        $(".close").click();
    });



</script>
<script>
    var current_icon_input = null
    var current_icon_input_alt_text = null

    $(document).on('click', '.input-icon-wrapper, .input-icon-wrapper-inclass, .input-icon-wrapper-city', function() {

        let btn = $(this).data('infowrapper')

        if(btn === undefined){
            return false;
        }

        document.getElementById('image-input-button').click();

        current_icon_input = btn+'_path';
        current_icon_input_alt_text = btn+'_alt_text';

    })

</script>


@endpush

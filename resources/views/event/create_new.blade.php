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
                                    <input type="text" id="input-hours" name="hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Course Hours') }}" value="{{ old('hours') }}hr" autofocus>
                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>

                                <div class="form-group col-sm-12 col-md-4 col-lg-3">
                                    <input style="background:aliceblue;" type="text" name="course[{{'hours'}}][{{'text'}}]" id="input-hours-text" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('hours') }}" autofocus>
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
                                        <div class="col-10 form-group{{ $errors->has('city_id') ? ' has-danger' : '' }} ">
                                            <select name="city_id" id="input-city_id" class="form-control" placeholder="{{ __('Please select the city of this course') }}" >
                                                <option selected disabled value="">Please select the city of this course</option>
                                                @foreach ($cities as $city)

                                                    <option value="{{$city->id}}"> {{ $city->name }} </option>

                                                @endforeach
                                            </select>
                                        </div>

                                        <span class="input-icon-wrapper-city" data-infowrapper="inclass_city">
                                            <span class="btn btn-outline-primary input-icon">
                                                <i class="fa fa-plane-departure"></i>
                                            </span>

                                        </span>
                                        <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'alt_text'}}]">




                                    </div>
                                    <div class="row ">

                                        <div class="form-group col-sm-12 col-md-4 col-lg-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ (isset($dates) && isset($dates['text']) ) ? $dates['text'] : '' }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" placeholder="Dates(from/to)">
                                                <span data-infowrapper="inclass_dates" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                        <span class="fa fa-calendar"></span>
                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_dates_icon_path') }}" id="inclass_dates_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_dates_icon_alt_text') }}" id="inclass_dates_alt_text" id="inclass_dates_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-6 col-lg-2">
                                            <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing" type="checkbox">
                                                <label class="custom-control-label" for="input-delivery-landing">Course landing page (summary)</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'home'}}]" id="input-delivery-home" type="checkbox">
                                                <label class="custom-control-label" for="input-delivery-home">Course box in home page</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-6 col-lg-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'list'}}]" id="input-delivery-list" type="checkbox">
                                                <label class="custom-control-label" for="input-delivery-list">Course box in list page</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-6 col-lg-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'invoice'}}]" id="input-delivery-invoice" type="checkbox">
                                                <label class="custom-control-label" for="input-delivery-invoice">Invoice description</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-6 col-lg-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'emails'}}]" id="input-delivery-emails" type="checkbox">
                                                <label class="custom-control-label" for="input-delivery-emails">Automated emails</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="form-group col-sm-12 col-md-4 col-lg-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'text'}}]" placeholder="Day" >
                                                <span data-infowrapper="inclass_day" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                        <i class="fas fa-calendar-day"></i>

                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_day_icon_path') }}" id="inclass_day_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_day_icon_alt_text') }}" id="inclass_day_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>






                                        <div class="form-group col-sm-12 col-md-2">
                                            <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'landing'}}]" id="input-day-landing" type="checkbox">
                                                <label class="custom-control-label" for="input-day-landing">Course landing page (summary)</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'home'}}]" id="input-day-home" type="checkbox">
                                                <label class="custom-control-label" for="input-day-home">Course box in home page</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'list'}}]" id="input-day-list" type="checkbox">
                                                <label class="custom-control-label" for="input-day-list">Course box in list page</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'invoice'}}]" id="input-day-invoice" type="checkbox">
                                                <label class="custom-control-label" for="input-day-invoice">Invoice description</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'emails'}}]" id="input-day-emails" type="checkbox">
                                                <label class="custom-control-label" for="input-day-emails">Automated emails</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="form-group col-sm-12 col-md-4 col-lg-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ old('times')}}" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" placeholder="Times(from/to)">
                                                <span data-infowrapper="inclass_times" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                    <span class="btn btn-outline-primary input-icon">
                                                        <span class="ni ni-watch-time"></span>

                                                    </span>
                                                </span>
                                                <input type="hidden" value="{{ old('inclass_times_icon_path') }}" id="inclass_times_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'path'}}]">
                                                <input type="hidden" value="{{ old('inclass_times_icon_alt_text') }}" id="inclass_times_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'alt_text'}}]">
                                            </div>
                                        </div>


                                        <div class="form-group col-sm-12 col-md-2">
                                            <label class="form-control-label visible-label" for="input-delivery">{{ __('Visible on:') }}</label>
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'landing'}}]" id="input-times-landing" type="checkbox">
                                                <label class="custom-control-label" for="input-times-landing">Course landing page (summary)</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'home'}}]" id="input-times-home" type="checkbox">
                                                <label class="custom-control-label" for="input-times-home">Course box in home page</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'list'}}]" id="input-times-list" type="checkbox">
                                                <label class="custom-control-label" for="input-times-list">Course box in list page</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'invoice'}}]" id="input-times-invoice" type="checkbox">
                                                <label class="custom-control-label" for="input-times-invoice">Invoice description</label>
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12 col-md-2">
                                            <div class="custom-control custom-checkbox mb-3 visible-item">
                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'emails'}}]" id="input-times-emails" type="checkbox">
                                                <label class="custom-control-label" for="input-times-emails">Automated emails</label>
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
                                                <input id="access-student" name="course[{{'free_courses'}}][{{'enabled'}}]" type="checkbox" checked="">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                            </label>
                                        </div>


                                        @if(count($elearning_events) != 0)
                                        <div class="free-course-wrapper">

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
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
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

                                    <select multiple name="partner_id[]" id="input-partner_id" class="form-control" placeholder="{{ __('Partner') }}" required>
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

                                        <span class="input-group-addon input-group-append input-icon-wrapper">
                                            <span class="btn btn-outline-primary input-icon"> <span class="fa fa-calendar"></span></span>
                                        </span>
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













                                {{--<div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}">
                                        <option value="">-</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'category_id'])
                                </div>--}}

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

                                <div id="exp_input" class="form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
                                </div>



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

                                {{{--<div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header') }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'header'])
                                </div>--}}

                                <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary">{{ __('Summary') }}</label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'summary'])
                                </div>

                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}"  required autofocus></textarea>

                                    @include('alerts.feedback', ['field' => 'body'])
                                </div>

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

                                <div class="form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }}">
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
                                </div>

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
@endsection


@push('js')

<script>

    instructors = @json($instructors);

    $(document).ready(function(){
        $("#input-syllabus").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions
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
        }else{
            $('.delivery_child_wrapper').addClass('d-none')
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


@endpush

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


    <link href="{{ cdn(mix('theme/assets/css/bootstrap.css')) }}" rel="stylesheet" media="all" />
    <link href="{{asset('admin_assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin_assets/css/saas/app-limited.css')}} " rel="stylesheet" type="text/css"/>
    @include('admin.upload.upload_new', ['from' => 'event_info'])
    {{--<script src="{{asset('js/app.js')}}"></script>--}}
    {{--<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>--}}


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
                        <form id="event_create_form" method="post" action="{{ route('events.store') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                            <div class="pl-lg-4">

                                <div class="d-none form-group{{ $errors->has('priority') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-priority">{{ __('Priority') }}</label>
                                    <input type="number" name="priority" id="input-priority" class="form-control{{ $errors->has('priority') ? ' is-invalid' : '' }}" placeholder="{{ __('Priority') }}" value="{{ old('priority') }}">

                                    @include('alerts.feedback', ['field' => 'priority'])
                                </div>


                                <div class="row">
                                    <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">

                                        <div class="form-group col-12">
                                            <h3 class="mb-0 title" for="input-status">{{ __('Course Publish') }}</h3>
                                        </div>

                                        <div class="form-group col-12">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input type="checkbox" name="published" id="input-published">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                            </label>
                                            @include('alerts.feedback', ['field' => 'published'])
                                        </div>


                                    </div>
                                </div>



                            <div class="row">
                                <div class="form-group col-12">
                                    <h3 class="mb-0 title" for="input-status">{{ __('Course Status') }}</h3>
                                </div>
                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }} col-sm-12 col-md-6 col-lg-4">

                                    <select name="status" id="input-status" class="form-control " placeholder="{{ __('Please select the status of this course') }}" required>
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
                                <div class="col-12">
                                    <div class="row form-group">
                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <h3 class="mb-0 title">{{ __('Course Hours') }} (course_hours)</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="hours" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img src="/theme/assets/images/icons/Start-Finish.svg"/>
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('hours_icon_path')}}" id="hours_path" name="course[{{'hours'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('hours_icon_alt_text') }}" id="hours_alt_text" name="course[{{'hours'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'hours'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'hours'}}][{{'icon'}}][{{'link'}}]" value="{{ old('hours_icon_link', (isset($info['hours']['icon']) && $info['hours']['icon'] != null && isset($info['hours']['icon']['link'])) ? $info['hours']['icon']['link'] : '' ) }}">
                                        </div>

                                    </div>
                                    <div class="row form-group">
                                            <div class="form-group col-12 col-md-6 col-lg-4 hours-input-wrapper">

                                                <!-- <label class="form-control-label">Hours Title (course_hours_title)</label> -->

                                                <input type="text" id="input-hours-title" name="course[{{'hours'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Hours Title') }}" value="{{ old('hours-title') }}" autofocus>


                                            </div>
                                        </div>
                                </div>

                                <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                    <!-- anto's editor -->
                                    <input class="hidden" id="input-hours-text" name="course[{{'hours'}}][{{'text'}}]" value="{{ old('hours_text', (isset($info['hours']['text']) && $info['hours']['text'] != null) ? $info['hours']['text'] : '' ) }}"/>
                                    <?php $data = isset($info['hours']['text']) && $info['hours']['text'] != null ? $info['hours']['text'] : '' ?>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-hours_title", 'data'=> "$data", 'inputname' => "'course[hours][text]'" ])
                                    <!-- anto's editor -->

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-12 accordion" >
                                    <div class="card">
                                        <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <h5 class="mb-0">Visible on:</h5>
                                        </div>
                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
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

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <!-- LANGUAGE Section-->

                            <div class="row">

                                <div class="col-12">

                                    <div class="row form-group">

                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <h3 class="mb-0 title">{{ __('Course Language') }} (course_language)</h3>
                                        </div>
                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="language" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img src="/theme/assets/images/icons/Language.svg" alt="">
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('language_icon_path') }}" id="language_path" name="course[{{'language'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('language_icon_alt_text') }}" id="language_alt_text" name="course[{{'language'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>
                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'language'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_language_icon['link_status']) && $course_language_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>

                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'language'}}][{{'icon'}}][{{'link'}}]" value="{{ old('language_icon_link', (isset($course_language_icon) && $course_language_icon != null && isset($course_language_icon['link'])) ? $course_language_icon['link'] : '' ) }}">
                                        </div>

                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-12 col-md-6 col-lg-4 language-input-wrapper">

                                            <!-- <label class="form-control-label">Language Title (course_language_title)</label> -->

                                            <input type="text" id="input-language-title" name="course[{{'language'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Language Title') }}" value="{{ old('language-title') }}" autofocus>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                     <!-- anto's editor -->
                                     <input class="hidden" id="input-language" name="course[{{'language'}}][{{'text'}}]" value="{{ old('language_text', (isset($info['language']['text']) && $info['certificate']['messages']['success'] != null) ? $info['certificate']['messages']['success'] : '') }}"/>
                                    <?php $data = isset($info['language']['text']) && $info['language']['text'] != null ? $info['language']['text'] : '' ?>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-language_title", 'data'=> "$data", 'inputname' => "'course[language][text]'" ])
                                    <!-- anto's editor -->
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-12 accordion"  style="width: 100%;">
                                    <div class="card">
                                            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <h5 class="mb-0">Visible on:</h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                <div class="card-body">
                                                <div class="row">
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

                                                </div>
                                            </div>
                                    </div>
                                </div>


                            </div>

                            <hr>

                            <!-- DELIVERY -->
                            <div class="row">

                                <div class="form-group col-12">

                                    <div class="input-group">
                                        <h3 class="mb-0 title">{{ __('Course Delivery') }} (course_delivery)</h3>


                                    </div>
                                </div>

                                <div class="col-12 form-group">

                                    <div class="row form_group">
                                        <div class="col-9 col-md-6 col-lg-4 {{ $errors->has('delivery') ? ' has-danger' : '' }}">

                                            <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" required>
                                                <option disabled selected value="">Please select where this course takes place</option>
                                                @foreach ($delivery as $delivery)
                                                    <option value="{{ $delivery->id }}" >{{ $delivery->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="delivery" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <i class="fa fa-university"></i>
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('course_delivery_icon_path') }}" id="delivery_path" name="course[{{'delivery_icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('course_delivery_icon_alt_text') }}" id="delivery_alt_text" name="course[{{'delivery_icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery_icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 align-self-center input d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery_icon'}}][{{'link'}}]" value="{{ old('delivery_icon_link', (isset($course_delivery_icon) && $course_delivery_icon != null && isset($course_delivery_icon['link'])) ? $course_delivery_icon['link'] : '' ) }}">
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12 col-md-6 col-lg-4 expiration-input-wrapper">

                                    <!-- <label class="form-control-label">Course Delivery Title</label> -->

                                    <input type="text" id="input-delivery-title" name="course[{{'delivery_info'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Delivery Title') }}" value="{{ old('delivery_title') }}" autofocus>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-9 col-md-6 col-lg-4">

                                    <!-- anto's editor -->
                                    <input class="hidden" id="input-delivery-text" name="course[{{'delivery_info'}}][{{'text'}}]" value=""/>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-delivery_title", 'data'=> "", 'inputname' => "'course[delivery_info][text]'" ])
                                    <!-- anto's editor -->

                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-12 accordion course-delivery-list-visible" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingThree1" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                            <h5 class="mb-0">Visible on:</h5>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'delivery_info'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing1" type="checkbox">
                                                            <label class="custom-control-label" for="input-delivery-landing1">Course landing page (summary)</label>
                                                        </div>

                                                    </div>


                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-12 delivery_child_wrapper d-none">
                                    <div class="row delivery_city_wrapper">
                                        <div class="col-9 col-md-6 col-lg-4 align-self-center form-group{{ $errors->has('city_id') ? ' has-danger' : '' }} ">
                                            <select name="city_id" id="input-city_id" class="form-control" placeholder="{{ __('Please select the city of this course') }}" >
                                                <option selected disabled value="">Please select the city of this course</option>
                                                @foreach ($cities as $city)

                                                    <option value="{{$city->id}}"> {{ $city->name }} </option>

                                                @endforeach
                                            </select>

                                        </div>


                                        <div class="col-2 col-md-auto col-lg-auto">
                                            <span class="input-icon-wrapper-city " data-infowrapper="inclass_city">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img src="/theme/assets/images/icons/marker.svg" alt="">
                                                </span>

                                            </span>
                                            <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('inclass_city_icon_path') }}" id="inclass_city_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-12 col-md-auto col-lg-auto align-self-center form-group d-none">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center form-group d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link') }}">
                                        </div>







                                    </div>
                                    <div class="row ">

                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <label class="form-control-label">{{ __('(course_inclass_dates)') }}</label>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="inclass_dates" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                <span class="btn btn-outline-primary input-icon">
                                                    {{--<span class="fa fa-calendar"></span>--}}
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="">
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('inclass_dates_icon_path') }}" id="inclass_dates_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('inclass_dates_icon_alt_text') }}" id="inclass_dates_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-auto col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'link'}}]" value="{{ old('course_inclass_dates', (isset($course_inclass_dates_icon) && $course_inclass_dates_icon != null && isset($course_inclass_dates_icon['link'])) ? $course_inclass_dates_icon['link'] : '' ) }}">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                            <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                            <input type="text" id="input-dates-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Dates Title') }}" value="{{ old('date_title') }}" autofocus>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-9 col-md-6 col-lg-4">

                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-dates" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" value="{{ (isset($dates) && isset($dates['text']) ) ? $dates['text'] : '' }}"/>
                                            <?php $data = (isset($dates) && isset($dates['text'])) ? $dates['text'] : '' ?>
                                            @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-dates_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][dates][text]'" ])
                                            <!-- anto's editor -->


                                            {{--<input type="text" class="form-control" value="{{ (isset($dates) && isset($dates['text']) ) ? $dates['text'] : '' }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" placeholder="Dates(from/to)">--}}

                                        </div>

                                        <div class="form-group col-12 accordion" >
                                            <div class="card">
                                                    <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing" type="checkbox">
                                                                        <label class="custom-control-label" for="input-delivery-landing">Course landing page (summary)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'home'}}]" id="input-delivery-home" type="checkbox">
                                                                        <label class="custom-control-label" for="input-delivery-home">Course box in home page</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'list'}}]" id="input-delivery-list" type="checkbox">
                                                                        <label class="custom-control-label" for="input-delivery-list">Course box in list page</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'invoice'}}]" id="input-delivery-invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="input-delivery-invoice">Invoice description</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'emails'}}]" id="input-delivery-emails" type="checkbox">
                                                                        <label class="custom-control-label" for="input-delivery-emails">Automated emails</label>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>







                                    <div class="row">

                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <label class="form-control-label">{{ __('(course_inclass_days)') }}</label>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="inclass_day" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                <span class="btn btn-outline-primary input-icon">
                                                <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('inclass_day_icon_path') }}" id="inclass_day_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('inclass_day_icon_alt_text') }}" id="inclass_day_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'alt_text'}}]">

                                        </div>
                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_inclass_day_icon['link_status']) && $course_inclass_day_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_elearning_icon['link_status']) && $course_elearning_icon['link_status'] == 'off') || !isset($course_elearning_icon['link_status'])) {{'d-none'}} @endif">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link', (isset($course_inclass_day_icon) && $course_inclass_day_icon != null && isset($course_inclass_day_icon['link'])) ? $course_inclass_day_icon['link'] : '' ) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                            <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                            <input type="text" id="input-day-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Day Title') }}" value="{{ old('day_title') }}" autofocus>

                                        </div>

                                    </div>
                                    <div class="row">

                                            <div class="col-9 col-md-6 col-lg-4">

                                                <!-- anto's editor -->
                                                <input class="hidden" id="input-days" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'text'}}]" value=""/>
                                                <?php $data = isset($days) && $days['text'] != null ? $days['text'] : '' ?>
                                                @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-language_days_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][day][text]'" ])
                                                <!-- anto's editor -->

                                            </div>






                                        <div class="form-group col-12 accordion" id="accordionExample">
                                            <div class="card">
                                                    <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'landing'}}]" id="input-day-landing" type="checkbox">
                                                                        <label class="custom-control-label" for="input-day-landing">Course landing page (summary)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'home'}}]" id="input-day-home" type="checkbox">
                                                                        <label class="custom-control-label" for="input-day-home">Course box in home page</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'list'}}]" id="input-day-list" type="checkbox">
                                                                        <label class="custom-control-label" for="input-day-list">Course box in list page</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'invoice'}}]" id="input-day-invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="input-day-invoice">Invoice description</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'emails'}}]" id="input-day-emails" type="checkbox">
                                                                        <label class="custom-control-label" for="input-day-emails">Automated emails</label>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <label class="form-control-label">{{ __('(course_inclass_times)') }}</label>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="inclass_times" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                <span class="btn btn-outline-primary input-icon">

                                                <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('inclass_times_icon_path') }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('inclass_times_icon_alt_text') }}" id="inclass_times_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>
                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                            <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                            <input type="text" id="input-time-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Time Title') }}" value="{{ old('time_title') }}" autofocus>

                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-9 col-md-6 col-lg-4">


                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-times" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" value="{{ old('times', (isset($times) && $times['text']) ? $times['text'] : '' ) }}"/>
                                            <?php $data = isset($times) && $times['text'] != null ? $times['text'] : '' ?>
                                            @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-times_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][times][text]'" ])
                                            <!-- anto's editor -->


                                            {{--<input type="text" class="form-control" value="{{ old('times', (isset($times['text']) && $times['text']) ? $times['text'] : '' ) }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" placeholder="Times(from/to)">--}}

                                        </div>



                                        <div class="form-group col-12 accordion" id="accordionExample">
                                            <div class="card">
                                                    <div class="card-header" id="headingFive" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'landing'}}]" id="input-times-landing" type="checkbox">
                                                                        <label class="custom-control-label" for="input-times-landing">Course landing page (summary)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'home'}}]" id="input-times-home" type="checkbox">
                                                                        <label class="custom-control-label" for="input-times-home">Course box in home page</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'list'}}]" id="input-times-list" type="checkbox">
                                                                        <label class="custom-control-label" for="input-times-list">Course box in list page</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'invoice'}}]" id="input-times-invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="input-times-invoice">Invoice description</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'emails'}}]" id="input-times-emails" type="checkbox">
                                                                        <label class="custom-control-label" for="input-times-emails">Automated emails</label>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                            </div>
                                            </div>






                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                            <label class="form-control-label" for="input-hours">{{ __('Absences Limit(%)') }}(course_inclass_absences)</label>
                                            <div class="input-group">
                                                <input type="text" name="course[{{'delivery'}}][{{'inclass'}}][{{'absences'}}]" id="input-absences_limit" class="form-control{{ $errors->has('Absences Limit(%)') ? ' is-invalid' : '' }}" placeholder="{{ __('absences_limit') }}" value="{{ old('$course_inclass_absences') }}"autofocus>
                                                <!-- <span class="input-group-addon input-group-append">
                                                    <span class="btn btn-outline-primary input-icon"> <span class="fa fa-calendar d-none"></span></span>
                                                </span> -->
                                            </div>
                                            {{--@include('alerts.feedback', ['field' => 'absences_imit'])--}}
                                        </div>
                                    </div>




                                    <div class="row access-student-wrapper">

                                        <div class="form-group col-12">

                                            <div class="input-group">
                                                <h3 class="mb-0 title">{{ __('Course Access') }}</h3>

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
                                            <label id="access-student-toggle" class="custom-toggle enroll-toggle visible">
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

                                <div class="exp_input col-12 form-group d-none">

                                    <div class="row form-group">
                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <label class="form-control-label" for="input-expiration">{{ __('Months access') }} {{ __('(course_elearning_expiration)')}}</label>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="elearning" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                </span>
                                            </span>

                                            <input type="hidden" value="{{ old('elearning_icon_path') }}" id="elearning_path" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('elearning_icon_alt_text') }}" id="elearning_alt_text" id="elearning_alt_text" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>
                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">


                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>



                                        </div>
                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'link'}}]" value="{{ old('elearning_icon_link') }}">
                                        </div>

                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <input type="number" min="1" name="course[{{'delivery'}}][{{'elearning'}}][{{'expiration'}}]" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration') }}"autofocus>

                                        </div>
                                    </div>

                                </div>
                                <div class="exp_input col-sm-12 col-md-6 col-lg-4 form-group d-none">
                                    <div class="row">
                                        <div class="form-group col-12 expiration-input-wrapper">

                                            <label class="form-control-label">Months access title (course_elearning_expiration_title)</label>

                                            <input type="text" id="input-elearning-exp-title" name="course[{{'delivery'}}][{{'elearning'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Month Access Title') }}" value="{{ old('expiration_title', (isset($info['elearning']['title']) && $info['elearning']['title'] != null) ? $info['elearning']['title'] : '' ) }}" autofocus>

                                        </div>

                                    </div>

                                    <label class="form-control-label" for="input-test">{{ __('Months access text') }} {{ __('(course_elearning_expiration)') }}</label>

                                    <!-- anto's editor -->
                                    <input class="hidden" id="elearning_exp" name="course[{{'delivery'}}][{{'elearning'}}][{{'text'}}]" value="{{ old('expiration_text', (isset($info['elearning']['text']) && $info['elearning']['text'] != null) ? $info['elearning']['text'] : '' ) }}"/>
                                    <?php $data = isset($info['elearning']['text']) && $info['elearning']['text'] != null ? $info['elearning']['text'] : '' ?>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "elearning_exp_title", 'data'=> "$data", 'inputname' => "'course[delivery][elearning][text]'" ])
                                    <!-- anto's editor -->
                                </div>
                            </div>

                            <div class="row elearning_visible_wrapper d-none">


                            <div class="form-group col-12 accordion" id="accordionExample">
                                <div class="card">
                                        <div class="card-header" id="headingSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                            <h5 class="mb-0">Visible on:</h5>
                                        </div>
                                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
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

                                            </div>
                                        </div>
                                </div>
                            </div>










                            </div>

                            <div class="row elearning_exam_visible_wrapper d-none">

                                <div class="exam_input col-12 form-group d-none">
                                    <div class="row form-group">

                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <label class="form-control-label" for="input-expiration">{{ __('Online Exam') }} <br>{{ __('(course_elearning_exam_text)')}}</label>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">

                                            <span data-infowrapper="elearning_exam" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/messages-warning-information.svg" alt="">
                                                </span>
                                            </span>

                                            <input type="hidden" value="{{ old('elearning_exam_icon_path') }}" id="elearning_exam_path" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('elearning_exam_icon_alt_text') }}" id="elearning_exam_alt_text" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'alt_text'}}]">

                                        </div>
                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>

                                        </div>
                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'link'}}]" value="{{ old('elearning_icon_link', (isset($course_elearning_exam_icon) && $course_elearning_exam_icon != null && isset($course_elearning_exam_icon['link'])) ? $course_elearning_exam_icon['link'] : '' ) }}">
                                        </div>

                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-12 col-md-6 col-lg-4 expiration-input-wrapper">

                                            <label class="form-control-label">(course_elearning_exam_title)</label>

                                            <input type="text" id="input-exam-title" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course exam title') }}" value="{{ old('exam_title') }}" autofocus>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-lg-4 form-group">
                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-exam" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'text'}}]" value="{{ old('exam') }}"/>
                                            <?php $data = isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null ? $info['elearning']['exam']['text'] : '' ?>
                                            @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-exam_text", 'data'=> "$data", 'inputname' => "'course[delivery][elearning][exam][text]'" ])
                                            <!-- anto's editor -->
                                        </div>
                                    </div>

                                </div>

                                <div class="exam_input col-sm-12 col-md-6 col-lg-4 form-group">
                                    <label class="form-control-label" for="input-expiration">{{ __('Exam Activate Months') }} {{ __('(course_elearning_exam_activate_months)') }}</label>
                                    <div class="input-group">
                                        <input type="number" min="1" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'activate_months'}}]" id="input-exam-activate-months" class="form-control" placeholder="{{ __('Months') }}" value="{{ old('exam_activate_months') }}"autofocus>
                                    </div>
                                </div>


                                <div class="form-group col-12 accordion" id="accordionExample">
                                    <div class="card">
                                            <div class="card-header" id="headingSeven" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                                <h5 class="mb-0">Visible on:</h5>
                                            </div>
                                            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'landing'}}]" id="input-elearning-exam-landing" type="checkbox">
                                                                <label class="custom-control-label" for="input-elearning-exam-landing">Course landing page (summary)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'home'}}]" id="input-elearning-exam-home" type="checkbox">
                                                                <label class="custom-control-label" for="input-elearning-exam-home">Course box in home page</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'list'}}]" id="input-elearning-exam-list" type="checkbox">
                                                                <label class="custom-control-label" for="input-elearning-exam-list">Course box in list page</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'invoice'}}]" id="input-elearning-exam-invoice" type="checkbox">
                                                                <label class="custom-control-label" for="input-elearning-exam-invoice">Invoice description</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input class="custom-control-input" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'emails'}}]" id="input-elearning-exam-emails" type="checkbox">
                                                                <label class="custom-control-label" for="input-elearning-exam-emails">Automated emails</label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                    </div>
                                    </div>






                            </div>



                            <hr>


                            <div class="row course-partner-wrapper">

                                <div class="form-group col-12">

                                    <div class="row">

                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <h3 class="mb-0 title" for="input-hours">{{ __('Course Partners') }} (course_partner)</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="partner" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('course_partner_icon_path') }}" id="partner_path" name="course[{{'partner'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('course_partner_icon_alt_text') }}" id="partner_alt_text" name="course[{{'partner'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'partner'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 align-self-center input d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'partner'}}][{{'icon'}}][{{'link'}}]" value="{{ old('hours_icon_link', (isset($course_partner_icon) && $course_partner_icon != null && isset($course_partner_icon['link'])) ? $course_partner_icon['link'] : '' ) }}">
                                        </div>


                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have supporters/partners?</span>
                                    <label class="custom-toggle enroll-toggle visible">
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

                                    <input class="hidden" id="input-partner" name="course[{{'partner'}}][{{'text'}}]" value="{{ old('partner_text', (isset($info['partner']['text']) && $info['partner']['text'] != null) ? $info['partner']['text'] : '' ) }}"/>
                                    <?php $data = isset($info['partner']['text']) && $info['partner']['text'] != null ? $info['partner']['text'] : '' ?>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-partner-text", 'data'=> "$data", 'inputname' => "'course[partner][text]'" ])
                                    <!-- anto's editor -->
                                </div>
                                <div class="form-group col-12 accordion course-partner-list-visible d-none" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingThree1" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                            <h5 class="mb-0">Visible on:</h5>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'partner'}}][{{'visible'}}][{{'landing'}}]" id="input-partner-landing" type="checkbox">
                                                            <label class="custom-control-label" for="input-partner-landing">Course landing page (summary)</label>
                                                        </div>

                                                    </div>

                                                    {{--<div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'partner'}}][{{'visible'}}][{{'home'}}]" id="input-partner-home" type="checkbox">
                                                            <label class="custom-control-label" for="input-partner-home">Course box in home page</label>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'partner'}}][{{'visible'}}][{{'list'}}]" id="input-partner-list" type="checkbox">
                                                            <label class="custom-control-label" for="input-partner-list">Course box in list page</label>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'partner'}}][{{'visible'}}][{{'invoice'}}]" id="input-partner-invoice" type="checkbox">
                                                            <label class="custom-control-label" for="input-partner-invoice">Invoice description</label>
                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input class="custom-control-input" name="course[{{'partner'}}][{{'visible'}}][{{'emails'}}]" id="input-partner-emails" type="checkbox">
                                                            <label class="custom-control-label" for="input-partner-emails">Automated emails</label>
                                                        </div>

                                                    </div>--}}

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <hr>

                            <div class="row course-dropbox-wrapper">


                                <div class="form-group col-12">

                                    <div class="row">
                                        <div class="col-9 col-md-auto col-lg-auto">
                                            <h3 class="mb-0 title" for="input-hours">{{ __('Course Files') }}</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto">
                                            <span data-infowrapper="files" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                <img src="/theme/assets/images/icons/Access-Files.svg" alt="">
                                                </span>
                                            </span>
                                        </div>
                                        <input type="hidden" value="" id="files_path" name="course[{{'files'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="" id="files_alt_text" name="course[{{'files'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <input type="hidden" id="selectedFiles" name="selectedFiles" value="">

                                    <div id="filesTreeContainer"></div>
                                </div>


                                <div class="col-sm-12 col-md-6 col-lg-4 form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
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

                                    <div class="row">

                                        <div class="col-9 col-md-auto col-lg-auto">
                                            <h3 class="mb-0 title" >{{ __('Course Manager') }} (course_manager)</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto">
                                            <span data-infowrapper="manager" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">

                                                    <span class="fa fa-calendar"></span>

                                                </span>
                                            </span>
                                        </div>
                                        <input type="hidden" value="{{ old('course_manager_icon_path') }}" id="manager_path" name="course[{{'manager'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_manager_icon_alt_text') }}" id="manager_alt_text" name="course[{{'manager'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have a visible manager?</span>
                                    <label class="custom-toggle enroll-toggle visible">
                                        <input id="manager-toggle" name="manager-enabled" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                @if(count($instructors) != 0)
                                <div class="col-sm-12 col-md-6 col-lg-4 course-manager-list form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }} d-none">
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

                                    <div class="row">

                                        <div class="col-9 col-md-auto col-lg-auto">
                                            <h3 class="mb-0 title">{{ __('Course Awards & Badges') }} (course_awards)</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto">
                                            <span data-infowrapper="awards" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </span>
                                        </div>
                                        <input type="hidden" value="{{ old('course_awards_icon_path') }}" id="awards_path" name="course[{{'awards'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_awards_icon_alt_text') }}" id="awards_alt_text" name="course[{{'awards'}}][{{'icon'}}][{{'alt_text'}}]">
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course have some award?</span>
                                    <label class="custom-toggle enroll-toggle visible">
                                        <input id="award-toggle" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-4 form-group award-text d-none">
                                    <input style="background: aliceblue;" id="input-award-text" type="text" name="course[{{'awards'}}][{{'text'}}]" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('awards') }}" autofocus>
                                </div>
                            </div>


                            <hr>

                            <div class="row course-certification-wrapper">

                                <div class="form-group col-12">

                                    <div class="row">
                                        <div class="col-9 col-md-auto col-lg-auto">
                                            <h3 class="mb-0 title">{{ __('Course Certification') }}</h3>
                                        </div>

                                        <div class="col-2 col-md-auto col-lg-auto">
                                            <span data-infowrapper="certificate" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt="">
                                                </span>
                                            </span>
                                            <input type="hidden" value="{{ old('course_certification_icon_path') }}" id="certificate_path" name="course[{{'certificate'}}][{{'icon'}}][{{'path'}}]">
                                        <input type="hidden" value="{{ old('course_certification_icon_alt_text') }}" id="certificate_alt_text" name="course[{{'certificate'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'certificate'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_certification_icon['link_status']) && $course_certification_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>

                                        </div>

                                        <div class="col-12 col-md-5 col-lg-4 align-self-center input @if((isset($course_certification_icon['link_status']) && $course_certification_icon['link_status'] == 'off') || !isset($course_certification_icon['link_status'])) {{'d-none'}} @endif">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'certificate'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link', (isset($course_certification_icon) && $course_certification_icon != null && isset($course_certification_icon['link'])) ? $course_certification_icon['link'] : '' ) }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-12">
                                    <span class="toggle-btn-inline-text">Does this course offer a certification? </span>
                                    <label class="custom-toggle enroll-toggle visible">
                                        <input name="course[{{'certificate'}}][{{'certification'}}]" id="certification-toggle" type="checkbox">
                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                    </label>
                                </div>

                                <div class="form-group col-12 course-certification-visible-wrapper d-none">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-4 form-group">

                                            <label class="form-control-label">Certification Title (course_certificate_title)</label>

                                            <input type="text" id="input-certification-title" name="course[{{'certificate'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course certificate Title') }}" value="{{ old('certificate-title') }}" autofocus>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 title" for="input-hours">{{ __('Courses with exams') }}</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 form-group{{ $errors->has('fb_') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-hours">{{ __('Certificate Title') }}  (course_certification_name_success)</label>

                                            {{--<textarea type="text" name="course[{{'certificate'}}][{{'success_text'}}]" id="input-certificate_title" class="ckeditor form-control" autofocus>{{ old('certificate_title') }}</textarea>--}}
                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-certificate_title_hidden" name="course[{{'certificate'}}][{{'success_text'}}]" value="{{ old('certificate_title') }}"/>
                                            <?php $data =  '' ?>
                                            @include('event.editor.editor', ['keyinput' => "input-certificate_title", 'data'=> "$data", 'inputname' => "'course[certificate][success_text]'" ])
                                            <!-- anto's editor -->

                                            @include('alerts.feedback', ['field' => 'certificate_title'])

                                        </div>

                                        <div class="col-sm-12 col-md-6 form-group">
                                            <label class="form-control-label" for="input-hours">{{ __('Title Of Certification (in case of exams failure)') }} (course_certification_name_failure)</label>

                                            {{--<textarea type="text" id="input-certificate_text_failure_hidden" name="course[{{'certificate'}}][{{'failure_text'}}]" id="input-certificate_text_failure" class="form-control ckeditor"  autofocus>{{old('certificate_failure')}}</textarea>--}}

                                            <!-- anto's editor -->
                                            <input class="hidden" name="course[{{'certificate'}}][{{'failure_text'}}]" value="{{ old('certificate_failure') }}"/>
                                            <?php $data =  '' ?>
                                            @include('event.editor.editor', ['keyinput' => "input-certificate_text_failure", 'data'=> "$data", 'inputname' => "'course[certificate][failure_text]'" ])
                                            <!-- anto's editor -->
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 title" for="input-hours">{{ __('Courses without exams') }}</h4>
                                    </div>
                                    <div class="row">


                                        {{--<div class="col-sm-12 col-md-6 form-group">
                                            <label class="form-control-label" for="input-hours">{{ __('Certificate Event Title') }} (course_certification_event_title)</label>
                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-certificate_event_title_hidden" name="course[{{'certificate'}}][{{'event_title'}}]" value="{{ old('certificate_event_title') }}"/>
                                            <?php $data = '' ?>
                                            @include('event.editor.editor', ['keyinput' => "input-certificate_event_title", 'data'=> "$data", 'inputname' => "'course[certificate][event_title]'" ])
                                            <!-- anto's editor -->

                                        </div>--}}

                                        <div class="col-sm-12 col-md-6 form-group">
                                            <label class="form-control-label" for="input-hours">{{ __('Certificate Type') }} (course_certification_type)</label>
                                            {{--<input type="text" name="course[{{'certificate'}}][{{'type'}}]" id="input-certificate_type" class="form-control" placeholder="{{ __('alphanumeric text ') }}" value="{{old('certificate_type')}}" autofocus/>--}}

                                            <!-- anto's editor -->
                                            <input class="hidden" id="input-certificate" name="course[{{'certificate'}}][{{'type'}}]" value="{{old('certificate_type')}}"/>
                                            <?php $data = isset($info['certificate']['type']) && $info['certificate']['type'] != null ? $info['certificate']['type'] : '' ?>
                                            @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-certificate_type", 'data'=> "$data", 'inputname' => "'course[certificate][type]'" ])
                                            <!-- anto's editor -->

                                        </div>
                                    </div>
                                        <div class="row">



                                            <div class="form-group col-12 accordion">
                                                <div class="card">
                                                        <div class="card-header" id="headingEight" data-toggle="collapse" data-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                                                            <h5 class="mb-0">Visible on:</h5>
                                                        </div>
                                                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
                                                            <div class="card-body">
                                                                <div class="row">
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
                                                </div>
                                            </div>

                                        </div>
                                </div>




                            </div>



                            <hr>

                            <div class="row course-student-wrapper">
                                <div class="form-group col-12">

                                    <div class="row">
                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                            <h3 class="mb-0 title">{{ __('Course Students') }}</h3>
                                        </div>
                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                            <span data-infowrapper="students" class="input-group-addon input-group-append input-icon-wrapper">
                                                <span class="btn btn-outline-primary input-icon">
                                                    <img src="/theme/assets/images/icons/Group_User.1.svg" alt="">
                                                </span>
                                            </span>

                                            <input type="hidden" value="{{ old('course_students_icon_path') }}" id="students_path" name="course[{{'students'}}][{{'icon'}}][{{'path'}}]">
                                            <input type="hidden" value="{{ old('course_students_icon_alt_text') }}" id="students_alt_text" name="course[{{'students'}}][{{'icon'}}][{{'alt_text'}}]">
                                        </div>

                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                            <label class="custom-toggle enroll-toggle visible">
                                                <input class="icon_link" name="course[{{'students'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                            </label>

                                        </div>
                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none">
                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'students'}}][{{'icon'}}][{{'link'}}]" value="{{ old('course_students_icon_link', (isset($course_students_icon) && $course_students_icon != null && isset($course_students_icon['link'])) ? $course_students_icon['link'] : '' ) }}">
                                        </div>

                                    </div>
                                </div>


                                <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                    <label class="form-control-label" for="input-hours">{{ __('Student should start count from this number:') }} {{ __('(course_students_number)')}}</label>
                                    <input type="text" name="course[{{'students'}}][{{'count_start'}}]" class="form-control" placeholder="{{ __('number') }}" value="{{ old('count_start') }}" autofocus>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-8 form-group"></div>
                                <div class="col-sm-12 col-md-6 col-lg-4 form-group">

                                    <label class="form-control-label">Students Title (course_students_title)</label>

                                    <input type="text" id="input-students-title" name="course[{{'students'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course students Title') }}" value="{{ old('students-title', (isset($info['students']['title']) && $info['students']['title'] != null) ? $info['students']['title'] : '' ) }}" autofocus>

                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-8 form-group"></div>


                            </div>

                            <div class="row">

                                <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                    <label class="form-control-label" for="input-hours">{{ __('Text after the number of students:') }}<br> {{ __('(course_students_text)') }}</label>

                                    <!-- anto's editor -->
                                    <input class="hidden" id="input-students" name="course[{{'students'}}][{{'text'}}]" value="{{ old('count_text') }}"/>
                                    <?php $data = isset($info['students']['text']) && $info['students']['text'] != null ? $info['students']['text'] : '' ?>
                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-students_title", 'data'=> "$data", 'inputname' => "'course[students][text]'" ])
                                    <!-- anto's editor -->

                                </div>

                            </div>

                            <div class="row">


                                <div class="form-group col-12 accordion" >
                                    <div class="card">
                                        <div class="card-header" id="headingNine" data-toggle="collapse" data-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                                            <h5 class="mb-0">Visible on:</h5>
                                        </div>
                                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
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

                                                </div>
                                            </div>
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
                                    <button type="submit" class="btn btn-outline-success mt-4">{{ __('Save') }}</button>
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

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/devextreme/20.2.11/css/dx.carmine.compact.css" rel="stylesheet">
@endpush

@push('js')
<script src="{{ asset('argon') }}/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/devextreme/20.2.11/js/dx.all.js"></script>

<script>

    let files = []
    const dropFiles = JSON.parse(@json($dropbox));
    let treeList = null;

    function treeData(){
        return new Promise(function(resolve, reject) {
            let count   = 10000;
            let count1  = 100000;
            let count2  = 1000000;
            let count3  = 10000000;
            $.each(dropFiles,function(index, value) {

                files.push({
                    ID: value.id,
                    Full_Name: value.folder_name,
                    isRootFolder: true
                })

                let folders = value.folders;
                let files1 = value.files

                if(folders != null && folders[0] != null){
                    $.each(folders[0], function(index1, value1) {

                        //foreach for folders
                        files.push({
                            ID: count,
                            Head_ID: value.id,
                            Full_Name: value1.foldername,
                            dirname: value1.dirname,
                            dropboxFolder: value.folder_name,
                            isRootFolder: false
                        })

                        //foreach for files
                        if(files1[1]){
                            $.each(files1[1], function(index22, value22) {
                                if(value22.fid == value1.id){

                                    files.push({
                                        ID: count2,
                                        Head_ID: count,
                                        Full_Name: value22.filename,
                                        dirname: value22.dirname,
                                        dropboxFolder: value.folder_name,
                                        isRootFolder: false
                                    })
                                }
                                count2++;
                            })
                        }

                    //Bonus folder
                    if(folders[1] != null){
                        //console.log('bonus folders', folders[1])
                        $.each(folders[1], function(index11, value11) {
                            if(value11.parent == value1.id){

                                files.push({
                                    ID: count1,
                                    Head_ID: count,
                                    Full_Name: value11.foldername,
                                    dirname: value11.dirname,
                                    dropboxFolder: value.folder_name
                                })

                                if(files1[2]){
                                    $.each(files1[2], function(index33, value33) {
                                        if(value33.fid == value11.id && value33.parent == value1.id){
                                            files.push({
                                                ID: count3,
                                                Head_ID: count1,
                                                Full_Name: value33.filename,
                                                dirname: value33.dirname,
                                                dropboxFolder: value.folder_name,
                                                isRootFolder: false
                                            })
                                        }
                                        count3++;
                                    })
                                }
                            }
                            count1++
                        })
                    }
                        count++
                    })
                }
            })

            resolve();
        })
    }

    function treeFiles(){
        treeList = $('#filesTreeContainer').dxTreeList({
            dataSource: files,
            keyExpr: 'ID',
            parentIdExpr: 'Head_ID',
            allowColumnReordering: false,
            allowColumnResizing: false,
            showBorders: false,
            searchPanel: { visible: true },
            selection: {
                mode: 'multiple',
                recursive: true,
            },
                filterRow: {
                visible: false,
            },
            stateStoring: {
                enabled: false,
                type: 'localStorage',
                storageKey: 'treeListStorage',
            },
            columns: [
                {
                    dataField: 'Full_Name',
                }
            ],
        }).dxTreeList('instance');
    }

    $(() => {



        treeData().then(function () {
            treeFiles()
            $('.dx-toolbar-after').addClass('col-sm-12 col-md-6 col-lg-4 form-group');
        })

        $('#state-reset-link').on('click', () => {
            treeList.state(null);
        });

        $("#filesTreeContainer").dxTreeList({
            onSelectionChanged: function(e) { // Handler of the "selectionChanged" event
                let deselectIDS = [];
                const currentSelectedRowKeys = e.currentSelectedRowKeys[0];
                var currentSelectedRow = [];
                selectedFolders = [];
                let selectedDropbox = null;
                let selectedAllFolders = false;
                const allSelectedRowsData = e.selectedRowsData;

                $.each(files, function(index, value){
                    if(currentSelectedRowKeys == value.ID){
                        currentSelectedRow = value
                    }
                })


                if(currentSelectedRow.isRootFolder && allSelectedRowsData.length != 1){

                    allSelectedRowsData.filter(value => value.ID == currentSelectedRowKeys);

                    $.each(allSelectedRowsData,function(index,value){
                        if(value.ID != currentSelectedRowKeys){
                            deselectIDS.push(value.ID)
                        }
                    })

                }

                if(!currentSelectedRow.isRootFolder && allSelectedRowsData.length != 1){
                    $.each(allSelectedRowsData,function(index,value){
                        if(value.isRootFolder && currentSelectedRow.Head_ID != value.ID){
                            deselectIDS.push(value.ID)
                        }
                    })
                }

                treeList.deselectRows(deselectIDS);

                let dataForSubmit = [];

                $.each(allSelectedRowsData, function(index, value) {

                    if(value.isRootFolder){
                        selectedAllFolders = true;
                        selectedDropbox = value.Full_Name;

                    }else{
                        selectedFolders.push(value.dirname)
                        selectedDropbox = value.dropboxFolder;
                    }



                })

                dataForSubmit = {
                    selectedDropbox :selectedDropbox,
                    selectedAllFolders :selectedAllFolders,
                    selectedFolders :selectedFolders
                };


                dataForSubmit = JSON.stringify(dataForSubmit);
                $('#selectedFiles').val(dataForSubmit);
            }
        });
    });

    instructors = @json($instructors);

    $(document).ready(function(){

        $.validator.addMethod('checkIfRequired', function (value, element, param) {
            let isValid = false;
            let toggle_btn = $(element).parent().parent();

            toggle_btn = $(toggle_btn).find('.icon_link')[0];

            if($(toggle_btn).prop('checked') && value != ''){
                isValid = true;
            }



            return isValid; // return bool here if valid or not.
        }, 'Field is required!');

        $("#event_create_form").validate({

            rules: {
                "course[hours][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery_icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[language][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[students][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[certificate][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[partner][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][inclass][city][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][inclass][dates][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][inclass][day][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][inclass][times][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][elearning][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery][elearning][exam][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                }
            }
        });

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
        if($(this).val() == 139){
            $('.delivery_child_wrapper').removeClass('d-none')
            $('.delivery_city_wrapper').removeClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
            $('.elearning__exam_visible_wrapper').addClass('d-none')
            $('.exp_input').addClass('d-none')
            $('.exam_input').addClass('d-none')
        }else if($(this).val() == 143){
            $('.delivery_child_wrapper').addClass('d-none')
            $('.elearning_visible_wrapper').removeClass('d-none')
            $('.elearning_exam_visible_wrapper').removeClass('d-none')
            $('.exp_input').removeClass('d-none')
            $('.exam_input').removeClass('d-none')
            $('#input-city_id').val('')
        }else if($(this).val() == 215){
            $('.exp_input').addClass('d-none')
            $('.exam_input').addClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
            $('.elearning_exam_visible_wrapper').addClass('d-none')
            $('.delivery_child_wrapper').removeClass('d-none')
            $('.delivery_city_wrapper').addClass('d-none')
            $('#input-city_id').val('')
        }
    });

    $("#partner-toggle").change(function() {
        let status = $(this).prop('checked')

        if(status){
            $('.course-partner-list').removeClass('d-none');
            $('.course-partner-list-visible').removeClass('d-none');

            $('#input-partner_id').val([])

        }else{
            $('.course-partner-list').addClass('d-none');
            $('.course-partner-list-visible').addClass('d-none');

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
            let elem = document.getElementsByClassName('tox-editor-header');

            elem.forEach(function(element, index){
                elem[index].style.removeProperty('position')
                elem[index].style.removeProperty('left')
                elem[index].style.removeProperty('top')
                elem[index].style.removeProperty('width')
            })
            $('.course-certification-visible-wrapper').removeClass('d-none');

            $('#input-certificate_type').val('')
        }else{
            $('.course-certification-visible-wrapper').addClass('d-none');

            $('#input-certificate_type').val('')

            tinymce.get("input-certificate_title").setContent("")
            tinymce.get("input-certificate_text_failure").setContent("")
            $("#input-certificate_title_hidden").val("")
            $("#input-certificate_text_failure_hidden").val("")

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

    $('.icon_link').change(function() {
        let status = $(this).prop('checked');
        let elem = $(this).parent().parent().parent();
        elem = $(elem).find('.input')[0]

        if(status){

            $(elem).removeClass('d-none');

        }else{
            $(elem).addClass('d-none');
        }
    })

</script>


@endpush

@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
])



@section('content')

    @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')


            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Event') }}</li>

            
            
    
    @endcomponent

        @include('event.layouts.event_card')

    @endcomponent

    @include('admin.upload.upload_new', ['from' => 'event_info'])
    <?php


        $uri = \Request::input();

        $show_popup = isset($uri['show_popup']) ? $uri['show_popup'] : 0

    ?>

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">

                    <div class="card-header">
                        <!-- <div class="row align-items-center"> -->
                            <div class="col-8">
                                <h3 class="mb-0 ml-1">{{ $event['htmlTitle'] }}</h3>
                            </div>
                            <div id="mobile_menu" class="dropdown d-none">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Menu
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item settings-btn active" data-toggle="tab"  href="#tabs-icons-text-1" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span>
                                    <span class="btn-inner--text">Settings</span>
                                </button>
                                <button class="dropdown-item seo" data-toggle="tab"  href="#metas" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-world"></i></span>
                                    <span class="btn-inner--text">Seo</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#tabs-icons-text-2" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-folder-17"></i></span>
                                    <span class="btn-inner--text">Content</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#emails_fields" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-curved-next"></i></span>
                                    <span class="btn-inner--text">Links</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#tabs-icons-text-3" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-hat-3"></i></span>
                                    <span class="btn-inner--text">Students</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#waiting_list" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-hat-3"></i></span>
                                    <span class="btn-inner--text">Waiting List Students</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#tabs-icons-text-5" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-tag"></i></span>
                                    <span class="btn-inner--text">Coupons</span>
                                </button>
                                <button class="dropdown-item" data-toggle="tab"  href="#xml_fields" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-single-copy-04"></i></span>
                                    <span class="btn-inner--text">XML fields</span>
                                </button>
                                {{--<a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}"  class="dropdown-item download">Download course schedule</a>--}}

                                </div>
                            </div>

                    </div>





                    <div class="nav-wrapper tab-buttons">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row event-tabs" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                {{--<a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-settings-gear-65 mr-2"></i>Settings</a>--}}
                                <button class="btn btn-icon btn-primary settings-btn" data-toggle="tab"  href="#tabs-icons-text-1" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span>
                                    <span class="btn-inner--text">Settings</span>
                                </button>
                            </li>


                            <li class="nav-item">
                                <button class="btn btn-icon btn-primary seo" data-toggle="tab"  href="#metas" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-world"></i></span>
                                    <span class="btn-inner--text">Seo</span>
                                </button>
                            </li>



                            <li class="nav-item">
                                {{--<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-folder-17 mr-2"></i>Content</a>--}}
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#tabs-icons-text-2" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-folder-17"></i></span>
                                    <span class="btn-inner--text">Content</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                {{--<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#emails_fields" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false"><i class="ni ni-world mr-2"></i>Links</a>--}}
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#emails_fields" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-curved-next"></i></span>
                                    <span class="btn-inner--text">Links</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                {{--<a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-hat-3 mr-2"></i>Students</a>--}}
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#tabs-icons-text-3" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-hat-3"></i></span>
                                    <span class="btn-inner--text">Students</span>
                                </button>
                            </li>

                            @if(count($eventWaitingUsers) != 0)
                            <li class="nav-item">
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#waiting_list" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-hat-3"></i></span>
                                    <span class="btn-inner--text">Waiting List Students</span>
                                </button>
                            </li>
                            @endif

                            <li class="nav-item">
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#tabs-icons-text-5" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-tag"></i></span>
                                    <span class="btn-inner--text">Coupons</span>
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#xml_fields" role="tab" type="button">
                                    <span class="btn-inner--icon"><i class="ni ni-single-copy-04"></i></span>
                                    <span class="btn-inner--text">XML fields</span>
                                </button>
                            </li>

                            {{--<li class="nav-item">
                                <a target="_blank" href="/print/syllabus/{{$event['slugable']['slug']}}"  class="btn btn-icon btn-primary">Download course schedule</a>

                            </li>--}}



                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="col-12 mt-2">
                                @include('alerts.success')
                                @include('alerts.errors')
                            </div>

                            {{--<div class="form_event_btn_new d-none">
                                <div class="save_event_btn" >@include('admin.save.save',['event' => isset($event) ? $event : null])</div>
                                <div class="preview_event_btn">@include('admin.preview.preview',['slug' => isset($slug) ? $slug : null])</div>
                                <div class="save_event_btn" >@include('admin.download.schedule',['event' => isset($event) ? $event : null])</div>
                            </div>--}}
                            
                            <form id="event_edit_form" method="POST" action="{{ route('events.update', $event) }}" autocomplete="off"
                                        enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">


                                        <div class="row align-center">


                                            <div class="col-lg-2 col-md-6 col-sm-6 col-6">

                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-published">{{ __('Date created') }}</label>
                                                    <input type="text" name="created_at" type="text" id="input-published-input"
                                                                value="{{ date('d-m-Y',strtotime(old('created_at', $event->created_at))) }}" class="form-control" disabled />

                                                </div>

                                            </div>


                                            <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                                                <div class="form-group">
                                                        <label class="form-control-label" for="launch_date">{{ __('Launch Date') }}</label>
                                                        <input type="text" name="launch_date" type="text" id="input-launch-input"
                                                                    value="{{ date('d-m-Y',strtotime(old('launch_date', $event->launch_date))) }}" class="form-control datepicker" />

                                                </div>
                                            </div>



                                            <div class="col-lg-2 col-md-4 col-sm-6 col-6">

                                                <div class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-method">{{ __('Published') }}</label>


                                                    <div style="margin: auto;" class="form-group">
                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input type="checkbox" name="published" id="input-published" @if($event['published']) checked @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                    @include('alerts.feedback', ['field' => 'published'])



                                                </div>

                                            </div>


                                            <div class="col-lg-2 col-md-4 col-sm-6 col-6">

                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-method">{{ __('Index') }}</label>
                                                    <div style="margin: auto;" class="form-group">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input type="checkbox" name="index" id="input-index" @if($event['index']) checked @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-4 col-sm-6 col-6">

                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-method">{{ __('Feed') }}</label>
                                                    <div style="margin: auto;" class="form-group">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input type="checkbox" name="feed" id="input-feed" @if($event['feed']) checked @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>


                                            {{--<div class="col-lg-2 col-md-12 col-sm-6 col-6  form_event_btn">--}}
                                            {{--<div class="col-md-12 col-sm-12 text-sm-center text-lg-right text-md-right form_event_btn">
                                                <div class="save_event_btn" >@include('admin.save.save',['event' => isset($event) ? $event : null])</div>
                                                <div class="preview_event_btn">@include('admin.preview.preview',['slug' => isset($slug) ? $slug : null])</div>
                                                <div class="save_event_btn" >@include('admin.download.schedule',['event' => isset($event) ? $event : null])</div>
                                            </div>--}}



                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="form-group col-12">
                                                <h3 class="mb-0 title" for="input-status">{{ __('Course Status') }} (course_status)</h3>
                                            </div>
                                            <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }} col-sm-12 col-md-6 col-lg-4">
                                                <input hidden name="old_status" value="{{$event['status']}}">

                                                <select name="status" id="input-status" class="form-control " placeholder="{{ __('Please select the status of this course') }}" required>
                                                    <option selected disabled value="">Please select the status of this course</option>
                                                    <option <?= ($event['status'] == 4) ? "selected" : ''; ?> value="4">{{ __('My Account Only') }}</option>
                                                    <option <?= ($event['status'] == 2) ? "selected" : ''; ?> value="2">{{ __('Soldout') }}</option>
                                                    <option <?= ($event['status'] == 3) ? "selected" : ''; ?> value="3">{{ __('Completed') }}</option>
                                                    <option <?= ($event['status'] == 0) ? "selected" : ''; ?> value="0">{{ __('Open') }}</option>
                                                    <option <?= ($event['status'] == 1) ? "selected" : ''; ?> value="1">{{ __('Close') }}</option>
                                                    <option <?= ($event['status'] == 5) ? "selected" : ''; ?> value="5">{{ __('Waiting') }}</option>
                                                </select>

                                                @include('alerts.feedback', ['field' => 'status'])
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row form-group">

                                            <div class="form-group col-12">


                                                <?php
                                                    //dd(isset($info['course_hours_icon']));

                                                    // if(isset($info['course_hours_icon']) && $info['course_hours_icon'] != null){
                                                    //     dd('asd');
                                                    //     $course_hours_icon = json_decode($info['course_hours_icon'], true);
                                                    // }else{
                                                    //     $course_hours_icon = null;
                                                    // }
                                                    // //dd($course_hours_icon);
                                                    //dd($info);
                                                ?>

                                                <div class="row">

                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <h3 class="mb-0 title">{{ __('Course Hours') }} (course_hours)</h3>

                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="hours" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if(isset($info['hours']['icon']) && $info['hours']['icon']['path'] != null)
                                                                    <img src="{{ asset($info['hours']['icon']['path']) }}"/>
                                                                @else

                                                                    <img src="/theme/assets/images/icons/Start-Finish.svg"/>

                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('hours_icon_path', (isset($info['hours']['icon']) && $info['hours']['icon'] != null) ? $info['hours']['icon']['path'] : '' ) }}" id="hours_path" name="course[{{'hours'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('hours_icon_alt_text', (isset($info['hours']['icon']) && $info['hours']['icon']['alt_text'] != '') ? $info['hours']['icon']['alt_text'] : '' ) }}" id="hours_alt_text" name="course[{{'hours'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'hours'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>

                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($info['hours']['icon']['link_status']) && $info['hours']['icon']['link_status'] == 'off') || !isset($info['hours']['icon']['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'hours'}}][{{'icon'}}][{{'link'}}]" value="{{ old('hours_icon_link', (isset($info['hours']['icon']) && $info['hours']['icon'] != null && isset($info['hours']['icon']['link'])) ? $info['hours']['icon']['link'] : '' ) }}">
                                                    </div>

                                                    <!-- <div class="col-12 col-md-auto col-lg-auto">
                                                        <div class="row">


                                                        </div>

                                                    </div> -->


                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12 col-md-6 col-lg-4 hours-input-wrapper">
                                                <input type="text" id="input-hours" name="course[{{'hours'}}][{{'hour'}}]" class="form-control" placeholder="{{ __('Course Hours') }}" value="{{ old('hours', (isset($info['hours']['hour']) && $info['hours']['hour'] != null) ? $info['hours']['hour'] : '' ) }}" autofocus>

                                            </div>
                                            <div class="form-group col-sm-12 col-md-6 col-lg-8 hours-input-wrapper">
                                                <button id="calculate-total-hours-btn" type="button" class="btn btn-outline-primary">Automatically calculate & add lessons hours</button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-12 col-md-6 col-lg-4 hours-input-wrapper">

                                                <!-- <label class="form-control-label">Hours Title (course_hours_title)</label> -->

                                                <input type="text" id="input-hours-title" name="course[{{'hours'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Hours Title') }}" value="{{ old('hours-title', (isset($info['hours']['title']) && $info['hours']['title'] != null) ? $info['hours']['title'] : '' ) }}" autofocus>


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-12 hours-input-wrapper">

                                                <label class="form-control-label">Hours Text (course_hours_text)</label>

                                                <!-- anto's editor -->
                                                <input class="hidden" id="input-hours-text" name="course[{{'hours'}}][{{'text'}}]" value="{{ old('hours_text', (isset($info['hours']['text']) && $info['hours']['text'] != null) ? $info['hours']['text'] : '' ) }}"/>
                                                <?php $data = isset($info['hours']['text']) && $info['hours']['text'] != null ? $info['hours']['text'] : '' ?>
                                                @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-hours_title", 'data'=> "$data", 'inputname' => "'course[hours][text]'" ])
                                                <!-- anto's editor -->

                                                {{--<input style="background: aliceblue;" type="text" name="course[{{'hours'}}][{{'text'}}]" id="input-hours-text" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('hours_text', (isset($info['hours']['text']) && $info['hours']['text'] != null) ? $info['hours']['text'] : '' ) }}" autofocus>--}}


                                            </div>
                                        </div>

                                        <div class="row">

                                            <?php
                                                $visible_hours = (isset($info['hours']['visible'])) ? $info['hours']['visible'] : null;
                                            ?>

                                            <div class="form-group col-12 accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_hours != null && $visible_hours['landing']) ? 'checked' : '' }} name="course[{{'hours'}}][{{'visible'}}][{{'landing'}}]" id="hours_landing" type="checkbox">
                                                                        <label class="custom-control-label" for="hours_landing">Course landing page (summary)</label>
                                                                    </div>

                                                                    </div>

                                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_hours != null && $visible_hours['home']) ? 'checked' : '' }} name="course[{{'hours'}}][{{'visible'}}][{{'home'}}]" id="hours_home" type="checkbox">
                                                                        <label class="custom-control-label" for="hours_home">Course box in home page</label>
                                                                    </div>

                                                                    </div>

                                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_hours != null && $visible_hours['list']) ? 'checked' : '' }} name="course[{{'hours'}}][{{'visible'}}][{{'list'}}]" id="hours_list" type="checkbox">
                                                                        <label class="custom-control-label" for="hours_list">Course box in list page</label>
                                                                    </div>

                                                                    </div>

                                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_hours != null && $visible_hours['invoice']) ? 'checked' : '' }} name="course[{{'hours'}}][{{'visible'}}][{{'invoice'}}]" id="hourls_invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="hourls_invoice">Invoice description</label>
                                                                    </div>

                                                                    </div>

                                                                    <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_hours != null && $visible_hours['emails']) ? 'checked' : '' }} name="course[{{'hours'}}][{{'visible'}}][{{'emails'}}]" id="hours_emails" type="checkbox">
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
                                        <!-- Language section -->
                                        <div class="row">
                                            <?php
                                                if(isset($info['language']['icon']) && $info['language']['icon'] != null){
                                                    $course_language_icon = $info['language']['icon'];
                                                }else{
                                                    $course_language_icon = null;
                                                }
                                            ?>

                                            <div class="form-group col-12">

                                                <div class="row form-group">
                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <h3 class="mb-0 title">{{ __('Course Language') }} (course_language)</h3>
                                                    </div>


                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="language" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_language_icon != null && $course_language_icon['path'] != null)
                                                                    <img src="{{ asset($course_language_icon['path']) }}"/>
                                                                @else
                                                                    <img src="/theme/assets/images/icons/Language.svg" alt="">
                                                                @endif

                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('language_icon_path', ($course_language_icon != null) ? $course_language_icon['path'] : '' ) }}" id="language_path" name="course[{{'language'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('language_icon_alt_text', ($course_language_icon != null) ? $course_language_icon['alt_text'] : '' ) }}" id="language_alt_text" name="course[{{'language'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'language'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_language_icon['link_status']) && $course_language_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_language_icon['link_status']) && $course_language_icon['link_status'] == 'off') || (!isset($course_language_icon['link_status']))) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'language'}}][{{'icon'}}][{{'link'}}]" value="{{ old('language_icon_link', (isset($course_language_icon) && $course_language_icon != null && isset($course_language_icon['link'])) ? $course_language_icon['link'] : '' ) }}">
                                                    </div>

                                                    <!-- <div class="col-12 col-md-auto col-lg-auto">
                                                        <div class="row">


                                                        </div>



                                                    </div> -->


                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-4 language-input-wrapper">

                                                        <!-- <label class="form-control-label">Language Title (course_language_title)</label> -->

                                                        <input type="text" id="input-language-title" name="course[{{'language'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Language Title') }}" value="{{ old('language-title', (isset($info['language']['title']) && $info['language']['title'] != null) ? $info['language']['title'] : '' ) }}" autofocus>

                                                    </div>

                                                </div>
                                            </div>






                                            <div class="form-group col-12">


                                                <!-- anto's editor -->
                                                <input class="hidden" id="input-language" name="course[{{'language'}}][{{'text'}}]" value="{{ old('language_text', (isset($info['language']['text']) && $info['certificate']['messages']['success'] != null) ? $info['certificate']['messages']['success'] : '') }}"/>
                                                <?php $data = isset($info['language']['text']) && $info['language']['text'] != null ? $info['language']['text'] : '' ?>
                                                @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-language_title", 'data'=> "$data", 'inputname' => "'course[language][text]'" ])
                                                <!-- anto's editor -->


                                                {{--<input type="text" id="input-language" name="course[{{'language'}}][{{'text'}}]" class="form-control" value="{{ old('language',  (isset($info['language']['text']) && $info['language']['text'] != null) ? $info['language']['text'] : '' )}} " placeholder="{{ __('Language') }}" autofocus>--}}
                                            </div>

                                        </div>

                                        <div class="row">
                                            <?php
                                                $visible_language = (isset($info['language']['visible'])) ? $info['language']['visible'] : null;
                                            ?>


                                            <div class="form-group col-12 accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_language != null && $visible_language['landing']) ? 'checked' : '' }} name="course[{{'language'}}][{{'visible'}}][{{'landing'}}]" id="language_landing" type="checkbox">
                                                                        <label class="custom-control-label" for="language_landing">Course landing page (summary)</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_language != null && $visible_language['home']) ? 'checked' : '' }} name="course[{{'language'}}][{{'visible'}}][{{'home'}}]" id="language_home" type="checkbox">
                                                                        <label class="custom-control-label" for="language_home">Course box in home page</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_language != null && $visible_language['list']) ? 'checked' : '' }} name="course[{{'language'}}][{{'visible'}}][{{'list'}}]" id="language_list" type="checkbox">
                                                                        <label class="custom-control-label" for="language_list">Course box in list page</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_language != null && $visible_language['invoice']) ? 'checked' : '' }} name="course[{{'language'}}][{{'visible'}}][{{'invoice'}}]" id="language_invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="language_invoice">Invoice description</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_language != null && $visible_language['emails']) ? 'checked' : '' }} name="course[{{'language'}}][{{'visible'}}][{{'emails'}}]" id="language_emails" type="checkbox">
                                                                        <label class="custom-control-label" for="language_emails">Automated emails</label>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- End Language Section -->

                                        <hr>

                                        <!-- Delivery Section -->

                                        <div class="row">

                                            <?php

                                                if(isset($info['delivery_icon']) && $info['delivery_icon'] != null){
                                                    $course_delivery_icon = $info['delivery_icon'];
                                                }else{
                                                    $course_delivery_icon = null;
                                                }


                                                $delivery_text = $info['delivery_info']['text'];
                                                $delivery_title = $info['delivery_info']['title'];
                                                $delivery_visible = $info['delivery_info']['visible'];
                                            ?>

                                            <div class="form-group col-12">

                                                <div class="input-group">
                                                    <h3 class="mb-0 title">{{ __('Course Delivery') }} (course_delivery)</h3>
                                                </div>
                                            </div>

                                            <div class="col-12 form-group">

                                                <div class="row form-group">

                                                    <div class="col-9 col-md-6 col-lg-4 {{ $errors->has('delivery') ? ' has-danger' : '' }}">

                                                        <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" required>
                                                            <option disabled selected value="">Please select where this course takes place</option>
                                                            @foreach ($delivery as $delivery)
                                                                <option <?php if(count($event->delivery) != 0){
                                                                    if($event->delivery[0]->id == $delivery->id){
                                                                        echo 'selected';
                                                                    }else{
                                                                        echo '';
                                                                    }
                                                                }
                                                                ?> value="{{ $delivery->id }}" >{{ $delivery->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="delivery" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_delivery_icon != null && $course_delivery_icon['path'] != null)
                                                                    <img src="{{ asset($course_delivery_icon['path']) }}"/>
                                                                @else
                                                                    <i class="fa fa-university"></i>

                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('course_delivery_icon_path', ($course_delivery_icon != null && $course_delivery_icon['path'] != '') ? $course_delivery_icon['path'] : '' ) }}" id="delivery_path" name="course[{{'delivery_icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('course_delivery_icon_alt_text', ($course_delivery_icon != null && $course_delivery_icon['alt_text'] != '') ? $course_delivery_icon['alt_text'] : '' ) }}" id="delivery_alt_text" name="course[{{'delivery_icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery_icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_delivery_icon['link_status']) && $course_delivery_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 align-self-center input @if((isset($course_delivery_icon['link_status']) && $course_delivery_icon['link_status'] == 'off') || !isset($course_delivery_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery_icon'}}][{{'link'}}]" value="{{ old('delivery_icon_link', (isset($course_delivery_icon) && $course_delivery_icon != null && isset($course_delivery_icon['link'])) ? $course_delivery_icon['link'] : '' ) }}">
                                                    </div>


                                                    @include('alerts.feedback', ['field' => 'delivery'])
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-sm-12 col-md-6 col-lg-4 expiration-input-wrapper">

                                                        <label class="form-control-label">Course Delivery Title</label>

                                                        <input type="text" id="input-delivery-title" name="course[{{'delivery_info'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course Delivery Title') }}" value="{{ old('delivery_title', (isset($delivery_title) ? $delivery_title : '' )) }}" autofocus>

                                                    </div>

                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-12">

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-delivery-text" name="course[{{'delivery_info'}}][{{'text'}}]" value="{{ isset($delivery_text) ? $delivery_text : '' }}"/>
                                                        <?php $data = isset($delivery_text) ? $delivery_text : '' ?>
                                                        @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-delivery_title", 'data'=> "$data", 'inputname' => "'course[delivery_info][text]'" ])
                                                        <!-- anto's editor -->

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <?php

                                                        $visible_delivery = (isset($info['delivery_info']['visible']) && $info['delivery_info']['visible'] != null) ? $info['delivery_info']['visible'] : null;

                                                    ?>



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
                                                                                <input class="custom-control-input" {{ ($visible_delivery != null && $visible_delivery['landing']) ? 'checked' : '' }} name="course[{{'delivery_info'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing1" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-landing1">Course landing page (summary)</label>
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

                                        <!-- End Delivery Section -->

                                        <!-- City Section -->

                                        <div class="row">

                                            <?php $eventCity = isset($event) && $event->city->first() ? $event->city->first()->id : -1 ?>

                                            <?php

                                                if(isset($info['inclass']['city']['icon']) && $info['inclass']['city']['icon'] != null){
                                                    $course_inclass_city_icon = $info['inclass']['city']['icon'];
                                                }else{

                                                    $course_inclass_city_icon = null;
                                                }

                                            ?>


                                            <div class="col-12 delivery_child_wrapper <?= isset($event->delivery->first()['id']) && ( $event->delivery->first()['id'] == 139 || $event->delivery->first()['id'] == 215) ? '' : 'd-none' ?>">
                                                <div class="row delivery_city_wrapper form-group<?= isset($event->delivery->first()['id']) && $event->delivery->first()['id'] == 215 ? 'd-none' : '' ?>">

                                                    <div class="col-9 col-md-6 col-lg-4 {{ $errors->has('city_id') ? ' has-danger' : '' }} ">
                                                            <!-- <div class="col-sm-12 col-md-6 col-lg-3 form-group{{ $errors->has('city_id') ? ' has-danger' : '' }} "> -->
                                                            <select name="city_id" id="input-city_id" class="form-control" placeholder="{{ __('Please select the city of this course') }}" >
                                                                <option selected disabled value="">Please select the city of this course</option>
                                                                @foreach ($cities as $city)

                                                                    <option value="{{$city->id}}" @if($city->id == $eventCity) selected @endif> {{ $city->name }} </option>

                                                                @endforeach
                                                            </select>

                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto">
                                                        <span class="input-icon-wrapper-city" data-infowrapper="inclass_city">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_inclass_city_icon && $course_inclass_city_icon != null && $course_inclass_city_icon['path'] != null)
                                                                    <img src="{{ asset($course_inclass_city_icon['path']) }}" alt="{{ $course_inclass_city_icon != null && $course_inclass_city_icon['alt_text'] != '' ? $course_inclass_city_icon['alt_text'] : '' }}"/>
                                                                @else
                                                                    <img src="/theme/assets/images/icons/marker.svg" alt="">
                                                                @endif
                                                            </span>
                                                            <!-- (course_inclass_city) -->
                                                        </span>

                                                        <input type="hidden" value="{{ old('inclass_city_icon_path', ($course_inclass_city_icon != null && $course_inclass_city_icon['path'] != '') ? $course_inclass_city_icon['path'] : '' ) }}" id="inclass_city_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('inclass_city_icon_alt_text', ($course_inclass_city_icon != null && $course_inclass_city_icon['alt_text'] != '') ? $course_inclass_city_icon['alt_text'] : '' ) }}" id="inclass_city_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'alt_text'}}]">


                                                    </div>
                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center d-none">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_inclass_city_icon['link_status']) && $course_inclass_city_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>


                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center d-none @if((isset($course_inclass_city_icon['link_status']) && $course_inclass_city_icon['link_status'] == 'off') || !isset($course_inclass_city_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'city'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link', (isset($course_inclass_city_icon) && $course_inclass_city_icon != null && isset($course_inclass_city_icon['link'])) ? $course_inclass_city_icon['link'] : '' ) }}">
                                                    </div>
                                                    <!-- <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                                        <div class="row">

                                                        </div>

                                                    </div> -->



                                                </div>
                                                <div class="row form-group">
                                                    <?php

                                                        $dates = (isset($info['inclass']['dates']) && $info['inclass']['dates'] != null) ? $info['inclass']['dates'] : null;
                                                        $visible_dates = (isset($info['inclass']['dates']['visible'])) ? $info['inclass']['dates']['visible'] : null;
                                                        //$visible_dates = (isset($dates) && isset($dates['visible'])) ? json_decode($dates['visible'], true) : null;
                                                    ?>

                                                    <?php

                                                        if(isset($dates['icon'])){
                                                            $course_inclass_dates_icon = $dates['icon'];
                                                        }else{
                                                            $course_inclass_dates_icon = null;
                                                        }
                                                    ?>

                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <label class="form-control-label">{{ __('(course_inclass_dates)') }}</label>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="inclass_dates" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_inclass_dates_icon != null && $course_inclass_dates_icon['path'] != null)
                                                                    <img src="{{ asset($course_inclass_dates_icon['path']) }}"/>
                                                                @else
                                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Duration_Hours.svg" alt="">

                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('inclass_dates_icon_path', ($course_inclass_dates_icon != null && $course_inclass_dates_icon['path'] != '') ? $course_inclass_dates_icon['path'] : '' ) }}" id="inclass_dates_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('inclass_dates_icon_alt_text', ($course_inclass_dates_icon != null && $course_inclass_dates_icon['alt_text'] != '') ? $course_inclass_dates_icon['alt_text'] : '' ) }}" id="inclass_dates_alt_text" id="inclass_dates_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-auto col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_inclass_dates_icon['link_status']) && $course_inclass_dates_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>

                                                    </div>
                                                        {{--dd($course_inclass_dates_icon)--}}
                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if($course_inclass_dates_icon == null || (isset($course_inclass_dates_icon['link_status']) && $course_inclass_dates_icon['link_status'] == 'off') || !isset($course_inclass_dates_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'icon'}}][{{'link'}}]" value="{{ old('course_inclass_dates', (isset($course_inclass_dates_icon) && $course_inclass_dates_icon != null && isset($course_inclass_dates_icon['link'])) ? $course_inclass_dates_icon['link'] : '' ) }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                                        <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                                        <input type="text" id="input-dates-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Dates Title') }}" value="{{ old('date_title', (isset($dates) && isset($dates['title']) ) ? $dates['title'] : '') }}" autofocus>

                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-12">

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-dates" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" value="{{ (isset($dates) && isset($dates['text']) ) ? $dates['text'] : '' }}"/>
                                                        <?php $data = (isset($dates) && isset($dates['text'])) ? $dates['text'] : '' ?>
                                                        @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-dates_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][dates][text]'" ])
                                                        <!-- anto's editor -->


                                                        {{--<input type="text" class="form-control" value="{{ (isset($dates) && isset($dates['text']) ) ? $dates['text'] : '' }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'text'}}]" placeholder="Dates(from/to)">--}}

                                                    </div>
                                                </div>
                                                <div class="row">

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
                                                                                <input class="custom-control-input" {{ ($visible_dates != null && $visible_dates['landing']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'landing'}}]" id="input-delivery-landing" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-landing">Course landing page (summary)</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_dates != null && $visible_dates['home']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'home'}}]" id="input-delivery-home" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-home">Course box in home page</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_dates != null && $visible_dates['list']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'list'}}]" id="input-delivery-list" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-list">Course box in list page</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_dates != null && $visible_dates['invoice']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'invoice'}}]" id="input-delivery-invoice" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-invoice">Invoice description</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_dates != null && $visible_dates['emails']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'dates'}}][{{'visible'}}][{{'emails'}}]" id="input-delivery-emails" type="checkbox">
                                                                                <label class="custom-control-label" for="input-delivery-emails">Automated emails</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row form-group">
                                                    <?php

                                                        $days = (isset($info['inclass']['days']) && $info['inclass']['days'] != null) ? $info['inclass']['days'] : null;
                                                        $visible_days = ( $days != null && isset($days['visible']) && $days['visible'] != null) ? $days['visible'] : null;

                                                    ?>
                                                    <?php

                                                        if(isset($days['icon']) && $days['icon'] != null){
                                                            $course_inclass_day_icon = $days['icon'];
                                                        }else{
                                                            $course_inclass_day_icon = null;
                                                        }
                                                    ?>


                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <label class="form-control-label">{{ __('(course_inclass_days)') }}</label>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="inclass_day" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_inclass_day_icon != null && isset($course_inclass_day_icon['path']) && $course_inclass_day_icon['path'] != null)
                                                                    <img src="{{ asset($course_inclass_day_icon['path']) }}"/>
                                                                @else
                                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_inclass_day_icon['link_status']) && $course_inclass_day_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_inclass_day_icon['link_status']) && $course_inclass_day_icon['link_status'] == 'off') || !isset($course_inclass_day_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link', (isset($course_inclass_day_icon) && $course_inclass_day_icon != null && isset($course_inclass_day_icon['link'])) ? $course_inclass_day_icon['link'] : '' ) }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                                        <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                                        <input type="text" id="input-day-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Day Title') }}" value="{{ old('day_title', (isset($days) && isset($days['title']) ) ? $days['title'] : '') }}" autofocus>

                                                    </div>

                                                </div>
                                                <div class="row">

                                                    <div class="col-12">

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-days" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'text'}}]" value="{{ (isset($days) && isset($days['text']) ) ? $days['text'] : '' }}"/>
                                                        <?php $data = isset($days) && $days['text'] != null ? $days['text'] : '' ?>
                                                        @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-language_days_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][day][text]'" ])
                                                        <!-- anto's editor -->


                                                        {{--<input type="text" class="form-control" value="{{ (isset($days) && isset($days['text']) ) ? $days['text'] : '' }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'text'}}]" placeholder="Day" >--}}
                                                    </div>








                                                    <div class="form-group col-12 accordion" id="accordionExample">
                                                        <div class="card">
                                                            <div class="card-header" id="headingOne1" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                                                                <h5 class="mb-0">Visible on:</h5>
                                                            </div>
                                                            <div id="collapseOne1" class="collapse" aria-labelledby="headingOne1" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_days != null && $visible_days['landing']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'landing'}}]" id="input-day-landing" type="checkbox">
                                                                                <label class="custom-control-label" for="input-day-landing">Course landing page (summary)</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_days != null && $visible_days['home']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'home'}}]" id="input-day-home" type="checkbox">
                                                                                <label class="custom-control-label" for="input-day-home">Course box in home page</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_days != null && $visible_days['list']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'list'}}]" id="input-day-list" type="checkbox">
                                                                                <label class="custom-control-label" for="input-day-list">Course box in list page</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_days != null && $visible_days['invoice']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'invoice'}}]" id="input-day-invoice" type="checkbox">
                                                                                <label class="custom-control-label" for="input-day-invoice">Invoice description</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_days != null && $visible_days['emails']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'day'}}][{{'visible'}}][{{'emails'}}]" id="input-day-emails" type="checkbox">
                                                                                <label class="custom-control-label" for="input-day-emails">Automated emails</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row form-group">

                                                    <?php

                                                        $times = (isset($info['inclass']['times']) && $info['inclass']['times'] != null) ? $info['inclass']['times'] : null;

                                                        //$visible_times = (isset($times) && isset($times['visible'])) ? json_decode($times['visible'], true) : null;
                                                        $visible_times = ($times != null && isset($times['visible']) && $times['visible'] != null) ? $times['visible'] : null;
                                                    ?>

                                                    <?php

                                                        if(isset($times['icon']) && $times['icon'] != null){
                                                            $course_inclass_times_icon = $times['icon'];
                                                        }else{
                                                            $course_inclass_times_icon = null;
                                                        }

                                                    ?>

                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <label class="form-control-label">{{ __('(course_inclass_times)') }}</label>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="inclass_times" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_inclass_times_icon != null && $course_inclass_times_icon['path'] != null)
                                                                    <img src="{{ asset($course_inclass_times_icon['path']) }}"/>
                                                                @else
                                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">
                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('inclass_times_icon_path', ($course_inclass_times_icon != null && $course_inclass_times_icon['path'] != '') ? $course_inclass_times_icon['path'] : '' ) }}" id="inclass_times_path" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('inclass_times_icon_alt_text', ($course_inclass_times_icon != null && $course_inclass_times_icon['alt_text'] != '') ? $course_inclass_times_icon['alt_text'] : '' ) }}" id="inclass_times_alt_text" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">
                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ ((isset($course_inclass_times_icon['link_status']) && $course_inclass_times_icon['link_status'] == 'on') ) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_inclass_times_icon['link_status']) && $course_inclass_times_icon['link_status'] == 'off') || !isset($course_inclass_times_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'icon'}}][{{'link'}}]" value="{{ old('certificate_icon_link', (isset($course_inclass_times_icon) && $course_inclass_times_icon != null && isset($course_inclass_times_icon['link'])) ? $course_inclass_times_icon['link'] : '' ) }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-sm-12 col-md-6 col-lg-4">

                                                        <!-- <label class="form-control-label">Months access title (course_elearning_expiration_title)</label> -->

                                                        <input type="text" id="input-time-title" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Time Title') }}" value="{{ old('time_title', (isset($times) && isset($times['title']) ) ? $times['title'] : '') }}" autofocus>

                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-12">


                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-times" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" value="{{ old('times', (isset($times) && $times['text']) ? $times['text'] : '' ) }}"/>
                                                        <?php $data = isset($times) && $times['text'] != null ? $times['text'] : '' ?>
                                                        @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-times_title", 'data'=> "$data", 'inputname' => "'course[delivery][inclass][times][text]'" ])
                                                        <!-- anto's editor -->


                                                        {{--<input type="text" class="form-control" value="{{ old('times', (isset($times['text']) && $times['text']) ? $times['text'] : '' ) }}" name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'text'}}]" placeholder="Times(from/to)">--}}

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
                                                                                <input class="custom-control-input" {{ ($visible_times != null && $visible_times['landing']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'landing'}}]" id="input-times-landing" type="checkbox">
                                                                                <label class="custom-control-label" for="input-times-landing">Course landing page (summary)</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_times != null && $visible_times['home']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'home'}}]" id="input-times-home" type="checkbox">
                                                                                <label class="custom-control-label" for="input-times-home">Course box in home page</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_times != null && $visible_times['list']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'list'}}]" id="input-times-list" type="checkbox">
                                                                                <label class="custom-control-label" for="input-times-list">Course box in list page</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_times != null && $visible_times['invoice']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'invoice'}}]" id="input-times-invoice" type="checkbox">
                                                                                <label class="custom-control-label" for="input-times-invoice">Invoice description</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_times != null && $visible_times['emails']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'inclass'}}][{{'times'}}][{{'visible'}}][{{'emails'}}]" id="input-times-emails" type="checkbox">
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
                                                    <div class="form-group form-group col-sm-12 col-md-6 col-lg-4">
                                                        <label class="form-control-label" for="input-hours">{{ __('Absences Limit(%)') }}(course_inclass_absences)</label>
                                                        <div class="input-group">
                                                            <input type="text" name="course[{{'delivery'}}][{{'inclass'}}][{{'absences'}}]" id="input-absences_limit" class="form-control{{ $errors->has('Absences Limit(%)') ? ' is-invalid' : '' }}" placeholder="{{ __('absences_limit') }}" value="{{ old('$course_inclass_absences', (isset($info['inclass']['absences']) && $info['inclass']['absences'] != null) ? $info['inclass']['absences'] : 0 ) }}"autofocus>
                                                            <!-- <span class="input-group-addon input-group-append">
                                                                <span class="btn btn-outline-primary input-icon"> <span class="fa fa-calendar d-none"></span></span>
                                                            </span> -->
                                                        </div>
                                                        {{--@include('alerts.feedback', ['field' => 'absences_imit'])--}}
                                                    </div>
                                                </div>



                                                <div class="row access-student-wrapper">

                                                    <?php

                                                        if(isset($info['inclass']['elearning_access_icon']) && $info['inclass']['elearning_access_icon'] != null){
                                                            $course_free_access_icon = $info['inclass']['elearning_access_icon'];
                                                        }else{
                                                            $course_free_access_icon = null;
                                                        }

                                                    ?>

                                                    <div class="form-group col-12">

                                                        <div class="row">
                                                            <div class="col-9 col-md-auto col-lg-auto">
                                                                <h3 class="mb-0 title">{{ __('Course Access') }}</h3>
                                                            </div>

                                                            <div class="col-2 col-md-auto col-lg-auto">
                                                                <span data-infowrapper="free_courses" class="input-group-addon input-group-append input-icon-wrapper">
                                                                    <span class="btn btn-outline-primary input-icon">

                                                                        @if($course_free_access_icon != null && $course_free_access_icon['path'] != null)
                                                                            <img src="{{ asset($course_free_access_icon['path']) }}"/>
                                                                        @else
                                                                            <span class="fa fa-calendar"></span>
                                                                        @endif
                                                                    </span>
                                                                </span>
                                                            </div>


                                                            <input type="hidden" value="{{ old('$course_free_access_icon_path', ($course_free_access_icon != null && $course_free_access_icon['path'] != '') ? $course_free_access_icon['path'] : '' ) }}" id="free_courses_path" name="course[{{'free_courses'}}][{{'icon'}}][{{'path'}}]">
                                                            <input type="hidden" value="{{ old('$course_free_access_icon_alt_text', ($course_free_access_icon != null && $course_free_access_icon['alt_text'] != '') ? $course_free_access_icon['alt_text'] : '' ) }}" id="free_courses_alt_text" name="course[{{'free_courses'}}][{{'icon'}}][{{'alt_text'}}]">
                                                        </div>
                                                    </div>

                                                    <label class="form-control-label col-12" for="input-hours">{{ __('Add students to another course') }}</label>
                                                    <?php

                                                        $access_events = (isset($info['inclass']['elearning_access'])) ? $info['inclass']['elearning_access'] : null;
                                                        //dd($access_events);
                                                        $access_events_exams = (isset($info['inclass']['elearning_exam']) && $info['inclass']['elearning_exam']) ? true : false;
                                                    ?>
                                                    <div class="form-group col-12">
                                                        <span class="toggle-btn-inline-text">Would you like to let students access an e-learning course for free?</span>
                                                        <label id="access-student-toggle" class="custom-toggle enroll-toggle visible">
                                                            <input id="access-student" name="course[{{'free_courses'}}][{{'enabled'}}]" type="checkbox" {{ (isset($access_events) && count($access_events) != 0) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                    <div id="elearning_exams_wrapper" class="form-group col-12">
                                                        <span class="toggle-btn-inline-text">Exams for selected free course access?</span>
                                                        <label id="access-student-toggle" class="custom-toggle enroll-toggle visible">
                                                            <input id="access-student-exams" name="course[{{'free_courses'}}][{{'exams'}}]" type="checkbox" {{ (isset($access_events_exams) && $access_events_exams) ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>


                                                    @if(count($elearning_events) != 0)

                                                    <div class="free-course-wrapper">
                                                        <div class="form-group col-12">
                                                            <label class="form-control-label" for="exampleFormControlSelect3">Please select the courses you want to allow free access</label>
                                                            <select multiple="" name="course[{{'free_courses'}}][{{'list'}}][]" class="form-control" id="free_course_list">

                                                            @foreach($elearning_events as $elearning_event)
                                                                <option {{ (isset($access_events) && in_array($elearning_event['id'],$access_events)) ? 'selected' : '' }} value="{{ $elearning_event['id'] }}">{{ $elearning_event['title'] }}</option>
                                                            @endforeach
                                                            </select>
                                                            </div>

                                                    </div>
                                                    @endif

                                                </div>


                                            </div>

                                            <div class="exp_input col-12">
                                                <?php

                                                    $course_elearning_icon = (isset($info['elearning']['icon']) && $info['elearning']['icon']) ? $info['elearning']['icon'] : null;

                                                ?>

                                                <div class="row form-group">
                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <label class="form-control-label">{{ __('Months access') }} (course_elearning_expiration)</label>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="elearning" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if(isset($course_elearning_icon) &&  $course_elearning_icon != null && $course_elearning_icon['path'] != null)
                                                                    <img src="{{ asset($course_elearning_icon['path']) }}"/>
                                                                @else
                                                                    <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/Days-Week.svg" alt="">

                                                                @endif
                                                            </span>
                                                        </span>

                                                        <input type="hidden" value="{{ old('elearning_icon_path', ($course_elearning_icon != null && $course_elearning_icon['path'] != '') ? $course_elearning_icon['path'] : '' ) }}" id="elearning_path" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('elearning_icon_alt_text', ($course_elearning_icon != null && $course_elearning_icon['alt_text'] != '') ? $course_elearning_icon['alt_text'] : '' ) }}" id="elearning_alt_text" id="elearning_alt_text" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'alt_text'}}]">

                                                    </div>
                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">


                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_elearning_icon['link_status']) && $course_elearning_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>



                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_elearning_icon['link_status']) && $course_elearning_icon['link_status'] == 'off') || !isset($course_elearning_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'elearning'}}][{{'icon'}}][{{'link'}}]" value="{{ old('elearning_icon_link', ( $course_elearning_icon != null && isset($course_elearning_icon) && isset($course_elearning_icon['link'])) ? $course_elearning_icon['link'] : '' ) }}">
                                                    </div>




                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-12 col-md-6 col-lg-4">
                                                        <input type="number" min="1" name="course[{{'delivery'}}][{{'elearning'}}][{{'expiration'}}]" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration', (isset($info['elearning']['expiration']) && $info['elearning']['expiration'] != null) ? $info['elearning']['expiration'] : '' ) }}"autofocus>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-sm-12 col-md-6 col-lg-4 expiration-input-wrapper">

                                                        <label class="form-control-label">Months access title (course_elearning_expiration_title)</label>

                                                        <input type="text" id="input-elearning-exp-title" name="course[{{'delivery'}}][{{'elearning'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Month Access Title') }}" value="{{ old('expiration_title', (isset($info['elearning']['title']) && $info['elearning']['title'] != null) ? $info['elearning']['title'] : '' ) }}" autofocus>

                                                    </div>

                                                </div>


                                                <!-- <label class="form-control-label" for="input-expiration">{{ __('Months access') }} (course_elearning_expiration)</label> -->




                                            </div>
                                        </div>

                                        <!-- End City Section -->

                                        <div class="row">
                                            <div class="exp_input col-12 form-group">
                                                <label class="form-control-label" for="input-test">{{ __('Months access text') }} (course_elearning_expiration)</label>

                                                <!-- anto's editor -->
                                                <input class="hidden" id="elearning_exp" name="course[{{'delivery'}}][{{'elearning'}}][{{'text'}}]" value="{{ old('expiration_text', (isset($info['elearning']['text']) && $info['elearning']['text'] != null) ? $info['elearning']['text'] : '' ) }}"/>
                                                <?php $data = isset($info['elearning']['text']) && $info['elearning']['text'] != null ? $info['elearning']['text'] : '' ?>
                                                @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "elearning_exp_title", 'data'=> "$data", 'inputname' => "'course[delivery][elearning][text]'" ])
                                                <!-- anto's editor -->


                                            {{--<input style="background: aliceblue;" type="text" name="course[{{'delivery'}}][{{'elearning'}}][{{'text'}}]"  class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('expiration_text', (isset($info['elearning']['text']) && $info['elearning']['text'] != null) ? $info['elearning']['text'] : '' ) }}"autofocus>--}}                                                </div>
                                        </div>






                                        <?php
                                            $visible_elearning = (isset($info['elearning']['visible'])) ? $info['elearning']['visible'] : null;
                                        ?>
                                        <div class="row elearning_visible_wrapper @if(isset($event['delivery'][0]['id']) && $event['delivery'][0]['id'] != 143) ? 'd-none' : '' @endif">



                                            <div class="form-group col-12 accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" id="headingEight" data-toggle="collapse" data-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                                                        <h5 class="mb-0">Visible on:</h5>
                                                    </div>
                                                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_elearning != null && $visible_elearning['landing']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'landing'}}]" id="input-elearning-landing" type="checkbox">
                                                                        <label class="custom-control-label" for="input-elearning-landing">Course landing page (summary)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_elearning != null && $visible_elearning['home']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'home'}}]" id="input-elearning-home" type="checkbox">
                                                                        <label class="custom-control-label" for="input-elearning-home">Course box in home page</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_elearning != null && $visible_elearning['list']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'list'}}]" id="input-elearning-list" type="checkbox">
                                                                        <label class="custom-control-label" for="input-elearning-list">Course box in list page</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_elearning != null && $visible_elearning['invoice']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'invoice'}}]" id="input-elearning-invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="input-elearning-invoice">Invoice description</label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">
                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_elearning != null && $visible_elearning['emails']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'visible'}}][{{'emails'}}]" id="input-elearning-emails" type="checkbox">
                                                                        <label class="custom-control-label" for="input-elearning-emails">Automated emails</label>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>




                                            <?php
                                                $visible_elearning_exam = (isset($info['elearning']['exam']['visible'])) ? $info['elearning']['exam']['visible'] : null;
                                            ?>
                                            <div class="row elearning_exam_visible_wrapper @if(isset($event['delivery'][0]['id']) && $event['delivery'][0]['id'] != 143) ? 'd-none' : '' @endif">

                                                <div class="exam_input col-12">
                                                    <?php

                                                        $course_elearning_exam_icon = (isset($info['elearning']['exam']['icon']) && $info['elearning']['exam']['icon']) ? $info['elearning']['exam']['icon'] : null;

                                                    ?>
                                                    <div class="row form-group">
                                                        <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                            <label class="form-control-label" for="input-expiration">{{ __('Online Exam') }} (course_elearning_exam_text)</label>
                                                        </div>

                                                        <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                            <span data-infowrapper="elearning_exam" class="input-group-addon input-group-append input-icon-wrapper-inclass">
                                                                <span class="btn btn-outline-primary input-icon">

                                                                    @if(isset($course_elearning_exam_icon) &&  $course_elearning_exam_icon != null && $course_elearning_exam_icon['path'] != null)
                                                                        <img class="replace-with-svg" width="20" src="{{ asset($course_elearning_exam_icon['path']) }}"/>
                                                                    @else
                                                                        <img class="replace-with-svg" width="20" src="/theme/assets/img/summary_icons/messages-warning-information.svg" alt="">

                                                                    @endif
                                                                </span>
                                                            </span>
                                                            <input type="hidden" value="{{ old('elearning_icon_exam_path', ($course_elearning_exam_icon != null && $course_elearning_exam_icon['path'] != '') ? $course_elearning_exam_icon['path'] : '' ) }}" id="elearning_exam_path" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'path'}}]">
                                                            <input type="hidden" value="{{ old('elearning_icon_exam_alt_text', ($course_elearning_exam_icon != null && $course_elearning_exam_icon['alt_text'] != '') ? $course_elearning_exam_icon['alt_text'] : '' ) }}" id="elearning_exam_alt_text" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'alt_text'}}]">
                                                        </div>

                                                        <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                                            <label class="custom-toggle enroll-toggle visible">
                                                                <input class="icon_link" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_elearning_exam_icon['link_status']) && $course_elearning_exam_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                            </label>

                                                        </div>
                                                        <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_elearning_exam_icon['link_status']) && $course_elearning_exam_icon['link_status'] == 'off') || !isset($course_elearning_exam_icon['link_status'])) {{'d-none'}} @endif">
                                                            <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'icon'}}][{{'link'}}]" value="{{ old('elearning_icon_link', (isset($course_elearning_exam_icon) && $course_elearning_exam_icon != null && isset($course_elearning_exam_icon['link'])) ? $course_elearning_exam_icon['link'] : '' ) }}">
                                                        </div>



                                                    </div>

                                                    <div class="row form-group">
                                                        <div class="col-sm-12 col-md-6 col-lg-4 expiration-input-wrapper">

                                                            <label class="form-control-label">(course_elearning_exam_title)</label>

                                                            <input type="text" id="input-exam-title" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course exam title') }}" value="{{ old('exam_title', (isset($info['elearning']['exam']['title']) ? $info['elearning']['exam']['title'] : '' )) }}" autofocus>

                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 form-group">

                                                            <input class="hidden" id="input-exam" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'text'}}]" value="{{ old('exam', (isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null) ? $info['elearning']['exam']['text'] : '' ) }}"/>
                                                            <?php $data = isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null ? $info['elearning']['exam']['text'] : '' ?>
                                                            @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-exam_text", 'data'=> "$data", 'inputname' => "'course[delivery][elearning][exam][text]'" ])
                                                            <!-- anto's editor -->

                                                            {{--<input type="text" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'text'}}]" id="input-exam" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('exam', (isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null) ? $info['elearning']['exam']['text'] : '' ) }}"autofocus>--}}

                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="exam_input col-12 col-md-6 col-lg-4 form-group">
                                                            <label class="form-control-label" for="input-expiration">{{ __('Exam Activate Months') }} <br> {{ __('(course_elearning_exam_activate_months)') }}</label>
                                                            <div class="input-group">
                                                                <input type="number" min="1" name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'activate_months'}}]" id="input-exam-activate-months" class="form-control" placeholder="{{ __('Months') }}" value="{{ old('exam_activate_months', (isset($info['elearning']['exam']['activate_months']) && $info['elearning']['exam']['activate_months'] != null) ? $info['elearning']['exam']['activate_months'] : '' ) }}"autofocus>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- NOW -->




                                                 <!-- END NOW -->

                                                <div class="form-group col-12 accordion" id="accordionExample">
                                                    <div class="card">
                                                            <div class="card-header" id="headingNine" data-toggle="collapse" data-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                                                                <h5 class="mb-0">Visible on:</h5>
                                                            </div>
                                                            <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_elearning_exam != null && $visible_elearning_exam['landing']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'landing'}}]" id="input-elearning-exam-landing" type="checkbox">
                                                                                <label class="custom-control-label" for="input-elearning-exam-landing">Course landing page (summary)</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_elearning_exam != null && $visible_elearning_exam['home']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'home'}}]" id="input-elearning-exam-home" type="checkbox">
                                                                                <label class="custom-control-label" for="input-elearning-exam-home">Course box in home page</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_elearning_exam != null && $visible_elearning_exam['list']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'list'}}]" id="input-elearning-exam-list" type="checkbox">
                                                                                <label class="custom-control-label" for="input-elearning-exam-list">Course box in list page</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_elearning_exam != null && $visible_elearning_exam['invoice']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'invoice'}}]" id="input-elearning-exam-invoice" type="checkbox">
                                                                                <label class="custom-control-label" for="input-elearning-exam-invoice">Invoice description</label>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">
                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_elearning_exam != null && $visible_elearning_exam['emails']) ? 'checked' : '' }} name="course[{{'delivery'}}][{{'elearning'}}][{{'exam'}}][{{'visible'}}][{{'emails'}}]" id="input-elearning-exam-emails" type="checkbox">
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

                                        <div class="row course-payment-method">

                                            <?php

                                                if(isset($info['payment_icon']) && $info['payment_icon'] != null){
                                                    $course_payment_icon = $info['payment_icon'];
                                                }else{
                                                    $course_payment_icon = null;
                                                }
                                            ?>

                                            <div class="form-group col-12">

                                                <div class="row">

                                                    <div class="col-9 col-md-auto col-lg-auto">
                                                        <h3 class="mb-0 title" for="input-hours">{{ __('Course Payment Method') }} (course_payment_method)</h3>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto">
                                                        <span data-infowrapper="payment" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_payment_icon != null && $course_payment_icon['path'] != null)
                                                                    <img src="{{ asset($course_payment_icon['path']) }}" alt="{{ (isset($course_payment_icon['alt_text']) && $course_payment_icon['alt_text'] != null) ? $course_payment_icon['alt_text'] : '' }}"/>
                                                                @else
                                                                    <i class="ni ni-credit-card"></i>
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <input type="hidden" value="{{ old('course_payment_icon_path', ($course_payment_icon != null && $course_payment_icon['path'] != '') ? $course_payment_icon['path'] : '' ) }}" id="payment_path" name="course[{{'payment'}}][{{'icon'}}][{{'path'}}]">
                                                    <input type="hidden" value="{{ old('course_payment_icon_alt_text', ($course_payment_icon != null && $course_payment_icon['alt_text'] != '') ? $course_payment_icon['alt_text'] : '' ) }}" id="payment_alt_text" name="course[{{'payment'}}][{{'icon'}}][{{'alt_text'}}]">
                                                </div>
                                            </div>


                                            <div class="form-group col-12">
                                                <span class="toggle-btn-inline-text">Is this course free or paid?</span>
                                                <label class="custom-toggle enroll-toggle visible">
                                                    <input id="payment-method-toggle" value="on" name="course[{{'payment'}}][{{'paid'}}]" type="checkbox" {{($event['paymentMethod']->first()) ? 'checked=""' : ''}} >
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="Free" data-label-on="Paid"></span>
                                                </label>
                                            </div>


                                            @if(count($methods) != 0)
                                            <div class="col-sm-12 col-md-6 col-lg-4 form-group payment-method-wrapper @if(!$event['paymentMethod']->first()) {{ 'd-none' }} @endif">
                                                <select name="payment_method" id="input-method" class="form-control" placeholder="{{ __('Method Payment') }}" no-mouseflow>
                                                    <option value="" selected disabled>Please select the payment method of this course</option>
                                                    @foreach ($methods as $method)
                                                        <option value="{{ $method->id }}" {{$event['paymentMethod']->first() && $event['paymentMethod']->first()->id == $method->id ? 'selected' : ''}} >{{ $method->method_name }}</option>
                                                    @endforeach
                                                </select>

                                                @include('alerts.feedback', ['field' => 'payment_method'])
                                            </div>
                                            @endif
                                        </div>

                                        <hr>




                                        <div class="row course-partner-wrapper">
                                            <?php $eventPartners = isset($event)  ?  $event->partners->pluck('id')->toArray() : [] ?>

                                            <?php

                                                if(isset($info['partner']['icon']) && $info['partner']['icon'] != null){
                                                    $course_partner_icon = $info['partner']['icon'];
                                                }else{
                                                    $course_partner_icon = null;
                                                }
                                            ?>


                                            <div class="form-group col-12">

                                                <div class="row">

                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center" >
                                                        <h3 class="mb-0 title" for="input-hours">{{ __('Course Partners') }} (course_partner)</h3>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="partner" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">

                                                                @if($course_partner_icon != null && $course_partner_icon['path'] != null)
                                                                    <img src="{{ asset($course_partner_icon['path']) }}"/>
                                                                @else
                                                                    <span class="fa fa-calendar"></span>

                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('course_partner_icon_path', ($course_partner_icon != null && $course_partner_icon['path'] != '') ? $course_partner_icon['path'] : '' ) }}" id="partner_path" name="course[{{'partner'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('course_partner_icon_alt_text', ($course_partner_icon != null && $course_partner_icon['alt_text'] != '') ? $course_partner_icon['alt_text'] : '' ) }}" id="partner_alt_text" name="course[{{'partner'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'partner'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_partner_icon['link_status']) && $course_partner_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 align-self-center input @if((isset($course_partner_icon['link_status']) && $course_partner_icon['link_status'] == 'off') || !isset($course_partner_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'partner'}}][{{'icon'}}][{{'link'}}]" value="{{ old('hours_icon_link', (isset($course_partner_icon) && $course_partner_icon != null && isset($course_partner_icon['link'])) ? $course_partner_icon['link'] : '' ) }}">
                                                    </div>

                                                    <!-- <div class="col-12 col-md-auto col-lg-auto">
                                                        <div class="row">


                                                        </div>

                                                    </div> -->


                                                </div>

                                            </div>


                                            <div class="form-group col-12">
                                                <span class="toggle-btn-inline-text">Does this course have supporters/partners?</span>
                                                <label class="custom-toggle enroll-toggle visible">
                                                    <input id="partner-toggle" name="partner_enabled" type="checkbox" {{(count($eventPartners) != 0) ? 'checked=""' : ''}} >
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-4 form-group{{ $errors->has('partner_id') ? ' has-danger' : '' }} course-partner-list {{(count($eventPartners) == 0) ? 'd-none' : ''}} ">

                                                <select multiple name="partner_id[]" id="input-partner_id" class="form-control" placeholder="{{ __('Partner') }}" required>
                                                    @foreach ($partners as $partner)
                                                        <option @if(in_array($partner->id,$eventPartners)) selected @endif value="{{ $partner->id }}" >{{ $partner->name }}</option>
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'type_id'])

                                                <div style="margin-top:2rem;" class="">
                                                    <input class="hidden" id="input-partner" name="course[{{'partner'}}][{{'text'}}]" value="{{ old('partner_text', (isset($info['partner']['text']) && $info['partner']['text'] != null) ? $info['partner']['text'] : '' ) }}"/>
                                                    <?php $data = isset($info['partner']['text']) && $info['partner']['text'] != null ? $info['partner']['text'] : '' ?>
                                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-partner-text", 'data'=> "$data", 'inputname' => "'course[partner][text]'" ])
                                                    <!-- anto's editor -->
                                                </div>

                                                {{--<input type="text" name="course[{{'partner'}}][{{'text'}}]" id="input-partner" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('exam', (isset($info['elearning']['exam']['text']) && $info['elearning']['exam']['text'] != null) ? $info['elearning']['exam']['text'] : '' ) }}"autofocus>--}}


                                            </div>


                                                    <?php

                                                        $visible_partner = (isset($info['partner']['visible']) && $info['partner']['visible'] != null) ? $info['partner']['visible'] : null;

                                                    ?>



                                                    <div class="form-group col-12 accordion {{(count($eventPartners) != 0) ? '' : 'd-none'}} course-partner-list-visible" id="accordionExample">
                                                        <div class="card">
                                                            <div class="card-header" id="headingThree1" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                                <h5 class="mb-0">Visible on:</h5>
                                                            </div>
                                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_partner != null && $visible_partner['landing']) ? 'checked' : '' }} name="course[{{'partner'}}][{{'visible'}}][{{'landing'}}]" id="input-partner-landing" type="checkbox">
                                                                                <label class="custom-control-label" for="input-partner-landing">Course landing page (summary)</label>
                                                                            </div>

                                                                        </div>

                                                                        {{--<div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_partner != null && $visible_partner['home']) ? 'checked' : '' }} name="course[{{'partner'}}][{{'visible'}}][{{'home'}}]" id="input-partner-home" type="checkbox">
                                                                                <label class="custom-control-label" for="input-partner-home">Course box in home page</label>
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_partner != null && $visible_partner['list']) ? 'checked' : '' }} name="course[{{'partner'}}][{{'visible'}}][{{'list'}}]" id="input-partner-list" type="checkbox">
                                                                                <label class="custom-control-label" for="input-partner-list">Course box in list page</label>
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_partner != null && $visible_partner['invoice']) ? 'checked' : '' }} name="course[{{'partner'}}][{{'visible'}}][{{'invoice'}}]" id="input-partner-invoice" type="checkbox">
                                                                                <label class="custom-control-label" for="input-partner-invoice">Invoice description</label>
                                                                            </div>

                                                                        </div>

                                                                        <div class="col-sm-12 col-md-6 col-lg-2">

                                                                            <div class="custom-control custom-checkbox mb-3">
                                                                                <input class="custom-control-input" {{ ($visible_partner != null && $visible_partner['emails']) ? 'checked' : '' }} name="course[{{'partner'}}][{{'visible'}}][{{'emails'}}]" id="input-partner-emails" type="checkbox">
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

                                                <?php
                                                    if(isset($info['files_icon']) && $info['files_icon'] != null){
                                                        $course_files_icon = $info['files_icon'];
                                                    }else{
                                                        $course_files_icon = null;
                                                    }
                                                ?>

                                                <div class="row">
                                                    <div class="col-9 col-md-auto col-lg-auto">
                                                        <h3 class="mb-0 title" for="input-hours">{{ __('Course Files') }}</h3>
                                                    </div>


                                                    <div class="col-2 col-md-auto col-lg-auto">
                                                        <span data-infowrapper="files" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_files_icon != null && $course_files_icon['path'] != null)
                                                                    <img src="{{ asset($course_files_icon['path']) }}" alt="{{ (isset($course_files_icon['alt_text']) && $course_files_icon['alt_text'] != null) ? $course_files_icon['alt_text'] : ''  }}"/>
                                                                @else
                                                                    <img src="/theme/assets/images/icons/Access-Files.svg" alt="">
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <input type="hidden" value="" id="files_path" name="course[{{'files'}}][{{'icon'}}][{{'path'}}]">
                                                    <input type="hidden" value="" id="files_alt_text" name="course[{{'files'}}][{{'icon'}}][{{'alt_text'}}]">
                                                </div>
                                            </div>

                                            {{--<div class="form-group col-sm-12 col-md-6 col-lg-4">
                                                <label for="exampleFormControlSelect1">Select Dropbox Folder</label>
                                                <select class="form-control" name="folder_name" id="folder_name">

                                                    @foreach($folders as $folder)


                                                        <?php $found = false; ?>
                                                        @foreach($already_assign as $ass)
                                                            @if(isset($ass) && $ass['folder_name'] == $folder)
                                                            <?php $found = true; ?>

                                                            @endif
                                                        @endforeach
                                                        @if($found)
                                                        <?php //dd($folder); ?>
                                                            <option selected value="{{ $folder }}">{{ $folder }}</option>
                                                        @else
                                                            <option value="{{ $folder }}">{{ $folder }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'dropbox'])
                                            </div>--}}

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
                                                    <input class="form-control datepicker" id="input-release_date_file" name="release_date_files" placeholder="Select date" type="text" @if(old('release_date_files', $event->release_date_files) && old('release_date_files', $event->release_date_files) !='1970-01-01 00:00:00') value="{{ date('d-m-Y',strtotime(old('release_date_files', $event->release_date_files))) }}" @endif>
                                                </div>
                                                @include('alerts.feedback', ['field' => 'release_date_files'])
                                            </div>

                                        </div>

                                        <hr>

                                        <div class="row course-manager-wrapper">
                                            <?php

                                                if(isset($info['manager']['icon']) && $info['manager']['icon'] != null){
                                                    $course_manager_icon = $info['manager']['icon'];
                                                }else{
                                                    $course_manager_icon = null;
                                                }
                                            ?>
                                            <div class="form-group col-12">

                                                <div class="row">
                                                    <div class="col-9 col-md-auto col-lg-auto">
                                                        <h3 class="mb-0 title" >{{ __('Course Manager') }} (course_manager)</h3>
                                                    </div>


                                                    <div class="col-2 col-md-auto col-lg-auto">
                                                        <span data-infowrapper="manager" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_manager_icon != null && $course_manager_icon['path'] != null)
                                                                    <img src="{{ asset($course_manager_icon['path']) }}" alt="{{ (isset($course_manager_icon['alt_text']) && $course_manager_icon['alt_text'] != null) ? $course_manager_icon['alt_text'] : ''  }}"/>
                                                                @else
                                                                    <span class="fa fa-calendar"></span>
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <input type="hidden" value="{{ old('course_manager_icon_path', ($course_manager_icon != null && $course_manager_icon['path'] != '') ? $course_manager_icon['path'] : '' ) }}" id="manager_path" name="course[{{'manager'}}][{{'icon'}}][{{'path'}}]">
                                                    <input type="hidden" value="{{ old('course_manager_icon_alt_text', ($course_manager_icon != null && $course_manager_icon['alt_text'] != '') ? $course_manager_icon['alt_text'] : '' ) }}" id="manager_alt_text" name="course[{{'manager'}}][{{'icon'}}][{{'alt_text'}}]">
                                                </div>
                                            </div>


                                            <div class="form-group col-12">
                                                <span class="toggle-btn-inline-text">Does this course have a visible manager?</span>
                                                <label class="custom-toggle enroll-toggle visible">
                                                    <input id="manager-toggle" name="manager-enabled" type="checkbox" {{(isset($event->syllabus[0])) ? 'checked' : ''}}>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                            </div>

                                            @if(count($instructors1) != 0)
                                            <div class="col-sm-12 col-md-6 col-lg-4 course-manager-list form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }} {{ (isset($event->syllabus[0])) ? '' : 'd-none' }}">
                                                <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus1" class="form-control" placeholder="{{ __('Syllabus Manager') }}">
                                                    <option value=""></option>


                                                    @foreach ($instructors1 as $key => $instructor)

                                                        <option
                                                        <?php if(count($event->syllabus) != 0){
                                                            if($key == $event->syllabus[0]['id']){
                                                                echo 'selected';
                                                            }else{
                                                                echo '';
                                                            }
                                                        }
                                                        ?>
                                                        @if($instructors1[$key][0]->medias != null)
                                                            ext="{{$instructors1[$key][0]->medias['ext']}}" original_name="{{$instructors1[$key][0]->medias['original_name']}}" name="{{$instructors1[$key][0]->medias['name']}}" path="{{$instructors1[$key][0]->medias['path']}}" value="{{$key}}">{{ $instructors1[$key][0]['title'] }} {{ $instructors1[$key][0]['subtitle'] }}</option>
                                                        @else
                                                        ext="null" original_name="null" name="null" path="null" value="{{$key}}">{{ $instructors1[$key][0]['title'] }} {{ $instructors1[$key][0]['subtitle'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                @include('alerts.feedback', ['field' => 'syllabus'])
                                            </div>
                                            @endif
                                        </div>


                                        <hr>

                                        <div class="row course-awards-wrapper">

                                            <?php
                                                if(isset($info['awards']['icon']) && $info['awards']['icon'] != null){
                                                    $course_awards_icon = $info['awards']['icon'];
                                                }else{
                                                    $course_awards_icon = null;
                                                }
                                            ?>

                                            <div class="form-group col-12">

                                                <div class="row">
                                                    <div class="col-9 col-md-auto col-lg-auto">
                                                        <h3 class="mb-0 title">{{ __('Course Awards & Badges') }} (course_awards)</h3>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto">
                                                        <span data-infowrapper="awards" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_awards_icon != null && $course_awards_icon['path'] != null)
                                                                    <img src="{{ asset($course_awards_icon['path']) }}"/>
                                                                @else
                                                                    <span class="fa fa-calendar"></span>
                                                                @endif

                                                            </span>
                                                        </span>
                                                    </div>

                                                    <input type="hidden" value="{{ old('course_awards_icon_path', ($course_awards_icon != null && $course_awards_icon['path'] != '') ? $course_awards_icon['path'] : '' ) }}" id="awards_path" name="course[{{'awards'}}][{{'icon'}}][{{'path'}}]">
                                                    <input type="hidden" value="{{ old('course_awards_icon_alt_text', ($course_awards_icon != null && $course_awards_icon['alt_text'] != '') ? $course_awards_icon['alt_text'] : '' ) }}" id="awards_alt_text" name="course[{{'awards'}}][{{'icon'}}][{{'alt_text'}}]">
                                                </div>
                                            </div>
                                            <div class="form-group col-12">
                                                <span class="toggle-btn-inline-text">Does this course have some award?</span>
                                                <label class="custom-toggle enroll-toggle visible">
                                                    <input id="award-toggle" {{ (isset($info['awards']['text']) && $info['awards']['text'] != null) ? 'checked' : ''}} type="checkbox">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-4 form-group award-text {{ (isset($info['awards']['text']) && $info['awards']['text'] != null) ? '' : 'd-none'}}">
                                                <input style="background: aliceblue;" id="input-award-text" type="text" name="course[{{'awards'}}][{{'text'}}]" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('awards', (isset($info['awards']['text']) && $info['awards']['text'] != null) ? $info['awards']['text'] : '' ) }}" autofocus>
                                            </div>
                                        </div>




                                        <hr>

                                        <div class="row course-certification-wrapper">

                                            <?php

                                                if(isset($info['certificate']['icon']) && $info['certificate']['icon'] != null){
                                                    $course_certification_icon = $info['certificate']['icon'];
                                                }else{
                                                    $course_certification_icon = null;
                                                }
                                            ?>

                                            <div class="form-group col-12">

                                                <div class="row">
                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <h3 class="mb-1 title">{{ __('Course Certification') }}</h3>
                                                    </div>


                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="certificate" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_certification_icon  != null && $course_certification_icon['path'] != null)
                                                                    <img src="{{ asset($course_certification_icon['path']) }}"/>
                                                                @else
                                                                    {{--<span class="fa fa-calendar"></span>--}}
                                                                    <img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Level.svg" alt="">
                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('course_certification_icon_path', ($course_certification_icon != null && $course_certification_icon['path'] != '') ? $course_certification_icon['path'] : '' ) }}" id="certificate_path" name="course[{{'certificate'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('course_certification_icon_alt_text', ($course_certification_icon != null && $course_certification_icon['alt_text'] != '') ? $course_certification_icon['alt_text'] : '' ) }}" id="certificate_alt_text" name="course[{{'certificate'}}][{{'icon'}}][{{'alt_text'}}]">
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
                                                    <input name="course[{{'certificate'}}][{{'certification'}}]" id="certification-toggle" {{isset($info['certificate']['has_certificate']) && $info['certificate']['has_certificate'] ? 'checked' : '' }} type="checkbox">
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>
                                            </div>

                                            <?php

                                                $visible_certification = (isset($info['certificate']['visible']) && $info['certificate']['visible'] != null) ? $info['certificate']['visible'] : null;

                                            ?>



                                            <div class="form-group col-12 course-certification-visible-wrapper {{ isset($info['certificate']['has_certificate']) && $info['certificate']['has_certificate'] ? '' : 'd-none'  }}">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 col-lg-4 form-group">

                                                        <label class="form-control-label">Certification Title (course_certificate_title)</label>

                                                        <input type="text" id="input-certification-title" name="course[{{'certificate'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course certificate Title') }}" value="{{ old('certificate-title', (isset($info['certificate']['title']) && $info['certificate']['title'] != null) ? $info['certificate']['title'] : '' ) }}" autofocus>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h4 class="mb-1 title" for="input-hours">{{ __('Courses with exams') }}</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6 form-group{{ $errors->has('fb_') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="input-hours">{{ __('Certificate Title') }} <br>  (course_certification_name_success)</label>

                                                        {{--<textarea type="text" name="course[{{'certificate'}}][{{'success_text'}}]" id="input-certificate_title" class="ckeditor form-control" placeholder="{{ __('alphanumeric text ') }}" autofocus>{{ old('certificate_title', (isset($info['certificate']['messages']['success']) && $info['certificate']['messages']['success'] != null) ? $info['certificate']['messages']['success'] : '') }}</textarea>--}}

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-certificate_title_hidden" name="course[{{'certificate'}}][{{'success_text'}}]" value="{{ old('certificate_title', (isset($info['certificate']['messages']['success']) && $info['certificate']['messages']['success'] != null) ? $info['certificate']['messages']['success'] : '') }}"/>
                                                        <?php $data = isset($info['certificate']['messages']['success']) && $info['certificate']['messages']['success'] != null ? $info['certificate']['messages']['success'] : '' ?>
                                                        @include('event.editor.editor', ['keyinput' => "input-certificate_title", 'data'=> "$data", 'inputname' => "'course[certificate][success_text]'" ])
                                                        <!-- anto's editor -->

                                                        @include('alerts.feedback', ['field' => 'certificate_title'])

                                                    </div>

                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <label class="form-control-label" for="input-hours">{{ __('Title Of Certification (in case of exams failure)') }} <br>  (course_certification_name_failure)</label>

                                                        {{--<textarea type="text" name="course[{{'certificate'}}][{{'failure_text'}}]" id="input-certificate_text_failure" class="form-control ckeditor" placeholder="{{ __('alphanumeric text ') }}"  autofocus>{{ old('certificate_failure', (isset($info['certificate']['messages']['failure']) && $info['certificate']['messages']['failure'] != null) ? $info['certificate']['messages']['failure'] : '') }}</textarea>--}}

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-certificate_text_failure_hidden" name="course[{{'certificate'}}][{{'failure_text'}}]" value="{{ old('certificate_failure', (isset($info['certificate']['messages']['failure']) && $info['certificate']['messages']['failure'] != null) ? $info['certificate']['messages']['failure'] : '') }}"/>
                                                        <?php $data = isset($info['certificate']['messages']['failure']) && $info['certificate']['messages']['failure'] != null ? $info['certificate']['messages']['failure'] : '' ?>
                                                        @include('event.editor.editor', ['keyinput' => "input-certificate_text_failure", 'data'=> "$data", 'inputname' => "'course[certificate][failure_text]'" ])
                                                        <!-- anto's editor -->

                                                    </div>

                                                </div>

                                                <div>
                                                    <h4 class="mb-0 title" for="input-hours">{{ __('Courses without exams') }}</h4>
                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <label class="form-control-label" for="input-hours">{{ __('Certificate Type') }} (course_certification_type)</label>

                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-certificate" name="course[{{'certificate'}}][{{'type'}}]" value="{{old('certificate_type',(isset($info['certificate']['type']) && $info['certificate']['type'] != null) ? $info['certificate']['type'] : '' )}}"/>
                                                        <?php $data = isset($info['certificate']['type']) && $info['certificate']['type'] != null ? $info['certificate']['type'] : '' ?>
                                                        @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-certificate_type", 'data'=> "$data", 'inputname' => "'course[certificate][type]'" ])
                                                        <!-- anto's editor -->

                                                        {{--<input type="text" name="course[{{'certificate'}}][{{'type'}}]" id="input-certificate_type" class="form-control" placeholder="{{ __('alphanumeric text ') }}" value="{{old('certificate_type',(isset($info['certificate']['type']) && $info['certificate']['type'] != null) ? $info['certificate']['type'] : '' )}}" autofocus/>--}}

                                                    </div>

                                                </div>
                                                {{--<div class="row">
                                                    <div class="col-sm-12 col-md-6 form-group">
                                                        <label class="form-control-label" for="input-hours">{{ __('Certificate Event Title') }} (course_certification_event_title)</label>
                                                        <!-- anto's editor -->
                                                        <input class="hidden" id="input-certificate_event_title_hidden" name="course[{{'certificate'}}][{{'event_title'}}]" value="{{ old('certificate_event_title', (isset($info['certificate']['event_title']) && $info['certificate']['event_title'] != null) ?$info['certificate']['event_title'] : '') }}"/>
                                                        <?php $data = isset($info['certificate']['event_title']) && $info['certificate']['event_title'] ? $info['certificate']['event_title'] : explode(',',$event->title)[0] ?>
                                                        @include('event.editor.editor', ['keyinput' => "input-certificate_event_title", 'data'=> "$data", 'inputname' => "'course[certificate][event_title]'" ])
                                                        <!-- anto's editor -->

                                                    </div>
                                                </div>--}}

                                                    <div class="row">



                                                        <div class="form-group col-12 accordion" id="accordionExample">
                                                            <div class="card">
                                                                <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                                    <h5 class="mb-0">Visible on:</h5>
                                                                </div>
                                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                                                <div class="custom-control custom-checkbox mb-3">
                                                                                    <input class="custom-control-input" {{ ($visible_certification != null && $visible_certification['landing']) ? 'checked' : '' }} name="course[{{'certificate'}}][{{'visible'}}][{{'landing'}}]" id="input-certificate-landing" type="checkbox">
                                                                                    <label class="custom-control-label" for="input-certificate-landing">Course landing page (summary)</label>
                                                                                </div>

                                                                            </div>

                                                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                                                <div class="custom-control custom-checkbox mb-3">
                                                                                    <input class="custom-control-input" {{ ($visible_certification != null && $visible_certification['home']) ? 'checked' : '' }} name="course[{{'certificate'}}][{{'visible'}}][{{'home'}}]" id="input-certificate-home" type="checkbox">
                                                                                    <label class="custom-control-label" for="input-certificate-home">Course box in home page</label>
                                                                                </div>

                                                                            </div>

                                                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                                                <div class="custom-control custom-checkbox mb-3">
                                                                                    <input class="custom-control-input" {{ ($visible_certification != null && $visible_certification['list']) ? 'checked' : '' }} name="course[{{'certificate'}}][{{'visible'}}][{{'list'}}]" id="input-certificate-list" type="checkbox">
                                                                                    <label class="custom-control-label" for="input-certificate-list">Course box in list page</label>
                                                                                </div>

                                                                            </div>

                                                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                                                <div class="custom-control custom-checkbox mb-3">
                                                                                    <input class="custom-control-input" {{ ($visible_certification != null && $visible_certification['invoice']) ? 'checked' : '' }} name="course[{{'certificate'}}][{{'visible'}}][{{'invoice'}}]" id="input-certificate-invoice" type="checkbox">
                                                                                    <label class="custom-control-label" for="input-certificate-invoice">Invoice description</label>
                                                                                </div>

                                                                            </div>

                                                                            <div class="col-sm-12 col-md-6 col-lg-2">

                                                                                <div class="custom-control custom-checkbox mb-3">
                                                                                    <input class="custom-control-input" {{ ($visible_certification != null && $visible_certification['emails']) ? 'checked' : '' }} name="course[{{'certificate'}}][{{'visible'}}][{{'emails'}}]" id="input-certificate-emails" type="checkbox">
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

                                            <?php
                                                if(isset($info['students']['icon']) && $info['students']['icon'] != null){
                                                    $course_students_icon = $info['students']['icon'];
                                                }else{
                                                    $course_students_icon = null;
                                                }
                                            ?>
                                            <div class="form-group col-12">

                                                <div class="row">
                                                    <div class="col-9 col-md-auto col-lg-auto align-self-center">
                                                        <h3 class="mb-0 title">{{ __('Course Students') }}</h3>
                                                    </div>

                                                    <div class="col-2 col-md-auto col-lg-auto align-self-center">
                                                        <span data-infowrapper="students" class="input-group-addon input-group-append input-icon-wrapper">
                                                            <span class="btn btn-outline-primary input-icon">
                                                                @if($course_students_icon != null && $course_students_icon['path'] != null)
                                                                    <img src="{{ asset($course_students_icon ['path']) }}"/>
                                                                @else
                                                                    <img src="/theme/assets/images/icons/Group_User.1.svg" alt="">
                                                                @endif
                                                            </span>
                                                        </span>
                                                        <input type="hidden" value="{{ old('course_students_icon_path', ($course_students_icon != null && $course_students_icon['path'] != '') ? $course_students_icon['path'] : '' ) }}" id="students_path" name="course[{{'students'}}][{{'icon'}}][{{'path'}}]">
                                                        <input type="hidden" value="{{ old('course_students_icon_alt_text', ($course_students_icon != null && $course_students_icon['alt_text'] != '') ? $course_students_icon['alt_text'] : '' ) }}" id="students_alt_text" name="course[{{'students'}}][{{'icon'}}][{{'alt_text'}}]">
                                                    </div>

                                                    <div class="col-12 col-md-auto col-lg-auto align-self-center">

                                                        <label class="custom-toggle enroll-toggle visible">
                                                            <input class="icon_link" name="course[{{'students'}}][{{'icon'}}][{{'link_status'}}]" type="checkbox" {{ (isset($course_students_icon['link_status']) && $course_students_icon['link_status'] == 'on') ? 'checked' : ''}}>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No Link" data-label-on="Link"></span>
                                                        </label>

                                                    </div>

                                                    <div class="col-12 col-md-5 col-lg-4 input align-self-center @if((isset($course_students_icon['link_status']) && $course_students_icon['link_status'] == 'off') || !isset($course_students_icon['link_status'])) {{'d-none'}} @endif">
                                                        <input placeholder="https://example.com" type="text" class="form-control" name="course[{{'students'}}][{{'icon'}}][{{'link'}}]" value="{{ old('course_students_icon_link', (isset($course_students_icon) && $course_students_icon != null && isset($course_students_icon['link'])) ? $course_students_icon['link'] : '' ) }}">
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-4 form-group">
                                                <label class="form-control-label" for="input-hours">{{ __('Student should start count from this number:') }} (course_students_number)</label>
                                                <input type="text" name="course[{{'students'}}][{{'count_start'}}]" class="form-control" placeholder="{{ __('number') }}" value="{{ old('count_start', (isset($info['students']['number']) && $info['students']['number'] != null) ? $info['students']['number'] : '' ) }}" autofocus>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-8 form-group"></div>


                                            <div class="col-sm-12 col-md-6 col-lg-4 form-group">

                                                <label class="form-control-label">Students Title (course_students_title)</label>

                                                <input type="text" id="input-students-title" name="course[{{'students'}}][{{'title'}}]" class="form-control" placeholder="{{ __('Course students Title') }}" value="{{ old('students-title', (isset($info['students']['title']) && $info['students']['title'] != null) ? $info['students']['title'] : '' ) }}" autofocus>

                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-8 form-group"></div>

                                            <div class="col-12 form-group">
                                                <label class="form-control-label" for="input-hours">{{ __('Text after the number of students:') }} (course_students_text)</label>


                                                <!-- anto's editor -->
                                                <input class="hidden" id="input-students" name="course[{{'students'}}][{{'text'}}]" value="{{ old('count_text', (isset($info['students']['text']) && $info['students']['text'] != null) ? $info['students']['text'] : '' ) }}"/>
                                                <?php $data = isset($info['students']['text']) && $info['students']['text'] != null ? $info['students']['text'] : '' ?>
                                                @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-students_title", 'data'=> "$data", 'inputname' => "'course[students][text]'" ])
                                                <!-- anto's editor -->

                                                {{--<input style="background: aliceblue;" name="course[{{'students'}}][{{'text'}}]" type="text" class="form-control" placeholder="{{ __('alphanumeric text') }}" value="{{ old('count_text', (isset($info['students']['text']) && $info['students']['text'] != null) ? $info['students']['text'] : '' ) }}" autofocus>--}}
                                            </div>




                                        </div>
                                        <div class="row">
                                            <?php

                                                $visible_students = (isset($info['students']['visible']) && $info['students']['visible'] != null) ? $info['students']['visible'] : null;

                                            ?>

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
                                                                        <input class="custom-control-input" {{ ($visible_students != null && $visible_students['landing']) ? 'checked' : '' }} name="course[{{'students'}}][{{'visible'}}][{{'landing'}}]" id="input-students-landing" type="checkbox">
                                                                        <label class="custom-control-label" for="input-students-landing">Course landing page (summary)</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_students != null && $visible_students['home']) ? 'checked' : '' }} name="course[{{'students'}}][{{'visible'}}][{{'home'}}]" id="input-students-home" type="checkbox">
                                                                        <label class="custom-control-label" for="input-students-home">Course box in home page</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_students != null && $visible_students['list']) ? 'checked' : '' }} name="course[{{'students'}}][{{'visible'}}][{{'list'}}]" id="input-students-list" type="checkbox">
                                                                        <label class="custom-control-label" for="input-students-list">Course box in list page</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_students != null && $visible_students['invoice']) ? 'checked' : '' }} name="course[{{'students'}}][{{'visible'}}][{{'invoice'}}]" id="input-students-invoice" type="checkbox">
                                                                        <label class="custom-control-label" for="input-students-invoice">Invoice description</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-sm-12 col-md-6 col-lg-2">

                                                                    <div class="custom-control custom-checkbox mb-3">
                                                                        <input class="custom-control-input" {{ ($visible_students != null && $visible_students['emails']) ? 'checked' : '' }} name="course[{{'students'}}][{{'visible'}}][{{'emails'}}]" id="input-students-emails" type="checkbox">
                                                                        <label class="custom-control-label" for="input-students-emails">Automated emails</label>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                        @if($event->exam()->first())
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-method">{{ __('Export Certificates') }}</label>
                                                    <div class="form-group">

                                                        <a href="/admin/events/export-certificates/{{$event->id}}"  class="btn btn-outline-primary"> {{ __('Export Certificates') }} </a>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        @endif

                                        <hr>


                                        <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
                                            <input id="old-category" name="oldCategory" value="{{isset($event->category[0]) ? $event->category[0]->id : -1}}" hidden>
                                            <label class="form-control-label" for="input-category_id">{{ __('Category') }}</label>
                                            <select name="category_id" id="input-category_id" class="form-control" placeholder="{{ __('Category') }}" required>
                                                <option></option>
                                                @foreach ($categories as $category)
                                                    <option <?php if(count($event->category) != 0){
                                                        if($event->category[0]->id == $category->id){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?> value="{{ $category->id }}" >{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                            @include('alerts.feedback', ['field' => 'category_id'])
                                        </div>




                                    </div>





                                    <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                        <div class="tab-pane-mobile d-none">
                                            <ul id="tab_inside_tab_mobile" class="nav nav-pills nav-fill flex-column flex-md-row owl-carousel" id="tabs-icons-text" role="tablist">
                                                <li class="nav-item item">
                                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_inside" data-toggle="tab" href="#tabs-icons-text-1_inside" role="tab" aria-controls="tabs-icons-text-1_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Overview</a>
                                                </li>
                                                <li class="nav-item item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-10-tab_inside" data-toggle="tab" href="#tabs-icons-text-10_inside" role="tab" aria-controls="tabs-icons-text-10_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Faqs</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-12-tab_inside" data-toggle="tab" href="#tabs-icons-text-11_inside" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#testimonials-tab" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Testimonials</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#videos" role="tab" aria-controls="videos" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Videos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#instructors-tab" role="tab" aria-controls="instructors-tab" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Instructors</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="nav-wrapper">

                                            <ul id="tab_inside_tab" class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_inside" data-toggle="tab" href="#tabs-icons-text-1_inside" role="tab" aria-controls="tabs-icons-text-1_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Overview</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-10-tab_inside" data-toggle="tab" href="#tabs-icons-text-10_inside" role="tab" aria-controls="tabs-icons-text-10_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Faqs</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-12-tab_inside" data-toggle="tab" href="#tabs-icons-text-12_inside" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#testimonials-tab" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Testimonials</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#videos" role="tab" aria-controls="videos" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Videos</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#instructors-tab" role="tab" aria-controls="instructors-tab" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Instructors</a>
                                                </li>


                                            </ul>
                                        </div>
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <div class="tab-content" id="myTabContent">

                                                    <div class="tab-pane fade show active" id="tabs-icons-text-1_inside" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab_inside">



                                                            <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                                                            <div class="pl-lg-4">
                                                                <?php

                                                                    $id = isset($sections['overview'][0]) ? $sections['overview'][0]['id'] : '';
                                                                    $tab_title = isset($sections['overview'][0]) ? $sections['overview'][0]['tab_title'] : '' ;
                                                                    $title = isset($sections['overview'][0]) ? $sections['overview'][0]['title'] : '' ;
                                                                    $visible = true ;

                                                                ?>
                                                                <div class="form-group">

                                                                    <input hidden name="sections[overview][id]" value="{{$id}}">

                                                                    <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
                                                                    <input type="text" name="sections[overview][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[instructors][tab_title]", $tab_title) }}" autofocus>
                                                                    {{--<label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>--}}
                                                                    <input hidden type="text" name="sections[overview][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[instructors][title]", $title) }}" autofocus>
                                                                    <input hidden type="checkbox"  name="sections[overview][visible]" @if($visible)) checked @endif>

                                                                    {{--<label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
                                                                    <div style="margin: auto;" class="form-group">

                                                                    <label class="custom-toggle enroll-toggle visible">
                                                                        <input type="checkbox"  name="sections[instructors][visible]" @if($visible)) checked @endif>
                                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
                                                                    </label>

                                                                    </div>--}}


                                                                </div>



                                                                <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-title">{{ __('Η1 public title') }}</label>
                                                                    <input type="text" name="eventTitle" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Η1 public title') }}" value="{{ old('title', $event->title) }}" required autofocus>

                                                                    @include('alerts.feedback', ['field' => 'title'])
                                                                </div>
                                                                {{--@include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])--}}
                                                                <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-htmlTitle">{{ __('Admin title') }}</label>
                                                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('Admin title') }}" value="{{ old('Short title', $event->htmlTitle) }}" autofocus>

                                                                    @include('alerts.feedback', ['field' => 'htmlTitle'])
                                                                </div>

                                                                <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-subtitle">{{ __('H2 subtitle') }}</label>
                                                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('H2 subtitle') }}" value="{{ old('Subtitle', $event->subtitle) }}" autofocus>

                                                                    @include('alerts.feedback', ['field' => 'subtitle'])
                                                                </div>

                                                                {{--<div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                                                    <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $event->header) }}" autofocus>

                                                                    @include('alerts.feedback', ['field' => 'header'])
                                                                </div>--}}

                                                                <div class="form-group{{ $errors->has('body') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-body">{{ __('Body') }}</label>



                                                                    {{--<textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $event->body) }}</textarea>--}}

                                                                    <!-- anto's editor -->
                                                                    <input class="hidden" name="body" value="{{ old('body',$event->body) }}"/>
                                                                    <?php $data = $event->body?>
                                                                    @include('event.editor.editor', ['toolbar' => 'insertfile image media link anchor codesample','plugins' => 'link','keyinput' => "input-body", 'data'=> "$data", 'inputname' => "'body'" ])
                                                                    <!-- anto's editor -->


                                                                    @include('alerts.feedback', ['field' => 'body'])
                                                                </div>

                                                                <div class="form-group{{ $errors->has('view_tpl') ? ' has-danger' : '' }}">
                                                                    <label class="form-control-label" for="input-view_tpl">{{ __('View tpl') }}</label>
                                                                    <select name="view_tpl"  class="form-control" placeholder="{{ __('View tpl') }}">
                                                                        @foreach (get_templates('events') as $key => $template)
                                                                            <option value="{{ $template }}" {{ $template == old('template',$event->view_tpl) ? 'selected' : '' }}>{{ $key }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @include('alerts.feedback', ['field' => 'view_tpl'])
                                                                </div>





                                                            </div>


                                                    </div>




                                                    <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                        @include('admin.videos.event.index',['model' => $event])
                                                    </div>




                                                    <div class="tab-pane fade show" id="tabs-icons-text-4_inside" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab_inside">
                                                        @include('topics.event.instructors',['sections' => $sections])
                                                    </div>
                                                    <div class="tab-pane fade" id="tabs-icons-text-5_inside" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab_inside">
                                                        @include('admin.ticket.index', ['model' => $event, 'sections' => $sections])
                                                    </div>

                                                    <div class="tab-pane fade" id="tabs-icons-text-7_inside" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab_inside">
                                                        @include('admin.venue.event.index', ['model' => $event,'sections' => $sections])
                                                    </div>

                                                    <div class="tab-pane fade" id="tabs-icons-text-10_inside" role="tabpanel" aria-labelledby="tabs-icons-text-10-tab_inside">
                                                        @include('admin.faq.index', ['model' => $event,'sections' => $sections])
                                                    </div>
                                                    <div class="tab-pane fade" id="tabs-icons-text-12_inside" role="tabpanel" aria-labelledby="tabs-icons-text-12-tab_inside">

                                                       @include('admin.upload.upload', ['event' => ($event->medias != null) ? $event->medias : null,'image_version' => 'null', 'versions' => ['header-image', 'social-media-sharing']])

                                                        <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="{{$event->creator_id}}">
                                                        <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="{{$event->author_id}}">

                                                        <div id="version-btn" style="margin-bottom:20px" class="col">
                                                            <a href="{{ route('media2.eventImage', $event->medias) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
                                                        </div>
                                                        @include('alerts.feedback', ['field' => 'ext_url'])

                                                        {{--@include('event.image_versions', ['event' => $event->medias,'versions1'=> ['event-card', 'header-image', 'social-media-sharing']])--}}
                                                        {{--@include('event.image_versions_new', ['event' => $event->medias,'versions1'=> ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image']])--}}
                                                    </div>
                                                    <div class="tab-pane fade" id="instructors-tab" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">


                                                            <?php

                                                                $id = isset($sections['instructors'][0]) ? $sections['instructors'][0]['id'] : '';
                                                                $tab_title = isset($sections['instructors'][0]) ? $sections['instructors'][0]['tab_title'] : '' ;
                                                                $title = isset($sections['instructors'][0]) ? $sections['instructors'][0]['title'] : '' ;
                                                                $visible = isset($sections['instructors'][0]) ? $sections['instructors'][0]['visible'] : false ;

                                                            ?>
                                                            <div class="form-group">

                                                            <input hidden name="sections[instructors][id]" value="{{$id}}">

                                                            <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
                                                            <input type="text" name="sections[instructors][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[instructors][tab_title]", $tab_title) }}" autofocus>
                                                            <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
                                                            <input type="text" name="sections[instructors][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[instructors][title]", $title) }}" autofocus>


                                                            <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
                                                            <div style="margin: auto;" class="form-group">

                                                                <label class="custom-toggle enroll-toggle visible visible">
                                                                    <input type="checkbox"  name="sections[instructors][visible]" @if($visible)) checked @endif>
                                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
                                                                </label>

                                                            </div>


                                                            </div>

                                                    </div>

                                                    <div class="tab-pane fade" id="testimonials-tab" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                                        <?php

                                                            $id = isset($sections['testimonials'][0]) ? $sections['testimonials'][0]['id'] : '';
                                                            $tab_title = isset($sections['testimonials'][0]) ? $sections['testimonials'][0]['tab_title'] : '' ;
                                                            $title = isset($sections['testimonials'][0]) ? $sections['testimonials'][0]['title'] : '' ;
                                                            $visible = isset($sections['testimonials'][0]) ? $sections['testimonials'][0]['visible'] : false ;

                                                        ?>

                                                        <div class="form-group">

                                                        <input hidden name="sections[testimonials][id]" value="{{$id}}">

                                                        <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
                                                        <input type="text" name="sections[testimonials][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[testimonials][tab_title]", $tab_title) }}" autofocus>
                                                        <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
                                                        <input type="text" name="sections[testimonials][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[testimonials][title]", $title) }}" autofocus>


                                                        <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
                                                        <div style="margin: auto;" class="form-group">

                                                            <label class="custom-toggle enroll-toggle visible visible">
                                                                <input type="checkbox"  name="sections[testimonials][visible]" @if($visible) checked @endif>
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
                                                            </label>

                                                        </div>


                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                        @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                                        @include('admin.metas.metas',['metas' => $metas])
                                    </div>


                                    <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                        @include('event.students')
                                    </div>

                                    @if(count($eventWaitingUsers) != 0)
                                    <div class="tab-pane fade" id="waiting_list" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                        @include('event.students_waiting_list')
                                    </div>
                                    @endif

                                    <div class="tab-pane fade" id="xml_fields" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">

                                        <div class="form-group{{ $errors->has('xml_title') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-xml_title">{{ __('XML  Title') }}</label>
                                            <input name="xml_title" id="input-xml_title"  class="ckeditor form-control{{ $errors->has('xml_title') ? ' is-invalid' : '' }}" placeholder="{{ __('XML  Title') }}" value="{{ old('xml_title', $event->xml_title) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'xml_title'])
                                        </div>

                                        <div class="form-group{{ $errors->has('xml_description') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-xml_description">{{ __('XML  Description') }}</label>
                                            <input name="xml_description" id="input-xml_description"  class="ckeditor form-control{{ $errors->has('xml_description') ? ' is-invalid' : '' }}" placeholder="{{ __('XML  Description') }}" value="{{ old('xml_description', $event->xml_description) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'xml_description'])
                                        </div>

                                        <div class="form-group{{ $errors->has('xml_short_description') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-xml_short_description">{{ __('XML Short Description') }}</label>
                                            <input name="xml_short_description" id="input-xml_short_description"  class="ckeditor form-control{{ $errors->has('xml_short_description') ? ' is-invalid' : '' }}" placeholder="{{ __('XML Short Description') }}" value="{{ old('xml_short_description', $event->xml_short_description) }}" autofocus>

                                            @include('alerts.feedback', ['field' => 'xml_short_description'])
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="emails_fields" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                        <p class="text-sm mb-0">
                                            {{ __("Please submit the full links for for this course's automated emails.") }}
                                        </p>
                                        <div class="form-group">
                                            <div class="form-group{{ $errors->has('fb_group') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-fb_group">{{ __("Course's Facebook group:") }}</label>
                                                <input type="text" name="fb_group" id="input-fb_group" class="form-control{{ $errors->has('fb_group') ? ' is-invalid' : '' }}" placeholder='Example: https:/facebook.com/groups/yourgroup' value="{{ old('fb_group', $event->fb_group) }}"autofocus>

                                                @include('alerts.feedback', ['field' => 'fb_group'])
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-group{{ $errors->has('evaluate_instructors') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-evaluate_instructors">{{ __("Course's evaluation survey for instructors:") }}</label>
                                                <input type="text" name="evaluate_instructors" id="input-evaluate_instructors" class="form-control{{ $errors->has('evaluate_instructors') ? ' is-invalid' : '' }}" placeholder='Example: https:/typeform.com/yoursurvey' value="{{ old('evaluate_instructors', $event->evaluate_instructors) }}"autofocus>

                                                @include('alerts.feedback', ['field' => 'evaluate_instructors'])
                                            </div>
                                        </div>

                                        {{--<div class="form-group">
                                            <div class="form-group{{ $errors->has('evaluate_topics') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-evaluate_topics">{{ __("Course's evaluation survey for topics:") }}</label>
                                                <input type="text" name="evaluate_topics" id="input-evaluate_topics" class="form-control{{ $errors->has('evaluate_topics') ? ' is-invalid' : '' }}" placeholder='Example: https:/typeform.com/yoursurvey' value="{{ old('evaluate_topics', $event->evaluate_topics) }}"autofocus>

                                                @include('alerts.feedback', ['field' => 'evaluate_topics'])
                                            </div>
                                        </div>--}}

                                        <div class="form-group">
                                            <div class="form-group{{ $errors->has('fb_testimonial') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-fb_testimonial">{{ __("Course's testimonial survey:") }}</label>
                                                <input type="text" name="fb_testimonial" id="input-fb_testimonial" class="form-control{{ $errors->has('fb_testimonial') ? ' is-invalid' : '' }}" placeholder='Example: https:/typeform.com/yoursurvey' value="{{ old('fb_testimonial', $event->fb_testimonial) }}"autofocus>

                                                @include('alerts.feedback', ['field' => 'fb_testimonial'])
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">

                                        <div class="table-responsive py-4">
                                            <table class="table align-items-center table-flush"  id="datatable-coupon">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">{{ __('Code') }}</th>
                                                        <th scope="col">{{ __('Price') }}</th>
                                                        <th scope="col">{{ __('Status') }}</th>
                                                        <th scope="col">{{ __('Used') }}</th>
                                                        <th scope="col">{{ __('Assigned') }}</th>



                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php //dd($allTopicsByCategory);
                                                    $eventCoupons = $event['coupons']->pluck('id')->toArray();
                                                    //dd($eventCoupons);
                                                ?>

                                                    @foreach ($coupons as $key => $coupon)
                                                        <tr>
                                                            <td>{{ $coupon['code_coupon'] }}</td>
                                                            <td>{{ $coupon['price'] }}</td>
                                                            <td>{{ $coupon['status'] }}</td>
                                                            <td>{{ $coupon['used'] }}</td>

                                                            <td>
                                                                <div class="col-2 assign-toggle" id="toggle_{{$key}}">
                                                                    <label class="custom-toggle">
                                                                        <input class="coupon-input" type="checkbox" data-status="{{in_array($coupon['id'],$eventCoupons)}}" data-event-id="{{$event['id']}}" data-coupon-id="{{$coupon['id']}}" @if(in_array($coupon['id'],$eventCoupons)) checked @endif>
                                                                        <span class="coupon custom-toggle-slider rounded-circle" ></span>
                                                                    </label>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>


                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

            </div>

        </div>
        @include('layouts.footers.auth')
    </div>


    <div class="">
        <div class="col-md-4">
            <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Instructor</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="lesson_details">
                            <div class="form-group instFormControl">
                                <label class="instFormControl" for="exampleFormControlSelect1">Select instructor</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control instFormControl" id="instFormControlSelect12">
                                </select>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="lesson_update_btn" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-link ml-auto close-modal" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>


    <!-- <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('admin_assets/js/vendor.min.js')}}"></script> -->

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/devextreme/20.2.11/css/dx.carmine.compact.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/devextreme/20.2.11/js/dx.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    $( document ).ready(function() {

        $.validator.addMethod('checkIfRequired', function (value, element, param) {
            let isValid = false;
            let toggle_btn = $(element).parent().parent();

            toggle_btn = $(toggle_btn).find('.icon_link')[0];

            if($(toggle_btn).prop('checked') && value != ''){
                isValid = true;
            }



            return isValid; // return bool here if valid or not.
        }, 'Field is required!');

        $("#event_edit_form").validate({

            rules: {
                "course[hours][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[language][icon][link]" : {
                    checkIfRequired: true,
                    url: true
                },
                "course[delivery_icon][link]" : {
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
    });
</script>
<script>
    let selectedFolders = [];
    let selectedIds = [];
    let loadAllFolders = [];
    let already_assign_files = @json($already_assign);

    if(already_assign_files.length != 0){
        already_assign_files = already_assign_files

    }


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

    function parseIdsForSelectFiles(){



        if(files.length != 0 && already_assign_files.length != 0){

            $.each(already_assign_files, function(index11, value11) {
                loadAllFolders = JSON.parse(value11.pivot.selectedFolders);

                if(loadAllFolders.selectedAllFolders){
                    $.each(files, function(index, value) {

                        if(value.Full_Name == value11.folder_name){
                            selectedIds.push(value.ID)
                        }
                    })
                }else{
                    $.each(files, function(index, value) {
                        if(loadAllFolders.selectedFolders.length != 0){
                            $.each(loadAllFolders.selectedFolders, function(index1, value1){

                                if(value.dirname == value1 && value11.folder_name == value.dropboxFolder){
                                    selectedIds.push(value.ID)
                                }
                            })

                        }
                    })
                }
            })




            treeList.selectRows(selectedIds);

        }
    }

    $(() => {


        treeData().then(function () {
            treeFiles()
            parseIdsForSelectFiles()
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

                let selectedDropbox = null;
                let selectedAllFolders = false;
                //const allSelectedRowsData = e.selectedRowsData;
                const allSelectedRowsDataForSave = treeList.getSelectedRowsData('multiple')

                $.each(files, function(index, value){
                    if(currentSelectedRowKeys == value.ID){
                        currentSelectedRow = value
                    }
                })


                // Deselect previous rows

                // if(currentSelectedRow.isRootFolder && allSelectedRowsData.length != 1){

                //     allSelectedRowsData.filter(value => value.ID == currentSelectedRowKeys);

                //     $.each(allSelectedRowsData,function(index,value){
                //         if(value.ID != currentSelectedRowKeys){
                //             deselectIDS.push(value.ID)
                //         }
                //     })

                // }


                // if(!currentSelectedRow.isRootFolder && allSelectedRowsData.length != 1){
                //     $.each(allSelectedRowsData,function(index,value){
                //         if(value.isRootFolder && currentSelectedRow.Head_ID != value.ID){
                //             deselectIDS.push(value.ID)
                //         }
                //     })
                // }
                // treeList.deselectRows(deselectIDS);

                let dataForSubmit = {};
                selectedFolders = [];



                if(allSelectedRowsDataForSave.length != 0){

                    $.each(allSelectedRowsDataForSave, function(index, value) {

                        if(value.isRootFolder){
                            selectedAllFolders = true;
                            selectedDropbox = value.Full_Name;

                        }else{
                            if(selectedFolders[selectedDropbox] === undefined){
                                selectedFolders[selectedDropbox] = [];
                            }
                            selectedFolders[selectedDropbox].push(value.dirname)
                            selectedDropbox = value.dropboxFolder;
                        }



                        dataForSubmit[selectedDropbox] = {
                            selectedDropbox :selectedDropbox,
                            selectedAllFolders :selectedAllFolders,
                            selectedFolders :selectedFolders[selectedDropbox],
                        };
                    })



                }

                $.each(dataForSubmit, function(index, value) {
                    if(value.selectedFolders === undefined){
                        delete dataForSubmit[index]
                    }
                })


                dataForSubmit = JSON.stringify(dataForSubmit);
                $('#selectedFiles').val(dataForSubmit);

            }
        });
    });

    var eventPartners = @json($eventPartners);
    var eventInfos = @json($info)

    if('{{$event->syllabus}}' != '[]'){
        var eventSyllabus = @json('{{$event->syllabus[0]}}');
    }


    var table = $('#datatable-coupon').DataTable({
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });

    $(document).on('click',".edit-btn",function(){
        $(this).parent().parent().find('.dropdown-item').click()
    })

    $( "#input-delivery" ).change(function() {
        $('#free_course_list').val("")
        if($(this).val() == 139){
            $('.delivery_child_wrapper').removeClass('d-none')
            $('.delivery_city_wrapper').removeClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
            $('.elearning_exam_visible_wrapper').addClass('d-none')
            $('.exp_input').addClass('d-none')
            $('.exam_input').addClass('d-none')

            //
        }else if($(this).val() == 143){
            $('.delivery_child_wrapper').addClass('d-none')
            $('.elearning_visible_wrapper').removeClass('d-none')
            $('.elearning_exam_visible_wrapper').removeClass('d-none')
            $('.exp_input').removeClass('d-none')
            $('.exam_input').removeClass('d-none')

            $('#input-city_id').val('')
        }else if($(this).val() == 215){
            $('.exp_input').addClass('d-none')
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

            $('#input-partner_id').val(eventPartners)

        }else{
            $('.course-partner-list').addClass('d-none');
            $('.course-partner-list-visible').addClass('d-none');

            $('#input-partner_id').val([])
        }
    });

    $('#manager-toggle').change(function() {
        let status = $(this).prop('checked')

        if(status){
            $('.course-manager-list').removeClass('d-none');
            if(eventSyllabus !== undefined){
                $('#input-syllabus1').val(eventSyllabus.id).trigger('change');
            }

        }else{
            $('.course-manager-list').addClass('d-none');

            if(eventSyllabus !== undefined){
                $('#input-syllabus1').val('').trigger('change');
            }
        }
    });

    $('#award-toggle').change(function() {
        let status = $(this).prop('checked');

        if(status){

            $('.award-text').removeClass('d-none');
            if(eventInfos !== undefined){
                $('#input-award-text').val(eventInfos.course_awards_text);
            }

        }else{
            $('.award-text').addClass('d-none');
            $('#input-award-text').val('');
        }
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


    $(document).ready(function(){
        let status = $('#certification-toggle').prop('checked');
        if(status){
            $('.course-certification-visible-wrapper').removeClass('d-none');



            if(eventInfos !== undefined){

                $('#input-certificate_title').val(eventInfos.certificate.messages.success)
                //CKEDITOR.instances['input-certificate_title'].setData(eventInfos.certificate.messages.success)

                //CKEDITOR.instances['input-certificate_text_failure'].setData(eventInfos.certificate.messages.failure)
                $('#input-certificate_text_failure').val(eventInfos.certificate.messages.failure)
            }



        }else{
            $('.course-certification-visible-wrapper').addClass('d-none');
            tinymce.get("input-certificate_title").setContent("")
            tinymce.get("input-certificate_text_failure").setContent("")

            $('#input-certificate_type').val('')

        }
    })

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



            if(eventInfos !== undefined){

                $('#input-certificate_title').val(eventInfos.course_certification_name_success)
                $('#input-certificate_text_failure').val(eventInfos.course_certification_name_failure)
            }


            $('#input-certificate_type').val('')
        }else{
            $('.course-certification-visible-wrapper').addClass('d-none');

            $("#input-certificate_title_hidden").val("")
            $("#input-certificate_text_failure_hidden").val("")

            tinymce.get("input-certificate_title").setContent("")
            tinymce.get("input-certificate_text_failure").setContent("")

            $('#input-certificate_type').val('')

        }
    });



    $(function() {

        if($("#input-delivery").val() == 143){
            $('.exp_input').css('display', 'block')
            $('.exam_input').css('display', 'block')
        }else if($("#input-delivery").val() == 139){
            $('.exp_input').addClass('d-none')
            $('.exam_input').addClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
            $('.elearning_exam_visible_wrapper').addClass('d-none')
        }else if($("#input-delivery").val() == 215) {
            $('.exp_input').addClass('d-none')
            $('.exam_input').addClass('d-none')
            $('.elearning_visible_wrapper').addClass('d-none')
            $('.elearning_exam_visible_wrapper').addClass('d-none')
        }

    });

</script>

<script>

        $(document).on('click', '#access-student', function() {
            let status = $(this).prop('checked')

            if(status){
                $('.free-course-wrapper').removeClass('d-none')
                $('#elearning_exams_wrapper').removeClass('d-none')
            }else{
                $('.free-course-wrapper').addClass('d-none')
                $('#free_course_list').val("")

                $('#elearning_exams_wrapper').addClass('d-none')
            }
        })

        $(document).on('click', '#calculate-total-hours-btn', function() {
            $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
   			        type: 'GET',
   			        url: '/admin/events/totalHours/' + "{{$event->id}}",
   			        success: function (data) {

                        if(data.success){
                            $(".success-message p").html(data.message);
   	                        $(".success-message").show();



                            //$('#input-hours').val(Math.ceil(data.data/60)+'hr')
                            $('#input-hours').val(Math.ceil(data.data/60))

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }else{
                            $(".error-message p").html(data.message);
   	                        $(".error-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }



   			        },
   			        error: function() {
   			             //console.log(data);
   			        }
   			    });
        })

        $(document).on('click', '#payment-method-toggle', function() {
            let status = $(this).prop('checked');

            if(status){
                $('.payment-method-wrapper').removeClass('d-none');
            }else{
                $('.payment-method-wrapper').addClass('d-none');

                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
   			        type: 'POST',
   			        url: '/admin/events/remove-method/' + "{{$event->id}}",
                    data: {'payment_method': $(this).val()},
   			        success: function (data) {

                        if(data.success){
                            $(".success-message p").html(data.message);
   	                        $(".success-message").show();

                            $('#input-method').val('')

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }else{
                            $(".error-message p").html(data.message);
   	                        $(".error-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }



   			        },
   			        error: function() {
   			             //console.log(data);
   			        }
   			    });


            }
        });

        $(document).on('click','.close-modal',function(){

            $('#instFormControlSelect option').each(function(key, value) {
                    $(value).remove()
            });

            $('#instFormControlSelect').append(`<option value="" disabled selected>Choose instructor</option>`)
        });
        var getLocation = function(href) {
            var l = document.createElement("a");
            l.href = href;
            return l;
        };

        $("#modal-default").on("hide.bs.modal", function () {
            $("body").removeClass("modal-open").removeAttr("style");
        });

        $(document).on('click','#lesson_update_btn',function(e){
            let start = $('#time_starts').val()
            let date = $('#date').val()
            let end =  $('#time_ends').val()
            let room = $('#room').val()
            let location_url = $('#location_url').val()
            let topic_id = $('#topic_id').val()
            let event_id = $('#event_id').val()
            let lesson_id = $('#lesson_id').val()
            let instructor_id = $('#instFormControlSelect12').val()

            if(!date && event_type){

                alert('You must fill date field')
                return false;

            }else if(!start && event_type){

                alert('You must fill start time field')
                return false;

            }else if(!end && event_type){

                alert('You must fill end time field')
                return false;

            }

            data = {date:date, start:start, event_id:event_id, end:end, room:room,location_url:location_url, instructor_id:instructor_id, topic_id:topic_id, lesson_id:lesson_id}

            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/save_instructor",
                data:data,
                success: function(data) {
                    data = JSON.parse(data)

                    inst_media = data.instructor.medias

                    var base_url = window.location.origin


                    row =`
                        <span style="display:inline-block" class="avatar avatar-sm rounded-circle">
                            <img src="${window.location.origin + inst_media.path + inst_media.name + '-instructors-small' + inst_media.ext}" alt="${data.instructor.title+' '+data.instructor.subtitle}" style="max-width: 100px; max-height: 40px; border-radius: 25px" draggable="false">

                        </span>
                        <div style="display:inline-block">${data.instructor.title+' '+data.instructor.subtitle}</div>
                    `
                    $('#inst_lesson_edit_'+data.lesson_id).html(row)
                    $('#date_lesson_edit_'+data.lesson_id).text(data.date1)
                    $('#start_lesson_edit_'+data.lesson_id).text(data.start)
                    $('#end_lesson_edit_'+data.lesson_id).text(data.end)
                    $('#room_lesson_edit_'+data.lesson_id).text(data.room)

                    $('#modal-default').modal()
                    $('.close-modal').click()
                    $('#modal-default').modal('hide')

                }
            });

        });




</script>


<script>
    var event_type = false;
            function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;


            return [day,month,year].join('-');
        }

        $(document).on('click', '#remove_lesson', function() {
            var confirmation = confirm("are you sure you want to remove the item?");
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')

            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type : 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/remove_lesson",
                data:data,
                success: function(data){
                    data = JSON.parse(data)

                    $('#'+data.lesson_id).remove()
                }
            });
        });

        $(document).on('click','#open_modal',function(){
            let eleme = $('#lesson_details').find('.form-group')
            $.each(eleme, function(key, value){
                if(key != 0){
                    $(value).remove()
                }
            })
            $("#instFormControlSelect12").html("")
            $('#lesson_details').find('input').remove()
            //$('#lesson_details').empty()
            //$('#lesson_details').find('*').not('.instFormControl').remove();
            let id = 0
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')
            let instructor_id = $('#instFormControlSelect12').val()


            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/edit_instructor",
                data:data,
                success: function(data) {
                    date = ''

                    data = JSON.parse(data)
                    let instructors = data.instructors

                    event_type = data.isInclassCourse
                    let event_id = data.event

                    lesson = data.lesson.pivot


                    $('#modal-title-default').text(lesson.title)

                //    inst_row =  `<div class="form-group">
                //                     <label for="exampleFormControlSelect1">Select instructor</label>
                //                     <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control" id="instFormControlSelect">
                //                     </select>
                //                 </div>`

                    //$('#lesson_details').append(inst_row)

                    $.each( instructors, function( key, value ) {
                        //console.log(key+':'+value.title)
                        // $('#instFormControlSelect').append(`<option ${lesson.instructor_id == value.id ? 'selected' : ''} value="${value.id}">${value.title} ${value.subtitle}</option>`)
                        $('#instFormControlSelect12').append(`<option ${lesson.instructor_id == value.id ? 'selected' : ''} path="${value.medias.path}" original_name="${value.medias.original_name}" name="${value.medias.name}" ext="${value.medias.ext}" value="${value.id}">${value.title} ${value.subtitle}</option>`)
                    });


                    if(lesson.date != ''){
                        var date = new Date(lesson.date);
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear()
                    }else{
                        if(lesson.time_starts == null){
                            date = ""
                        }else{
                            var date = new Date(lesson.time_starts);
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear()
                        }

                    }



                    if(lesson.time_starts != null)
                    {
                        d = new Date(lesson.time_starts)
                        time_starts = d.toLocaleTimeString('it-IT')
                    }

                    if(lesson.time_ends != null)
                    {
                        d = new Date(lesson.time_ends)
                        time_ends = d.toLocaleTimeString('it-IT')

                    }


                    if(event_type){
                        let row = `
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control datepicker" name="date" id="date" placeholder="Select date" type="text" value="${date}" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="date">Time starts</label>
                                <input type="text" name="time_starts" class="form-control timepicker" id="time_starts" value="${lesson.time_starts != null ? time_starts : ''}" placeholder="Start">
                            </div>
                            <div class="form-group">
                                <label for="date">Time ends</label>
                                <input type="text" name="time_ends" class="form-control timepicker" id="time_ends" value="${lesson.time_ends != null ? time_ends : ''}" placeholder="End">
                            </div>
                            <div class="form-group">
                                <label for="date">Location</label>
                                <input type="text" name="room" class="form-control" id="room" value="${lesson.room != null ? lesson.room : ''}" placeholder="Location">
                            </div>

                            <div class="form-group">
                                <label for="date">URL</label>
                                <input type="text" name="location_url" class="form-control" id="location_url" value="${lesson.location_url ? lesson.location_url : ''}" placeholder="URL">
                            </div>

                        `

                        $('#lesson_details').append(row)
                        var datePickerOptions = {
                            format: 'dd-mm-yyyy',
                            firstDay: 1,
                            changeMonth: true,
                            changeYear: true,
                            // ...
                        }
                        $(".datepicker").datepicker(datePickerOptions);
                        /*$('#time_starts').timepicker({
                            timeFormat: 'h:mm p',

                            minTime: '10',
                            maxTime: '6:00pm',
                            defaultTime: '11',
                            startTime: '10:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true,
                            zindex: 9999999
                        });*/
                        $('.timepicker').timepicker({
                            timeFormat: 'HH:mm',
                            zindex: 9999999,
                            interval: 5,
                            minTime: '00:00',
                            maxTime: '23:55',
                            dynamic: false,
                        });






                    }

                    let input_hidden = `
                            <input type="hidden" name="topic_id" id="topic_id" value="${data.topic_id}">
                            <input type="hidden" name="event_id" id="event_id" value="${data.event.id}">
                            <input type="hidden" name="lesson_id" id="lesson_id" value="${data.lesson_id}">
                        `

                    $('#lesson_details').append(input_hidden)



                    $('#modal-default').modal('show');
                }
            });

        });
</script>
<script>
        $(document).on('change',"#input-method",function(){

            if($(this).val()){
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
   			        type: 'POST',
   			        url: '/admin/events/assing-method/' + "{{$event->id}}",
                    data: {'payment_method': $(this).val()},
   			        success: function (data) {

                        if(data.success){
                            $(".success-message p").html(data.message);
   	                        $(".success-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }else{
                            $(".error-message p").html(data.message);
   	                        $(".error-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }



   			        },
   			        error: function() {
   			             //console.log(data);
   			        }
   			    });

            }


        })
</script>
<script>
        $('.submit-btn').on('click', function(){

            if($("#input-category_id").val() != $("#old-category").val() && $("#old-category").val() != -1){

                if(confirm("You are gonna change the event category. All data of instructors and lessons will be lost. Do you want to continue?")){
                    $('#event_edit_form').submit();
                    //document.getElementById('event_edit_form').submit();
                }
                else{
                    $("#input-category_id").val($("#old-category").val()).change();
                }

            }else{
                $('#event_edit_form').submit();
                //document.getElementById('event_edit_form').submit();
            }
        })

        $('#submit-seo-btn').on('click', function() {
            $('#seo-form').submit();
        })

        $('#tabs-icons-text button, #mobile_menu .dropdown-item').on('click', function(){

            let btn_clicked = $(this).attr('href')

            if(btn_clicked == '#metas'){
                $('.general-save-wrapper').addClass('d-none')
                $('.seo-save-wrapper').removeClass('d-none')
            }else{
                $('.general-save-wrapper').removeClass('d-none')
                $('.seo-save-wrapper').addClass('d-none')
            }
        })



</script>

<script>

    instructors = @json($instructors1);

    $(document).ready(function(){

        if('{{old('tab')}}' != ''){
            //$('#'+'{{old('tab')}}').trigger('click')
            $('#tab_inside_tab #tabs-icons-text-12-tab_inside').trigger('click')
        }

        $("#input-syllabus1").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions,
            placeholder: "Please select the instructor/manager of this course",
        });

        $("#instFormControlSelect12").select2({
                templateResult: formatOptions,
                templateSelection: formatOptions,
                dropdownParent: $("#modal-default")
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


    $(document).on('click', '.coupon.custom-toggle-slider', function(){

        let event_id = ($(this).parent().find('.coupon-input')).data('event-id')
        let coupon_id = ($(this).parent().find('.coupon-input')).data('coupon-id')
        let status = ($(this).parent().find('.coupon-input')).data('status')

        if (status) {
            $(this).parent().find('.coupon-input').data('status',0)
        }else{
            $(this).parent().find('.coupon-input').data('status',1)
        }

        let data = {'event':event_id, 'coupon' : coupon_id,'status':status}

        $.ajax({
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            Accept: 'application/json',
            url: "/admin/events/assing-coupon/" + event_id +"/" + coupon_id,
            data:data,
            success: function(data) {

            }
        });

    })


    $('.enroll-students').change(function(){

        let enroll = $("#input-enroll").is(":checked") ? 1 : 0;
        $.ajax({
            type: 'get',
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            Accept: 'application/json',
            url: "/admin/enroll-to-elearning/" + "{{$event->id}}" +"/" + enroll,
            success: function(data) {

            }
        });

    })


    $('.index-toggle').change(function(){

       let index = $("#input-index").is(":checked") ? 1 : 0;

       $.ajax({
           type: 'get',
           headers: {
           'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           },
           Accept: 'application/json',
           url: "/admin/change-index/" + "{{$event->id}}" +"/" + index,
           success: function(data) {

           }
       });

   })

   $('.feed-toggle').change(function(){

       let feed = $("#input-feed").is(":checked") ? 1 : 0;

       $.ajax({
           type: 'get',
           headers: {
           'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           },
           Accept: 'application/json',
           url: "/admin/change-feed/" + "{{$event->id}}" +"/" + feed,
           success: function(data) {

           }
       });

   })


</script>

<script>
//$("#input-release_date_file")
var datePickerOptions = {
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
    }
    $("#input-release_date_file").datepicker(datePickerOptions);
    $("#input-launch-input").datepicker(datePickerOptions);


</script>

{{--<script>
    $("#syllabus-pdf").click(function(){
        window
    })
</script>--}}

<script>
    var current_icon_input = null
    var current_icon_input_alt_text = null
    $(document).on('click','button',function(){
        if($(this).hasClass('seo')){
            $(".form_event_btn").hide();
            $(".form_event_btn_new").addClass('d-none');
        }else if($(this).hasClass('settings-btn')){
            $(".form_event_btn").show();
            $(".form_event_btn_new").addClass('d-none');
        }else if($(this).data( "toggle" )){
            $(".form_event_btn_new").removeClass('d-none');
        }
    });



    $(document).on('click', '.input-icon-wrapper, .input-icon-wrapper-inclass, .input-icon-wrapper-city', function() {
        let btn = $(this).data('infowrapper')
        if(btn === undefined){
            return false;
        }
        document.getElementById('image-input-button').click();
        current_icon_input = btn+'_path';
        current_icon_input_alt_text = btn+'_alt_text';
    })



    $(document).ready(function(){


        function setActiveLabelMobileMenu(){
            let items = $('#mobile_menu').find('.dropdown-item')
            $.each(items, function(index, value) {
                if($(value).hasClass('active') && !$(value).hasClass('download')){
                    $('#dropdownMenuButton').text($(value).text())
                }
            })
        }

        mobileMenu()
        //setActiveLabelMobileMenu()

        $(window).on('resize', function(){
            mobileMenu()
        })

        $(document).on('click', '#mobile_menu .dropdown-item', function() {
            let items = $('#mobile_menu').find('.dropdown-item')

            $.each(items, function(index, value) {
                $(value).removeClass('active')
            })

            $(this).addClass('active')
            setActiveLabelMobileMenu()

        })

        $(document).on('click', '#tab_inside_tab_mobile .nav-link ', function() {
            let items = $('#tab_inside_tab_mobile').find('.nav-link')

            $.each(items, function(index, value) {
                $(value).removeClass('active')
            })


            $(this).addClass('active')
        })


    })

    function mobileMenu(){
        var mobileWidth = 680;

        if($(window).width() <= mobileWidth){

            $('#mobile_menu').removeClass('d-none')
            $('.nav-wrapper.tab-buttons').addClass('d-none')

            $('.tab-pane-mobile').removeClass('d-none');
            $('#tab_inside_tab').addClass('has_mobile_menu');

        }else{

            $('#mobile_menu').addClass('d-none')
            $('.nav-wrapper.tab-buttons').removeClass('d-none')

            $('.tab-pane-mobile').addClass('d-none');
            $('#tab_inside_tab').removeClass('has_mobile_menu');

        }
        var fixOwl = function(){
        var $stage = $('.owl-stage'),
            stageW = $stage.width(),
            $el = $('.owl-item'),
            elW = 0;
        $el.each(function() {
            elW += $(this).width()+ +($(this).css("margin-right").slice(0, -2))
        });
        if ( elW > stageW ) {
            $stage.width( elW );
        };
    }

        $(document).ready(function(){
            $(".owl-carousel").owlCarousel({
                items:2,
                margin:10,
                nav:true,
                navText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'],
                onInitialized: fixOwl,
                onRefreshed: fixOwl,
                responsive:{
                    0:{
                        items:2,
                        nav:true,

                    },
                    600:{
                        items:3,
                        nav:true
                    },
                    1000:{
                        items:5,
                        nav:true,
                        loop:false
                    }
                }
            });
        });

    }
</script>
<script>
    $( document ).ready(function() {
        let show_popup = @json($show_popup)

        if(show_popup == 1){

            Swal.fire({
                type: 'info',
                title: 'Please allow 1 minute for changes to take effects',
                text: '',
                footer: ''
            })

        }

        var getUrl = window.location;
        var pathname = getUrl.pathname
        var event = pathname.split("/");
        event = event[event.length - 2]


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '/admin/events/statistics/'+event,
            success: function (data) {

                let stats = data.data;

                let income = stats.income;
                let count = stats.count;
                let incomeInstalments = stats.incomeInstalments;

                $('#student_total').text(count.total)
                $('#students_paid').text(count.regular + count.special + count.alumni + count.early)
                $('#students_free').text(count.free)

                $('#income-total').text('€ '+(income.total).toLocaleString())
                $('#income-early').text('€ '+(income.early).toLocaleString())
                $('#income-alumni').text('€ '+(income.alumni).toLocaleString())
                $('#income-special').text('€ '+(income.special).toLocaleString())
                $('#income-regular').text('€ '+(income.regular).toLocaleString())
                $('#income-subscription').text('€ '+(income.subscription).toLocaleString())

                $('#installments-total').text('€ '+(Math.round(incomeInstalments.total)).toLocaleString())
                $('#installments-early').text('€ '+(Math.round(incomeInstalments.early)).toLocaleString())
                $('#installments-alumni').text('€ '+(Math.round(incomeInstalments.alumni)).toLocaleString())
                $('#installments-special').text('€ '+(Math.round(incomeInstalments.special)).toLocaleString())
                $('#installments-regular').text('€ '+(Math.round(incomeInstalments.regular)).toLocaleString())
                $('#installments-subscription').text('€ '+(Math.round(incomeInstalments.subscription)).toLocaleString())

                $('.widget .loader').addClass('d-none')
                $('.widget .info').removeClass('d-none')


            }
        });



    });
</script>

@endpush


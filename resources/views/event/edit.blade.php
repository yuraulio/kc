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
    <?php //container-fluid mt--6 ?>
    <div class="">
        <div class="row">
            <div class="col">
            <div class="">
            <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0 ml-1">{{ $event['title'] }}</h3>
                            </div>
                        </div>
                    </div>
                <div class="nav-wrapper tab-buttons">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row event-tabs" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            {{--<a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-settings-gear-65 mr-2"></i>Settings</a>--}}
                            <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#tabs-icons-text-1" role="tab" type="button">
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

                        <li class="nav-item">
                            <button class="btn btn-icon btn-primary" data-toggle="tab"  href="#waiting_list" role="tab" type="button">
                            	<span class="btn-inner--icon"><i class="ni ni-hat-3"></i></span>
                                <span class="btn-inner--text">Waiting List Students</span>
                            </button>
                        </li>

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

                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                    <div class="col-12 mt-2">
                        @include('alerts.success')
                        @include('alerts.errors')
                    </div>


                        <div class="form_event_btn">
                            <div class="save_event_btn" >@include('admin.save.save',['event' => isset($event) ? $event : null])</div>
                            <div class="preview_event_btn">@include('admin.preview.preview',['slug' => isset($slug) ? $slug : null])</div>

                        </div>
                        <form method="post" id="event_edit_form" method="POST" action="{{ route('events.update', $event) }}" autocomplete="off"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">


                                <div class="row">


                                    <div class="col-md-6 col-sm-6">

                                        @if($event->is_inclass_course() && isset($slug))
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-method">{{ __('Syllabus Pdf') }}</label>
                                                <div class="form-group">

                                                    <a target="_blank" href="/print/syllabus/{{$slug->slug}}" id="syllabus-pdf" class="btn btn-primary">Get Pdf</a>

                                                </div>
                                            </div>
                                        @endif




                                        <div class="form-group">
                                            <label class="form-control-label" for="input-method">{{ __('Enroll Students to E-Learning') }}</label>
                                            <div style="margin: auto;" class="form-group">

                                                <label class="custom-toggle enroll-toggle enroll-students">
                                                    <input type="checkbox" id="input-enroll" @if($event['enroll']) checked @endif>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="enroll" data-label-on="unroll"></span>
                                                </label>

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-6 col-sm-6 is-flex"  style="padding:0">
                                        <div style="margin: auto 0;" class="col-md-3 col-sm-3">
                                            <div style="margin: auto;" class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">



                                            <label class="custom-toggle custom-published">
                                                <input type="checkbox" name="published" id="input-published" @if($event['published']) checked @endif>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                            </label>
                                            @include('alerts.feedback', ['field' => 'published'])



                                            </div>
                                        </div>
                                        @if($event['published_at'] != null)
                                        <div class="col-md-6 col-sm-6 is-flex">

                                            <div class="form-group col-sm-6">
                                                <label class="form-control-label" for="launch_date">{{ __('Launch Date') }}</label>
                                                <input type="text" name="launch_date" type="text" id="input-launch-input"
                                                            value="{{ date('d-m-Y',strtotime(old('launch_date', $event->launch_date))) }}" class="form-control datepicker" />

                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="form-control-label" for="input-published">{{ __('Published at') }}</label>
                                                <input type="text" name="published_at" type="text" id="input-published-input"
                                                            value="{{ date('d-m-Y',strtotime(old('published_at', $event->published_at))) }}" class="form-control" disabled />

                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="row">


                                    <div class="col-md-6 col-sm-6">


                                        <div class="form-group">
                                            <label class="form-control-label" for="input-method">{{ __('Index') }}</label>
                                            <div style="margin: auto;" class="form-group">

                                                <label class="custom-toggle index-toggle">
                                                    <input type="checkbox" id="input-index" @if($event['index']) checked @endif>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6">


                                        <div class="form-group">
                                            <label class="form-control-label" for="input-method">{{ __('Feed') }}</label>
                                            <div style="margin: auto;" class="form-group">

                                                <label class="custom-toggle feed-toggle">
                                                    <input type="checkbox" id="input-feed" @if($event['feed']) checked @endif>
                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                </label>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @if($event->exam()->first())
                                <div class="form-group">
                                    <label class="form-control-label" for="input-method">{{ __('Export Certificates') }}</label>
                                    <div class="form-group">

                                        <a href="/admin/events/export-certificates/{{$event->id}}"  class="btn btn-primary"> {{ __('Export Certificates') }} </a>

                                    </div>
                                </div>
                                @endif

                                <div class="form-group">


                                    <div class="form-group{{ $errors->has('fb_') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-hours">{{ __('Absences Limit(%)') }}</label>
                                        <input type="text" name="absences_limit" id="input-absences_limit" class="form-control{{ $errors->has('Absences Limit(%)') ? ' is-invalid' : '' }}" placeholder="{{ __('absences_limit') }}" value="{{ old('absences_imit', $event->absences_limit) }}"autofocus>

                                        @include('alerts.feedback', ['field' => 'absences_imit'])
                                    </div>

                                    <label class="form-control-label" for="input-method">{{ __('Method Payment') }}</label>
                                    <select name="payment_method" id="input-method" class="form-control" placeholder="{{ __('Method Payment') }}" no-mouseflow>
                                        <option value="">-</option>
                                        @foreach ($methods as $method)
                                            <option value="{{ $method->id }}" {{$event['paymentMethod']->first() && $event['paymentMethod']->first()->id ==$method->id ? 'selected' : ''}} >{{ $method->method_name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'payment_method'])
                                </div>

                                <div class="form-group">
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
                                </div>

                                <div class="form-group">


                                    <div class="form-group{{ $errors->has('fb_') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-hours">{{ __('Certificate Title') }}</label>
                                        <textarea type="text" name="certificate_title" id="input-certificate_title" class="ckeditor form-control{{ $errors->has('certificate_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Certificate Title') }}" autofocus>{{ old('certificate_title', $event->certificate_title) }}</textarea>

                                        @include('alerts.feedback', ['field' => 'certificate_title'])
                                    </div>


                                </div>


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


                                @include('admin.city.event.index')

                                @include('admin.partner.event.index')

                                <div class="form-group{{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_id">{{ __('Type') }}</label>
                                    <select multiple name="type_id[]" id="input-type_id" class="form-control" placeholder="{{ __('Type') }}" required>
                                        <option value="">-</option>

                                        @foreach ($types as $type)
                                        <?php $found = false; ?>
                                            <option <?php if(count($event->type) != 0){
                                                foreach($event->type as $selected_type){
                                                    if($selected_type['id'] == $type['id']){
                                                        $found = true;
                                                    }
                                                }
                                                if($found){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?> value="{{ $type->id }}" >{{ $type->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'type_id'])
                                </div>


                                <div class="form-group{{ $errors->has('delivery') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Delivery') }}</label>
                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="{{ __('Delivery') }}" required>
                                        <option value="">-</option>
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

                                    @include('alerts.feedback', ['field' => 'delivery'])
                                </div>



                                <div id="exp_input" class="form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration', $event->expiration) }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
                                </div>


                                <div class="form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Access to files until') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input class="form-control datepicker" id="input-release_date_file" name="release_date_files" placeholder="Select date" type="text" @if(old('release_date_files', $event->release_date_files) && old('release_date_files', $event->release_date_files) !='1970-01-01 00:00:00') value="{{ date('d-m-Y',strtotime(old('release_date_files', $event->release_date_files))) }}" @endif>
                                        </div>
                                        @include('alerts.feedback', ['field' => 'release_date_files'])
                                    </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <input hidden name="old_status" value="{{$event['status']}}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                        <option value="">-</option>
                                            <option <?= ($event['status'] == 4) ? "selected" : ''; ?> value="4">{{ __('My Account Only') }}</option>
                                            <option <?= ($event['status'] == 2) ? "selected" : ''; ?> value="2">{{ __('Soldout') }}</option>
                                            <option <?= ($event['status'] == 3) ? "selected" : ''; ?> value="3">{{ __('Completed') }}</option>
                                            <option <?= ($event['status'] == 0) ? "selected" : ''; ?> value="0">{{ __('Open') }}</option>
                                            <option <?= ($event['status'] == 1) ? "selected" : ''; ?> value="1">{{ __('Close') }}</option>
                                            <option <?= ($event['status'] == 5) ? "selected" : ''; ?> value="5">{{ __('Waiting') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="text" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours', $event->hours) }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>


                                <div class="form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-syllabus1">{{ __('Syllabus Manager') }}</label>
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

                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                <div class="nav-wrapper">
                                    <ul id="tab_inside_tab" class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_inside" data-toggle="tab" href="#tabs-icons-text-1_inside" role="tab" aria-controls="tabs-icons-text-1_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Overview</a>
                                        </li>
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#metas" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Metas</a>
                                        </li>--}}
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab_inside" data-toggle="tab" href="#tabs-icons-text-2_inside" role="tab" aria-controls="tabs-icons-text-2_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Summary </a>
                                        </li>--}}
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab_inside" data-toggle="tab" href="#tabs-icons-text-3_inside" role="tab" aria-controls="tabs-icons-text-3_inside" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Benefit</a>
                                        </li>--}}
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                        </li>
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab_inside" data-toggle="tab" href="#tabs-icons-text-6_inside" role="tab" aria-controls="tabs-icons-text-6_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>City</a>
                                        </li>--}}
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                        </li>
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#tabs-icons-text-8_inside" role="tab" aria-controls="tabs-icons-text-8_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Partners</a>
                                        </li>--}}
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#tabs-icons-text-9_inside" role="tab" aria-controls="tabs-icons-text-9_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Sections</a>
                                        </li>--}}
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-10-tab_inside" data-toggle="tab" href="#tabs-icons-text-10_inside" role="tab" aria-controls="tabs-icons-text-10_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Faqs</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#tabs-icons-text-11_inside" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#testimonials-tab" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Testimonials</a>
                                        </li>
                                        {{--<li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#coupons" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Coupons</a>
                                        </li>--}}

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

                                                               <label class="custom-toggle enroll-toggle">
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
                                                            <textarea name="body" id="input-body"  class="ckeditor form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" placeholder="{{ __('Body') }}" required autofocus>{{ old('body', $event->body) }}</textarea>

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

                                            {{--<div class="tab-pane fade" id="tabs-icons-text-2_inside" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab_inside">
                                                @include('admin.summary.summary', ['model' => $event,'sections' => $sections])
                                            </div>--}}
                                            {{--<div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                @include('admin.metas.metas',['metas' => $metas])
                                            </div>--}}

                                            {{--<div class="tab-pane fade" id="coupons" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">

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

                                            </div>--}}

                                            <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                @include('admin.videos.event.index',['model' => $event])
                                            </div>



                                            {{--<div class="tab-pane fade" id="tabs-icons-text-3_inside" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab_inside">
                                                @include('admin.benefits.benefits',['model' => $event, 'sections' => $sections])
                                            </div>--}}
                                            <div class="tab-pane fade show" id="tabs-icons-text-4_inside" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab_inside">
                                                @include('topics.event.instructors',['sections' => $sections])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-5_inside" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab_inside">
                                                @include('admin.ticket.index', ['model' => $event, 'sections' => $sections])
                                            </div>
                                            {{--<div class="tab-pane fade" id="tabs-icons-text-6_inside" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab_inside">
                                                @include('admin.city.event.index', ['model' => $event])
                                            </div>--}}
                                            <div class="tab-pane fade" id="tabs-icons-text-7_inside" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab_inside">
                                                @include('admin.venue.event.index', ['model' => $event,'sections' => $sections])
                                            </div>
                                            {{--<div class="tab-pane fade" id="tabs-icons-text-8_inside" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                @include('admin.partner.event.index', ['model' => $event])
                                            </div>--}}
                                            {{--<div class="tab-pane fade" id="tabs-icons-text-9_inside" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                @include('admin.section.index', ['model' => $event])
                                            </div>--}}
                                            <div class="tab-pane fade" id="tabs-icons-text-10_inside" role="tabpanel" aria-labelledby="tabs-icons-text-10-tab_inside">
                                                @include('admin.faq.index', ['model' => $event,'sections' => $sections])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-11_inside" role="tabpanel" aria-labelledby="tabs-icons-text-11-tab_inside">

                                                @include('admin.upload.upload', ['event' => ($event->medias != null) ? $event->medias : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']])

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

                                                           <label class="custom-toggle enroll-toggle">
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

                                                       <label class="custom-toggle enroll-toggle">
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


                            <div class="tab-pane fade" id="waiting_list" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                @include('event.students_waiting_list')
                            </div>

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
        </div>
        @include('layouts.footers.auth')
    </div>

    <div class="row">
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

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables-datetime/datetime.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>



<script>

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
        if($(this).val() == 143){
            $('#exp_input').css('display', 'block')
        }else{
            $('#exp_input').css('display', 'none')
        }
    });

    $(function() {

        if($("#input-delivery").val() == 143){
                $('#exp_input').css('display', 'block')
            }
        });
</script>

<script>

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

            data = {date:date, start:start, event_id:event_id, end:end, room:room, instructor_id:instructor_id, topic_id:topic_id, lesson_id:lesson_id}

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
                                <label for="date">Room</label>
                                <input type="text" name="room" class="form-control" id="room" value="${lesson.room != null ? lesson.room : ''}" placeholder="Room">
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
        $('#submit-btn').on('click', function(){

            if($("#input-category_id").val() != $("#old-category").val() && $("#old-category").val() != -1){

                if(confirm("You are gonna change the event category. All data of instructors and lessons will be lost. Do you want to continue?")){
                    $('#event_edit_form').submit();
                }
                else{
                    $("#input-category_id").val($("#old-category").val()).change();
                }

            }else{
                $('#event_edit_form').submit();
            }
        })



</script>

<script>

    instructors = @json($instructors1);

    $(document).ready(function(){

        if('{{old('tab')}}' != ''){
            $('#'+'{{old('tab')}}').trigger('click')
            $('#tabs-icons-text-11-tab_inside').trigger('click')
        }

        $("#input-syllabus1").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions
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
        console.log('dfsd');
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
    $(document).on('click','button',function(){
        if($(this).hasClass('seo')){
            $(".form_event_btn").hide();
        }else{
            $(".form_event_btn").show();
        }
    });
</script>

@endpush



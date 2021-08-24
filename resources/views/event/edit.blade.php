@extends('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
])

@section('content')
    @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
            @slot('title')
                {{ __($event['title']) }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('Events Management') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Event') }}</li>
        @endcomponent
    @endcomponent
    <?php //dd($event->type); ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Students</a>
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

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <form method="post" id="event_edit_form" method="POST" action="{{ route('events.update', $event) }}" autocomplete="off"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                <div class="form-group">
                                    <label class="form-control-label" for="input-method">{{ __('Method Payment') }}</label>
                                    <select name="payment_method" id="input-method" class="form-control" placeholder="{{ __('Method Payment') }}" no-mouseflow>
                                        <option value="">-</option>
                                        @foreach ($methods as $method)
                                            <option value="{{ $method->id }}" {{$event['paymentMethod']->first() && $event['paymentMethod']->first()->id ==$method->id ? 'selected' : ''}} >{{ $method->method_name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'payment_method'])
                                </div>


                                <div class="form-group{{ $errors->has('category_id') ? ' has-danger' : '' }}">
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

                                <div class="row">

                                    <div style="margin: auto;" class="col-md-6 col-sm-6">
                                        <div style="margin: auto;" class="form-group{{ $errors->has('published') ? ' has-danger' : '' }}">



                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="published" id="input-published" @if($event['published']) checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'published'])



                                        </div>
                                    </div>
                                    @if($event['published_at'] != null)
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-published">{{ __('Published at') }}</label>
                                            <input type="text" placeholder="{{$event['published_at']}}" class="form-control" disabled />
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div id="exp_input" class="form-group{{ $errors->has('expiration') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-expiration">{{ __('Months access') }}</label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control{{ $errors->has('expiration') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter number of months') }}" value="{{ old('expiration', $event->expiration) }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'expiration'])
                                </div>

                                <?php
                                $date = date_create($event->release_date_files);
                                $old_date = date_format($date,"d/m/Y");
                                    ?>
                                <div class="form-group{{ $errors->has('release_date_files') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-delivery">{{ __('Release Date Files') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input class="form-control datepicker" id="input-release_date_file" name="release_date_files" placeholder="Select date" type="text" value="{{ old('release_date_files', $old_date) }}">
                                        </div>
                                        @include('alerts.feedback', ['field' => 'release_date_files'])
                                    </div>

                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                    <select name="status" id="input-status" class="form-control" placeholder="{{ __('Status') }}" >
                                        <option value="">-</option>
                                            <option <?= ($event['status'] == 4) ? "selected" : ''; ?> value="4">{{ __('My Account Only') }}</option>
                                            <option <?= ($event['status'] == 2) ? "selected" : ''; ?> value="2">{{ __('Soldout') }}</option>
                                            <option <?= ($event['status'] == 3) ? "selected" : ''; ?> value="3">{{ __('Completed') }}</option>
                                            <option <?= ($event['status'] == 0) ? "selected" : ''; ?> value="0">{{ __('Open') }}</option>
                                            <option <?= ($event['status'] == 1) ? "selected" : ''; ?> value="1">{{ __('Close') }}</option>
                                    </select>

                                    @include('alerts.feedback', ['field' => 'status'])
                                </div>

                                <div class="form-group{{ $errors->has('hours') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-hours">{{ __('Hours') }}</label>
                                    <input type="text" name="hours" id="input-hours" class="form-control{{ $errors->has('hours') ? ' is-invalid' : '' }}" placeholder="{{ __('Hours') }}" value="{{ old('hours', $event->hours) }}"autofocus>

                                    @include('alerts.feedback', ['field' => 'hours'])
                                </div>
                                <?php //dd($event->instructors); ?>
                                <div class="form-group{{ $errors->has('syllabus') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-syllabus1">{{ __('Syllabus Manager') }}</label>
                                            <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus1" class="form-control" placeholder="{{ __('Syllabus Manager') }}">
                                                <option value=""></option>


                                                @foreach ($instructors1 as $key => $instructor)
                                                <?php //dd($key); ?>

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
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab_inside" data-toggle="tab" href="#tabs-icons-text-2_inside" role="tab" aria-controls="tabs-icons-text-2_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Summary </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab_inside" data-toggle="tab" href="#tabs-icons-text-3_inside" role="tab" aria-controls="tabs-icons-text-3_inside" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Benefit</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab_inside" data-toggle="tab" href="#tabs-icons-text-6_inside" role="tab" aria-controls="tabs-icons-text-6_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>City</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#tabs-icons-text-8_inside" role="tab" aria-controls="tabs-icons-text-8_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Partners</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#tabs-icons-text-9_inside" role="tab" aria-controls="tabs-icons-text-9_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Sections</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-10-tab_inside" data-toggle="tab" href="#tabs-icons-text-10_inside" role="tab" aria-controls="tabs-icons-text-10_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Faqs</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#tabs-icons-text-11_inside" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#coupons" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Coupons</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#metas" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Metas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#videos" role="tab" aria-controls="videos" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Videos</a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">

                                            <div class="tab-pane fade show active" id="tabs-icons-text-1_inside" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab_inside">



                                                    <h6 class="heading-small text-muted mb-4">{{ __('Event information') }}</h6>
                                                    <div class="pl-lg-4">



                                                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                                                            <input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title', $event->title) }}" required autofocus>

                                                            @include('alerts.feedback', ['field' => 'title'])
                                                        </div>
                                                        @include('admin.slug.slug',['slug' => isset($slug) ? $slug : null])
                                                        <div class="form-group{{ $errors->has('htmlTitle') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-htmlTitle">{{ __('HTML Title') }}</label>
                                                            <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control{{ $errors->has('htmlTitle') ? ' is-invalid' : '' }}" placeholder="{{ __('HTML Title') }}" value="{{ old('Short title', $event->htmlTitle) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'htmlTitle'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-subtitle">{{ __('Subtitle') }}</label>
                                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('subtitle') }}" value="{{ old('Subtitle', $event->subtitle) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'subtitle'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('header') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-header">{{ __('Header') }}</label>
                                                            <input type="text" name="header" id="input-header" class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" placeholder="{{ __('Header') }}" value="{{ old('header', $event->header) }}" autofocus>

                                                            @include('alerts.feedback', ['field' => 'header'])
                                                        </div>

                                                        <div class="form-group{{ $errors->has('summary') ? ' has-danger' : '' }}">
                                                            <label class="form-control-label" for="input-summary">{{ __('XML Summary') }}</label>
                                                            <textarea name="summary" id="input-summary"  class="ckeditor form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" placeholder="{{ __('Summary') }}" required autofocus>{{ old('summary', $event->summary) }}</textarea>

                                                            @include('alerts.feedback', ['field' => 'summary'])
                                                        </div>

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
                                            </form>
                                            <div class="tab-pane fade" id="tabs-icons-text-2_inside" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab_inside">
                                                @include('admin.summary.summary', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                @include('admin.metas.metas',['metas' => $metas])
                                            </div>

                                            <div class="tab-pane fade" id="coupons" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">

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

                                            <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                @include('admin.videos.event.index',['model' => $event])
                                            </div>

                                            <div class="tab-pane fade" id="tabs-icons-text-3_inside" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab_inside">
                                                @include('admin.benefits.benefits',['model' => $event])
                                            </div>
                                            <div class="tab-pane fade show" id="tabs-icons-text-4_inside" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab_inside">
                                                @include('topics.event.instructors')
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-5_inside" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab_inside">
                                                @include('admin.ticket.index', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-6_inside" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab_inside">
                                                @include('admin.city.event.index', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-7_inside" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab_inside">
                                                @include('admin.venue.event.index', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-8_inside" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                @include('admin.partner.event.index', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-9_inside" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                @include('admin.section.index', ['model' => $event])
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-10_inside" role="tabpanel" aria-labelledby="tabs-icons-text-10-tab_inside">
                                                @include('admin.faq.index', ['model' => $event])
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                @include('event.students')
                            </div>

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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        <button type="button" class="btn btn-link ml-auto close-modal" data-dismiss="modal">Close</button>
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

        $(document).on('click','#lesson_update_btn',function(e){
            let start = $('#time_starts').val()
            let date = $('#date').val()
            let end =  $('#time_ends').val()
            let room = $('#room').val()
            let topic_id = $('#topic_id').val()
            let event_id = $('#event_id').val()
            let lesson_id = $('#lesson_id').val()
            let instructor_id = $('#instFormControlSelect12').val()


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

                }
            });

        });




</script>


<script>
            function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;


            return [day,month,year].join('/');
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

                    let event_type = data.isInclassCourse
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
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + date.getFullYear()
                    }else{
                        if(lesson.time_starts == null){
                            date = ""
                        }else{
                            var date = new Date(lesson.time_starts);
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + date.getFullYear()
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
                            dateFormat: 'Y-m-d',
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
            $('#event_edit_form').submit()
        })



</script>

<script>

    instructors = @json($instructors1);

    $(document).ready(function(){
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


</script>

@endpush



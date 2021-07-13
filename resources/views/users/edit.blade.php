<?php use App\Model\Media; ?>
@extends('layouts.app', [
    'title' => __('User Profile'),
    'navClass' => 'bg-default',
    'parentSection' => 'laravel',
    'elementName' => 'profile'
])

@section('content')
    @include('forms.header', [
        'title' => __('Hello') . ' '. $user['firstname'] . ' '. $user['lastname'],
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-7'
    ])


    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-4 order-xl-2">
                <div class="card card-profile">
                    <img src="{{ asset('argon') }}/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                <?php //dd($user->image->name); ?>
                                @if(isset($user->image->original_name))
                                <?php
                                    if($user->image->details != null){
                                        //dd('with details');
                                        $details = json_decode($user->image->details, true);
                                        //dd($details);
                                    }
                                    //dd(asset($user->image->path.$user->image->original_name.'-crop'.$user->image->ext));

                                    //$user->image->original_name;
                                    $name1 = explode('.',$user->image->original_name);


                                    if(file_exists(asset($user->image->path.$name1[0].'-crop'.$user->image->ext))){
                                        $path = asset($user->image->path.$name1[0].'-crop'.$user->image->ext);
                                    }else{
                                        $path = asset($user->image->path.$user->image->original_name);
                                    }
                                ?>
                                <?php //dd($path); ?>
                                    <img src="{{ $path }}" class="rounded-circle">
                                @else
                                <img src="" alt="{{$user['firstname']}}" class="rounded-circle">
                                @endif
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-sm btn-info mr-4">Connect</a>
                            <a href="#" class="btn btn-sm btn-default float-right">Message</a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center">
                                    <div>
                                        <span class="heading">22</span>
                                        <span class="description">Friends</span>
                                    </div>
                                    <div>
                                        <span class="heading">10</span>
                                        <span class="description">Photos</span>
                                    </div>
                                    <div>
                                        <span class="heading">89</span>
                                        <span class="description">Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="h3">
                                {{ $user['fisrtname'] . ' '.  $user['lastname']}}<span class="font-weight-light">, 27</span>
                            </h5>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>Bucharest, Romania
                            </div>
                            <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Progress track -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <!-- Title -->
                        <h5 class="h3 mb-0">Progress track</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <!-- List group -->
                        <ul class="list-group list-group-flush list my--3">
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <a href="#" class="avatar rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/bootstrap.jpg">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5>Argon Design System</h5>
                                        <div class="progress progress-xs mb-0">
                                            <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <a href="#" class="avatar rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/angular.jpg">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5>Angular Now UI Kit PRO</h5>
                                        <div class="progress progress-xs mb-0">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <a href="#" class="avatar rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/sketch.jpg">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5>Black Dashboard</h5>
                                        <div class="progress progress-xs mb-0">
                                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <a href="#" class="avatar rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/react.jpg">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5>React Material Dashboard</h5>
                                        <div class="progress progress-xs mb-0">
                                            <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <a href="#" class="avatar rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/vue.jpg">
                                        </a>
                                    </div>
                                    <div class="col">
                                        <h5>Vue Paper UI Kit PRO</h5>
                                        <div class="progress progress-xs mb-0">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card bg-gradient-info border-0">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Total traffic</h5>
                                        <span class="h2 font-weight-bold mb-0 text-white">350,897</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                            <i class="ni ni-active-40"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap text-light">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card bg-gradient-danger border-0">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0 text-white">Performance</h5>
                                        <span class="h2 font-weight-bold mb-0 text-white">49,65%</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                            <i class="ni ni-spaceship"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-white mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                    <span class="text-nowrap text-light">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>



<!-- wrappertabs -->
<div class="nav-wrapper">
    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Personal Data</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab" data-toggle="tab" href="#tabs-icons-text-7" role="tab" aria-controls="tabs-icons-text-7" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Billing details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Roles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Courses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Payments & Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Activity Timeline</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Messages</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab" data-toggle="tab" href="#tabs-icons-text-8" role="tab" aria-controls="tabs-icons-text-8" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Profile image</a>
        </li>
    </ul>
</div>
<?php //dd($user); ?>
<div class="card shadow">
    <div class="card-body">
        <div class="tab-content" id="myTabContent">
        @include('alerts.success')
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                <div class="card-body">
                <?php //dd($user); ?>
                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>

                        @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-firstname">{{ __('Firstname') }}</label>
                                <input type="text" name="firstname" id="input-firstname" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="{{ __('firstname') }}" value="{{ old('firstname', $user['firstname']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'firstname'])
                            </div>
                            <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-lastname">{{ __('Lastname') }}</label>
                                <input type="text" name="lastname" id="input-lastname" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="{{ __('Lastname') }}" value="{{ old('lastname', $user['lastname']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'lastname'])
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', $user['email']) }}" required>

                                @include('alerts.feedback', ['field' => 'email'])
                            </div>
                            <div class="form-group{{ $errors->has('photo') ? ' has-danger' : '' }}">
                                <label class="form-control-label">{{ __('Profile photo') }}</label>
                                <div class="custom-file">
                                    <label class="custom-file-label" for="input-picture">{{ __('Select profile photo') }}</label>
                                    <input type="file" name="photo" class="custom-file-input{{ $errors->has('photo') ? ' is-invalid' : '' }}" id="input-picture" accept="image/*">

                                </div>

                                @include('alerts.feedback', ['field' => 'photo'])
                            </div>
                            <div class="form-group{{ $errors->has('company') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-company">{{ __('Company') }}</label>
                                <input type="text" name="company" id="input-company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" placeholder="{{ __('Company') }}" value="{{ old('company', $user['company']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'company'])
                            </div>
                            <div class="form-group{{ $errors->has('job_title') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-job_title">{{ __('Job title') }}</label>
                                <input type="text" name="job_title" id="input-job_title" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Job title') }}" value="{{ old('job_title', $user['job_title']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'job_title'])
                            </div>
                            <div class="form-group{{ $errors->has('birthday') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-birthday">{{ __('Birthday') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    @if($user['birthday'] != null)
                                    <?php $birthday = $user['birthday']; ?>
                                    @else
                                    <?php $birthday = ''; ?>
                                    @endif
                                    <?php //dd($birthday); ?>
                                    <input name="birthday" id="input-birthday" class="form-control datepicker{{ $errors->has('birthday') ? ' is-invalid' : '' }}" placeholder="Select date" type="text" value="{{ old('birthday', $user['birthday']) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'birthday'])

                                </div>
                            </div>



                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-mobile">{{ __('Mobile') }}</label>
                                <input type="number" name="mobile" id="input-mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile', $user['mobile']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>

                            <?php //dd(auth()->user()); ?>

                            <div class="form-group{{ $errors->has('telephone') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-telephone">{{ __('Telephone') }}</label>
                                <input type="number" name="telephone" id="input-telephone" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" placeholder="{{ __('Telephone') }}" value="{{ old('telephone', $user['telephone']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'telephone'])
                            </div>

                            <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-address">{{ __('Address') }}</label>
                                <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address', $user['address']) }}" required autofocus>

                                @include('alerts.feedback', ['field' => 'address'])
                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('address_num') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-address_num">{{ __('Address No') }}</label>
                                    <input type="number" name="address_num" id="input-address_num" class="form-control{{ $errors->has('address_num') ? ' is-invalid' : '' }}" placeholder="{{ __('Address No') }}" value="{{ old('address_num', $user['address_num']) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'address_num'])
                                </div>

                                <div class="form-group{{ $errors->has('postcode') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-postcode">{{ __('Postcode') }}</label>
                                    <input type="number" name="postcode" id="input-postcode" class="form-control{{ $errors->has('postcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ old('postcode', $user['postcode']) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'postcode'])
                                </div>

                                <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-city">{{ __('City') }}</label>
                                    <input type="text" name="city" id="input-city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="{{ __('City') }}" value="{{ old('city', $user['city']) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'city'])
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('afm') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-afm">{{ __('Afm') }}</label>
                                    <input type="number" name="afm" id="input-afm" class="form-control{{ $errors->has('afm') ? ' is-invalid' : '' }}" placeholder="{{ __('Afm') }}" value="{{ old('afm', $user['afm']) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'afm'])
                                </div>

                                <input type="hidden" name="user_id" value="{{$user['id']}}">

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form method="post" action="{{ route('profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                        @include('alerts.success', ['key' => 'password_status'])
                        @include('alerts.error_self_update', ['key' => 'not_allow_password'])

                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-current-password">{{ __('Current Password') }}</label>
                                <input type="password" name="old_password" id="input-current-password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" required>

                                @include('alerts.feedback', ['field' => 'old_password'])
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>

                                @include('alerts.feedback', ['field' => 'password'])
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}" value="" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                <form method="post" action="{{ route('profile.updateRole') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('User Role') }}</h6>
                            <div class="pl-lg-4">

                            <div class="form-group{{ $errors->has('role_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-role_id">{{ __('User Roles') }}</label>
                                    <ul>
                                        @foreach($user->role as $role)
                                        <li>{{$role['name']}}</li>
                                        @endforeach
                                    </ul>
                                    @include('alerts.feedback', ['field' => 'role_id'])
                                </div>

                                <div class="form-group{{ $errors->has('role_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-role_id">{{ __('Roles') }}</label>
                                    <select multiple name="role_id[]" id="input-role_id" class="form-control roles" placeholder="{{ __('Roles') }}" required>
                                        <option value="">-</option>
                                        @foreach ($roles as $role)
                                            <?php $selected = false; ?>
                                            @foreach($user->role as $selected_role)
                                                @if($role->id == $selected_role['id'])
                                                    {{$selected = true}}
                                                @endif
                                            @endforeach

                                            <option <?php if($selected === true){echo 'selected';} ?> value="{{ $role->id }}" > {{ $role->name }}</option>
                                        @endforeach
                                    </select>

                                    @include('alerts.feedback', ['field' => 'role_id'])
                                </div>



                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">


                <!-- Create Modal -->
                <div class="modal fade" id="assignUserEvent" tabindex="-1" role="dialog" aria-labelledby="assignUserEventLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignUserEventLabel">Assign Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>

                            <div class="form-group{{ $errors->has('event_id') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-topic_id">{{ __('Events') }}</label>
                                <select name="event_id" id="input-event_id" class="form-control event" placeholder="{{ __('Event') }}">
                                    <option selected="selected" value="">-</option>

                                    @foreach ($events as $event)
                                    <?php $selected = false; ?>
                                        @if(count($event->users) != 0)
                                            @foreach($event->users as $user_event)
                                                @if($user_event['id'] != $user['id'])
                                                    <?php $selected = true; ?>
                                                @endif
                                            @endforeach
                                            @if($selected)
                                                    <option value="{{ $event->id }}" >{{ $event->title }}</option>
                                            @endif
                                        @else
                                        <option value="{{ $event->id }}" >{{ $event->title }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @include('alerts.feedback', ['field' => 'event_id'])
                            </div>

                            <div class="form-group">
                                <div class="card-deck" id="card-ticket">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="input-topic_id">{{ __('Select Billing Details') }}</label>
                                <select class="form-control" name="billing" id="billing">
                                    <option value="1">Receipt</option>
                                    <option value="2">Invoice</option>

                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="input-topic_id">{{ __('Select Payment Type') }}</label>
                                <select class="form-control" name="cardtype" id="cardtype">
                                    <option value="3">Bank Transfer</option>
                                    <option value="4">Cash</option>
                                </select>
                            </div>




                            <input type="hidden" name="user_id" value="{{$user['id']}}">

                            <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
                        <button type="button" data-user-id="{{$user['id']}}" id="assignTicketUser" class="btn btn-primary">Save changes</button>
                    </div>

                        </form>
                    </div>

                    </div>
                </div>
                </div>



                <div class="row align-items-center">
                    <div class="col-8">
                    </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignUserEvent">
                            Add course
                            </button>
                        </div>
                </div>

                <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Event') }}</th>
                                <th scope="col">{{ __('Ticket') }}</th>
                                <th scope="col">{{ __('Expired') }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="assigned_ticket_users">
                            @foreach ($user->events as $user_event)
                                <tr id="event_{{$user_event->id}}">
                                    <td>{{ $user_event->title }}</td>
                                    <td>{{ $user_event->ticket_title }}</td>

                                    <td>{{ $user_event->pivot->expiration}}</td>

                                        <td class="text-right">

                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" id="remove_ticket_user" data-event-id="{{$user_event->id}}" data-user-id="{{$user->id}}" data-ticket-id="{{ $user_event->ticket_id }}">{{ __('Delete') }}</a>
                                                    </div>
                                                </div>

                                        </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>





            </div>
            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
                <p class="description">TAB 4 Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
                <p class="description">TAB 5  Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
                <p class="description">TAB 6 Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-7" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab">
                <form method="post" action="{{ route('profile.update_billing') }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <h6 class="heading-small text-muted mb-4">{{ __('Student invoice details') }}</h6>

                    @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                    <?php //dd($user); ?>


                    <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('companyname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-companyname">{{ __('Company Name') }}</label>
                            <input type="text" name="companyname" id="input-companyname" class="form-control{{ $errors->has('companyname') ? ' is-invalid' : '' }}" placeholder="{{ __('Company Name') }}" value="{{($invoice != null && $invoice['companyname'] != null) ? $invoice['companyname'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'companyname'])
                        </div>
                        <div class="form-group{{ $errors->has('companyprofession') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-companyprofession">{{ __('Company Profession') }}</label>
                            <input type="text" name="companyprofession" id="input-companyprofession" class="form-control{{ $errors->has('companyprofession') ? ' is-invalid' : '' }}" placeholder="{{ __('Company Profession') }}" value="{{($invoice != null && $invoice['companyprofession'] != null) ? $invoice['companyprofession'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'companyprofession'])
                        </div>
                        <div class="form-group{{ $errors->has('companyafm') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-companyafm">{{ __('VAT Number') }}</label>
                            <input type="text" name="companyafm" id="input-companyafm" class="form-control{{ $errors->has('companyafm') ? ' is-invalid' : '' }}" placeholder="{{ __('VAT Number') }}" value="{{ ($invoice != null && $invoice['companyafm'] != null) ? $invoice['companyafm'] : '' }}" >

                            @include('alerts.feedback', ['field' => 'companyafm'])
                        </div>
                        <div class="form-group{{ $errors->has('companydoy') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-companydoy">{{ __('Company area') }}</label>
                            <input type="text" name="companydoy" id="input-companydoy" class="form-control{{ $errors->has('companydoy') ? ' is-invalid' : '' }}" placeholder="{{ __('Company area') }}" value="{{ ($invoice != null && $invoice['companydoy'] != null) ? $invoice['companydoy'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'companydoy'])
                        </div>
                        <div class="form-group{{ $errors->has('companyaddress') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-companyaddress">{{ __('Company address') }}</label>
                            <input type="text" name="companyaddress" id="input-companyaddress" class="form-control{{ $errors->has('companyaddress') ? ' is-invalid' : '' }}" placeholder="{{ __('Company address') }}" value="{{($invoice != null && $invoice['companyaddress'] != null) ? $invoice['companyaddress'] : ''}}" autofocus>

                            @include('alerts.feedback', ['field' => 'companyaddress'])
                        </div>
                        <div class="form-row">
                                <div class="form-group{{ $errors->has('companyaddressnum') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-companyaddressnum">{{ __('Company address num') }}</label>
                                    <input type="number" name="companyaddressnum" id="input-companyaddressnum" class="form-control{{ $errors->has('companyaddressnum') ? ' is-invalid' : '' }}" placeholder="{{ __('Company address num') }}" value="{{ ($invoice != null && $invoice['companyaddressnum'] != null) ? $invoice['companyaddressnum'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'companyaddressnum'])
                                </div>

                                <div class="form-group{{ $errors->has('companypostcode') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-companypostcode">{{ __('Postcode') }}</label>
                                    <input type="number" name="companypostcode" id="input-companypostcode" class="form-control{{ $errors->has('companypostcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ ($invoice != null && $invoice['companypostcode'] != null) ? $invoice['companypostcode'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'companypostcode'])
                                </div>

                                <div class="form-group{{ $errors->has('companycity') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-companycity">{{ __('City') }}</label>
                                    <input type="text" name="companycity" id="input-companycity" class="form-control{{ $errors->has('companycity') ? ' is-invalid' : '' }}" placeholder="{{ __('City') }}" value="{{($invoice != null && $invoice['companycity'] != null) ? $invoice['companycity'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'companycity'])
                                </div>
                            </div>

                        <h6 class="heading-small text-muted mb-4">{{ __('Student receipt details') }}</h6>

                        <div class="form-group{{ $errors->has('billname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billname">{{ __('Firstname') }}</label>
                            <input type="text" name="billname" id="input-billname" class="form-control{{ $errors->has('billname') ? ' is-invalid' : '' }}" placeholder="{{ __('Firstname') }}" value="{{ ($invoice != null && $receipt['billname'] != null) ? $receipt['billname'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billname'])
                        </div>

                        <div class="form-group{{ $errors->has('billsurname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billsurname">{{ __('Lastname') }}</label>
                            <input type="text" name="billsurname" id="input-billsurname" class="form-control{{ $errors->has('billsurname') ? ' is-invalid' : '' }}" placeholder="{{ __('Lastname') }}" value="{{($invoice != null && $receipt['billsurname'] != null) ? $receipt['billsurname'] : ''}}" autofocus>

                            @include('alerts.feedback', ['field' => 'billsurname'])
                        </div>
                        <div class="form-group{{ $errors->has('billaddress') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billaddress">{{ __('Address') }}</label>
                            <input type="text" name="billaddress" id="input-billaddress" class="form-control{{ $errors->has('billaddress') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{($invoice != null && $receipt['billaddress'] != null) ? $receipt['billaddress'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billaddress'])
                        </div>
                        <div class="form-row">
                                <div class="form-group{{ $errors->has('billaddressnum') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-billaddressnum">{{ __('Address No') }}</label>
                                    <input type="number" name="billaddressnum" id="input-billaddressnum" class="form-control{{ $errors->has('billaddressnum') ? ' is-invalid' : '' }}" placeholder="{{ __('Address No') }}" value="{{($invoice != null && $receipt['billaddressnum'] != null) ? $receipt['billaddressnum'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'billaddressnum'])
                                </div>

                                <div class="form-group{{ $errors->has('billpostcode') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-billpostcode">{{ __('Postcode') }}</label>
                                    <input type="number" name="billpostcode" id="input-billpostcode" class="form-control{{ $errors->has('billpostcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ ($invoice != null && $receipt['billpostcode'] != null) ? $receipt['billpostcode'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'billpostcode'])
                                </div>

                                <div class="form-group{{ $errors->has('billcity') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-billcity">{{ __('City') }}</label>
                                    <input type="text" name="billcity" id="input-billcity" class="form-control{{ $errors->has('billcity') ? ' is-invalid' : '' }}" placeholder="{{ __('City') }}" value="{{ ($invoice != null && $receipt['billcity'] != null) ? $receipt['billcity'] : '' }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'billcity'])
                                </div>
                            </div>



                            <input type="hidden" name="user_id" value="{{$user['id']}}">

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-8" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab">
                <div class="col-12 img_version">
                    <h3>Profile Image</h3>
                    <img class="img-fluid" id="profile_image" src="
                    <?php
                        if(isset($user) && $user->image != null) {

                            echo asset('uploads/profile_user').'/'.$user->image->original_name;
                        }else{
                            echo '';
                        }
                    ?>" alt="">

                    <button class="btn btn-primary crop_profile" type="button">Crop</button>
                    <div class="crop-msg" id="profile_image_msg"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php //dd($user->image); ?>

            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')

<script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
<script>
    $(document).on('click', '#remove_ticket_user',function(e) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        url: '{{route("user.remove_ticket_user")}}',
        data: {'ticket_id':$(this).data('ticket-id'), 'user_id':$(this).data('user-id'), 'event_id': $(this).data('event-id')},
        success: function (data) {

            $('#event_'+data.data).remove()
            $(".close_modal").click();
            $("#success-message p").html(data.success);
            $("#success-message").show();
            console.log($("#success-message"))

        }
    });
    })
</script>

<script>
    //on change event fetch ticket
    $( "#input-event_id" ).change(function() {
        $('#card-ticket').empty()

        var event_id = $(this).val()
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/ticket/fetchTicketsById',
                data:{'eventId': event_id},
                success: function (data) {
                    console.log(data)

                    let tickets = data.data

                    $.each( tickets, function(key, value) {
                        ticketCard = `
                            <div class="card ticket-card${value.pivot.quantity == 0 ? 'not_selectable' : ''}">
                                <div class="card-body">
                                <input style="display:none;" id="${value.id}" type="radio" name="ticket_radio" ${value.pivot.quantity == 0 ? 'disabled' : ''}>
                                <h5 class="card-title">${value.title}</h5>
                                <p class="card-text">${value.pivot.price}€</p>
                                <p class="card-text"><small class="text-muted">${value.subtitle}</small></p>
                                </div>
                            </div>

                    `
                    $('#card-ticket').append(ticketCard)

                    })

                }
            });
    });

</script>

<script>
$(document).on('click', '.ticket-card', function () {
    var ticket_id = $(this).find('input').attr('id')
    if(!$('#'+ticket_id).attr('disabled')){
        $('#'+ticket_id).prop("checked", true);
    }

    $('.ticket-card').removeClass('selected_ticket')
    $(this).addClass('selected_ticket')
})

</script>

<script>
    $(document).on('click', '#assignTicketUser', function () {
        const user_id = $(this).data('user-id')
        var event_id = $('#input-event_id').val()
        var billing = $('#billing').val()
        var cardtype = $('#cardtype').val()
        var ticket_id = $(".ticket-card input[type='radio']:checked").attr('id');

        $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/user/assignEventToUserCreate',
                data: {'cardtype':cardtype, 'billing':billing ,'event_id': event_id, 'ticket_id': ticket_id, 'user_id': user_id},
                success: function (data) {

                    let ticket = data.data.ticket;
                    //console.log(data.success)
                    let newRow =
                    `<tr id="event_`+ticket.event_id +`">` +
                    `<td>` + ticket.event + `</td>` +
                    `<td>` + ticket.ticket_title + `</td>` +
                    `<td>` + ticket.exp + `</td>` +
                    `<td class="text-right">

                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" id="remove_ticket_user" data-event-id="${ticket.event_id}" data-user-id="${data.data.user_id}" data-ticket-id="${ticket.ticket_id}">{{ __('Delete') }}</a>
                            </div>
                        </div>

                        </td>

                        </tr>`;


                    $("#assigned_ticket_users").append(newRow);
                    $(".close_modal").click();
                    $("#success-message p").html(data.success);
                    $("#success-message").show();
                        },
                        error: function() {
                            //console.log(data);
                        }




            })
    })
    $img = @json($user->image);

    console.log($img['details'])


    if($img != null && $img['details'] != null){
        image_details = JSON.parse($img['details'].split(','))
        width = image_details.width
        height = image_details.height
        x = image_details.x
        y = image_details.y


    }else{
        width = 800
        height = 800
        x = 0;
        y = 0;
    }



    const cropper = new Cropper(document.getElementById(`profile_image`), {
        aspectRatio: Number((width/height), 4),
        viewMode: 0,
        dragMode: "crop",
        responsive: false,
        autoCropArea: 1,
        restore: false,
        movable: false,
        rotatable: false,
        scalable: false,
        zoomable: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        minContainerWidth: 300,
        minContainerHeight: 300,
        // minCanvasWidth: 350,
        // minCanvasHeight: 350,

        data:{
            x:parseInt(x),
            y:parseInt(y),
            width: parseInt(width),
            height: parseInt(height)
        }
    });

    $(".crop_profile").click(function(){
        let media = @json($user->image);

        let path = $(this).parent().find('img').attr('src')

        path = path.split('/')

        path = '/'+path[3]+'/' + path[4]+'/' + path[5]



        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/media/crop_profile_image',
            data: {'media_id': media.id,'path':path, 'x':cropper.getData({rounded: true}).x, 'y':cropper.getData({rounded: true}).y, 'width':cropper.getData({rounded: true}).width, 'height':cropper.getData({rounded: true}).height},
            success: function (data) {
                //console.log(data)
                if(data){
                    $('#profile_image_msg').append(data.success)
                    $('#profile_image_msg').css('display', 'inline-block')
                }
            }
        });

    });











</script>



@endpush


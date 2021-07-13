<?php use App\Model\Media; ?>
@extends('layouts.app', [
    'title' => __('User Profile'),
    'navClass' => 'bg-default',
    'parentSection' => 'laravel',
    'elementName' => 'profile'
])

@section('content')
    @include('forms.header', [
        'title' => __('Hello') . ' '. auth()->user()->firstname . ' '. auth()->user()->lastname,
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
                                @if(isset(auth()->user()->image->original_name))
                                    <img src="{{ asset('profile_user') }}/{{ auth()->user()->image->original_name }}" class="rounded-circle">
                                @else
                                <img src="" alt="{{auth()->user()->firstname}}" class="rounded-circle">
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
                                {{ auth()->user()->fisrtname . ' '.  auth()->user()->lastname}}<span class="font-weight-light">, 27</span>
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
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Edit Profile') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#!" class="btn btn-sm btn-primary">{{ __('Settings') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>

                            @include('alerts.success')
                            @include('alerts.error_self_update', ['key' => 'not_allow_profile'])

                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-firstname">{{ __('Firstname') }}</label>
                                    <input type="text" name="firstname" id="input-firstname" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="{{ __('firstname') }}" value="{{ old('firstname', auth()->user()->firstname) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'firstname'])
                                </div>
                                <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-lastname">{{ __('Lastname') }}</label>
                                    <input type="text" name="lastname" id="input-lastname" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="{{ __('Lastname') }}" value="{{ old('lastname', auth()->user()->lastname) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'lastname'])
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}" required>

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
                                    <input type="text" name="company" id="input-company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" placeholder="{{ __('Company') }}" value="{{ old('company', auth()->user()->company) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'company'])
                                </div>
                                <div class="form-group{{ $errors->has('job_title') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-job_title">{{ __('Job title') }}</label>
                                    <input type="text" name="job_title" id="input-job_title" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Job title') }}" value="{{ old('job_title', auth()->user()->job_title) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'job_title'])
                                </div>
                                <div class="form-group{{ $errors->has('birthday') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-birthday">{{ __('Birthday') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input name="birthday" id="input-birthday" class="form-control datepicker{{ $errors->has('birthday') ? ' is-invalid' : '' }}" placeholder="Select date" type="text" value="{{ old('birthday', auth()->user()->birthday) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'birthday'])

                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-mobile">{{ __('Mobile') }}</label>
                                    <input type="number" name="mobile" id="input-mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile', auth()->user()->mobile) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>

                                <div class="form-group{{ $errors->has('telephone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-telephone">{{ __('Telephone') }}</label>
                                    <input type="number" name="telephone" id="input-telephone" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" placeholder="{{ __('Telephone') }}" value="{{ old('telephone', auth()->user()->telephone) }}" autofocus>

                                    @include('alerts.feedback', ['field' => 'telephone'])
                                </div>

                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-address">{{ __('Address') }}</label>
                                    <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address', auth()->user()->address) }}" required autofocus>

                                    @include('alerts.feedback', ['field' => 'address'])
                                </div>
                                <div class="form-row">
                                    <div class="form-group{{ $errors->has('address_num') ? ' has-danger' : '' }} col-md-4">
                                        <label class="form-control-label" for="input-address_num">{{ __('Address No') }}</label>
                                        <input type="number" name="address_num" id="input-address_num" class="form-control{{ $errors->has('address_num') ? ' is-invalid' : '' }}" placeholder="{{ __('Address No') }}" value="{{ old('address_num', auth()->user()->address_num) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'address_num'])
                                    </div>

                                    <div class="form-group{{ $errors->has('postcode') ? ' has-danger' : '' }} col-md-4">
                                        <label class="form-control-label" for="input-postcode">{{ __('Postcode') }}</label>
                                        <input type="number" name="postcode" id="input-postcode" class="form-control{{ $errors->has('postcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ old('postcode', auth()->user()->postcode) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'postcode'])
                                    </div>

                                    <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }} col-md-4">
                                        <label class="form-control-label" for="input-city">{{ __('City') }}</label>
                                        <input type="text" name="city" id="input-city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="{{ __('City') }}" value="{{ old('city', auth()->user()->city) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'city'])
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('afm') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-afm">{{ __('Afm') }}</label>
                                        <input type="number" name="afm" id="input-afm" class="form-control{{ $errors->has('afm') ? ' is-invalid' : '' }}" placeholder="{{ __('Afm') }}" value="{{ old('afm', auth()->user()->afm) }}" required autofocus>

                                        @include('alerts.feedback', ['field' => 'afm'])
                                    </div>

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
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

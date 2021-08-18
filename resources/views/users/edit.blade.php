<?php use App\Model\Media; ?>
@extends('layouts.app', [
    'title' => __('User Profile'),
    'navClass' => 'bg-default',
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
])

@section('content')
    @include('forms.header', [
        'title' => $user['firstname'] . ' '. $user['lastname'],
        'description' => __(''),
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

                                @if(isset($user->image->original_name))
                                <?php
                                    $name1 = explode('.',$user->image->original_name);
                                    $path = $user->image->path.$name1[0].$user->image->ext;
                                    $path_crop = $user->image->path.$name1[0].'-crop'.$user->image->ext;
                                    $path_crop = substr_replace($path_crop, "", 0, 1);

                                    if(file_exists($path_crop)){
                                        //dd('asd');
                                        $path = asset($path_crop);
                                    }else{
                                        $path = asset($path);
                                    }
                                ?>
                                <?php //dd($path); ?>
                                    <img src="{{ $path }}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image1.png')}}'" class="rounded-circle">
                                @else
                                <img src="" alt="{{$user['firstname']}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image1.png')}}'" class="rounded-circle">
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center personal-info">
                            <h5 class="h3">
                                {{ $user['firstname'] }} {{ $user['lastname'] }}
                            </h5>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{$user['company']}}
                            </div>
                            <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>{{$user['job_title']}} - {{$user['company']}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Progress track -->
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="row">


                </div>



<!-- wrappertabs -->
<div id="nav-wrapper-user" class="nav-wrapper">
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
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false"><i class="fas fa-envelope mr-2"></i>Messages</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab" data-toggle="tab" href="#tabs-icons-text-8" role="tab" aria-controls="tabs-icons-text-8" aria-selected="false"><i class="fas fa-images mr-2"></i>Profile image</a>
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
                            @if(Auth::user()->isAdmin())
                            <?php //dd($user->statusAccount()->first()['completed']); ?>
                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <div style="display:inline-flex;">
                                        <label class="form-control-label" for="input-firstname">{{ __('Status') }}</label>
                                    </div>
                                    <div style="display:inline-flex; margin-left:1rem;">
                                        <label class="custom-toggle custom-published-user">
                                            <input name="status" id="user-status" type="checkbox" @if($user->statusAccount()->first()['completed'] == 1) checked @endif>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="inactive" data-label-on="active"></span>
                                        </label>
                                        @include('alerts.feedback', ['field' => 'status'])
                                    </div>


                                </div>
                            @endif

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
                                <input type="text" name="company" id="input-company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" placeholder="{{ __('Company') }}" value="{{ old('company', $user['company']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'company'])
                            </div>
                            <div class="form-group{{ $errors->has('job_title') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-job_title">{{ __('Job title') }}</label>
                                <input type="text" name="job_title" id="input-job_title" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Job title') }}" value="{{ old('job_title', $user['job_title']) }}"  autofocus>

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
                                    <input name="birthday" id="input-birthday" class="form-control datepicker{{ $errors->has('birthday') ? ' is-invalid' : '' }}" placeholder="Select date" type="text" value="{{ old('birthday', $user['birthday']) }}"  autofocus>

                                    @include('alerts.feedback', ['field' => 'birthday'])

                                </div>
                            </div>



                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-mobile">{{ __('Mobile') }}</label>
                                <input type="number" name="mobile" id="input-mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile', $user['mobile']) }}"  autofocus>

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
                                <input type="text" name="address" id="input-address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address', $user['address']) }}"  autofocus>

                                @include('alerts.feedback', ['field' => 'address'])
                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('address_num') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-address_num">{{ __('Address No') }}</label>
                                    <input type="number" name="address_num" id="input-address_num" class="form-control{{ $errors->has('address_num') ? ' is-invalid' : '' }}" placeholder="{{ __('Address No') }}" value="{{ old('address_num', $user['address_num']) }}"  autofocus>

                                    @include('alerts.feedback', ['field' => 'address_num'])
                                </div>

                                <div class="form-group{{ $errors->has('postcode') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-postcode">{{ __('Postcode') }}</label>
                                    <input type="number" name="postcode" id="input-postcode" class="form-control{{ $errors->has('postcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ old('postcode', $user['postcode']) }}"  autofocus>

                                    @include('alerts.feedback', ['field' => 'postcode'])
                                </div>

                                <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }} col-md-4">
                                    <label class="form-control-label" for="input-city">{{ __('City') }}</label>
                                    <input type="text" name="city" id="input-city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="{{ __('City') }}" value="{{ old('city', $user['city']) }}"  autofocus>

                                    @include('alerts.feedback', ['field' => 'city'])
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('afm') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-afm">{{ __('VAT Number') }}</label>
                                    <input type="number" name="afm" id="input-afm" class="form-control{{ $errors->has('afm') ? ' is-invalid' : '' }}" placeholder="{{ __('VAT Number') }}" value="{{ old('afm', $user['afm']) }}" autofocus>

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
                        @method('post')

                        <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                        @include('alerts.success', ['key' => 'password_status'])
                        @include('alerts.error_self_update', ['key' => 'not_allow_password'])

                        <div class="pl-lg-4">
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="input-password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" required>

                                @include('alerts.feedback', ['field' => 'password'])
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="{{ __('Confirm New Password') }}" value="" required>
                            </div>

                            <input type="hidden" name="user" value="{{ $user->id }}">

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
                                <input type="hidden" name="userId" value="{{$user->id}}">



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
                    <table class="table align-items-center table-flush"  id="datatable-basic44">
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

            <div class="table-responsive py-4">
                    <table class="table align-items-center table-flush"  id="datatable-basic102">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Info') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Email the user informing about his current status.</td>
                                <td><a href="javascript:void(0);" data-id="{{$user['id']}}" class="btn btn-sm btn-primary email_user_status">Email Account Status</a></td>
                            </tr>
                            <tr>
                                <td>Email the user a link to create/change password .</td>
                                <td><a href="javascript:void(0);" data-id="{{$user['id']}}" class="btn btn-sm btn-primary email_user_change_password">Email Reset Password Link</a></td>
                            </tr>
                            <tr>
                                <td>Reset activation and Email the user informing how to activate account using a link.</td>
                                <td><a href="javascript:void(0);" data-id="{{$user['id']}}" class="btn btn-sm btn-primary email_user_activation_link">Email Activation Link</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>




            </div>
            <div class="tab-pane fade" id="tabs-icons-text-7" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab">
                <form method="post" action="{{ route('profile.update_billing') }}" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <h6 class="heading-small text-muted mb-4">{{ __('Student invoice details') }}</h6>

                    @include('alerts.error_self_update', ['key' => 'not_allow_profile'])



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


                        @if(isset($user->image) && $user->image['name'] != '')
                        <h3>Profile Image</h3>
                        <img class="img-fluid" id="profile_image" src="
                        <?php
                            if(isset($user) && $user->image != null) {

                                echo asset('uploads/profile_user').'/'.$user->image->original_name;
                            }else{
                                echo '';
                            }
                        ?>" alt="">
                        <div id="version-btn" style="margin-bottom:20px" class="col">
                            <a href="{{ route('media2.eventImage', $user->image) }}" target="_blank" class="btn btn-primary">{{ __('Versions') }}</a>
                        </div>
                        @else
                            {{__('No Image')}}
                        @endif


                    {{--<button class="btn btn-primary crop_profile" type="button">Crop</button>--}}
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

@push('css')
<link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('js')

    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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


    if($img['name'] != '' && $img['details'] != null){
        image_details = JSON.parse($img['details'].split(','))
        width = image_details.width
        height = image_details.height
        x = image_details.x
        y = image_details.y

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


    }





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
                if(data){
                    $('#profile_image_msg').append(data.success)
                    $('#profile_image_msg').css('display', 'inline-block')
                }
            }
        });

    });

    function filterScopeUserEmailStatus(id){
        user_id = $(id).data('id')

        Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to inform the user about his current status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                closeOnConfirm: false
                }).then((result) => {
                    console.log(result)
                if (result.value) {
                    $.ajax({
                        url: '/admin/status-inform',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    type: "post",
                        data: { content_id: user_id },
                        success: function(data) {
                            console.log(data)
                            if(data.status == 1){
                                //toastr.info(data.message);
                                Swal.fire(
                                    'Notification!',
                                    data.message,
                                    'success'
                                )
                                // $.toast({
                                //     heading: 'Information',
                                //     text: data.message,
                                //     position: 'top-right',    // Change it to false to disable loader
                                //     bgColor: '#0da825',
                                //     textColor: 'white'// To change the background
                                // })
                            }

                        }
                    });
                }
                })

    }




    function filterScopeUserChangePassword(id) {
        user_id = $(id).data('id')
        Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to inform the user to change/create password?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: '/admin/password-inform',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "post",
                        data: { content_id: user_id },
                        success: function(data) {
                            if(data.status == 1){

                                Swal.fire(
                                    'Information!',
                                    data.message,
                                    'success'
                                )
                                //toastr.info(data.message);

                                // $.toast({
                                //     heading: 'Information',
                                //     text: data.message,
                                //     position: 'top-right',    // Change it to false to disable loader
                                //     bgColor: '#0da825',
                                //     textColor: 'white'// To change the background
                                // })
                            }
                        }
                    });

                }
                })



	}

    function filterScopeUserActivationLink(id) {
        user_id = $(id).data('id')
        Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to reset user activation and send the user a link to activate the account?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
                }).then((result) => {
                if (result.value) {

                    $.ajax({
                        url: '/admin/activation-inform',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "post",
                        data: { content_id: user_id },
                        success: function(data) {
                            console.log(data)
                            if(data.status == 1){
                                //toastr.info(data.message);

                                Swal.fire(
                                    'Information!',
                                    data.message,
                                    'success'
                                )

                                // $.toast({
                                //     heading: 'Information',
                                //     text: data.message,
                                //     position: 'top-right',    // Change it to false to disable loader
                                //     bgColor: '#0da825',
                                //     textColor: 'white'// To change the background
                                // })
                            }
                        }
                    });

                }
        })




	}



    $( document ).ready(function() {

        if($img['name'] != ''){
            $('.custom-file-label').text($img['name']+$img['ext'])
        }

        $("#input-picture").on("input", function() {
            let filename = $(this).val()
            filename = filename.replace('C:\\fakepath\\','')
            $('.custom-file-label').text(filename)
        });

        $("body").on("click", '.email_user_status', function () {

	        filterScopeUserEmailStatus($(this));
	    });

	    $("body").on("click", '.email_user_change_password', function () {
	        filterScopeUserChangePassword($(this));
	    });

	    $("body").on("click", '.email_user_activation_link', function () {
	        filterScopeUserActivationLink($(this));
	    });
    });









</script>



@endpush


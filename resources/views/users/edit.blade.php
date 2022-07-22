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
        'kc_id' => $user['kc_id'],
        'partner_id' => $user['partner_id'],
        'description' => __(''),
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--6">
        <div class="row">
            {{--<div class="col-xl-4 order-xl-2">
                <div class="card card-profile">
                    <img src="{{ asset('argon') }}/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                @if($path = get_profile_image($user->image))
                                //dd($path);?>
                                    <img src="{{ cdn(asset($path)) }}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" class="rounded-circle">
                                @else
                                    <img src="" alt="{{$user['firstname']}}" onerror="this.src='{{cdn('/theme/assets/images/icons/user-profile-placeholder-image.png')}}'" class="rounded-circle">
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
            </div>--}}
            <div class="col-xl-12 order-xl-12">



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
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab" data-toggle="tab" href="#tabs-icons-text-9" role="tab" aria-controls="tabs-icons-text-4" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i> Subscriptions</a>
        </li>
        {{--<li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Activity Timeline</a>
        </li>--}}
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false"><i class="fas fa-envelope mr-2"></i>Messages</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab" data-toggle="tab" href="#tabs-icons-text-8" role="tab" aria-controls="tabs-icons-text-8" aria-selected="false"><i class="fas fa-images mr-2"></i>Profile image</a>
        </li>
        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="tabs-icons-text-8" aria-selected="false"><i class="fas fa-images mr-2"></i>Notes</a>
        </li>

        <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab" data-toggle="tab" href="#consent" role="tab" aria-controls="tabs-icons-text-8" aria-selected="false"><i class="fas fa-images mr-2"></i>Consent</a>
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
                    <div class="is-flex">
                        @if(!$user->kc_id)

                        <form method="post" action="{{ route('create-kc') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <input hidden name="user" value="{{$user->id}}">
                                <button class="btn btn-primary" type="submit">Create KC ID</button>
                        </form>
                        @endif

                        {{--@if(!$user->partner_id)
                        <form class="pad-left" method="post" action="{{ route('create-deree') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <input hidden name="user" value="{{$user->id}}">
                                <button class="btn btn-primary" type="submit">Create Deree </button>
                        </form>

                        @endif--}}
                    </div>
                    <form method="post" action="{{ route('profile.update') }}" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>

                        @include('alerts.error_self_update', ['key' => 'not_allow_profile'])
                        <div class="pl-lg-4">
                            @if(Auth::user()->isAdmin() || Auth::user()->isAdministrator())
                            <?php //dd($user->statusAccount()->first()['completed']); ?>
                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                    <div style="display:inline-flex;">
                                        <label class="form-control-label" for="input-firstname">{{ __('Status') }}</label>
                                    </div>
                                    <div style="display:inline-flex; margin-left:1rem;">
                                        <label class="custom-toggle custom-published-user">
                                            <input name="status" id="user-status" type="checkbox" @if($user->statusAccount()->first() != null && $user->statusAccount()->first()['completed'] == 1) checked @endif>
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
                                <label class="form-control-label" for="input-company">{{ __('Company/Employer') }}</label>
                                <input type="text" name="company" id="input-company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" placeholder="{{ __('Company/Employer') }}" value="{{ old('company', $user['company']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'company'])
                            </div>
                            <div class="form-group{{ $errors->has('job_title') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-job_title">{{ __('Occupation') }}</label>
                                <input type="text" name="job_title" id="input-job_title" class="form-control{{ $errors->has('job_title') ? ' is-invalid' : '' }}" placeholder="{{ __('Occupation') }}" value="{{ old('job_title', $user['job_title']) }}"  autofocus>

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

                            <div class="form-group{{ $errors->has('country_code') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-country_code">{{ __('Country Code') }}</label>
                                <input type="number" name="country_code" id="input-country_code" class="form-control{{ $errors->has('country_code') ? ' is-invalid' : '' }}" placeholder="{{ __('Country Code') }}" value="{{ old('country_code', $user['country_code']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'country_code'])
                            </div>


                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-mobile">{{ __('Mobile') }}</label>
                                <input type="number" name="mobile" id="input-mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile', $user['mobile']) }}"  autofocus>

                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>


                            <div class="form-group{{ $errors->has('telephone') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-telephone">{{ __('Telephone') }}</label>
                                <input type="number" name="telephone" id="input-telephone" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" placeholder="{{ __('Telephone') }}" value="{{ old('telephone', $user['telephone']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'telephone'])
                            </div>

                            <div class="form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="input-telephone">{{ __('Town / City') }}</label>
                                <input type="text" name="city" id="input-telephone" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" placeholder="{{ __('Town / City') }}" value="{{ old('city', $user['city']) }}" autofocus>

                                @include('alerts.feedback', ['field' => 'city'])
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



               @include('users.absences.absences_modal')


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
                                {{--<th scope="col">{{ __('Event') }}</th>
                                <th scope="col">{{ __('Ticket') }}</th>
                                <th scope="col">{{ __('Initial Expiration Date') }}</th>
                                <th scope="col">{{ __('New Expiration Date') }}</th>
                                <th scope="col"></th>--}}

                                <th scope="col">{{ __('Event') }}</th>
                                <th scope="col">{{ __('Ticket Price') }}</th>
                                <th scope="col">{{ __('Paid') }}</th>
                                <th scope="col">{{ __('Registration Date') }}</th>
                                <th scope="col">{{ __('Expiration Date') }}</th>
                                <th scope="col">{{ __('Absences') }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="assigned_ticket_users">
                            @foreach ($user->events_for_user_list as $key => $user_event)

                                <?php $trans = $user_event->transactionsByUser($user->id)->first()?>
                                <tr id="event_{{$user_event->id}}">
                                    <td>{{ $user_event->title }}</td>
                                    <td>@if($trans) {{ number_format($trans->amount , 2 , '.', '')}} @endif</td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" data-user_id="{{$user_event->pivot->user_id}}" data-event_id="{{$user_event->id}}" @if($user_event->pivot->paid) checked @endif class="paid custom-control-input" id="customCheck{{$key}}">
                                            <label class="custom-control-label" for="customCheck{{$key}}"></label>
                                        </div>
                                    </td>
                                    {{--<td class="exp_{{$user_event->id}}"><?= ($user_event->pivot->expiration != null) ? date_format( new DateTime($user_event->pivot->expiration),'m/d/Y') : ''; ?></td>--}}
                                   <td> @if($trans) {{date('d-m-Y',strtotime($trans->created_at))}} @endif</td>
                                    <td>
                                        <div style="display: inline-flex;">
                                            <input id="{{$user_event->id}}" class="form-control datepicker" placeholder="Select date" type="text" value="<?= ($user_event->pivot->expiration != null) ? date_format( new DateTime($user_event->pivot->expiration), 'm/d/Y') : ''; ?>">
                                        </div>

                                        <div style="display: inline-flex;">
                                            <button class="update_exp btn btn-info btn-sm" style="margin-top:10px;" type="button"
                                                data-user_id="{{$user_event->pivot->user_id}}" data-event_id="{{$user_event->id}}" >Update</button>
                                        </div>
                                    </td>

                                    @if($user_event->is_inclass_course())
                                    <td>
                                        <button class="absences btn btn-info btn-sm" style="margin-top:10px;" type="button"
                                                data-user_id="{{$user_event->pivot->user_id}}" data-event_id="{{$user_event->id}}"
                                                data-toggle="modal" data-target="#absences-info">Absences</button>

                                    </td>
                                    @endif

                                    <td class="text-right">

                                            <div class="dropdown" style="margin-left: 1.8rem;">
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
                <?php $index = 1; ?>
                <div class="accordion accord_topic" id="accordionExample">
                    @foreach($transactions as $key1 => $transaction)
                        <?php if(count($transaction) <= 0){
                                continue;
                                }
                        ?>
                        <div class="card">
                            <div class="row">
                                <div class="card-header col-10" id="{{$index}}" data-toggle="collapse" data-target="#col_{{$index}}" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="mb-0">{{$key1}}</h5>
                                </div>

                            </div>
                            <div id="col_{{$index}}" class="collapse" aria-labelledby="{{$index}}" data-parent="#accordionExample">
                                <div class="card-body">
                                    {{--<div class="table-responsive py-4">
                                        <table class="table align-items-center table-flush"  id="datatable-basic42">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">{{ __('ID') }}</th>
                                                    <th scope="col">{{ __('Amount') }}</th>
                                                    <th scope="col">{{ __('Created At') }}</th>
                                                    <th scope="col">{{ __('View Transaction') }}</th>
                                                    <th scope="col">{{ __('View Invoice') }}</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            @foreach($transaction as $tra)
                                            <tbody>

                                                    <th scope="col"> {{$tra['id']}} </th>
                                                    <th scope="col"> {{$tra['amount']}} </th>
                                                    <th scope="col"> {{$tra['created_at']}} </th>
                                                    <th scope="col"> transaction </th>
                                                    <th> invoices </th>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </div>--}}

                                    <div class="nav-wrapper">
                                        <ul class="nav nav-pills nav-fill flex-column flex-md-row"  role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link mb-sm-3 mb-md-0 active"  data-toggle="tab" href="#transaction-info-{{$index}}" role="tab"  aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Personal Data</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link mb-sm-3 mb-md-0"  data-toggle="tab" href="#invoices-{{$index}}" role="tab"  aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Invoices</a>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="tab-content" >

                                                <div class="tab-pane fade show active" id="transaction-info-{{$index}}" role="tabpanel" aria-labelledby="transaction-info-{{$index}}">
                                                    <form action="/admin/transaction/update" method="post" autocomplete="off" enctype="multipart/form-data">
                                                        @csrf
                                                        @foreach($transaction as  $tran)
                                                        <input name="transaction[]" value="{{$tran['id']}}" hidden>
                                                        <div class="row">

                                                            <div class="col-lg-4">
                                                                <h6 class="heading-small text-muted mb-4">{{ __('Transaction Details') }}</h6>

                                                                <div class="form-group">
                                                                    <label class="form-control-label" for="input-method">{{ __('Status') }}</label>
                                                                    <select name="statusTr[]" id="input-method" class="form-control" placeholder="{{ __('Status') }}">
                                                                        <option value="0" @if($tran['status'] == 0)  selected @endif>Cancelled</option>
                                                                        <option value="1" @if($tran['status'] == 1)  selected @endif>Approved</option>
                                                                        <option value="2" @if($tran['status'] == 2)  selected @endif>Abandonded</option>
                                                                    </select>
                                                                </div>


                                                                <div>
                                                                    <label class="form-control-label" for="input-method">{{ __('Total Amount') }}: </label> {{number_format($tran['amount'] , 2 , '.', '')}}
                                                                </div>

                                                                <div>
                                                                    <label class="form-control-label" for="input-method">{{ __('Associated user') }}: </label>
                                                                    <p> {{$tran['user']->first()->firstname}} {{$tran['user']->first()->lastname}} </p>
                                                                    <p> {{$tran['user']->first()->email}} </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">

                                                                <?php $billing = json_decode($tran['billing_details'],true); ?>

                                                                @if($billing)

                                                                    @if(!isset($billing['billing']) || $billing['billing'] == 1)
                                                                    <h6 class="heading-small text-muted mb-4">{{ __('Receipt Details') }}</h6>
                                                                    @if(isset($billing['billname']))<p><label class="form-control-label" for="input-method">{{ __('Name') }}: </label> {{$billing['billname']}} </p>@endif
                                                                    @if(isset($billing['billsurname']))<p><label class="form-control-label" for="input-method">{{ __('Surname') }}: </label> {{$billing['billsurname']}} </p>@endif
                                                                    @if(isset($billing['billaddress']) && isset($billing['billaddressnum']))<p><label class="form-control-label" for="input-method">{{ __('Address') }}: </label> {{$billing['billaddress']}} {{$billing['billaddressnum']}}</p>@endif
                                                                    @if(isset($billing['billpostcode']))<p><label class="form-control-label" for="input-method">{{ __('PostCode') }}: </label> {{$billing['billpostcode']}} </p>@endif
                                                                    @if(isset($billing['billpostcode']))<p><label class="form-control-label" for="input-method">{{ __('City') }}: </label> {{$billing['billcity']}} </p>@endif
                                                                    <p><label class="form-control-label" for="input-method">{{ __('Vat number') }}: </label> @if(isset($billing['billafm'])){{$billing['billafm']}} @else  @endif </p>
                                                                    @elseif($billing['billing'] == 2)
                                                                    <h6 class="heading-small text-muted mb-4">{{ __('Invoice Details') }}</h6>

                                                                    @if(isset($billing['companyname']))<p><label class="form-control-label" for="input-method">Επωνυμία: </label>{{ $billing['companyname'] }}</p>@endif
                                                                    @if(isset($billing['companyprofession']))<p><label class="form-control-label" for="input-method">Δραστηριότητα: </label>{{ $billing['companyprofession'] }}</p>@endif
                                                                    @if(isset($billing['companyafm']))<p><label class="form-control-label" for="input-method">ΑΦΜ: </label>{{ $billing['companyafm'] }}</p>@endif
                                                                    @if(isset($billing['companydoy']))<p><label class="form-control-label" for="input-method">ΔΟΥ:</label>{{ $billing['companydoy'] }}</p>@endif
                                                                    @if(isset( $billing['companyaddress']))<p><label class="form-control-label" for="input-method">Διεύθυνση: </label>{{ $billing['companyaddress'] }} {{ $billing['companyaddressnum'] }}</p>@endif
                                                                    @if(isset($billing['companypostcode']))<p><label class="form-control-label" for="input-method">Τ.Κ.: </label>{{ $billing['companypostcode'] }}</p>@endif
                                                                    @if(isset($billing['companycity']))<p><label class="form-control-label" for="input-method">Πόλη: </label>{{ $billing['companycity'] }}</p>@endif
                                                                    @if(isset($billing['companyemail']))<p><label class="form-control-label" for="input-method">Email Αποστολής Τιμολογίου: </label>{{ $billing['companyemail'] }} </p>@endif


                                                                    @endif
                                                                @else

                                                                @endif
                                                            </div>


                                                            <div class="col-lg-4">
                                                                <h6 class="heading-small text-muted mb-4">{{ __('Bank Details') }}</h6>

                                                                @if(isset($tran['status_history']))

                                                                    <?php $resp = json_decode($tran['payment_response'],true);
                                                                        $status_history = $tran['status_history'];

                                                                    ?>

                                                                    @if($tran['payment_method_id'] == 100)

                                                                        <span><strong>via STRIPE</strong>
                                                                            @if(isset($resp['source']))
                                                                                <?php echo "<p><span><strong>Card Type:</strong>".$resp['source']['brand']."</span></p>"; ?>
                                                                            @endif

                                                                            @if(isset($status_history[0]['installments']))
                                                                        <?php echo "<p><span><strong>Installments:</strong> ".$status_history[0]['installments']."</span></p>"; ?>
                                                                        @endif

                                                                        @if(isset($sub_data) && isset($sub_data['installments_paid']))
                                                                            <p><span><strong>Paid:</strong> {{$sub_data['installments_paid']}} of {{$sub_data['installments']}} total</span></p>
                                                                        @endif

                                                                    @else
                                                                        @if(isset($status_history[0]['cardtype']))
                                                                            @if($status_history[0]['cardtype'] == 2 &&  (isset($status_history[0]['installments'])))
                                                                                <?php echo "<p><span><strong>Card Type:</strong> Credit Card</span></p>"; ?>
                                                                                <?php echo "<p><span><strong>Installments:</strong> ".$status_history[0]['installments']."</span></p>"; ?>
                                                                            @elseif($status_history[0]['cardtype'] == 4)
                                                                                <?php echo "<p><span><strong>Payment Type:</strong> Cash</span></p>"; ?>
                                                                            @elseif($status_history[0]['cardtype'] == 3)
                                                                                <?php echo "<p><span><strong>Payment Type:</strong> Bank Transfer</span></p>"; ?>

                                                                            @else
                                                                                <?php echo "<p><span><strong>Card Type:</strong> Debit Card</span></p>"; ?>

                                                                            @endif

                                                                        @endif
                                                                    @endif

                                                                    @if(!empty($resp))

                                                                        @if($tran['payment_method_id'] == 100)

                                                                            <span class="content_slug">
                                                                            @if(isset($resp['source']))
                                                                            <strong>Card number:</strong> ****-****-{{ $resp['source']['last4'] }} <br />exp: {{ $resp['source']['exp_month'] }}/{{ $resp['source']['exp_year'] }}<br />
                                                                            <strong>TxId:</strong> {{ $resp['id'] }}
                                                                            @endif
                                                                            </span>

                                                                        @else

                                                                        <span class="content_slug"><strong>Card name:</strong> {{ $resp['payMethod'] }}<br />
                                                                        <strong>Bank Payment Reference:</strong> {{ $resp['paymentRef'] }} <br />
                                                                        <strong>Bank Transaction ID:</strong> {{ $resp['txId'] }}</span>
                                                                        @endif

                                                                    @endif
                                                                @endif

                                                            </div>

                                                            <div class="col-lg-12" style="border:1px solid">
                                                            {{--@if($tran['status'] == 1 || $tran['status'] == 2)--}}


                                                                <h3>Booking Seats Details</h3>
                                                                @foreach($tran['user'] as $key => $value)

                                                                    <input name="users[]" value="{{$value['id']}}" hidden>

                                                                    <h4><strong>Seat #{{ $key+1 }}</strong></h4>
                                                                    <div class="is-flex">
                                                                        <div class="col-lg-6">
                                                                            @foreach($events as $event)
                                                                                @if( $event['title'] == $key1 )
                                                                                    <input name="oldevents[]" value="{{$event['id']}}" hidden>
                                                                                @endif
                                                                            @endforeach
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="input-topic_id">{{ __('Select Event to transfer to') }}</label>
                                                                                <select name="newevents[]" class="form-control" name="cardtype" id="cardtype">

                                                                                    @foreach($events as $event)
                                                                                    <option value="{{$event['id']}}" @if( $event['title'] == $key1 ) selected @endif>{{$event['title']}}</option>
                                                                                    @endforeach

                                                                                </select>
                                                                            </div>

                                                                            <strong>Name:&nbsp;</strong> <a class="pUpdate" data-name="first_name" data-title="Change Name" data-pk="{{ $value['id'] }}">{{ $value['firstname'] }}</a> <br />
                                                                            <strong>Surname:</strong> <a class="pUpdate" data-name="last_name" data-title="Change Name" data-pk="{{ $value['id'] }}">{{ $value['lastname'] }}</a> <br />
                                                                            <strong>Email:&nbsp;</strong> <a class="pUpdate" data-name="email" data-title="Change Email" data-pk="{{ $value['id'] }}">{{ $value['email'] }}</a><br />
                                                                            <strong>Mobile:&nbsp;</strong> <a class="pUpdate" data-name="mobile" data-title="Change Mobile" data-pk="{{ $value['id'] }}">{{ $value['mobile'] }}</a><br />
                                                                            <strong>PostCode:&nbsp;</strong> <a class="pUpdate" data-name="postcode" data-title="Change Postcode" data-pk="{{ $value['id'] }}">{{ $value['postcode'] }}</a> <br />
                                                                            <strong>City: </strong> <a class="pUpdate" data-name="city" data-title="Change City" data-pk="{{ $value['id'] }}">{{ $value['city'] }}</a><br />
                                                                            <strong>Title or Job Title:&nbsp;</strong> <a class="pUpdate" data-name="job_title" data-title="Change Job Title" data-pk="{{ $value['id'] }}">{{ $value['job_title'] }}</a> <br />


                                                                        </div>

                                                                        <div class="col-lg-6">

                                                                            <div class="text-right"><strong>Deree ID:</strong>

                                                                                {{ $value['partner_id'] }}
                                                                                 <br/>

                                                                                <strong>KC ID:</strong>
                                                                                    {{ $value['kc_id'] }}
                                                                            </div>

                                                                        </div>
                                                                    </div>


                                                                @endforeach

                                                            {{--@endif--}}
                                                            </div>

                                                        </div>
                                                        @endforeach
                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                                        </div>
                                                    </form>
                                                </div>


                                                <div class="tab-pane " id="invoices-{{$index}}" role="tabpanel" aria-labelledby="invoices-{{$index}}">

                                                    <div class="table-responsive py-4">
                                                        <table class="table align-items-center table-flush"  id="datatable-basic42">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th scope="col">{{ __('ID') }}</th>
                                                                    <th scope="col">{{ __('Amount') }}</th>
                                                                    <th scope="col">{{ __('Created At') }}</th>

                                                                    <th scope="col">{{ __('View Invoice') }}</th>
                                                                    <th scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            @foreach($transaction as $tra)
                                                                @foreach($tra['invoice'] as $invoice)
                                                                <tbody>

                                                                        <th scope="col"> {{$invoice['id']}} </th>
                                                                        {{--<th scope="col"> {{$tra['amount']}} </th>--}}
                                                                        <th scope="col"> {{number_format($invoice['amount'] , 2 , '.', '')}} </th>
                                                                        <th scope="col"> {{date('d-m-y H:i',strtotime($invoice['created_at']))}} </th>
                                                                        <th scope="col"> <a href="/admin/invoice/{{ $invoice['id'] }}">view </a> </th>

                                                                </tbody>
                                                                @endforeach
                                                            @endforeach
                                                        </table>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <?php $index += 1; ?>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-9" role="tabpanel" aria-labelledby="subscriptions-tab">
            <div class="accordion accord_topic" id="accordionExample">
                    @foreach($subscriptions as $key1 => $subscription)
                        <?php if(count($subscription) <= 0){
                                continue;
                                }
                        ?>
                        <div class="card">
                            <div class="row">
                                <div class="card-header col-10" id="{{$index}}" data-toggle="collapse" data-target="#col_{{$index}}" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="mb-0">{{$key1}}</h5>
                                </div>

                            </div>
                            <div id="col_{{$index}}" class="collapse" aria-labelledby="{{$index}}" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="table-responsive py-4">
                                        <table class="table align-items-center table-flush"  id="datatable-basic42">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">{{ __('ID') }}</th>
                                                    <th scope="col">{{ __('Amount') }}</th>
                                                    <th scope="col">{{ __('Created At') }}</th>
                                                    <th scope="col">{{ __('Trial') }}</th>
                                                    <th scope="col">{{ __('Subscription Ends') }}</th>
                                                    <th scope="col">{{ __('View Invoice') }}</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            @foreach($subscription as $tra)

                                            <tbody>

                                                    <th scope="col"> {{$tra['id']}} </th>
                                                    <th scope="col"> {{$tra['amount']}} </th>
                                                    <th scope="col"> {{date('d-m-Y H:i',strtotime($tra['created_at']))}} </th>
                                                    <th scope="col"> @if($tra['trial']) trial @else no trial @endif </th>
                                                    <th scope="col"> {{date('d-m-Y H:i',strtotime($tra['ends_at']))}} </th>
                                                    <th> @if(isset($tra['invoice'][0])) <a href="/admin/invoice/{{$tra['invoice'][0]['id']}}" target="_blank"> view </a> @else - @endif </th>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <?php $index += 1; ?>
                    @endforeach
                </div>
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
                            {{--<tr>
                                <td>Email the user informing about his current status.</td>
                                <td><a href="javascript:void(0);" data-id="{{$user['id']}}" class="btn btn-sm btn-primary email_user_status">Email Account Status</a></td>
                            </tr>--}}
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


                    @include('alerts.error_self_update', ['key' => 'not_allow_profile'])



                    <div class="pl-lg-4">



                        <h6 class="heading-small text-muted mb-4">{{ __('Student receipt details') }}</h6>

                        <div class="form-group{{ $errors->has('billname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billname">{{ __('Company or full name ') }}</label>
                            <input type="text" name="billname" id="input-billname" class="form-control{{ $errors->has('billname') ? ' is-invalid' : '' }}" placeholder="{{ __('Firstname') }}" value="{{ (isset($receipt['billname']) ) ? $receipt['billname'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billname'])
                        </div>


                        <div class="form-group{{ $errors->has('billname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billafm">{{ __('VAT or tax ID') }}</label>
                            <input type="text" name="billafm" id="input-billafm" class="form-control{{ $errors->has('billafm') ? ' is-invalid' : '' }}" placeholder="{{ __('VAT or tax ID') }}" value="{{ (isset($receipt['billafm']) ) ? $receipt['billafm'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billafm'])
                        </div>



                        <div class="form-row">
                            <div class="form-group{{ $errors->has('billaddress') ? ' has-danger' : '' }} col-md-4">
                                <label class="form-control-label" for="input-billaddress">{{ __('Street') }}</label>
                                <input type="text" name="billaddress" id="input-billaddress" class="form-control{{ $errors->has('billaddress') ? ' is-invalid' : '' }}" placeholder="{{ __('Street') }}" value="{{(isset($receipt['billaddress'])) ? $receipt['billaddress'] : '' }}" autofocus>

                                @include('alerts.feedback', ['field' => 'billaddress'])
                            </div>

                            <div class="form-group{{ $errors->has('billaddressnum') ? ' has-danger' : '' }} col-md-2">
                                <label class="form-control-label" for="input-billaddressnum">{{ __('Street number') }}</label>
                                <input type="number" name="billaddressnum" id="input-billaddressnum" class="form-control{{ $errors->has('billaddressnum') ? ' is-invalid' : '' }}" placeholder="{{ __('Street number') }}" value="{{ (isset($receipt['billaddressnum'])) ? $receipt['billaddressnum'] : '' }}" autofocus>

                                @include('alerts.feedback', ['field' => 'billaddressnum'])
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group{{ $errors->has('billcity') ? ' has-danger' : '' }} col-md-4">
                                <label class="form-control-label" for="input-billcity">{{ __('Town/city') }}</label>
                                <input type="text" name="billcity" id="input-billaddressnum" class="form-control{{ $errors->has('billcity') ? ' is-invalid' : '' }}" placeholder="{{ __('Town/city') }}" value="{{(isset($receipt['billcity'])) ? $receipt['billcity'] : '' }}" autofocus>

                                @include('alerts.feedback', ['field' => 'billcity'])
                            </div>

                            <div class="form-group{{ $errors->has('billpostcode') ? ' has-danger' : '' }} col-md-2">
                                <label class="form-control-label" for="input-billpostcode">{{ __('Postcode') }}</label>
                                <input type="number" name="billpostcode" id="input-billpostcode" class="form-control{{ $errors->has('billpostcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Postcode') }}" value="{{ (isset($receipt['billpostcode'])) ? $receipt['billpostcode'] : '' }}" autofocus>

                                @include('alerts.feedback', ['field' => 'billpostcode'])
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('billstate') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billstate">{{ __('State') }}</label>
                            <input type="text" name="billstate" id="input-billstate" class="form-control{{ $errors->has('billstate') ? ' is-invalid' : '' }}" placeholder="{{ __('State') }}" value="{{ (isset($receipt['billstate']) ) ? $receipt['billstate'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billstate'])
                        </div>

                        <div class="form-group{{ $errors->has('billname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billcountry">{{ __('Country') }}</label>
                            <input type="text" name="billcountry" id="input-billcountry" class="form-control{{ $errors->has('billcountry') ? ' is-invalid' : '' }}" placeholder="{{ __('Country') }}" value="{{ (isset($receipt['billcountry']) ) ? $receipt['billcountry'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billcountry'])
                        </div>

                        <div class="form-group{{ $errors->has('billname') ? ' has-danger' : '' }}">
                            <label class="form-control-label" for="input-billafm">{{ __('Bill email') }}</label>
                            <input type="text" name="billemail" id="input-billafm" class="form-control{{ $errors->has('billemail') ? ' is-invalid' : '' }}" placeholder="{{ __('Bill email') }}" value="{{ (isset($receipt['billemail']) ) ? $receipt['billemail'] : '' }}" autofocus>

                            @include('alerts.feedback', ['field' => 'billemail'])
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

            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">

                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Notes</label>
                    <textarea name="notes" class="form-control" id="notes" rows="10">{{ $user->notes }} </textarea>

                    <button id="save-notes" type="button" class="btn btn-success mt-4">{{ __('Save') }}</button>
                </div>

            </div>

            <div class="tab-pane fade" id="consent" role="tabpanel" aria-labelledby="consent-tab">

                <div class="pl-lg-4">

                    <div class="form-row">

                        @foreach((array) json_decode($user->consent,true) as $key => $consent)

                        <div class="form-group{{ $errors->has('billaddress') ? ' has-danger' : '' }} col-md-4">

                                @if($key == 'ip')
                                    <label class="form-control-label" >
                                        {{ strtoupper($key) }}
                                    </label>
                                    <input type="text" class="form-control"  value="{{$consent}}" readonly>
                                @elseif($key == 'afm')
                                <label class="form-control-label" >
                                        {{ 'VAT Number' }}
                                    </label>
                                <input type="text" class="form-control"  value="{{$consent}}" readonly>
                                @else
                                    @if($key != 'billafm')
                                    <label class="form-control-label" >
                                        {{ ucfirst($key) }}
                                    </label>
                                    <input type="text" class="form-control"  value="{{$consent}}" readonly>
                                    @endif
                                @endif



                        </div>
                        @endforeach


                    </div>
                    <button id="download-consent" class="btn btn-primary" type="button">Download PDF</button>
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


    if($img != null && $img['name'] != '' && $img['details'] != null && $img['details'] !=''){
        image_details = JSON.parse($img['details'].split(','))
        width = image_details.width
        height = image_details.height
        x = image_details.x
        y = image_details.y



    }


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
                if (result.value) {
                    $.ajax({
                        url: '/admin/status-inform',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    type: "post",
                        data: { content_id: user_id },
                        success: function(data) {
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

        if($img != null && $img['name'] != ''){
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

        $( ".update_exp" ).on( "click", function() {
            let user_id = $(this).data('user_id')
            let event_id =  $(this).data('event_id')
            let new_date =  $('#'+ event_id).val()


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/transaction/updateExpirationDate',
                data: {'user_id': user_id ,'event_id': event_id , 'date': new_date},
                success: function (data) {
                    if(data){
                        data = data.data
                        $('.exp_'+data.id).text(data.date)
                    }

                }
            });
        });


    });

</script>

<script>

    $(document).on('click','.paid',function(){

        let paid = $(this).is(':checked')
        let user_id = $(this).data('user_id')
        let event_id =  $(this).data('event_id')

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/user/change-paid-status',
            data: {'user_id': user_id ,'event_id': event_id , 'paid': paid},
            success: function (data) {

            }
        });



    })

    $(document).on('click','#save-notes',function(){


        let user_id = "{{ $user['id'] }}"
        let notes =  $("textarea#notes").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/admin/user/save-notes',
            data: {'user_id': user_id , 'notes': notes},
            success: function (data) {

            }
        });



    })


</script>

<script>
$(document).on('click', '#download-consent', function () {
    window.location.href = '/admin/user/{{$user->id}}/generateConsent';
})

</script>



@endpush


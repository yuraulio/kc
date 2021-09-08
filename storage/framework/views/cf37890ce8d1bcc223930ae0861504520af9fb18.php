<?php use App\Model\Media; ?>



<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('forms.header', [
        'title' => $user['firstname'] . ' '. $user['lastname'],
        'kc_id' => $user['kc_id'],
        'partner_id' => $user['partner_id'],
        'description' => __(''),
        'class' => 'col-lg-7'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container-fluid mt--6">
        <div class="row">
            
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
        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                <div class="card-body">
                    <div class="is-flex">
                        <?php if(!$user->kc_id): ?>
                        
                        <form method="post" action="<?php echo e(route('create-kc')); ?>" autocomplete="off"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input hidden name="user" value="<?php echo e($user->id); ?>">
                                <button class="btn btn-primary" type="submit">Create KC ID</button>
                        </form>
                        <?php endif; ?>

                        <?php if(!$user->partner_id): ?>
                        <form class="pad-left" method="post" action="<?php echo e(route('create-deree')); ?>" autocomplete="off"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <input hidden name="user" value="<?php echo e($user->id); ?>">
                                <button class="btn btn-primary" type="submit">Create Deree </button>
                        </form>
                        
                        <?php endif; ?>
                    </div>
                    <form method="post" action="<?php echo e(route('profile.update')); ?>" autocomplete="off"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>

                        <h6 class="heading-small text-muted mb-4"><?php echo e(__('User information')); ?></h6>

                        <?php echo $__env->make('alerts.error_self_update', ['key' => 'not_allow_profile'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="pl-lg-4">
                            <?php if(Auth::user()->isAdmin()): ?>
                            <?php //dd($user->statusAccount()->first()['completed']); ?>
                                <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">
                                    <div style="display:inline-flex;">
                                        <label class="form-control-label" for="input-firstname"><?php echo e(__('Status')); ?></label>
                                    </div>
                                    <div style="display:inline-flex; margin-left:1rem;">
                                        <label class="custom-toggle custom-published-user">
                                            <input name="status" id="user-status" type="checkbox" <?php if($user->statusAccount()->first() != null && $user->statusAccount()->first()['completed'] == 1): ?> checked <?php endif; ?>>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="inactive" data-label-on="active"></span>
                                        </label>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>


                                </div>
                            <?php endif; ?>

                            <div class="form-group<?php echo e($errors->has('firstname') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-firstname"><?php echo e(__('Firstname')); ?></label>
                                <input type="text" name="firstname" id="input-firstname" class="form-control<?php echo e($errors->has('firstname') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('firstname')); ?>" value="<?php echo e(old('firstname', $user['firstname'])); ?>" required autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'firstname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('lastname') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-lastname"><?php echo e(__('Lastname')); ?></label>
                                <input type="text" name="lastname" id="input-lastname" class="form-control<?php echo e($errors->has('lastname') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Lastname')); ?>" value="<?php echo e(old('lastname', $user['lastname'])); ?>" required autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'lastname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-email"><?php echo e(__('Email')); ?></label>
                                <input type="email" name="email" id="input-email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Email')); ?>" value="<?php echo e(old('email', $user['email'])); ?>" required>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('photo') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label"><?php echo e(__('Profile photo')); ?></label>
                                <div class="custom-file">
                                    <label class="custom-file-label" for="input-picture"><?php echo e(__('Select profile photo')); ?></label>
                                    <input type="file" name="photo" class="custom-file-input<?php echo e($errors->has('photo') ? ' is-invalid' : ''); ?>" id="input-picture" accept="image/*">

                                </div>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'photo'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('company') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-company"><?php echo e(__('Company')); ?></label>
                                <input type="text" name="company" id="input-company" class="form-control<?php echo e($errors->has('company') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company')); ?>" value="<?php echo e(old('company', $user['company'])); ?>" autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'company'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('job_title') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-job_title"><?php echo e(__('Job title')); ?></label>
                                <input type="text" name="job_title" id="input-job_title" class="form-control<?php echo e($errors->has('job_title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Job title')); ?>" value="<?php echo e(old('job_title', $user['job_title'])); ?>"  autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'job_title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group<?php echo e($errors->has('birthday') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-birthday"><?php echo e(__('Birthday')); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <?php if($user['birthday'] != null): ?>
                                    <?php $birthday = $user['birthday']; ?>
                                    <?php else: ?>
                                    <?php $birthday = ''; ?>
                                    <?php endif; ?>
                                    <?php //dd($birthday); ?>
                                    <input name="birthday" id="input-birthday" class="form-control datepicker<?php echo e($errors->has('birthday') ? ' is-invalid' : ''); ?>" placeholder="Select date" type="text" value="<?php echo e(old('birthday', $user['birthday'])); ?>"  autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'birthday'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                </div>
                            </div>

                            <div class="form-group<?php echo e($errors->has('country_code') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-country_code"><?php echo e(__('Country Code')); ?></label>
                                <input type="number" name="country_code" id="input-country_code" class="form-control<?php echo e($errors->has('country_code') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Country Code')); ?>" value="<?php echo e(old('country_code', $user['country_code'])); ?>" autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'country_code'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>


                            <div class="form-group<?php echo e($errors->has('mobile') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-mobile"><?php echo e(__('Mobile')); ?></label>
                                <input type="number" name="mobile" id="input-mobile" class="form-control<?php echo e($errors->has('mobile') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Mobile')); ?>" value="<?php echo e(old('mobile', $user['mobile'])); ?>"  autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'mobile'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                           
                            <div class="form-group<?php echo e($errors->has('telephone') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-telephone"><?php echo e(__('Telephone')); ?></label>
                                <input type="number" name="telephone" id="input-telephone" class="form-control<?php echo e($errors->has('telephone') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Telephone')); ?>" value="<?php echo e(old('telephone', $user['telephone'])); ?>" autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'telephone'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                            <div class="form-group<?php echo e($errors->has('address') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-address"><?php echo e(__('Address')); ?></label>
                                <input type="text" name="address" id="input-address" class="form-control<?php echo e($errors->has('address') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Address')); ?>" value="<?php echo e(old('address', $user['address'])); ?>"  autofocus>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'address'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-row">
                                <div class="form-group<?php echo e($errors->has('address_num') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-address_num"><?php echo e(__('Address No')); ?></label>
                                    <input type="number" name="address_num" id="input-address_num" class="form-control<?php echo e($errors->has('address_num') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Address No')); ?>" value="<?php echo e(old('address_num', $user['address_num'])); ?>"  autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'address_num'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('postcode') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-postcode"><?php echo e(__('Postcode')); ?></label>
                                    <input type="number" name="postcode" id="input-postcode" class="form-control<?php echo e($errors->has('postcode') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Postcode')); ?>" value="<?php echo e(old('postcode', $user['postcode'])); ?>"  autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'postcode'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('city') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-city"><?php echo e(__('City')); ?></label>
                                    <input type="text" name="city" id="input-city" class="form-control<?php echo e($errors->has('city') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('City')); ?>" value="<?php echo e(old('city', $user['city'])); ?>"  autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'city'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>

                            <div class="form-group<?php echo e($errors->has('afm') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-afm"><?php echo e(__('VAT Number')); ?></label>
                                    <input type="number" name="afm" id="input-afm" class="form-control<?php echo e($errors->has('afm') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('VAT Number')); ?>" value="<?php echo e(old('afm', $user['afm'])); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'afm'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4" />
                    <form method="post" action="<?php echo e(route('profile.password')); ?>" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('post'); ?>

                        <h6 class="heading-small text-muted mb-4"><?php echo e(__('Password')); ?></h6>

                        <?php echo $__env->make('alerts.success', ['key' => 'password_status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.error_self_update', ['key' => 'not_allow_password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="pl-lg-4">
                            <div class="form-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-password"><?php echo e(__('New Password')); ?></label>
                                <input type="password" name="password" id="input-password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('New Password')); ?>" value="" required>

                                <?php echo $__env->make('alerts.feedback', ['field' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-password-confirmation"><?php echo e(__('Confirm New Password')); ?></label>
                                <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="<?php echo e(__('Confirm New Password')); ?>" value="" required>
                            </div>

                            <input type="hidden" name="user" value="<?php echo e($user->id); ?>">

                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Change password')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                <form method="post" action="<?php echo e(route('profile.updateRole')); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('User Role')); ?></h6>
                            <div class="pl-lg-4">

                            <div class="form-group<?php echo e($errors->has('role_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-role_id"><?php echo e(__('User Roles')); ?></label>
                                    <ul>
                                        <?php $__currentLoopData = $user->role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($role['name']); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <?php echo $__env->make('alerts.feedback', ['field' => 'role_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('role_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-role_id"><?php echo e(__('Roles')); ?></label>
                                    <select multiple name="role_id[]" id="input-role_id" class="form-control roles" placeholder="<?php echo e(__('Roles')); ?>" required>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $selected = false; ?>
                                            <?php $__currentLoopData = $user->role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected_role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($role->id == $selected_role['id']): ?>
                                                    <?php echo e($selected = true); ?>

                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <option <?php if($selected === true){echo 'selected';} ?> value="<?php echo e($role->id); ?>" > <?php echo e($role->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'role_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <input type="hidden" name="userId" value="<?php echo e($user->id); ?>">



                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
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

                                <div class="form-group<?php echo e($errors->has('event_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-topic_id"><?php echo e(__('Events')); ?></label>
                                    <select name="event_id" id="input-event_id" class="form-control event" placeholder="<?php echo e(__('Event')); ?>">
                                        <option selected="selected" value="">-</option>

                                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $selected = false; ?>
                                            <?php if(count($event->users) != 0): ?>
                                                <?php $__currentLoopData = $event->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($user_event['id'] != $user['id']): ?>
                                                        <?php $selected = true; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($selected): ?>
                                                        <option value="<?php echo e($event->id); ?>" ><?php echo e($event->title); ?></option>
                                                <?php endif; ?>
                                            <?php else: ?>
                                            <option value="<?php echo e($event->id); ?>" ><?php echo e($event->title); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'event_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group">
                                    <div class="card-deck" id="card-ticket">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-topic_id"><?php echo e(__('Select Billing Details')); ?></label>
                                    <select class="form-control" name="billing" id="billing">
                                        <option value="1">Receipt</option>
                                        <option value="2">Invoice</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-topic_id"><?php echo e(__('Select Payment Type')); ?></label>
                                    <select class="form-control" name="cardtype" id="cardtype">
                                        <option value="3">Bank Transfer</option>
                                        <option value="4">Cash</option>
                                    </select>
                                </div>




                                <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">

                                <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
                            <button type="button" data-user-id="<?php echo e($user['id']); ?>" id="assignTicketUser" class="btn btn-primary">Save changes</button>
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
                                <th scope="col"><?php echo e(__('Event')); ?></th>
                                <th scope="col"><?php echo e(__('Ticket')); ?></th>
                                <th scope="col"><?php echo e(__('Initial Expiration Date')); ?></th>
                                <th scope="col"><?php echo e(__('New Expiration Date')); ?></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="assigned_ticket_users">
                            <?php $__currentLoopData = $user->events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="event_<?php echo e($user_event->id); ?>">
                                    <td><?php echo e($user_event->title); ?></td>
                                    <td><?php echo e($user_event->ticket_title); ?></td>
                                    <td class="exp_<?php echo e($user_event->id); ?>"><?= ($user_event->pivot->expiration != null) ? date_format( new DateTime($user_event->pivot->expiration),'m/d/Y') : ''; ?></td>
                                    <td>
                                        <div style="display: inline-flex;">
                                            <input id="<?php echo e($user_event->id); ?>" class="form-control datepicker" placeholder="Select date" type="text" value="<?= ($user_event->pivot->expiration != null) ? date_format( new DateTime($user_event->pivot->expiration), 'm/d/Y') : ''; ?>">
                                        </div>

                                        <div style="display: inline-flex;">
                                            <button class="update_exp btn btn-info btn-sm" style="margin-top:10px;" type="button"
                                                data-user_id="<?php echo e($user_event->pivot->user_id); ?>" data-event_id="<?php echo e($user_event->id); ?>" >Update</button>
                                        </div>
                                    </td>

                                        <td class="text-right">

                                                <div class="dropdown" style="margin-left: 1.8rem;">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" id="remove_ticket_user" data-event-id="<?php echo e($user_event->id); ?>" data-user-id="<?php echo e($user->id); ?>" data-ticket-id="<?php echo e($user_event->ticket_id); ?>"><?php echo e(__('Delete')); ?></a>
                                                    </div>
                                                </div>

                                        </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
                <?php $index = 1; ?>
                <div class="accordion accord_topic" id="accordionExample">
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($transaction) <= 0){
                                continue;
                                }
                        ?>
                        <div class="card">
                            <div class="row">
                                <div class="card-header col-10" id="<?php echo e($index); ?>" data-toggle="collapse" data-target="#col_<?php echo e($index); ?>" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="mb-0"><?php echo e($key1); ?></h5>
                                </div>

                            </div>
                            <div id="col_<?php echo e($index); ?>" class="collapse" aria-labelledby="<?php echo e($index); ?>" data-parent="#accordionExample">
                                <div class="card-body">
                                    

                                    <div class="nav-wrapper">
                                        <ul class="nav nav-pills nav-fill flex-column flex-md-row"  role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link mb-sm-3 mb-md-0 active"  data-toggle="tab" href="#transaction-info-<?php echo e($index); ?>" role="tab"  aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Personal Data</a>
                                            </li>

                                            <li class="nav-item">
                                                <a class="nav-link mb-sm-3 mb-md-0"  data-toggle="tab" href="#invoices-<?php echo e($index); ?>" role="tab"  aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Invoices</a>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="tab-content" >

                                                <div class="tab-pane fade show active" id="transaction-info-<?php echo e($index); ?>" role="tabpanel" aria-labelledby="transaction-info-<?php echo e($index); ?>">
                                                    <form action="/admin/transaction/update" method="post" autocomplete="off" enctype="multipart/form-data">
                                                        <?php echo csrf_field(); ?>
                                                        <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input name="transaction[]" value="<?php echo e($tran['id']); ?>" hidden>
                                                        <div class="row">

                                                            <div class="col-lg-4">
                                                                <h6 class="heading-small text-muted mb-4"><?php echo e(__('Transaction Details')); ?></h6>

                                                                <div class="form-group">
                                                                    <label class="form-control-label" for="input-method"><?php echo e(__('Status')); ?></label>
                                                                    <select name="statusTr[]" id="input-method" class="form-control" placeholder="<?php echo e(__('Status')); ?>">
                                                                        <option value="0" <?php if($tran['status'] == 0): ?>  selected <?php endif; ?>>Cancelled</option>
                                                                        <option value="1" <?php if($tran['status'] == 1): ?>  selected <?php endif; ?>>Approved</option>
                                                                        <option value="2" <?php if($tran['status'] == 2): ?>  selected <?php endif; ?>>Abandonded</option>
                                                                    </select>
                                                                </div>


                                                                <div>
                                                                    <label class="form-control-label" for="input-method"><?php echo e(__('Total Amount')); ?>: </label> <?php echo e($tran['amount']); ?>

                                                                </div>

                                                                <div>
                                                                    <label class="form-control-label" for="input-method"><?php echo e(__('Associated user')); ?>: </label>
                                                                    <p> <?php echo e($tran['user']->first()->firstname); ?> <?php echo e($tran['user']->first()->lastname); ?> </p>
                                                                    <p> <?php echo e($tran['user']->first()->email); ?> </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">

                                                                <?php $billing = json_decode($tran['billing_details'],true); ?>

                                                                <?php if($billing): ?>

                                                                    <?php if($billing['billing'] == 1): ?>
                                                                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Receipt Details')); ?></h6>
                                                                    <?php if(isset($billing['billname'])): ?><p><label class="form-control-label" for="input-method"><?php echo e(__('Name')); ?>: </label> <?php echo e($billing['billname']); ?> </p><?php endif; ?>
                                                                    <?php if(isset($billing['billsurname'])): ?><p><label class="form-control-label" for="input-method"><?php echo e(__('Surname')); ?>: </label> <?php echo e($billing['billsurname']); ?> </p><?php endif; ?>
                                                                    <?php if(isset($billing['billaddress']) && isset($billing['billaddressnum'])): ?><p><label class="form-control-label" for="input-method"><?php echo e(__('Address')); ?>: </label> <?php echo e($billing['billaddress']); ?> <?php echo e($billing['billaddressnum']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['billpostcode'])): ?><p><label class="form-control-label" for="input-method"><?php echo e(__('PostCode')); ?>: </label> <?php echo e($billing['billpostcode']); ?> </p><?php endif; ?>
                                                                    <?php if(isset($billing['billpostcode'])): ?><p><label class="form-control-label" for="input-method"><?php echo e(__('City')); ?>: </label> <?php echo e($billing['billcity']); ?> </p><?php endif; ?>
                                                                    <p><label class="form-control-label" for="input-method"><?php echo e(__('Vat number')); ?>: </label> <?php if(isset($billing['billafm'])): ?><?php echo e($billing['billafm']); ?> <?php else: ?>  <?php endif; ?> </p>
                                                                    <?php elseif($billing['billing'] == 2): ?>
                                                                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Invoice Details')); ?></h6>

                                                                    <?php if(isset($billing['companyname'])): ?><p><label class="form-control-label" for="input-method">Επωνυμία: </label><?php echo e($billing['companyname']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companyprofession'])): ?><p><label class="form-control-label" for="input-method">Δραστηριότητα: </label><?php echo e($billing['companyprofession']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companyafm'])): ?><p><label class="form-control-label" for="input-method">ΑΦΜ: </label><?php echo e($billing['companyafm']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companydoy'])): ?><p><label class="form-control-label" for="input-method">ΔΟΥ:</label><?php echo e($billing['companydoy']); ?></p><?php endif; ?>
                                                                    <?php if(isset( $billing['companyaddress'])): ?><p><label class="form-control-label" for="input-method">Διεύθυνση: </label><?php echo e($billing['companyaddress']); ?> <?php echo e($billing['companyaddressnum']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companypostcode'])): ?><p><label class="form-control-label" for="input-method">Τ.Κ.: </label><?php echo e($billing['companypostcode']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companycity'])): ?><p><label class="form-control-label" for="input-method">Πόλη: </label><?php echo e($billing['companycity']); ?></p><?php endif; ?>
                                                                    <?php if(isset($billing['companyemail'])): ?><p><label class="form-control-label" for="input-method">Email Αποστολής Τιμολογίου: </label><?php echo e($billing['companyemail']); ?> </p><?php endif; ?>


                                                                    <?php endif; ?>
                                                                <?php else: ?>

                                                                <?php endif; ?>
                                                            </div>


                                                            <div class="col-lg-4">
                                                                <h6 class="heading-small text-muted mb-4"><?php echo e(__('Bank Details')); ?></h6>

                                                                <?php if(isset($tran['status_history'])): ?>

                                                                    <?php $resp = json_decode($tran['payment_response'],true);
                                                                        $status_history = $tran['status_history'];

                                                                    ?>

                                                                    <?php if($tran['payment_method_id'] == 100): ?>

                                                                        <span><strong>via STRIPE</strong>
                                                                            <?php if(isset($resp['source'])): ?>
                                                                                <?php echo "<p><span><strong>Card Type:</strong>".$resp['source']['brand']."</span></p>"; ?>
                                                                            <?php endif; ?>

                                                                            <?php if(isset($status_history[0]['installments'])): ?>
                                                                        <?php echo "<p><span><strong>Installments:</strong> ".$status_history[0]['installments']."</span></p>"; ?>
                                                                        <?php endif; ?>

                                                                        <?php if(isset($sub_data) && isset($sub_data['installments_paid'])): ?>
                                                                            <p><span><strong>Paid:</strong> <?php echo e($sub_data['installments_paid']); ?> of <?php echo e($sub_data['installments']); ?> total</span></p>
                                                                        <?php endif; ?>

                                                                    <?php else: ?>
                                                                        <?php if(isset($status_history[0]['cardtype'])): ?>
                                                                            <?php if($status_history[0]['cardtype'] == 2 &&  (isset($status_history[0]['installments']))): ?>
                                                                                <?php echo "<p><span><strong>Card Type:</strong> Credit Card</span></p>"; ?>
                                                                                <?php echo "<p><span><strong>Installments:</strong> ".$status_history[0]['installments']."</span></p>"; ?>
                                                                            <?php elseif($status_history[0]['cardtype'] == 4): ?>
                                                                                <?php echo "<p><span><strong>Payment Type:</strong> Cash</span></p>"; ?>
                                                                            <?php elseif($status_history[0]['cardtype'] == 3): ?>
                                                                                <?php echo "<p><span><strong>Payment Type:</strong> Bank Transfer</span></p>"; ?>

                                                                            <?php else: ?>
                                                                                <?php echo "<p><span><strong>Card Type:</strong> Debit Card</span></p>"; ?>

                                                                            <?php endif; ?>

                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if(!empty($resp)): ?>

                                                                        <?php if($tran['payment_method_id'] == 100): ?>

                                                                            <span class="content_slug">
                                                                            <?php if(isset($resp['source'])): ?>
                                                                            <strong>Card number:</strong> ****-****-<?php echo e($resp['source']['last4']); ?> <br />exp: <?php echo e($resp['source']['exp_month']); ?>/<?php echo e($resp['source']['exp_year']); ?><br />
                                                                            <strong>TxId:</strong> <?php echo e($resp['id']); ?>

                                                                            <?php endif; ?>
                                                                            </span>

                                                                        <?php else: ?>

                                                                        <span class="content_slug"><strong>Card name:</strong> <?php echo e($resp['payMethod']); ?><br />
                                                                        <strong>Bank Payment Reference:</strong> <?php echo e($resp['paymentRef']); ?> <br />
                                                                        <strong>Bank Transaction ID:</strong> <?php echo e($resp['txId']); ?></span>
                                                                        <?php endif; ?>

                                                                    <?php endif; ?>
                                                                <?php endif; ?>

                                                            </div>

                                                            <div class="col-lg-12" style="border:1px solid">
                                                            


                                                                <h3>Booking Seats Details</h3>
                                                                <?php $__currentLoopData = $tran['user']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                                    <input name="users[]" value="<?php echo e($value['id']); ?>" hidden>

                                                                    <h4><strong>Seat #<?php echo e($key+1); ?></strong></h4>
                                                                    <div class="is-flex">
                                                                        <div class="col-lg-6">
                                                                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php if( $event['title'] == $key1 ): ?>
                                                                                    <input name="oldevents[]" value="<?php echo e($event['id']); ?>" hidden>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" for="input-topic_id"><?php echo e(__('Select Event to transfer to')); ?></label>
                                                                                <select name="newevents[]" class="form-control" name="cardtype" id="cardtype">

                                                                                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($event['id']); ?>" <?php if( $event['title'] == $key1 ): ?> selected <?php endif; ?>><?php echo e($event['title']); ?></option>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                                </select>
                                                                            </div>

                                                                            <strong>Name:&nbsp;</strong> <a class="pUpdate" data-name="first_name" data-title="Change Name" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['firstname']); ?></a> <br />
                                                                            <strong>Surname:</strong> <a class="pUpdate" data-name="last_name" data-title="Change Name" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['lastname']); ?></a> <br />
                                                                            <strong>Email:&nbsp;</strong> <a class="pUpdate" data-name="email" data-title="Change Email" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['email']); ?></a><br />
                                                                            <strong>Mobile:&nbsp;</strong> <a class="pUpdate" data-name="mobile" data-title="Change Mobile" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['mobile']); ?></a><br />
                                                                            <strong>PostCode:&nbsp;</strong> <a class="pUpdate" data-name="postcode" data-title="Change Postcode" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['postcode']); ?></a> <br />
                                                                            <strong>City: </strong> <a class="pUpdate" data-name="city" data-title="Change City" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['city']); ?></a><br />
                                                                            <strong>Title or Job Title:&nbsp;</strong> <a class="pUpdate" data-name="job_title" data-title="Change Job Title" data-pk="<?php echo e($value['id']); ?>"><?php echo e($value['job_title']); ?></a> <br />


                                                                        </div>

                                                                        <div class="col-lg-6">

                                                                            <div class="text-right"><strong>Deree ID:</strong>

                                                                                <?php echo e($value['partner_id']); ?>

                                                                                 <br/>

                                                                                <strong>KC ID:</strong>
                                                                                    <?php echo e($value['kc_id']); ?>

                                                                            </div>

                                                                        </div>
                                                                    </div>


                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                            
                                                            </div>

                                                        </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                                                        </div>
                                                    </form>
                                                </div>


                                                <div class="tab-pane " id="invoices-<?php echo e($index); ?>" role="tabpanel" aria-labelledby="invoices-<?php echo e($index); ?>">

                                                    <div class="table-responsive py-4">
                                                        <table class="table align-items-center table-flush"  id="datatable-basic42">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th scope="col"><?php echo e(__('ID')); ?></th>
                                                                    <th scope="col"><?php echo e(__('Amount')); ?></th>
                                                                    <th scope="col"><?php echo e(__('Created At')); ?></th>

                                                                    <th scope="col"><?php echo e(__('View Invoice')); ?></th>
                                                                    <th scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <?php $__currentLoopData = $transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php $__currentLoopData = $tra['invoice']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tbody>

                                                                        <th scope="col"> <?php echo e($invoice['id']); ?> </th>
                                                                        <th scope="col"> <?php echo e($tra['amount']); ?> </th>
                                                                        <th scope="col"> <?php echo e(date('d-m-y H:i',strtotime($invoice['created_at']))); ?> </th>
                                                                        <th scope="col"> <a href="/admin/invoice/<?php echo e($invoice['id']); ?>">view </a> </th>

                                                                </tbody>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-9" role="tabpanel" aria-labelledby="subscriptions-tab">
            <div class="accordion accord_topic" id="accordionExample">
                    <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(count($subscription) <= 0){
                                continue;
                                }
                        ?>
                        <div class="card">
                            <div class="row">
                                <div class="card-header col-10" id="<?php echo e($index); ?>" data-toggle="collapse" data-target="#col_<?php echo e($index); ?>" aria-expanded="false" aria-controls="collapseOne">
                                    <h5 class="mb-0"><?php echo e($key1); ?></h5>
                                </div>

                            </div>
                            <div id="col_<?php echo e($index); ?>" class="collapse" aria-labelledby="<?php echo e($index); ?>" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="table-responsive py-4">
                                        <table class="table align-items-center table-flush"  id="datatable-basic42">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"><?php echo e(__('ID')); ?></th>
                                                    <th scope="col"><?php echo e(__('Amount')); ?></th>
                                                    <th scope="col"><?php echo e(__('Created At')); ?></th>
                                                    <th scope="col"><?php echo e(__('Trial')); ?></th>
                                                    <th scope="col"><?php echo e(__('Subscription Ends')); ?></th>
                                                    <th scope="col"><?php echo e(__('View Invoice')); ?></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <?php $__currentLoopData = $subscription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tbody>

                                                    <th scope="col"> <?php echo e($tra['id']); ?> </th>
                                                    <th scope="col"> <?php echo e($tra['amount']); ?> </th>
                                                    <th scope="col"> <?php echo e(date('d-m-Y H:i',strtotime($tra['created_at']))); ?> </th>
                                                    <th scope="col"> <?php if($tra['trial']): ?> trial <?php else: ?> no trial <?php endif; ?> </th>
                                                    <th scope="col"> <?php echo e(date('d-m-Y H:i',strtotime($tra['ends_at']))); ?> </th>
                                                    <th> <?php if(isset($tra['invoice'][0])): ?> <a href="/admin/invoice/<?php echo e($tra['invoice'][0]['id']); ?>" target="_blank"> view </a> <?php else: ?> - <?php endif; ?> </th>
                                            </tbody>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <?php $index += 1; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <th scope="col"><?php echo e(__('Info')); ?></th>
                                <th scope="col"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Email the user informing about his current status.</td>
                                <td><a href="javascript:void(0);" data-id="<?php echo e($user['id']); ?>" class="btn btn-sm btn-primary email_user_status">Email Account Status</a></td>
                            </tr>
                            <tr>
                                <td>Email the user a link to create/change password .</td>
                                <td><a href="javascript:void(0);" data-id="<?php echo e($user['id']); ?>" class="btn btn-sm btn-primary email_user_change_password">Email Reset Password Link</a></td>
                            </tr>
                            <tr>
                                <td>Reset activation and Email the user informing how to activate account using a link.</td>
                                <td><a href="javascript:void(0);" data-id="<?php echo e($user['id']); ?>" class="btn btn-sm btn-primary email_user_activation_link">Email Activation Link</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>




            </div>
            <div class="tab-pane fade" id="tabs-icons-text-7" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab">
                <form method="post" action="<?php echo e(route('profile.update_billing')); ?>" autocomplete="off"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>

                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Student invoice details')); ?></h6>

                    <?php echo $__env->make('alerts.error_self_update', ['key' => 'not_allow_profile'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



                    <div class="pl-lg-4">
                        <div class="form-group<?php echo e($errors->has('companyname') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-companyname"><?php echo e(__('Company Name')); ?></label>
                            <input type="text" name="companyname" id="input-companyname" class="form-control<?php echo e($errors->has('companyname') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company Name')); ?>" value="<?php echo e(($invoice != null && $invoice['companyname'] != null) ? $invoice['companyname'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'companyname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('companyprofession') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-companyprofession"><?php echo e(__('Company Profession')); ?></label>
                            <input type="text" name="companyprofession" id="input-companyprofession" class="form-control<?php echo e($errors->has('companyprofession') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company Profession')); ?>" value="<?php echo e(($invoice != null && $invoice['companyprofession'] != null) ? $invoice['companyprofession'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'companyprofession'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('companyafm') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-companyafm"><?php echo e(__('VAT Number')); ?></label>
                            <input type="text" name="companyafm" id="input-companyafm" class="form-control<?php echo e($errors->has('companyafm') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('VAT Number')); ?>" value="<?php echo e(($invoice != null && $invoice['companyafm'] != null) ? $invoice['companyafm'] : ''); ?>" >

                            <?php echo $__env->make('alerts.feedback', ['field' => 'companyafm'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('companydoy') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-companydoy"><?php echo e(__('Company area')); ?></label>
                            <input type="text" name="companydoy" id="input-companydoy" class="form-control<?php echo e($errors->has('companydoy') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company area')); ?>" value="<?php echo e(($invoice != null && $invoice['companydoy'] != null) ? $invoice['companydoy'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'companydoy'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('companyaddress') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-companyaddress"><?php echo e(__('Company address')); ?></label>
                            <input type="text" name="companyaddress" id="input-companyaddress" class="form-control<?php echo e($errors->has('companyaddress') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company address')); ?>" value="<?php echo e(($invoice != null && $invoice['companyaddress'] != null) ? $invoice['companyaddress'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'companyaddress'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-row">
                                <div class="form-group<?php echo e($errors->has('companyaddressnum') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-companyaddressnum"><?php echo e(__('Company address num')); ?></label>
                                    <input type="number" name="companyaddressnum" id="input-companyaddressnum" class="form-control<?php echo e($errors->has('companyaddressnum') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Company address num')); ?>" value="<?php echo e(($invoice != null && $invoice['companyaddressnum'] != null) ? $invoice['companyaddressnum'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'companyaddressnum'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('companypostcode') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-companypostcode"><?php echo e(__('Postcode')); ?></label>
                                    <input type="number" name="companypostcode" id="input-companypostcode" class="form-control<?php echo e($errors->has('companypostcode') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Postcode')); ?>" value="<?php echo e(($invoice != null && $invoice['companypostcode'] != null) ? $invoice['companypostcode'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'companypostcode'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('companycity') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-companycity"><?php echo e(__('City')); ?></label>
                                    <input type="text" name="companycity" id="input-companycity" class="form-control<?php echo e($errors->has('companycity') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('City')); ?>" value="<?php echo e(($invoice != null && $invoice['companycity'] != null) ? $invoice['companycity'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'companycity'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>

                        <h6 class="heading-small text-muted mb-4"><?php echo e(__('Student receipt details')); ?></h6>

                        <div class="form-group<?php echo e($errors->has('billname') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-billname"><?php echo e(__('Firstname')); ?></label>
                            <input type="text" name="billname" id="input-billname" class="form-control<?php echo e($errors->has('billname') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Firstname')); ?>" value="<?php echo e((isset($receipt['billname']) ) ? $receipt['billname'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'billname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('billsurname') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-billsurname"><?php echo e(__('Lastname')); ?></label>
                            <input type="text" name="billsurname" id="input-billsurname" class="form-control<?php echo e($errors->has('billsurname') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Lastname')); ?>" value="<?php echo e((isset($receipt['billsurname'])) ? $receipt['billsurname'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'billsurname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group<?php echo e($errors->has('billaddress') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-billaddress"><?php echo e(__('Address')); ?></label>
                            <input type="text" name="billaddress" id="input-billaddress" class="form-control<?php echo e($errors->has('billaddress') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Address')); ?>" value="<?php echo e((isset($receipt['billaddress'])) ? $receipt['billaddress'] : ''); ?>" autofocus>

                            <?php echo $__env->make('alerts.feedback', ['field' => 'billaddress'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-row">
                                <div class="form-group<?php echo e($errors->has('billaddressnum') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-billaddressnum"><?php echo e(__('Address No')); ?></label>
                                    <input type="number" name="billaddressnum" id="input-billaddressnum" class="form-control<?php echo e($errors->has('billaddressnum') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Address No')); ?>" value="<?php echo e((isset($receipt['billaddressnum'])) ? $receipt['billaddressnum'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'billaddressnum'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('billpostcode') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-billpostcode"><?php echo e(__('Postcode')); ?></label>
                                    <input type="number" name="billpostcode" id="input-billpostcode" class="form-control<?php echo e($errors->has('billpostcode') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Postcode')); ?>" value="<?php echo e((isset($receipt['billpostcode'])) ? $receipt['billpostcode'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'billpostcode'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('billcity') ? ' has-danger' : ''); ?> col-md-4">
                                    <label class="form-control-label" for="input-billcity"><?php echo e(__('City')); ?></label>
                                    <input type="text" name="billcity" id="input-billcity" class="form-control<?php echo e($errors->has('billcity') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('City')); ?>" value="<?php echo e((isset($receipt['billcity'])) ? $receipt['billcity'] : ''); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'billcity'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>



                            <input type="hidden" name="user_id" value="<?php echo e($user['id']); ?>">

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-8" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab">
                <div class="col-12 img_version">


                        <?php if(isset($user->image) && $user->image['name'] != ''): ?>
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
                            <a href="<?php echo e(route('media2.eventImage', $user->image)); ?>" target="_blank" class="btn btn-primary"><?php echo e(__('Versions')); ?></a>
                        </div>
                        <?php else: ?>
                            <?php echo e(__('No Image')); ?>

                        <?php endif; ?>


                    
                    <div class="crop-msg" id="profile_image_msg"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php //dd($user->image); ?>

            </div>
        </div>
        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>

    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $(document).on('click', '#remove_ticket_user',function(e) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        url: '<?php echo e(route("user.remove_ticket_user")); ?>',
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
                                <a class="dropdown-item" id="remove_ticket_user" data-event-id="${ticket.event_id}" data-user-id="${data.data.user_id}" data-ticket-id="${ticket.ticket_id}"><?php echo e(__('Delete')); ?></a>
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
    $img = <?php echo json_encode($user->image, 15, 512) ?>;


    if($img['name'] != '' && $img['details'] != null && $img['details'] !=''){
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



<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', [
    'title' => __('User Profile'),
    'navClass' => 'bg-default',
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/users/edit.blade.php ENDPATH**/ ?>
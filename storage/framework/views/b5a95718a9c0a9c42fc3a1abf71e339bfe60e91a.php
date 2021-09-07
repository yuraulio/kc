

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('user.index')); ?>"><?php echo e(__('User Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add User')); ?></li>
        <?php if (isset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd)): ?>
<?php $component = $__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd; ?>
<?php unset($__componentOriginalb0ed43a6eda44b84021326772a22e85ada88d5cd); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <?php if (isset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5)): ?>
<?php $component = $__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5; ?>
<?php unset($__componentOriginalf553482b463f6cda44d25fdb8f98d9a83d364bb5); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"><?php echo e(__('User Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('user.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('user.store')); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('User information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('firstname') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-firstname"><?php echo e(__('Firstname')); ?></label>
                                    <input type="text" name="firstname" id="input-firstname" class="form-control<?php echo e($errors->has('firstname') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('firstname')); ?>" placeholder="<?php echo e(__('Firstname')); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'firstname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('lastname') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-lastname"><?php echo e(__('Lastname')); ?></label>
                                    <input type="text" name="lastname" id="input-lastname" class="form-control<?php echo e($errors->has('lastname') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('lastname')); ?>" placeholder="<?php echo e(__('Lastname')); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'lastname'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-email"><?php echo e(__('Email')); ?></label>
                                    <input type="email" name="email" id="input-email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__('Email')); ?>" required>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('role_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-role"><?php echo e(__('Role')); ?></label>
                                    <select name="role_id" id="input-role" class="form-control<?php echo e($errors->has('role_id') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Role')); ?>" required>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php echo e($role->id == old('role_id') ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'role_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-password"><?php echo e(__('Password')); ?></label>
                                    <input type="password" name="password" id="input-password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Password')); ?>" value="" required>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation"><?php echo e(__('Confirm Password')); ?></label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control" placeholder="<?php echo e(__('Confirm Password')); ?>" value="" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('User Management'),
    'parentSection' => 'laravel',
    'elementName' => 'user-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/users/create.blade.php ENDPATH**/ ?>
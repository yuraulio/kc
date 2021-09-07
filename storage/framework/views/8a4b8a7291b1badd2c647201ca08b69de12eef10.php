

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('venue.index_main')); ?>"><?php echo e(__('Venues Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="type"><?php echo e(__('Edit Venue')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Venues Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('venue.index_main')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <form method="post" action="<?php echo e(route('venue.update', $venue)); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Venue information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-name"><?php echo e(__('Name')); ?></label>
                                    <input type="text" name="name" id="input-name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e(old('name', $venue->name)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('address') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-address"><?php echo e(__('Address')); ?></label>
                                    <input type="text" name="address" id="input-address" class="form-control<?php echo e($errors->has('address') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Address')); ?>" value="<?php echo e(old('address', $venue->address)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'address'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('longitude') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-longitude"><?php echo e(__('Longitude')); ?></label>
                                    <input type="number" min="-180" max="180" step="any" name="longitude" id="input-longitude" class="form-control<?php echo e($errors->has('longitude') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Longitude')); ?>" value="<?php echo e(old('longitude', $venue->longitude)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'longitude'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('latitude') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-latitude"><?php echo e(__('Latitude')); ?></label>
                                    <input type="number" min="-90" max="90" step="any" name="latitude" id="input-latitude" class="form-control<?php echo e($errors->has('latitude') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Latitude')); ?>" value="<?php echo e(old('latitude', $venue->latitude)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'latitude'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
    'title' => __('Venue Management'),
    'parentSection' => 'laravel',
    'elementName' => 'venues-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/venue/main/edit.blade.php ENDPATH**/ ?>
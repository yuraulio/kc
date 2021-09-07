

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('ticket.index')); ?>"><?php echo e(__('Ticket Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Ticket')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Tickets Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('ticket.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php //dd($ticket[0]); ?>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('ticket.update_main', $ticket)); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Ticket information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                    <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title', $ticket->title)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-subtitle"><?php echo e(__('Subtitle')); ?></label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control<?php echo e($errors->has('subtitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Subtitle')); ?>" value="<?php echo e(old('subtitle', $ticket->subtitle)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'subtitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('type') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-type"><?php echo e(__('Type')); ?></label>
                                    <select name="type" id="input-type" class="form-control" placeholder="<?php echo e(__('Type')); ?>" required>
                                        <option value="">-</option>
                                        <option <?php if($ticket['type'] == 'Early-bird')  echo 'selected'; ?> value="Early-bird">Early-bird</option>
                                        <option <?php if($ticket['type'] == 'Alumni')  echo 'selected'; ?> value="Alumni">Alumni</option>
                                        <option <?php if($ticket['type'] == 'Regular')  echo 'selected'; ?> value="Regular">Regural</option>
                                        <option <?php if($ticket['type'] == 'Special')  echo 'selected'; ?> value="Special">Special</option>
                                        <option <?php if($ticket['type'] == 'Sponsored')  echo 'selected'; ?> value="Special">Sponsored</option>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'type'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
    'title' => __('Ticket Management'),
    'parentSection' => 'laravel',
    'elementName' => 'tickets-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/ticket/main/edit.blade.php ENDPATH**/ ?>
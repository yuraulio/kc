<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('global.index')); ?>"><?php echo e(__('Category Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Category')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Category Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('global.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('category.update', $category)); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>
                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Category information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-name"><?php echo e(__('Name')); ?></label>
                                    <input type="text" name="name" id="input-name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e(old('name', $category->name)); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <?php echo $__env->make('admin.slug.slug',['slug' => isset($data['slug']) ? $data['slug'] : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <div class="form-group<?php echo e($errors->has('description') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-description"><?php echo e(__('Description')); ?></label>
                                    <textarea name="description" id="input-description" cols="30" rows="10" class="form-control<?php echo e($errors->has('description') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Description')); ?>"><?php echo e(old('description', $category->description)); ?></textarea>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'description'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('hourPs') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-hours"><?php echo e(__('Hours')); ?></label>
                                    <input type="text" name="hours" id="input-hours" class="form-control<?php echo e($errors->has('hours') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Hours')); ?>" value="<?php echo e(old('hours', $category->hours)); ?>">

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'hours'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Select Dropbox Folder</label>
                                    <?php //dd($data['folders']); ?>
                                    <select class="form-control" name="folder_name" id="folder_name">

                                        <?php $__currentLoopData = $data['folders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <?php $found = false; ?>
                                            <?php $__currentLoopData = $already_assign; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(isset($ass) && $ass['folder_name'] == $folder): ?>
                                                <?php $found = true; ?>

                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($found): ?>
                                            <?php //dd($folder); ?>
                                                <option selected value="<?php echo e($folder); ?>"><?php echo e($folder); ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo e($folder); ?>"><?php echo e($folder); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php echo $__env->make('alerts.feedback', ['field' => 'dropbox'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <?php //dd($category->show_homepage); ?>

                                <div class="form-group<?php echo e($errors->has('show_homepage') ? ' has-danger' : ''); ?>">
                                    <div class="status-label">
                                        <label class="form-control-label" for="input-show_homepage"><?php echo e(__('Show Homepage')); ?></label>
                                    </div>
                                    <div class="status-toogle">
                                        <label class="custom-toggle">
                                            <input type="checkbox" name="show_homepage" id="input-show_homepage" <?= ($category->show_homepage == 1) ? 'checked' : ''; ?>>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'show_homepage'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
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
    'title' => __('Category Management'),
    'parentSection' => 'laravel',
    'elementName' => 'categories-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/global_settings/categories/edit.blade.php ENDPATH**/ ?>
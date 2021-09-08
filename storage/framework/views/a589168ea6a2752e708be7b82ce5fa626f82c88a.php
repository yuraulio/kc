

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('Create option')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('role.index')); ?>"><?php echo e(__('option Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add option')); ?></li>
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


    
         <form id="sbt-option" method="post" action="<?php echo e(route('option.update',$option->id)); ?>" autocomplete="off" enctype="multipart/form-data">
         <?php echo method_field('put'); ?>
      
         <?php echo csrf_field(); ?>
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0"><?php echo e(__('Option Management')); ?></h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="<?php echo e(route('role.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4"><?php echo e(__('option information')); ?></h6>

                   
                     <div class="pl-lg-4">
                        <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                            <textarea rows="20" class="form-control" name="settings" id="settingsEditor"><?php echo $codes; ?></textarea>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="text-center">
                           <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </form>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', [
'title' => __('Create option'),
'parentSection' => 'laravel',
'elementName' => 'role-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/options/create.blade.php ENDPATH**/ ?>
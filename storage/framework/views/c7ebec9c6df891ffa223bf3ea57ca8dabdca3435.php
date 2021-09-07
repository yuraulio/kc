<form method="post" action="<?php echo e(route('metas.update',$metas->id)); ?>" autocomplete="off" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<div class="form-group<?php echo e($errors->has('metas') ? ' has-danger' : ''); ?>">
   <label class="form-control-label" for="input-title"><?php echo e(__('Metas')); ?></label>
   
   <div class="pl-lg-4">
                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                           <input type="text" name="title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title',$metas->meta_title)); ?>"  required autofocus>
                           
                        </div>

                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-title"><?php echo e(__('Keywords')); ?></label>
                           <input type="text" name="keywords" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Keywords')); ?>" value="<?php echo e(old('title',$metas->meta_keywords)); ?>"  required autofocus>
                           
                        </div>


                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-title"><?php echo e(__('Description')); ?></label>
                           <input type="text" name="description"  class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Description')); ?>" value="<?php echo e(old('title',$metas->meta_description)); ?>"  required autofocus>
                           
                        </div>
                  
                        <div class="text-right">
                           <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                        </div>
                     </div>

   <?php echo $__env->make('alerts.feedback', ['field' => 'metas'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</form><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/metas/metas.blade.php ENDPATH**/ ?>
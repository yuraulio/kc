

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('plans')); ?>"><?php echo e(__('Plan Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add Plan')); ?></li>
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
    <?php if(!$plan->name): ?>
         <form method="post" action="<?php echo e(route('plan.store')); ?>" autocomplete="off" enctype="multipart/form-data">
      <?php else: ?>
         <form method="post" action="<?php echo e(route('plan.update',$plan->id)); ?>" autocomplete="off" enctype="multipart/form-data">
         <?php echo method_field('put'); ?>
      <?php endif; ?>
         <?php echo csrf_field(); ?>
         <div class="row plan">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0"><?php echo e(__('Plan Management')); ?></h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="<?php echo e(route('role.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4"><?php echo e(__('Plan information')); ?></h6>


                     <div class="pl-lg-4">
                        <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-name"><?php echo e(__('Name')); ?></label>
                           <input type="text" name="name" id="input-name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e(old('name',$plan->name)); ?>"  required autofocus>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>


                        <div class="form-group<?php echo e($errors->has('description') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-title"><?php echo e(__('Description')); ?></label>
                           <textarea name="description" id="input-title" class="ckeditor form-control<?php echo e($errors->has('description') ? ' is-invalid' : ''); ?>" required autofocus><?php echo e(old('description',$plan->description)); ?></textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'description'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('price') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-price"><?php echo e(__('Price')); ?></label>
                           <input type="number" name="price" id="input-price" class="form-control<?php echo e($errors->has('price') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Price')); ?>" value="<?php echo e(old('price',$plan->cost)); ?>"  required autofocus>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'price'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('interval') ? ' has-danger' : ''); ?>">
                            <label class="form-control-label" for="input-price"><?php echo e(__('Billing Period')); ?></label>
                            <select class="form-control" id="interval" name="interval">
                                <option value="day">Daily</option>
                                <option value="week">Weekly</option>
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div>

                        <div class="form-group<?php echo e($errors->has('published') ? ' has-danger' : ''); ?>">
                            <label class="custom-toggle">
                                <input name="published" type="checkbox" <?php if($plan->published): ?> checked <?php endif; ?>>
                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                            </label>
                        </div>


                        <div class="input-group form-group<?php echo e($errors->has('interval_count') ? ' has-danger' : ''); ?>">
                           
                           <input class="form-control<?php echo e($errors->has('interval_count') ? ' is-invalid' : ''); ?>" id="interval_count" name='interval_count' value="<?php echo e($plan->interval_count); ?>" type="number" min="1"><div class="input-group-append"><span class="input-group-text" id="interval_count_label"> Day </span></div>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'interval_count'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('trial') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-trial"><?php echo e(__('Trial')); ?></label>
                           <input type="number" name="trial" id="input-trial" class="form-control<?php echo e($errors->has('trial') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Trial')); ?>" value="<?php echo e(old('trial_days',isset($plan->trial_days) ? $plan->trial_days : 0)); ?>"  required autofocus>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'trial'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <label class="form-control-label" for="input-events"><?php echo e(__('Events')); ?></label>
                        <div class="checkbox-overflow">

                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <input name='events[]' value="<?php echo e($event->id); ?>" type="checkbox" aria-label="Checkbox for following text input" <?php if(in_array($event->id, $event_plans)): ?> checked <?php endif; ?>>
                                    </div>
                                  </div>
                                  <input type="text" value="<?php echo e($event->title); ?>" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <label class="form-control-label" for="input-title"><?php echo e(__('Categories')); ?></label>
                        <div class="checkbox-overflow">

                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                      <input name='categories[]' value="<?php echo e($category->id); ?>" type="checkbox" aria-label="Checkbox for following text input" <?php if(in_array($category->id, $category_plans)): ?> checked <?php endif; ?>>
                                    </div>
                                  </div>
                                  <input type="text" value="<?php echo e($category->name); ?>" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <label class="form-control-label" for="input-title"><?php echo e(__('No Events')); ?></label>
                        <div class="checkbox-overflow">

                            <?php $__currentLoopData = $noevents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input name='noevents[]' value="<?php echo e($event->id); ?>" type="checkbox" <?php if(in_array($event->id, $event_noplans)): ?> checked <?php endif; ?>>
                                    </div>
                                  </div>
                                  <input type="text" value="<?php echo e($event->title); ?>" class="form-control" aria-label="Text input with checkbox">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>



    $(document).ready(function(){


        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }

        $("#interval_count_label").text(interval);

        if($("#interval_count").val() < 1){
            $("#interval_count").val(1);
        }

        if("<?php echo e($plan->interval); ?>"){
            $("#interval").val("<?php echo e($plan->interval); ?>");
        }


    })

    $("#interval").change(function(){
        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }

        $("#interval_count_label").text(interval);
    })

    $("#interval_count").keyup(function(){
        let interval = $("#interval").val();
        let interval_count = $("#interval_count").val();

        if(interval == 'year'){
            $("#interval_count").val(1);
        }

        if(interval == 'month'){

            if($("#interval_count").val() < 1){
                $("#interval_count").val(1);
            }else if($("#interval_count").val() > 12){
                $("#interval_count").val(12);
            }


        }

        if(interval == 'week'){

            if($("#interval_count").val() < 1){
                $("#interval_count").val(1);
            }else if($("#interval_count").val() > 52){
                $("#interval_count").val(52);
            }


        }


       interval_count = $("#interval_count").val();

        if(interval_count > 1){
            interval +='s';
        }


        $("#interval_count_label").text(interval);

    })

</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
'title' => __('Create Plan'),
'parentSection' => 'laravel',
'elementName' => 'plans-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/subscription/create.blade.php ENDPATH**/ ?>
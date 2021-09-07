
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('layouts.headers.auth'); ?>
<?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
<?php $__env->slot('title'); ?>
<?php echo e(__('')); ?>

<?php $__env->endSlot(); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('payments.index')); ?>"><?php echo e(__('Payments Management')); ?></a></li>
<li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add Payment Method')); ?></li>
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
   <div class="nav-wrapper" style="margin-top: 65px;">
      <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#method" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Method</a>
         </li>
         <?php if($form_type == 'edit'): ?>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#processor" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Processor</a>
         </li>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#test_processor" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Test Processor</a>
         </li>
         <?php endif; ?>
      </ul>
   </div>
   <div class="row">
      <div class="col-xl-12 order-xl-1">
         <div class="card">
            <div class="card-header">
               <div class="row align-items-center">
                  <div class="col-8">
                     <h3 class="mb-0"><?php echo e(__('Payments Management')); ?></h3>
                  </div>
                  <div class="col-4 text-right">
                     <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                  </div>
               </div>
            </div>
            <div class="card-body">
						<?php if($form_type == 'create'): ?>
                  <form method="post" action="<?php echo e(route('payments.store')); ?>" autocomplete="off"
                     enctype="multipart/form-data">
						<?php else: ?>
						<form method="post" action="<?php echo e(route('payments.update',$method['id'])); ?>" autocomplete="off"
                     enctype="multipart/form-data">
						<?php endif; ?>
                     <?php echo csrf_field(); ?>
							<div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade show active" id="method" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                        <h6 class="heading-small text-muted mb-4"><?php echo e(__('Payment information')); ?></h6>
                        <div class="pl-lg-4">
                           <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                              <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                              <input type="text" name="method_name" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('method_name',$method['method_name'])); ?>" required autofocus>
                              <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                           </div>
                           <div class="form-group<?php echo e($errors->has('processor') ? ' has-danger' : ''); ?>">
                              <label class="form-control-label" for="input-type"><?php echo e(__('Processor')); ?></label>
                              <select name="processor_id" id="input-type" class="form-control" placeholder="<?php echo e(__('Processor')); ?>">
                                 <option value="">-</option>
                                 <?php $__currentLoopData = $availableProcessors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $processor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <option value="<?php echo e($key); ?>" <?php echo e($key == old('processor_id',$method['processor_id']) ? 'selected' : ''); ?>><?php echo e($processor['name']); ?></option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                              <?php echo $__env->make('alerts.feedback', ['field' => 'type'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                           </div>
                           <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">
                              <label class="form-control-label" for="input-published"><?php echo e(__('Status')); ?></label>
                              <select name="status" id="input-published" class="form-control" placeholder="<?php echo e(__('Status')); ?>" >
                                 <option value="">-</option>
                                 <option value="0" <?php echo e(0 == old('status',$method['status']) ? 'selected' : ''); ?> ><?php echo e(__('Inactive')); ?></option>
                                 <option value="1" <?php echo e(1 == old('status',$method['status']) ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                                 <option value="2"<?php echo e(2 == old('status',$method['status']) ? 'selected' : ''); ?>><?php echo e(__('Test')); ?></option>
                              </select>
                              <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                           </div>
                           
                        </div>
                     </div>
                     <?php if($form_type == 'edit'): ?>
                     <div class="tab-pane fade" id="processor" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
								<?php echo $html; ?>


                     </div>

                     <div class="tab-pane fade" id="test_processor" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
								<?php echo $html_test; ?>


                     </div>
                     <?php endif; ?>
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
'title' => __('Payment Method Management'),
'parentSection' => 'laravel',
'elementName' => 'payment-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/payment_methods/add_method.blade.php ENDPATH**/ ?>
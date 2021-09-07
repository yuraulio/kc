<?php $noEditablePages = ['home','cart']; ?>


<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('layouts.headers.auth'); ?>
<?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
<?php $__env->slot('title'); ?>
<?php echo e(__('')); ?>

<?php $__env->endSlot(); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('pages.index')); ?>"><?php echo e(__('Page Management')); ?></a></li>
<li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add Page')); ?></li>
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
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#page" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Page</a>
         </li>
         <?php if($page->name || in_array($page->template,$noEditablePages)): ?>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-1-tab" data-toggle="tab" href="#metas" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Metas</a>
         </li>
         <?php endif; ?>

         <?php if($page->template !== 'cart'): ?>
         <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#media" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Media</a>
         </li>

         <?php endif; ?>

      </ul>
   </div>
<div class="tab-content" id="myTabContent">
   <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <?php if(!$page->name): ?>
         <form method="post" action="<?php echo e(route('pages.store')); ?>" autocomplete="off" enctype="multipart/form-data">
      <?php else: ?>
         <form method="post" action="<?php echo e(route('pages.update',$page->id)); ?>" autocomplete="off" enctype="multipart/form-data">
         <?php echo method_field('put'); ?>
      <?php endif; ?>
         <?php echo csrf_field(); ?>
         <div class="row">
            <div class="col-xl-9 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <div class="row align-items-center">
                        <div class="col-8">
                           <h3 class="mb-0"><?php echo e(__('Page Management')); ?></h3>
                        </div>
                        <div class="col-4 text-right">
                           <a href="<?php echo e(route('pages.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body">
                     <h6 class="heading-small text-muted mb-4"><?php echo e(__('Page information')); ?></h6>

                    <?php if($page->template != 'cart'): ?>
                     <div class="pl-lg-4">
                        <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-name"><?php echo e(__('Name')); ?></label>
                           <input type="text" name="name" id="input-name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e(old('name',$page->name)); ?>"  required autofocus>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <?php if(!in_array($page->template,$noEditablePages)): ?>
                           <?php echo $__env->make('admin.slug.slug',['slug' => isset($slug) ? $slug : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                           <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title',$page->title)); ?>"  autofocus>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('summary') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-summary"><?php echo e(__('Page Summary')); ?></label>
                           <textarea name="summary" id="input-summary"  class="ckeditor form-control<?php echo e($errors->has('summary') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Page summary')); ?>"  required autofocus><?php echo e(old('summary',$page->summary)); ?></textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'summary'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('permissions') ? ' has-danger' : ''); ?>">
                           <label class="form-control-label" for="input-permissions"><?php echo e(__('Page Editor')); ?></label>
                           <textarea name="content" id="input-content"  class="ckeditor form-control<?php echo e($errors->has('content') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Page editor')); ?>"  required autofocus><?php echo e(old('content',$page->content)); ?></textarea>
                           <?php echo $__env->make('alerts.feedback', ['field' => 'permissions'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                     </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
            <div   class="col-xl-3 order-xl-1">
               <div class="card">
                  <div class="card-header">
                     <?php if(!in_array($page->template,$noEditablePages)): ?>

                     <?php echo $__env->make('admin.preview.preview',['slug' => isset($slug) ? $slug : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                     <div class="form-group<?php echo e($errors->has('template') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-category_id"><?php echo e(__('Template')); ?></label>
                        <select name="template" id="input-category_id" class="form-control" placeholder="<?php echo e(__('Template')); ?>">
                        <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($template); ?>" <?php echo e($template == old('template',$page->template) ? 'selected' : ''); ?>><?php echo e($key); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'template'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     </div>

                     <div class="form-group<?php echo e($errors->has('published') ? ' has-danger' : ''); ?>">
                        <label class="form-control-label" for="input-category_id"><?php echo e(__('Published')); ?></label>
                        <select name="published" id="input-category_id" class="form-control" placeholder="<?php echo e(__('Published')); ?>">

                        <option value="0" <?php echo e(0 == old('published',$page->published) ? 'selected' : ''); ?>> Unpublished </option>
                        <option value="1" <?php echo e(1 == old('published',$page->published) ? 'selected' : ''); ?>> Published </option>
                        </select>
                        <?php echo $__env->make('alerts.feedback', ['field' => 'template'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     </div>
                     <?php endif; ?>
                     <div class="pl-lg-4">
                     <div class="text-center">
                           <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                        </div>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>

   <?php if($page->name || in_array($page->template,$noEditablePages)): ?>
   <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
            <?php echo $__env->make('admin.metas.metas',['metas' => $metas], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
      </div>
   </div>
   <?php endif; ?>
   <?php if($page->name && $page->template != 'cart'): ?>
   <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
      <div class="row">
         <div class="col-xl-12 order-xl-1">
         <?php echo $__env->make('admin.upload.upload', ['event' => ($media != null) ? $media : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>

      </div>
        <?php if($media != null && $media['name'] != ''): ?>
            <div id="version-btn" style="margin-bottom:20px" class="col">
                <a href="<?php echo e(route('media2.eventImage', $media)); ?>" target="_blank" class="btn btn-primary"><?php echo e(__('Versions')); ?></a>
            </div>
        <?php endif; ?>
   </div>

   
   <?php endif; ?>

   <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', [
'title' => __('Pages Management'),
'parentSection' => 'laravel',
'elementName' => 'pages-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/pages/create.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
    <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__()); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('events.index')); ?>"><?php echo e(__('Events Management')); ?></a></li>
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
    <?php
    //dd($media);
    //$model_type = get_class($media);
    if($media['mediable_type'] == 'App\Model\User'){
        $versions = [];
    }else if($media['mediable_type'] == 'App\Model\Event'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Pages' || $media['mediable_type'] == 'App\Model\Logos'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Instructor'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }else if($media['mediable_type'] == 'App\Model\Testimonial'){
        $versions = ['social-media-sharing','instructors-testimonials', 'event-card', 'users' ,'header-image', 'instructors-small' ,'feed-image'];
    }
    ?>

    <?php

     ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col image-version-page">
                <?php echo $__env->make('event.image_versions_new', ['event' => $media,'versions1'=> $versions], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/layouts/media_versions.blade.php ENDPATH**/ ?>
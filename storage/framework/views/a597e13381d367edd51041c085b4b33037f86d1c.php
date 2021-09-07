

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('Examples')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('instructors.index')); ?>"><?php echo e(__('Instructors Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Instructor')); ?></li>
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

            <div class="col-xl-12 order-xl-1">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Edit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                        </li>

                    </ul>
                </div>
            <div>

            <div class="col-xl-12 order-xl-1">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <form method="post" action="<?php echo e(route('instructors.update', $instructor)); ?>" autocomplete="off"
                                    enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('put'); ?>

                                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Instructor information')); ?></h6>
                                    <div class="pl-lg-4">


                                        <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">


                                            <label class="custom-toggle custom-published">
                                                <input type="checkbox" name="status" id="input-status" <?php if($instructor['status'] == '1'): ?> checked <?php endif; ?>>
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                            </label>
                                                <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                        </div>

                                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                            <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title', $instructor->title)); ?>" required autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('short_title') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-short_title"><?php echo e(__('Short title')); ?></label>
                                            <input type="text" name="short_title" id="input-short_title" class="form-control<?php echo e($errors->has('short_title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('short_title')); ?>" value="<?php echo e(old('Short title', $instructor->short_title)); ?>" autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'short_title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-subtitle"><?php echo e(__('Subtitle')); ?></label>
                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control<?php echo e($errors->has('subtitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('subtitle')); ?>" value="<?php echo e(old('Subtitle', $instructor->subtitle)); ?>" autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'subtitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('header') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-header"><?php echo e(__('Header')); ?></label>
                                            <input type="text" name="header" id="input-header" class="form-control<?php echo e($errors->has('header') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Header')); ?>" value="<?php echo e(old('header', $instructor->header)); ?>" autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'header'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('summary') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-summary"><?php echo e(__('Summary')); ?></label>
                                            <textarea name="summary" id="input-summary"  class="ckeditor form-control<?php echo e($errors->has('summary') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Summary')); ?>" required autofocus><?php echo e(old('summary', $instructor->summary)); ?></textarea>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'summary'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-body"><?php echo e(__('Body')); ?></label>
                                            <textarea name="body" id="input-body"  class="ckeditor form-control<?php echo e($errors->has('body') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Body')); ?>" required autofocus><?php echo e(old('body', $instructor->body)); ?></textarea>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'body'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('ext_url') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-ext_url"><?php echo e(__('External url')); ?></label>
                                            <input type="text" name="ext_url" id="input-ext_url" class="form-control<?php echo e($errors->has('ext_url') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('External url')); ?>" value="<?php echo e(old('ext_url', $instructor->ext_url)); ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'ext_url'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('user_id') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-user_id"><?php echo e(__('Assign User')); ?></label>
                                            <select name="user_id" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-user_id" class="form-control" placeholder="<?php echo e(__('Instructor')); ?>">
                                                <option value=""></option>

                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <?php if($instructor->user->first() != null && $instructor->user->first()['id'] == $user['id']): ?>
                                                <option selected value="<?php echo e($user['id']); ?>"><?php echo e($user['firstname']); ?> <?php echo e($user['lastname']); ?></option>
                                                <?php else: ?>
                                                <option value="<?php echo e($user['id']); ?>"><?php echo e($user['firstname']); ?> <?php echo e($user['lastname']); ?></option>
                                                <?php endif; ?>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'user_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <?php
                                            $social_media = json_decode($instructor['social_media'], true);
                                            //dd($social_media);
                                        ?>

                                        <div class="form-group<?php echo e($errors->has('facebook') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-facebook"><?php echo e(__('Facebook')); ?></label>
                                            <input type="text" name="facebook" id="input-facebook" class="form-control<?php echo e($errors->has('facebook') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Facebook link')); ?>" value="<?= (isset($social_media['facebook'])) ? $social_media['facebook'] : ''; ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'facebook'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('instagram') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-instagram"><?php echo e(__('Instagram')); ?></label>
                                            <input type="text" name="instagram" id="input-instagram" class="form-control<?php echo e($errors->has('instagram') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Instagram link')); ?>" value="<?= isset($social_media['instagram']) ? $social_media['instagram'] : ''; ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'instagram'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('linkedin') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-linkedin"><?php echo e(__('Linkedin')); ?></label>
                                            <input type="text" name="linkedin" id="input-linkedin" class="form-control<?php echo e($errors->has('linkedin') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Linkedin link')); ?>" value="<?= isset($social_media['linkedin']) ? $social_media['linkedin'] : ''; ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'linkedin'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('twitter') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-twitter"><?php echo e(__('Twitter')); ?></label>
                                            <input type="text" name="twitter" id="input-twitter" class="form-control<?php echo e($errors->has('twitter') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Twitter link')); ?>" value="<?= isset($social_media['twitter']) ? $social_media['twitter'] : ''; ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'twitter'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                                        <div class="form-group<?php echo e($errors->has('youtube') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-youtube"><?php echo e(__('Youtube')); ?></label>
                                            <input type="text" name="youtube" id="input-youtube" class="form-control<?php echo e($errors->has('youtube') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Youtube link')); ?>" value="<?= isset($social_media['youtube']) ? $social_media['youtube'] : ''; ?>"autofocus>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'youtube'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>


                                        <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="<?php echo e($instructor->creator_id); ?>">
                                        <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="<?php echo e($instructor->author_id); ?>">


                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                            <?php echo $__env->make('admin.upload.upload', ['event' => ( isset($instructor) && $instructor->medias != null) ? $instructor->medias : null, 'versions' => ['instructors-testimonials', 'instructors-small']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <?php if($instructor->medias != null && $instructor->medias['name'] != ''): ?>
                                <div id="version-btn" style="margin-bottom:20px" class="col">
                                    <a href="<?php echo e(route('media2.eventImage', $instructor->medias)); ?>" target="_blank" class="btn btn-primary"><?php echo e(__('Versions')); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        <div>
    <div>















        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Instructors Management'),
    'parentSection' => 'laravel',
    'elementName' => 'instructors-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/instructors/edit.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('topics.index')); ?>"><?php echo e(__('Topics Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Topic')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Topics Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('topics.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('topics.update', $topic)); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Topic information')); ?></h6>
                            <div class="pl-lg-4">

                                <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">


                                    <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="status" id="input-status" <?php if($topic['status'] == '1'): ?> checked <?php endif; ?>>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                </div>

                                <div class="form-group<?php echo e($errors->has('comment_status') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-comment_status"><?php echo e(__('Comment status')); ?></label>
                                    <input type="text" name="comment_status" id="input-comment_status" class="form-control<?php echo e($errors->has('comment_status') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Comment status')); ?>" value="<?php echo e(old('comment_status', $topic->comment_status)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'comment_status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                    <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title', $topic->title)); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('category_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-category_id"><?php echo e(__('Category')); ?></label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="<?php echo e(__('Category')); ?>" required>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if(count($topic->category) != 0){
                                                if($topic->category[0]->id == $category->id){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>
                                            value="<?php echo e($category->id); ?>" ><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'category_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('short_title') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-short_title"><?php echo e(__('Short title')); ?></label>
                                    <input type="text" name="short_title" id="input-short_title" class="form-control<?php echo e($errors->has('short_title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('short_title')); ?>" value="<?php echo e(old('Short title', $topic->short_title)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'short_title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-subtitle"><?php echo e(__('Subtitle')); ?></label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control<?php echo e($errors->has('subtitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('subtitle')); ?>" value="<?php echo e(old('Subtitle', $topic->subtitle)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'subtitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('header') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-header"><?php echo e(__('Header')); ?></label>
                                    <input type="text" name="header" id="input-header" class="form-control<?php echo e($errors->has('header') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Header')); ?>" value="<?php echo e(old('header', $topic->header)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'header'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('summary') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-summary"><?php echo e(__('Summary')); ?></label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control<?php echo e($errors->has('summary') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Summary')); ?>" required autofocus><?php echo e(old('summary', $topic->summary)); ?></textarea>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'summary'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-body"><?php echo e(__('Body')); ?></label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control<?php echo e($errors->has('body') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Body')); ?>" required autofocus><?php echo e(old('body', $topic->body)); ?></textarea>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'body'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="<?php echo e($topic->creator_id); ?>">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="<?php echo e($topic->author_id); ?>">

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'ext_url'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


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
    'title' => __('Topic Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'topics-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/topics/edit.blade.php ENDPATH**/ ?>
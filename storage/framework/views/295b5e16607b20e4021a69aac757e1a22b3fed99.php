

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('events.index')); ?>"><?php echo e(__('Events Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Add Event')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Events Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('events.store')); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Event information')); ?></h6>
                            <div class="pl-lg-4">
                                <div class="d-none form-group<?php echo e($errors->has('priority') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-priority"><?php echo e(__('Priority')); ?></label>
                                    <input type="number" name="priority" id="input-priority" class="form-control<?php echo e($errors->has('priority') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Priority')); ?>" value="<?php echo e(old('priority')); ?>">

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'priority'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('published') ? ' has-danger' : ''); ?>">

                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="published" id="input-published">
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'published'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                </div>

                                <div class="form-group<?php echo e($errors->has('category_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-category_id"><?php echo e(__('Category')); ?></label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="<?php echo e(__('Category')); ?>">
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e($category->id == old('category_id') ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'category_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('type_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-type_id"><?php echo e(__('Type')); ?></label>
                                    <select multiple name="type_id[]" id="input-type_id" class="form-control" placeholder="<?php echo e(__('Type')); ?>" required>
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>" ><?php echo e($type->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'type_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('delivery') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-delivery"><?php echo e(__('Delivery')); ?></label>
                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="<?php echo e(__('Delivery')); ?>" >
                                            <?php $__currentLoopData = $delivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($delivery->id); ?>" ><?php echo e($delivery->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'delivery'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div id="exp_input" class="form-group<?php echo e($errors->has('expiration') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-expiration"><?php echo e(__('Months access')); ?></label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control<?php echo e($errors->has('expiration') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Enter number of months')); ?>"autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'expiration'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>



                                <div class="form-group<?php echo e($errors->has('release_date_files') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-delivery"><?php echo e(__('Access to files until')); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                        </div>
                                        <input class="form-control datepicker" id="input-release_date_files" name="release_date_files" placeholder="Select date" type="text" value="">
                                    </div>
                                    <?php echo $__env->make('alerts.feedback', ['field' => 'release_date_files'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>



                                <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-status"><?php echo e(__('Status')); ?></label>
                                    <select name="status" id="input-status" class="form-control" placeholder="<?php echo e(__('Status')); ?>" >
                                            <option value="">-</option>
                                            <option value="4"><?php echo e(__('My Account Only')); ?></option>
                                            <option value="2"><?php echo e(__('Soldout')); ?></option>
                                            <option value="3"><?php echo e(__('Completed')); ?></option>
                                            <option value="0"><?php echo e(__('Open')); ?></option>
                                            <option value="1"><?php echo e(__('Close')); ?></option>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                    <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title')); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <?php echo $__env->make('admin.slug.slug',['slug' => isset($slug) ? $slug : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <div class="form-group<?php echo e($errors->has('htmlTitle') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-htmlTitle"><?php echo e(__('HTML title')); ?></label>
                                    <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control<?php echo e($errors->has('htmlTitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('HTML title')); ?>" value="<?php echo e(old('Short title')); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'htmlTitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-subtitle"><?php echo e(__('Subtitle')); ?></label>
                                    <input type="text" name="subtitle" id="input-subtitle" class="form-control<?php echo e($errors->has('subtitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('subtitle')); ?>" value="<?php echo e(old('Subtitle')); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'subtitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('header') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-header"><?php echo e(__('Header')); ?></label>
                                    <input type="text" name="header" id="input-header" class="form-control<?php echo e($errors->has('header') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Header')); ?>" value="<?php echo e(old('header')); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'header'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('summary') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-summary"><?php echo e(__('Summary')); ?></label>
                                    <textarea name="summary" id="input-summary"  class="ckeditor form-control<?php echo e($errors->has('summary') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Summary')); ?>"  required autofocus></textarea>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'summary'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-body"><?php echo e(__('Body')); ?></label>
                                    <textarea name="body" id="input-body"  class="ckeditor form-control<?php echo e($errors->has('body') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Body')); ?>"  required autofocus></textarea>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'body'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('hours') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-hours"><?php echo e(__('Hours')); ?></label>
                                    <input type="text" name="hours" id="input-hours" class="form-control<?php echo e($errors->has('hours') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Hours')); ?>" value="<?php echo e(old('hours')); ?>"autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'hours'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <div class="form-group<?php echo e($errors->has('view_tpl') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-view_tpl"><?php echo e(__('View tpl')); ?></label>
                                    <select name="view_tpl"  class="form-control" placeholder="<?php echo e(__('View tpl')); ?>">
                                        <?php $__currentLoopData = get_templates('events'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($template); ?>" <?php echo e($template == old('template') ? 'selected' : ''); ?>><?php echo e($key); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'view_tpl'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <?php //dd($instructors[10][0]); ?>

                                <div class="form-group<?php echo e($errors->has('syllabus') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-syllabus"><?php echo e(__('Syllabus Manager')); ?></label>
                                    <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus" class="form-control" placeholder="<?php echo e(__('Syllabus Manager')); ?>">
                                        <option value=""></option>

                                        <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($instructors[$key][0]->medias != null): ?>
                                            <option ext="<?php echo e($instructors[$key][0]->medias['ext']); ?>" original_name="<?php echo e($instructors[$key][0]->medias['original_name']); ?>" name="<?php echo e($instructors[$key][0]->medias['name']); ?>" path="<?php echo e($instructors[$key][0]->medias['path']); ?>" value="<?php echo e($key); ?>"><?php echo e($instructors[$key][0]['title']); ?> <?php echo e($instructors[$key][0]['subtitle']); ?></option>
                                            <?php else: ?>
                                            <option path="null" name="null" ext="null" value="<?php echo e($key); ?>"><?php echo e($instructors[$key][0]['title']); ?> <?php echo e($instructors[$key][0]['subtitle']); ?></option>
                                            <?php endif; ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'syllabus'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="<?php echo e($user->id); ?>">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="<?php echo e($user->id); ?>">

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


<?php $__env->startPush('js'); ?>

<script>

    instructors = <?php echo json_encode($instructors, 15, 512) ?>;

    $(document).ready(function(){
        $("#input-syllabus").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions
            });
        });

        function formatOptions (state) {
        if (!state.id) {
            return state.text;
        }


        path = state.element.attributes['path'].value
        name = state.element.attributes['name'].value
        plus_name = '-instructors-small'
        ext = state.element.attributes['ext'].value

        var $state = $(
        '<span class="rounded-circle"><img class="avatar-sm rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'" /> ' + state.text + '</span>'
        );

        var $state1 = $(
        '<span class="avatar avatar-sm rounded-circle"><img class="rounded-circle" sytle="display: inline-block;" src="' +path + name + plus_name + ext +'"/></span>'
        );

        return $state;
        }
</script>

<script>
    $( "#input-delivery" ).change(function() {
        if($(this).val() == 143){
            $('#exp_input').css('display', 'block')
        }else{
            $('#exp_input').css('display', 'none')
        }
    });
</script>

<script>
    $( "#select-image" ).click(function() {
        path = ''
        $.each( $('.fm-breadcrumb li'), function(key, value) {
            if(key != 0){
                path = path+'/'+$(value).text()
            }
        })

        name = $('.table-info .fm-content-item').text()
        name = name.replace(/\s/g, '')
        ext = $('.table-info td:nth-child(3)').text()
        ext = ext.replace(/\s/g, '')
        path = path +'/'+name+'.'+ext

        $('#image_upload').val(path)



        $('#img-upload').attr('src', path);

        $(".close").click();
    });



</script>


<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/event/create.blade.php ENDPATH**/ ?>
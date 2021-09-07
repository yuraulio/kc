

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
        <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__('')); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('lessons.index')); ?>"><?php echo e(__('Lessons Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Lesson')); ?></li>
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
                                <h3 class="mb-0"><?php echo e(__('Lessons Management')); ?></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="<?php echo e(route('lessons.index')); ?>" class="btn btn-sm btn-primary"><?php echo e(__('Back to list')); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo e(route('lessons.update', $lesson)); ?>" autocomplete="off"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <h6 class="heading-small text-muted mb-4"><?php echo e(__('Lesson information')); ?></h6>
                            <div class="pl-lg-4">
                                <?php //dd($lesson); ?>

                            <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">
                                <label class="custom-toggle custom-published">
                                    <input name="status" id="input-status" type="checkbox" <?php if($lesson->status): ?> checked <?php endif; ?>>
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                </label>
                                <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                                <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                    <input type="text" name="title" id="input-title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title', $lesson->title)); ?>" required autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <?php //dd($topics[1]); ?>
                                <?php //dd($lesson->topic); ?>

                                <div class="form-group<?php echo e($errors->has('topic_id') ? ' has-danger' : ''); ?>">
                                    <div class="row">
                                        <div class="col-4">
                                                <label class="form-control-label" for="input-topic_id"><?php echo e(__('Filters')); ?></label>
                                                <div class="filter_col" data-column="9">
                                                    <select name="category" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..."  name="Name" class="column_filter" id="category">
                                                    <option></option>
                                                    </select>
                                                </div>
                                                <div class="filter_col" data-column="9">
                                                    <input class="select2-css" type="text" placeholder="Search Topic" id="searchTopic" name="searchTopic">
                                                </div>

                                            </div>

                                        <div class="col-8">
                                            <label class="form-control-label" for="input-topic_id"><?php echo e(__('Topic')); ?></label>
                                            <select multiple name="topic_id[]" id="input-topic_id" class="form-control topics" placeholder="<?php echo e(__('Topic')); ?>" required>

                                                <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $selected = false; ?>

                                                    <?php $__currentLoopData = $lesson->topic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected_topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($topic->id == $selected_topic['id']): ?>
                                                            <?php $selected = true; ?>
                                                        <?php endif; ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    <option <?= ($selected) ? 'selected' : ''; ?> data-categoryName="<?php echo e($topic->category[0]->name); ?>" data-category="<?php echo e($topic->category[0]->id); ?>" value="<?php echo e($topic->id); ?>" > <?php echo e($topic->title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php echo $__env->make('alerts.feedback', ['field' => 'topic_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                
                                <div class="form-group<?php echo e($errors->has('type_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-type_id"><?php echo e(__('Type')); ?></label>
                                    <select name="type_id" id="input-type_id" class="form-control" placeholder="<?php echo e(__('Type')); ?>">
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($type['id'] >= 150 && $type['id'] <= 161): ?>
                                            <option <?php if(isset($lesson->type[0]['id']) && $lesson->type[0]['id'] == $type['id']): ?> selected <?php endif; ?>
                                            value="<?php echo e($type->id); ?>" > <?php echo e($type->name); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'type_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('bold') ? ' has-danger' : ''); ?>">
                                <label class="form-control-label" for="input-bold"><?php echo e(__('Bold')); ?></label>
                                <label class="custom-toggle custom-published toggle-bold">
                                    <input name="bold" id="input-bold" type="checkbox" <?php if($lesson->bold): ?> checked <?php endif; ?>>
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="off" data-label-on="on"></span>
                                </label>
                                <?php echo $__env->make('alerts.feedback', ['field' => 'bold'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                

                                <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-vimeo_video"><?php echo e(__('Vimeo Video')); ?></label>
                                    <input type="text" name="vimeo_video" id="input-vimeo_video" class="form-control<?php echo e($errors->has('vimeo_video') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Vimeo Video')); ?>" value="<?php echo e(old('vimeo_video', $lesson->vimeo_video)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'vimeo_video'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-vimeo_duration"><?php echo e(__('Vimeo Duration')); ?></label>
                                    <input type="text" name="vimeo_duration" id="input-vimeo_duration" class="form-control<?php echo e($errors->has('vimeo_duration') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Vimeo Duration')); ?>" value="<?php echo e(old('vimeo_duration', $lesson->vimeo_duration)); ?>" autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'vimeo_duration'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group">
                                    <button class="btn btn-primary add-dynamic-link" type="button">Add Link</button>
                                    <div id="dynamic-link">
                                        <?php //dd($lesson); ?>
                                        <?php if(!empty($lesson['links'])): ?>
                                            <?php
                                            $links = json_decode($lesson['links'], true);
                                            //dd($links);
                                             ?>
                                            <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="lesson-links-admin">
                                                <input type="text" name="names[]" class="form-control names" placeholder="Enter Link Name" value="<?php echo e($link['name']); ?>">
                                                <input type="text" name="links[]" class="form-control links" placeholder="Enter Link <?php echo e($key + 1); ?>" value="<?php echo e($link['link']); ?>">
                                                <button type="button" class="btn btn-danger remove-link">Remove</button>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php endif; ?>

                                    </div>

                                </div>

                                    <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="<?php echo e($lesson->creator_id); ?>">
                                    <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="<?php echo e($lesson->author_id); ?>">

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
    let topics = <?php echo json_encode($topics, 15, 512) ?>;
    let uniqueTopics = []
    var selectedCategory = null

    function removeSpecial(s){
            s = s.replace(/ /g,'');
            s = s.replace(/&/g,'');
            s = s.replace(/amp;/g,'');
            return s
    }

    $(function() {
        $("#category").select2({
            placeholder: "By Category",
            allowClear: true
        });

        $.each(topics, function(key, value) {
            let categoryName = value.category[0].name
            if(uniqueTopics[categoryName] === undefined){
                uniqueTopics[categoryName] = categoryName
                $('#category').append(`<option value="${value.category[0].id}">${categoryName}</option>`)
            }
        })

        //on select filter category Show or Hide topics
        $('#category').on('select2:select', function (e) {
            var data = e.params.data;
            selectedCategory = data.text
            $.each($('#input-topic_id option'), function(key, value) {
                if($(value).data('categoryname') !== undefined){
                    if(removeSpecial($(value).data('categoryname')) != removeSpecial(selectedCategory)){
                        $(value).hide()
                    }
                    else{
                        $(value).show()
                    }
                }

            })
        });

        $("#category").on("select2:unselecting", function (e) {
            $.each($('#input-topic_id option'), function(key, value) {
                $(value).show()
            })
        });

        $( "#searchTopic" ).keyup(function() {

            let word = $('#searchTopic').val()
            if( word.length >= 3){
                $.each($('#input-topic_id option'), function(key, value) {
                    let name = $(value).text()
                    if(name !== undefined){
                        if(name.includes(word)){
                            $(value).show()
                        }else{
                            $(value).hide()
                        }
                    }

                })

            }else if(word === ""){
                $.each($('#input-topic_id option'), function(key, value) {
                    if($(value).data('categoryname') !== undefined){
                        if(removeSpecial($(value).data('categoryname')) != removeSpecial(selectedCategory)){
                            $(value).hide()
                        }
                        else{
                            $(value).show()
                        }
                    }

                })

            }
        });


    })





    $(document).on('click', '.add-dynamic-link', function() {

        count = $('.links').length + 1

        row = `
        <div class="lesson-links-admin">
            <input type="text" name="names[]" class="form-control names" placeholder="Enter Link Name" value="">
            <input type="text" name="links[]" class="form-control links" placeholder="Enter Link ${count}" value="">
        </div>
        `

        $('#dynamic-link').append(row)
    })

    $(document).on('click', '.remove-link', function() {
        $(this).parent().remove()

        $.each($('.links'), function(key, value) {
            key = key + 1
            $(value).attr('placeholder', 'Enter Link '+key)
        })
    })

    let a = [];
    $( "#input-topic_id" ).change(function(e) {
        //alert( "Handler for .change() called." );

        let selected_value = $(this).find(":selected").val();
        let selected_cat = $(this).find(":selected").data("category");

        if(selected_cat == undefined){
            $( '.topics option' ).each(function() {
                $( this ).removeAttr("disabled")
                $(this).css("opacity", "1")

                let a = [];
            })
        }
        //alert('sdf'+selected_category)
        //console.log(selected_category)



        $( '.topics option' ).each(function(e) {
            let cat = $( this ).data('category')
            let value = $( this ).val()

            if(selected_cat == cat && selected_value != value){
                $(this).attr("disabled", "true")
                $(this).css("opacity", "0.4")
            }



            // if(a.find(e => a = selected_category)){
            //     alert(a)
            //     $(this).attr("disabled", "true")
            //     $(this).css("opacity", "0.4")
            // }

            // if(value != undefined){
            //     a.push(cat)
            // }
        });
    });

</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', [
    'title' => __('Lesson Edit'),
    'parentSection' => 'laravel',
    'elementName' => 'lessons-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/lesson/edit.blade.php ENDPATH**/ ?>
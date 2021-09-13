<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('layouts.headers.auth'); ?>
    <?php $__env->startComponent('layouts.headers.breadcrumbs'); ?>
            <?php $__env->slot('title'); ?>
                <?php echo e(__($event['title'])); ?>

            <?php $__env->endSlot(); ?>

            <li class="breadcrumb-item"><a href="<?php echo e(route('events.index')); ?>"><?php echo e(__('Events Management')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Event')); ?></li>
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
    <?php //dd($event->type); ?>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Students</a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                    <div class="col-12 mt-2">
                        <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('alerts.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>


                        <div class="form_event_btn">
                            <div class="save_event_btn" ><?php echo $__env->make('admin.save.save',['event' => isset($event) ? $event : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                            <div class="preview_event_btn"><?php echo $__env->make('admin.preview.preview',['slug' => isset($slug) ? $slug : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>

                        </div>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <form method="post" id="event_edit_form" method="POST" action="<?php echo e(route('events.update', $event)); ?>" autocomplete="off"
                                            enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('put'); ?>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-method"><?php echo e(__('Method Payment')); ?></label>
                                    <select name="payment_method" id="input-method" class="form-control" placeholder="<?php echo e(__('Method Payment')); ?>" no-mouseflow>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($method->id); ?>" <?php echo e($event['paymentMethod']->first() && $event['paymentMethod']->first()->id ==$method->id ? 'selected' : ''); ?> ><?php echo e($method->method_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'payment_method'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group<?php echo e($errors->has('category_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-category_id"><?php echo e(__('Category')); ?></label>
                                    <select name="category_id" id="input-category_id" class="form-control" placeholder="<?php echo e(__('Category')); ?>" required>
                                        <option></option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if(count($event->category) != 0){
                                                if($event->category[0]->id == $category->id){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?> value="<?php echo e($category->id); ?>" ><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'category_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('type_id') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-type_id"><?php echo e(__('Type')); ?></label>
                                    <select multiple name="type_id[]" id="input-type_id" class="form-control" placeholder="<?php echo e(__('Type')); ?>" required>
                                        <option value="">-</option>

                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $found = false; ?>
                                            <option <?php if(count($event->type) != 0){
                                                foreach($event->type as $selected_type){
                                                    if($selected_type['id'] == $type['id']){
                                                        $found = true;
                                                    }
                                                }
                                                if($found){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?> value="<?php echo e($type->id); ?>" ><?php echo e($type->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'type_id'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group<?php echo e($errors->has('delivery') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-delivery"><?php echo e(__('Delivery')); ?></label>
                                    <select name="delivery" id="input-delivery" class="form-control" placeholder="<?php echo e(__('Delivery')); ?>" required>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $delivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php if(count($event->delivery) != 0){
                                                if($event->delivery[0]->id == $delivery->id){
                                                    echo 'selected';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?> value="<?php echo e($delivery->id); ?>" ><?php echo e($delivery->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'delivery'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="row">

                                    <div style="margin: auto;" class="col-md-6 col-sm-6">
                                        <div style="margin: auto;" class="form-group<?php echo e($errors->has('published') ? ' has-danger' : ''); ?>">



                                        <label class="custom-toggle custom-published">
                                            <input type="checkbox" name="published" id="input-published" <?php if($event['published']): ?> checked <?php endif; ?>>
                                            <span class="custom-toggle-slider rounded-circle" data-label-off="unpublished" data-label-on="published"></span>
                                        </label>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'published'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



                                        </div>
                                    </div>
                                    <?php if($event['published_at'] != null): ?>
                                    <div class="col-md-6 col-sm-6 is-flex">

                                        <div class="form-group col-sm-6">
                                            <label class="form-control-label" for="launch_date"><?php echo e(__('Launch Date')); ?></label>
                                            <input type="text" name="launch_date" type="text" id="input-launch-input"
                                                        value="<?php echo e(date('d-m-Y',strtotime(old('launch_date', $event->launch_date)))); ?>" class="form-control datepicker" />

                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label class="form-control-label" for="input-published"><?php echo e(__('Published at')); ?></label>
                                            <input type="text" name="published_at" type="text" id="input-published-input"
                                                        value="<?php echo e(date('d-m-Y',strtotime(old('published_at', $event->published_at)))); ?>" class="form-control" disabled />

                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div id="exp_input" class="form-group<?php echo e($errors->has('expiration') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-expiration"><?php echo e(__('Months access')); ?></label>
                                    <input type="number" min="1" name="expiration" id="input-expiration" class="form-control<?php echo e($errors->has('expiration') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Enter number of months')); ?>" value="<?php echo e(old('expiration', $event->expiration)); ?>"autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'expiration'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>


                                <div class="form-group<?php echo e($errors->has('release_date_files') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-delivery"><?php echo e(__('Access to files until')); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input class="form-control datepicker" id="input-release_date_file" name="release_date_files" placeholder="Select date" type="text" value="<?php echo e(date('d-m-Y',strtotime(old('release_date_files', $event->release_date_files)))); ?>">
                                        </div>
                                        <?php echo $__env->make('alerts.feedback', ['field' => 'release_date_files'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>

                                <div class="form-group<?php echo e($errors->has('status') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-status"><?php echo e(__('Status')); ?></label>
                                    <select name="status" id="input-status" class="form-control" placeholder="<?php echo e(__('Status')); ?>" >
                                        <option value="">-</option>
                                            <option <?= ($event['status'] == 4) ? "selected" : ''; ?> value="4"><?php echo e(__('My Account Only')); ?></option>
                                            <option <?= ($event['status'] == 2) ? "selected" : ''; ?> value="2"><?php echo e(__('Soldout')); ?></option>
                                            <option <?= ($event['status'] == 3) ? "selected" : ''; ?> value="3"><?php echo e(__('Completed')); ?></option>
                                            <option <?= ($event['status'] == 0) ? "selected" : ''; ?> value="0"><?php echo e(__('Open')); ?></option>
                                            <option <?= ($event['status'] == 1) ? "selected" : ''; ?> value="1"><?php echo e(__('Close')); ?></option>
                                    </select>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>

                                <div class="form-group<?php echo e($errors->has('hours') ? ' has-danger' : ''); ?>">
                                    <label class="form-control-label" for="input-hours"><?php echo e(__('Hours')); ?></label>
                                    <input type="text" name="hours" id="input-hours" class="form-control<?php echo e($errors->has('hours') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Hours')); ?>" value="<?php echo e(old('hours', $event->hours)); ?>"autofocus>

                                    <?php echo $__env->make('alerts.feedback', ['field' => 'hours'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <?php //dd($event->instructors); ?>
                                <div class="form-group<?php echo e($errors->has('syllabus') ? ' has-danger' : ''); ?>">
                                            <label class="form-control-label" for="input-syllabus1"><?php echo e(__('Syllabus Manager')); ?></label>
                                            <select name="syllabus" data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." id="input-syllabus1" class="form-control" placeholder="<?php echo e(__('Syllabus Manager')); ?>">
                                                <option value=""></option>


                                                <?php $__currentLoopData = $instructors1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php //dd($key); ?>

                                                    <option
                                                    <?php if(count($event->syllabus) != 0){
                                                        if($key == $event->syllabus[0]['id']){
                                                            echo 'selected';
                                                        }else{
                                                            echo '';
                                                        }
                                                    }
                                                    ?>
                                                    <?php if($instructors1[$key][0]->medias != null): ?>
                                                     ext="<?php echo e($instructors1[$key][0]->medias['ext']); ?>" original_name="<?php echo e($instructors1[$key][0]->medias['original_name']); ?>" name="<?php echo e($instructors1[$key][0]->medias['name']); ?>" path="<?php echo e($instructors1[$key][0]->medias['path']); ?>" value="<?php echo e($key); ?>"><?php echo e($instructors1[$key][0]['title']); ?> <?php echo e($instructors1[$key][0]['subtitle']); ?></option>
                                                    <?php else: ?>
                                                    ext="null" original_name="null" name="null" path="null" value="<?php echo e($key); ?>"><?php echo e($instructors1[$key][0]['title']); ?> <?php echo e($instructors1[$key][0]['subtitle']); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <?php echo $__env->make('alerts.feedback', ['field' => 'syllabus'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>

                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                <div class="nav-wrapper">
                                    <ul id="tab_inside_tab" class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_inside" data-toggle="tab" href="#tabs-icons-text-1_inside" role="tab" aria-controls="tabs-icons-text-1_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Overview</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab_inside" data-toggle="tab" href="#tabs-icons-text-2_inside" role="tab" aria-controls="tabs-icons-text-2_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Summary </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab_inside" data-toggle="tab" href="#tabs-icons-text-3_inside" role="tab" aria-controls="tabs-icons-text-3_inside" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Benefit</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab_inside" data-toggle="tab" href="#tabs-icons-text-4_inside" role="tab" aria-controls="tabs-icons-text-4_inside" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>Topics</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab_inside" data-toggle="tab" href="#tabs-icons-text-5_inside" role="tab" aria-controls="tabs-icons-text-5_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Tickets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-6-tab_inside" data-toggle="tab" href="#tabs-icons-text-6_inside" role="tab" aria-controls="tabs-icons-text-6_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>City</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-7-tab_inside" data-toggle="tab" href="#tabs-icons-text-7_inside" role="tab" aria-controls="tabs-icons-text-7_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Venue</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#tabs-icons-text-8_inside" role="tab" aria-controls="tabs-icons-text-8_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Partners</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#tabs-icons-text-9_inside" role="tab" aria-controls="tabs-icons-text-9_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Sections</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-10-tab_inside" data-toggle="tab" href="#tabs-icons-text-10_inside" role="tab" aria-controls="tabs-icons-text-10_inside" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Faqs</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-11-tab_inside" data-toggle="tab" href="#tabs-icons-text-11_inside" role="tab" aria-controls="tabs-icons-text-11_inside" aria-selected="false"><i class="far fa-images mr-2"></i>Image</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#coupons" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Coupons</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-8-tab_inside" data-toggle="tab" href="#metas" role="tab" aria-controls="metas" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Metas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-9-tab_inside" data-toggle="tab" href="#videos" role="tab" aria-controls="videos" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Videos</a>
                                        </li>

                                    </ul>
                                </div>
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">

                                            <div class="tab-pane fade show active" id="tabs-icons-text-1_inside" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab_inside">



                                                    <h6 class="heading-small text-muted mb-4"><?php echo e(__('Event information')); ?></h6>
                                                    <div class="pl-lg-4">



                                                        <div class="form-group<?php echo e($errors->has('title') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-title"><?php echo e(__('Title')); ?></label>
                                                            <input type="text" name="title" class="form-control<?php echo e($errors->has('title') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e(old('title', $event->title)); ?>" required autofocus>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'title'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>
                                                        <?php echo $__env->make('admin.slug.slug',['slug' => isset($slug) ? $slug : null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <div class="form-group<?php echo e($errors->has('htmlTitle') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-htmlTitle"><?php echo e(__('HTML Title')); ?></label>
                                                            <input type="text" name="htmlTitle" id="input-htmlTitle" class="form-control<?php echo e($errors->has('htmlTitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('HTML Title')); ?>" value="<?php echo e(old('Short title', $event->htmlTitle)); ?>" autofocus>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'htmlTitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>

                                                        <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-subtitle"><?php echo e(__('Subtitle')); ?></label>
                                                            <input type="text" name="subtitle" id="input-subtitle" class="form-control<?php echo e($errors->has('subtitle') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('subtitle')); ?>" value="<?php echo e(old('Subtitle', $event->subtitle)); ?>" autofocus>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'subtitle'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>

                                                        <div class="form-group<?php echo e($errors->has('header') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-header"><?php echo e(__('Header')); ?></label>
                                                            <input type="text" name="header" id="input-header" class="form-control<?php echo e($errors->has('header') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Header')); ?>" value="<?php echo e(old('header', $event->header)); ?>" autofocus>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'header'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>

                                                        <div class="form-group<?php echo e($errors->has('summary') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-summary"><?php echo e(__('XML Summary')); ?></label>
                                                            <textarea name="summary" id="input-summary"  class="ckeditor form-control<?php echo e($errors->has('summary') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Summary')); ?>" required autofocus><?php echo e(old('summary', $event->summary)); ?></textarea>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'summary'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>

                                                        <div class="form-group<?php echo e($errors->has('body') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-body"><?php echo e(__('Body')); ?></label>
                                                            <textarea name="body" id="input-body"  class="ckeditor form-control<?php echo e($errors->has('body') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Body')); ?>" required autofocus><?php echo e(old('body', $event->body)); ?></textarea>

                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'body'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>

                                                        <div class="form-group<?php echo e($errors->has('view_tpl') ? ' has-danger' : ''); ?>">
                                                            <label class="form-control-label" for="input-view_tpl"><?php echo e(__('View tpl')); ?></label>
                                                            <select name="view_tpl"  class="form-control" placeholder="<?php echo e(__('View tpl')); ?>">
                                                                <?php $__currentLoopData = get_templates('events'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($template); ?>" <?php echo e($template == old('template',$event->view_tpl) ? 'selected' : ''); ?>><?php echo e($key); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                            <?php echo $__env->make('alerts.feedback', ['field' => 'view_tpl'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        </div>





                                                    </div>


                                            </div>
                                            </form>
                                            <div class="tab-pane fade" id="tabs-icons-text-2_inside" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab_inside">
                                                <?php echo $__env->make('admin.summary.summary', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="metas" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                <?php echo $__env->make('admin.metas.metas',['metas' => $metas], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>

                                            <div class="tab-pane fade" id="coupons" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">

                                                <div class="table-responsive py-4">
                                                    <table class="table align-items-center table-flush"  id="datatable-coupon">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col"><?php echo e(__('Code')); ?></th>
                                                                <th scope="col"><?php echo e(__('Price')); ?></th>
                                                                <th scope="col"><?php echo e(__('Status')); ?></th>
                                                                <th scope="col"><?php echo e(__('Used')); ?></th>
                                                                <th scope="col"><?php echo e(__('Assigned')); ?></th>



                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php //dd($allTopicsByCategory);
                                                            $eventCoupons = $event['coupons']->pluck('id')->toArray();
                                                            //dd($eventCoupons);
                                                        ?>

                                                            <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($coupon['code_coupon']); ?></td>
                                                                    <td><?php echo e($coupon['price']); ?></td>
                                                                    <td><?php echo e($coupon['status']); ?></td>
                                                                    <td><?php echo e($coupon['used']); ?></td>

                                                                    <td>
                                                                        <div class="col-2 assign-toggle" id="toggle_<?php echo e($key); ?>">
                                                                            <label class="custom-toggle">
                                                                                <input class="coupon-input" type="checkbox" data-status="<?php echo e(in_array($coupon['id'],$eventCoupons)); ?>" data-event-id="<?php echo e($event['id']); ?>" data-coupon-id="<?php echo e($coupon['id']); ?>" <?php if(in_array($coupon['id'],$eventCoupons)): ?> checked <?php endif; ?>>
                                                                                <span class="coupon custom-toggle-slider rounded-circle" ></span>
                                                                            </label>
                                                                        </div>
                                                                    </td>

                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                <?php echo $__env->make('admin.videos.event.index',['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>

                                            <div class="tab-pane fade" id="tabs-icons-text-3_inside" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab_inside">
                                                <?php echo $__env->make('admin.benefits.benefits',['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade show" id="tabs-icons-text-4_inside" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab_inside">
                                                <?php echo $__env->make('topics.event.instructors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-5_inside" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab_inside">
                                                <?php echo $__env->make('admin.ticket.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-6_inside" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab_inside">
                                                <?php echo $__env->make('admin.city.event.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-7_inside" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab_inside">
                                                <?php echo $__env->make('admin.venue.event.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-8_inside" role="tabpanel" aria-labelledby="tabs-icons-text-8-tab_inside">
                                                <?php echo $__env->make('admin.partner.event.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-9_inside" role="tabpanel" aria-labelledby="tabs-icons-text-9-tab_inside">
                                                <?php echo $__env->make('admin.section.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-10_inside" role="tabpanel" aria-labelledby="tabs-icons-text-10-tab_inside">
                                                <?php echo $__env->make('admin.faq.index', ['model' => $event], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-icons-text-11_inside" role="tabpanel" aria-labelledby="tabs-icons-text-11-tab_inside">

                                                <?php echo $__env->make('admin.upload.upload', ['event' => ($event->medias != null) ? $event->medias : null, 'versions' => ['event-card', 'header-image', 'social-media-sharing']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                <input type="hidden" name="creator_id" id="input-creator_id" class="form-control" value="<?php echo e($event->creator_id); ?>">
                                                <input type="hidden" name="author_id" id="input-author_id" class="form-control" value="<?php echo e($event->author_id); ?>">

                                                <div id="version-btn" style="margin-bottom:20px" class="col">
                                                    <a href="<?php echo e(route('media2.eventImage', $event->medias)); ?>" target="_blank" class="btn btn-primary"><?php echo e(__('Versions')); ?></a>
                                                </div>
                                                <?php echo $__env->make('alerts.feedback', ['field' => 'ext_url'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                <?php echo $__env->make('event.students', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('layouts.footers.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Instructor</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="lesson_details">
                            <div class="form-group instFormControl">
                                <label class="instFormControl" for="exampleFormControlSelect1">Select instructor</label>
                                <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control instFormControl" id="instFormControlSelect12">
                                </select>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="lesson_update_btn" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-link ml-auto close-modal" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('argon')); ?>/vendor/datatables-datetime/datetime.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo e(asset('argon')); ?>/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>



<script>

    var table = $('#datatable-coupon').DataTable({
        language: {
            paginate: {
            next: '&#187;', // or '→'
            previous: '&#171;' // or '←'
            }
        }
    });

    $(document).on('click',".edit-btn",function(){
        $(this).parent().parent().find('.dropdown-item').click()
    })

    $( "#input-delivery" ).change(function() {
        if($(this).val() == 143){
            $('#exp_input').css('display', 'block')
        }else{
            $('#exp_input').css('display', 'none')
        }
    });

    $(function() {

        if($("#input-delivery").val() == 143){
                $('#exp_input').css('display', 'block')
            }
        });
</script>

<script>

        $(document).on('click','.close-modal',function(){

            $('#instFormControlSelect option').each(function(key, value) {
                    $(value).remove()
            });

            $('#instFormControlSelect').append(`<option value="" disabled selected>Choose instructor</option>`)
        });
        var getLocation = function(href) {
            var l = document.createElement("a");
            l.href = href;
            return l;
        };

        $(document).on('click','#lesson_update_btn',function(e){
            let start = $('#time_starts').val()
            let date = $('#date').val()
            let end =  $('#time_ends').val()
            let room = $('#room').val()
            let topic_id = $('#topic_id').val()
            let event_id = $('#event_id').val()
            let lesson_id = $('#lesson_id').val()
            let instructor_id = $('#instFormControlSelect12').val()


            data = {date:date, start:start, event_id:event_id, end:end, room:room, instructor_id:instructor_id, topic_id:topic_id, lesson_id:lesson_id}

            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/save_instructor",
                data:data,
                success: function(data) {
                    data = JSON.parse(data)

                    inst_media = data.instructor.medias

                    var base_url = window.location.origin


                    row =`
                        <span style="display:inline-block" class="avatar avatar-sm rounded-circle">
                            <img src="${window.location.origin + inst_media.path + inst_media.name + '-instructors-small' + inst_media.ext}" alt="${data.instructor.title+' '+data.instructor.subtitle}" style="max-width: 100px; max-height: 40px; border-radius: 25px" draggable="false">

                        </span>
                        <div style="display:inline-block">${data.instructor.title+' '+data.instructor.subtitle}</div>
                    `
                    $('#inst_lesson_edit_'+data.lesson_id).html(row)
                    $('#date_lesson_edit_'+data.lesson_id).text(data.date1)
                    $('#start_lesson_edit_'+data.lesson_id).text(data.start)
                    $('#end_lesson_edit_'+data.lesson_id).text(data.end)
                    $('#room_lesson_edit_'+data.lesson_id).text(data.room)

                    $('#modal-default').modal()
                    $('.close-modal').click()

                }
            });

        });




</script>


<script>
            function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;


            return [day,month,year].join('-');
        }

        $(document).on('click', '#remove_lesson', function() {
            var confirmation = confirm("are you sure you want to remove the item?");
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')

            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type : 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/remove_lesson",
                data:data,
                success: function(data){
                    data = JSON.parse(data)

                    $('#'+data.lesson_id).remove()
                }
            });
        });

        $(document).on('click','#open_modal',function(){
            let eleme = $('#lesson_details').find('.form-group')
            $.each(eleme, function(key, value){
                if(key != 0){
                    $(value).remove()
                }
            })
            $("#instFormControlSelect12").html("")
            $('#lesson_details').find('input').remove()
            //$('#lesson_details').empty()
            //$('#lesson_details').find('*').not('.instFormControl').remove();
            let id = 0
            let elem = $(this).data('lesson-id');
            elem = elem.split("_")
            let topic_id = $(this).data('topic-id')
            topic_id = topic_id.split("_")
            const event_id = $('#topic_lessons').data('event-id')
            let instructor_id = $('#instFormControlSelect12').val()

            data = {lesson_id:elem[1], topic_id:topic_id[1], event_id:event_id}
            $.ajax({
                type: 'POST',
                headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
               },
               Accept: 'application/json',
                url: "/admin/lesson/edit_instructor",
                data:data,
                success: function(data) {
                    date = ''

                    data = JSON.parse(data)
                    let instructors = data.instructors

                    let event_type = data.isInclassCourse
                    let event_id = data.event

                    lesson = data.lesson.pivot


                    $('#modal-title-default').text(lesson.title)

                //    inst_row =  `<div class="form-group">
                //                     <label for="exampleFormControlSelect1">Select instructor</label>
                //                     <select data-toggle="select" data-live-search="true" data-live-search-placeholder="Search ..." class="form-control" id="instFormControlSelect">
                //                     </select>
                //                 </div>`

                    //$('#lesson_details').append(inst_row)

                    $.each( instructors, function( key, value ) {
                        //console.log(key+':'+value.title)
                        // $('#instFormControlSelect').append(`<option ${lesson.instructor_id == value.id ? 'selected' : ''} value="${value.id}">${value.title} ${value.subtitle}</option>`)
                        $('#instFormControlSelect12').append(`<option ${lesson.instructor_id == value.id ? 'selected' : ''} path="${value.medias.path}" original_name="${value.medias.original_name}" name="${value.medias.name}" ext="${value.medias.ext}" value="${value.id}">${value.title} ${value.subtitle}</option>`)
                    });


                    if(lesson.date != ''){
                        var date = new Date(lesson.date);
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear()
                    }else{
                        if(lesson.time_starts == null){
                            date = ""
                        }else{
                            var date = new Date(lesson.time_starts);
                            date =((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '-' + ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '-' + date.getFullYear()
                        }

                    }



                    if(lesson.time_starts != null)
                    {
                        d = new Date(lesson.time_starts)
                        time_starts = d.toLocaleTimeString('it-IT')
                    }

                    if(lesson.time_ends != null)
                    {
                        d = new Date(lesson.time_ends)
                        time_ends = d.toLocaleTimeString('it-IT')

                    }


                    if(event_type){
                        let row = `
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control datepicker" name="date" id="date" placeholder="Select date" type="text" value="${date}" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="date">Time starts</label>
                                <input type="text" name="time_starts" class="form-control timepicker" id="time_starts" value="${lesson.time_starts != null ? time_starts : ''}" placeholder="Start">
                            </div>
                            <div class="form-group">
                                <label for="date">Time ends</label>
                                <input type="text" name="time_ends" class="form-control timepicker" id="time_ends" value="${lesson.time_ends != null ? time_ends : ''}" placeholder="End">
                            </div>
                            <div class="form-group">
                                <label for="date">Room</label>
                                <input type="text" name="room" class="form-control" id="room" value="${lesson.room != null ? lesson.room : ''}" placeholder="Room">
                            </div>
                        `

                        $('#lesson_details').append(row)
                        var datePickerOptions = {
                            format: 'dd-mm-yyyy',
                            firstDay: 1,
                            changeMonth: true,
                            changeYear: true,
                            // ...
                        }
                        $(".datepicker").datepicker(datePickerOptions);
                        /*$('#time_starts').timepicker({
                            timeFormat: 'h:mm p',

                            minTime: '10',
                            maxTime: '6:00pm',
                            defaultTime: '11',
                            startTime: '10:00',
                            dynamic: false,
                            dropdown: true,
                            scrollbar: true,
                            zindex: 9999999
                        });*/
                        $('.timepicker').timepicker({
                            timeFormat: 'HH:mm',
                            zindex: 9999999,
                            interval: 5,
                            minTime: '00:00',
                            maxTime: '23:55',
                            dynamic: false,
                        });






                    }

                    let input_hidden = `
                            <input type="hidden" name="topic_id" id="topic_id" value="${data.topic_id}">
                            <input type="hidden" name="event_id" id="event_id" value="${data.event.id}">
                            <input type="hidden" name="lesson_id" id="lesson_id" value="${data.lesson_id}">
                        `

                    $('#lesson_details').append(input_hidden)



                    $('#modal-default').modal('show');
                }
            });

        });
</script>
<script>
        $(document).on('change',"#input-method",function(){

            if($(this).val()){
                $.ajax({
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
   			        type: 'POST',
   			        url: '/admin/events/assing-method/' + "<?php echo e($event->id); ?>",
                    data: {'payment_method': $(this).val()},
   			        success: function (data) {

                        if(data.success){
                            $(".success-message p").html(data.message);
   	                        $(".success-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }else{
                            $(".error-message p").html(data.message);
   	                        $(".error-message").show();

                           setTimeout(function(){
                            $(".close-message").click();
                           }, 2000)
                        }



   			        },
   			        error: function() {
   			             //console.log(data);
   			        }
   			    });

            }


        })
</script>
<script>
        $('#submit-btn').on('click', function(){
            $('#event_edit_form').submit()
        })



</script>

<script>

    instructors = <?php echo json_encode($instructors1, 15, 512) ?>;

    $(document).ready(function(){

        if('<?php echo e(old('tab')); ?>' != ''){
            $('#'+'<?php echo e(old('tab')); ?>').trigger('click')
            $('#tabs-icons-text-11-tab_inside').trigger('click')
        }

        $("#input-syllabus1").select2({
            templateResult: formatOptions,
            templateSelection: formatOptions
        });

        $("#instFormControlSelect12").select2({
                templateResult: formatOptions,
                templateSelection: formatOptions,
                dropdownParent: $("#modal-default")
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


    $(document).on('click', '.coupon.custom-toggle-slider', function(){

        let event_id = ($(this).parent().find('.coupon-input')).data('event-id')
        let coupon_id = ($(this).parent().find('.coupon-input')).data('coupon-id')
        let status = ($(this).parent().find('.coupon-input')).data('status')

        if (status) {
            $(this).parent().find('.coupon-input').data('status',0)
        }else{
            $(this).parent().find('.coupon-input').data('status',1)
        }

        let data = {'event':event_id, 'coupon' : coupon_id,'status':status}

        $.ajax({
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            Accept: 'application/json',
            url: "/admin/events/assing-coupon/" + event_id +"/" + coupon_id,
            data:data,
            success: function(data) {

            }
        });

    })


</script>

<script>
//$("#input-release_date_file")
var datePickerOptions = {
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
    }
    $("#input-release_date_file").datepicker(datePickerOptions);
    $("#input-launch-input").datepicker(datePickerOptions);


</script>



<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', [
    'title' => __('Event Management'),
    'parentSection' => 'laravel',
    'elementName' => 'events-management'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/event/edit.blade.php ENDPATH**/ ?>
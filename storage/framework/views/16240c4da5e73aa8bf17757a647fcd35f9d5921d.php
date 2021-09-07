
<?php $__env->startSection('metas'); ?>
    <title><?php echo e($title); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main id="main-area" role="main">

                <div class="instructor-wrapper">
                    <div class="container">

                        <div class="instructor-area instructor-profile">
                            <div class="row">
                                <div class="col4 col-xs-12">
                                    <div class="avatar-wrapper">


                                        <div class="avatar" alt="<?php echo e($content['title']); ?> <?php echo e($content['subtitle']); ?>" title="<?php echo e($content['title']); ?> <?php echo e($content['subtitle']); ?>"  style="background-image:url(<?php echo e(cdn(get_image($content['medias'],'instructors-testimonials'))); ?>);"></div>
                                        <div class="social-links">
                                            <?php $social_media = json_decode($content['social_media'], true); ?>

                                            <?php if(isset($social_media['facebook']) && $social_media['facebook'] != ''): ?>
                                             <a target="_blank" href="<?php echo e($social_media['facebook']); ?>">
                                             <img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/social/Facebook.svg')); ?>" width="23" alt="Visit"></a>
                                             <?php endif; ?>
                                             <?php if(isset($social_media['instagram']) && $social_media['instagram'] != ''): ?>
                                             <a target="_blank" href="<?php echo e($social_media['instagram']); ?>">
                                             <img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/social/Instagram.svg')); ?>" width="23" alt="Visit"></a>
                                             <?php endif; ?>
                                             <?php if(isset($social_media['linkedin']) && $social_media['linkedin'] != ''): ?>
                                             <a target="_blank" href="<?php echo e($social_media['linkedin']); ?>">
                                             <img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/social/Linkedin.svg')); ?>" width="23" alt="Visit"></a>
                                             <?php endif; ?>
                                             <?php if(isset($social_media['twitter']) && $social_media['twitter'] != ''): ?>
                                             <a target="_blank" href="<?php echo e($social_media['twitter']); ?>">
                                             <img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/social/Twitter.svg')); ?>" width="23" alt="Visit"></a>
                                             <?php endif; ?>
                                             <?php if(isset($social_media['youtube']) && $social_media['youtube'] != ''): ?>
                                             <a target="_blank" href="<?php echo e($social_media['youtube']); ?>">
                                             <img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/social/Youtube.svg')); ?>" width="23" alt="Visit"></a>
                                             <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col8 col-xs-12">
                                <?php
                                    if(isset($content['header'])){
                                        $field2 = $content['ext_url'];
                                        $field2 = str_replace ( "https://www.", "", $field2 );
                                        $field2 = str_replace ( "https://.", "", $field2 );
                                        $field2 = str_replace ( "http://www.", "", $field2 );
                                    }
                                ?>
                                    <div class="text-area">

                                        <h1><?php echo e($content['title']); ?> <?php echo e($content['subtitle']); ?></h1>
                                        <h2><?php echo e($content['header']); ?>,<?php if(isset($content['ext_url'])): ?> <a target="_blank" title="<?php echo e($field2); ?>" href="<?php echo e($content['ext_url']); ?>"> <?php echo e($field2); ?></a> <?php endif; ?></h2>
                                        <?php echo $content['body']; ?>

                                    </div>
                                </div>
                            </div>
                        </div><!-- ./instructor-area -->

                        <?php if(count($instructorTeaches) >0): ?>
                        <div class="instructor-area instructor-studies">
                            <h2><?php echo e($content->title); ?> <?php echo e($content->subtitle); ?> teaches:</h2>
                            <ul>
                                <?php $__currentLoopData = $instructorTeaches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teach): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><img width="25" src="<?php echo e(cdn('/theme/assets/images/icons/graduate-hat.svg')); ?>" alt=""><?php echo e($teach); ?></li>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div><!-- ./instructor-area -->
                        <?php endif; ?>

                        <?php if(isset($instructorEvents) && count($instructorEvents) > 0): ?>

                            <div class="instructor-area instructor-courses">
                                <h2><?php echo e($content->title); ?> <?php echo e($content->subtitle); ?> participates in:</h2>

                                <div class="dynamic-courses-wrapper">

                                <?php $__currentLoopData = $instructorEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(isset($row)): ?>
                                        <?php $estatus = $row['status']; ?>

                                        <?php if($estatus == 0 || $estatus == 2): ?>

                                            <?php if($row['view_tpl'] =='elearning_event' || $row['view_tpl'] =='elearning_greek' || $row['view_tpl'] =='elearning_event'): ?>

                                            <div class="item">
                                                <div class="left">
                                                    <h2><?php echo e($row['title']); ?></h2>



                                                </div>
                                                <div class="right right--no-price">
                                                    <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--secondary btn--md">Course Details</a>
                                                </div>
                                            </div><!-- ./item -->
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div><!-- ./dynamic-courses-wrapper -->

                                <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                                    <?php $__currentLoopData = $instructorEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php $estatus = $row['status']; ?>

                                        <?php if($estatus == 0 || $estatus == 2): ?>
                                        <?php //dd($row); ?>
                                            <?php if($row['view_tpl'] !='elearning_event' && $row['view_tpl'] !='elearning_greek'): ?>
                                            <div class="item">
                                                <div class="left">
                                                    <h2><?php echo e($row['title']); ?></h2>
                                                    
                                                    <?php
                                                    if(isset($row['summary1']) && count($row['summary1']) >0){
                                                        foreach($row['summary1'] as $sum){
                                                            if($sum['section'] == 'date')
                                                                $date = $sum['title'];
                                                        }
                                                    }
                                                     ?>

                                                    <div class="bottom">
                                                    <?php if(count($row['city']) > 0 ): ?><a href="<?php echo e($row['city'][0]['slugable']['slug']); ?>" title="<?php echo e($row['city'][0]['name']); ?>" class="location"><img width="20" src="/theme/assets/images/icons/marker.svg" alt=""><?php echo e($row['city'][0]['name']); ?></a> <?php endif; ?>
                                                    <?php if(isset($date) && $date != ''): ?> <div class="duration"><img width="20" src="theme/assets/images/icons/icon-calendar.svg" alt=""> <?php echo e($date); ?> </div><?php endif; ?>
                                                    <?php if($row['hours'] && (is_numeric(substr($row['hours'], 0, 1)))): ?>  <div class="expire-date"><img width="20" src="theme/assets/images/icons/Start-Finish.svg" alt=""><?php echo e($row['hours']); ?></div><?php endif; ?>
                                                    </div>

                                                </div>
                                                <div class="right right--no-price">
                                                    <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--secondary btn--md">Course Details</a>
                                                </div>
                                            </div><!-- ./item -->
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div><!-- ./dynamic-courses-wrapper -->

                            </div><!-- ./instructor-area -->

                        <?php endif; ?>

                    </div>
                </div><!-- ./instructor-wrapper -->

			<!-- /#main-area -->
			</main>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/pages/instructor_page.blade.php ENDPATH**/ ?>
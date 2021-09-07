
<?php $__env->startSection('metas'); ?>

    <title><?php echo e($page['name']); ?></title>
   <?php echo $page->metable->getMetas(); ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('theme.preview.preview_warning', ["id" => $page['id'], "type" => "page", "status" => $page['status']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main id="main-area" class="with-hero" role="main">
<section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
            <h1>Our Instructors</h1>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
<section class="section-course-tabs">
    <div class="content-wrapper">
        <div class="tabs-wrapper">
        <div class="tabs-content">
        <div id="instructors" class="tab-content-wrapper tab-blue-gradient active-tab our-instuctors">
                  <div class="container">
                     <div class="course-full-text">
                        <?php if(isset($instructors)): ?>

                        <div class="instructors-wrapper row row-flex row-flex-23">
                           <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lkey => $lvalue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <div class="col-3 col-sm-6 col-xs-12">
                              <div class="instructor-box">
                                 <div class="instructor-inner">
                                    <?php
                                       $img = '';
                                       $inst_url = $lvalue['slugable']['slug'];
                                       $ext_url = $lvalue['ext_url'];
                                       $fb = '';
                                       $inst = '';
                                       $twitter = '';
                                       $med = '';
                                       $pint = '';
                                       $linkedIn = '';
                                       $yt = '';
                                       $name = $lvalue['title'] . ' ' . $lvalue['subtitle'];
                                       $field1 = '';
                                       $field2 ='';

                                       //dd($lvalue);

                                       if(isset($lvalue['medias'])){
                                         $img =  get_image($lvalue['medias'], 'instructors-testimonials');
                                       }

                                       if(isset($lvalue['header'])){
                                         $field1 =  $lvalue['header'];
                                       }

                                       if(isset($lvalue['ext_url'])){
                                            $field2 = $lvalue['ext_url'];
                                            $field2 = str_replace ( "https://www.", "", $field2 );
                                            $field2 = str_replace ( "https://.", "", $field2 );
                                            $field2 = str_replace ( "http://www.", "", $field2 );  
                                            $field2 = str_replace ( "https:", "", $field2 );
                                            $field2 = str_replace ( "http:", "", $field2 );
                                            $field2 = str_replace ( "/", "", $field2 );
                                         
                                       }
                                       $socialMedia = json_decode($lvalue['social_media'],true);

                                       if(isset($socialMedia['facebook'])){
                                          $fb = $socialMedia['facebook'];
                                       }

                                       if(isset($socialMedia['twitter'])){
                                          $twitter = $socialMedia['twitter'];
                                       }

                                       if(isset($socialMedia['instagram'])){
                                          $inst = $socialMedia['instagram'];
                                       }

                                       if(isset($socialMedia['linkedin'])){
                                          $linkedIn = $socialMedia['linkedin'];
                                       }

                                       if(isset($socialMedia['youtube'])){
                                          $yt = $socialMedia['youtube'];
                                       }

                                       //dd($inst_url);
                                       ?>
                                    <div class="profile-img">
                                       <a href="<?php echo e($lvalue['slugable']['slug']); ?>"><img src="<?php echo e(cdn($img)); ?>"  title="<?php echo e($name); ?>" alt="<?php echo e($name); ?>"></a>
                                    </div>
                                    <h3><a href="<?php echo e($lvalue['slugable']['slug']); ?>"><?php echo e($name); ?></a></h3>
                                    <p><?php echo e($field1); ?>, <a target="_blank" title="<?php echo e($field1); ?>" <?php if($ext_url!=''): ?> href="<?php echo e($ext_url); ?>"<?php endif; ?>><?php echo e($field2); ?></a>.</p>
                                    <ul class="social-wrapper">
                                       <?php if($fb != ''): ?>
                                       <li><a target="_blank" href="<?php echo e($fb); ?>"><img class="replace-with-svg"  src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($inst !=''): ?>
                                       <li><a target="_blank" href="<?php echo e($inst); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Instagram.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($linkedIn !=''): ?>
                                       <li><a target="_blank" href="<?php echo e($linkedIn); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Linkedin.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($pint !=''): ?>
                                       <li><a target="_blank" href="<?php echo e($pint); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Pinterest.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($twitter !=''): ?>
                                       <li><a target="_blank" href="<?php echo e($twitter); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Twitter.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($yt !=''): ?>
                                       <li><a target="_blank" href="<?php echo e($yt); ?>"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Youtube.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                       <?php if($med !=''): ?>
                                       <li><a target="_blank" href="#"><img class="replace-with-svg"  src="<?php echo e(cdn('/theme/assets/images/icons/social/Medium.svg')); ?>" width="16" alt="Visit"></a></li>
                                       <?php endif; ?>
                                    </ul>
                                    <!-- /.instructor-inner -->
                                 </div>
                                 <!-- /.instructor-box -->
                              </div>
                              <!-- /.col-3.col-sm-12 -->
                           </div>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           <?php endif; ?>
                           <!-- /.row.row-flex -->
                        </div>
                        <!-- /.course-full-text -->
                     </div>
                     <!-- /.container -->
                  </div>
                  <!-- /.tab-content-wrapper -->
               </div>
        </div>

        </div>
    </div>
</section>

</main>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/static_tpls/instructors/frontend.blade.php ENDPATH**/ ?>
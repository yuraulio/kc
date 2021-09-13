<?php $header_menus = get_header();?>


<?php $__env->startSection('metas'); ?>

    <title><?php echo e($homePage->metable->meta_title); ?></title>

   <?php echo $homePage->metable->getMetas(); ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
   if(Session::has('scopeone')){
       $fone = Session::get('scopeone');
   }
   else { $fone = 0; }

   $totalfound = 0;
   $totalcats = 0;
   ?>
<main id="main-area" class="with-hero" role="main">
   <?php if (isset($homePage) ) : ?>
   <?php $image = get_image($homePage['mediable'],'header-image');
     // dd($image);
   ?>
   <section class="section-hero"  style="background-image:url('<?php echo e(cdn($image)); ?>');">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message">
            <h1><?php echo e($homePage['title']); ?></h1>
            <h2><?php echo $homePage['summary']; ?></h2>
         </div>
      </div>
      <!-- /.section-hero -->
   </section>
   <?php


      endif; ?>


   <?php if(isset($nonElearningEvents)): ?>
   <?php $__currentLoopData = $nonElearningEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bcatid => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

   <section class="section-text-carousel event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2><?php echo e($category['name']); ?></h2>
                  <?php if($category['description'] != ''): ?>
                  <?php if($category['hours']): ?><span class="duration"><img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/Start-Finish.svg')); ?>" alt=""/><?php echo $category['hours']; ?></span><?php endif; ?>
                  <?php endif; ?>
                  <p><?php echo $category['description']; ?></p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">

                     <?php $__currentLoopData = $category['events']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                     <div class="slide">
                        <?php
                           $string = $row['title'];
                           if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                           }
                           else {
                               $until = $string;
                           }
                        ?>

                        <?php if( isset($row['mediable']) && isset($row['slugable'])): ?>
                        <a href="<?php echo e($row['slugable']['slug']); ?>"><img src="<?php echo e(cdn(get_image($row['mediable'],'event-card'))); ?>" alt="<?php echo e($until); ?>"/></a>
                        <?php endif; ?>

                        <?php //dd($row->slugable['slug']); ?>

                        <div class="box-text">
                           <?php if(isset($row['slugable']) && $row['slugable']['slug'] != ''): ?>
                              <h3><a href="<?php echo e($row['slugable']['slug']); ?>"><?php echo e($until); ?></a></h3>
                           <?php endif; ?>
                           <?php if(isset($row['city']) && count($row['city']) > 0): ?>

                           <a href="<?php echo e($row['city'][0]['slugable']['slug']); ?>" class="location"><?php echo e($row['city'][0]['name']); ?></a>
                           <?php endif; ?>
                              <?php $dateLaunch = !$row['launch_date'] ? date('F Y', strtotime($row['published_at'])) : date('F Y', strtotime($row['launch_date']));?>
                              <span class="date"><?php echo e($dateLaunch); ?> </span>
                           <?php if(isset($row['slugable']) && $row['slugable']['slug'] != ''): ?>

                              <?php if($row['status'] != 0): ?>
                              <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--sm btn--secondary btn--sold-out">sold out</a>
                              <?php else: ?>
                              <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--sm btn--secondary">course details</a>
                              <?php endif; ?>
                           <?php endif; ?>

                        </div>
                     </div>


                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>

   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   <?php endif; ?>

   <?php if(!empty($elearningEvents)): ?>
   <?php $__currentLoopData = $elearningEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bcatid => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

   <section class="section-text-carousel background section--blue-gradient">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2><?php echo e($category['name']); ?></h2>

                    <?php if($category['description'] != ''): ?>
                        <?php if($category['hours']): ?>
                            <span class="duration"><img src="<?php echo e(cdn('/theme/assets/images/icons/Start-Finish.svg')); ?>" class="replace-with-svg" alt=""/><?php echo e($category['hours']); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <p><?php echo $category['description']; ?></p>
               </div>
            </div>
            <?php //dd($events[0]); ?>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = '';

                     ?>
                     <?php $__currentLoopData = $category['events']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                     <div class="slide">
                        <?php
                        //dd($row);
                            $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                                $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                                $until = $string;

                            }
                            //dd($until);
                        ?>
                        <?php //var_dump($until) ?>
                        <?php if( isset($row['mediable']) && isset($row['slugable'])): ?>
                        <a href="<?php echo e($row['slugable']['slug']); ?>"><img src="<?php echo e(cdn(get_image($row['mediable'],'event-card'))); ?>" alt="<?php echo e($until); ?>"/></a>
                        <?php endif; ?>
                        <div class="box-text">
                           <?php
                            $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                                $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                                $until = $string;
                            }
                            ?>


                            <?php
                            if(isset($row['slugable'])){
                                $slug = $row['slugable']['slug'];
                            }else{
                                $slug = '';
                            }

                            $month = !$row['launch_date'] ? date('F Y', strtotime($row['published_at'])) : date('F Y', strtotime($row['launch_date']));

                             ?>
                           <?php $url = url($slug); ?>

                           <h3><a href="<?php echo e($url); ?>"><?php echo e($until); ?></a></h3>
                           
                           <?php if(isset($header_menus['elearning_card']['data']['slugable']) ): ?><a href="<?php echo e($header_menus['elearning_card']['data']['slugable']['slug']); ?>" class="location"> VIDEO E-LEARNING COURSES</a><?php endif; ?>
                           <span class="date"> </span>
                           <a href="<?php echo e($url); ?>" class="btn btn--sm btn--secondary">course details</a>

                        </div>
                     </div>


                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   <?php endif; ?>

<?php //dd('end'); ?>
<?php //dd($inclassEventsbycategoryFree); ?>
   <?php if(isset($inclassFree)): ?>
   <?php $__currentLoopData = $inclassFree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bcatid => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
   <?php
   //dd($bcateventids[]);
    ?>

   <?php //dd($bcateventids); ?>
   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">


                  <h2><?php echo e($category['name']); ?></h2>
                  <?php if($category['description'] != ''): ?>
                  <?php if($category['hours']): ?><span class="duration"><img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/Start-Finish.svg')); ?>" alt=""/><?php echo e($category['hours']); ?></span><?php endif; ?>
                  <?php endif; ?>
                  <p><?php echo $category['description']; ?></p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     <?php $__currentLoopData = $category['events']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                     <div class="slide">
                     <?php
                           $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               }

                               //dd($row);
                               ?>
                        <?php if( isset($row['mediable']) && isset($row['slugable'])): ?>
                        <a href="<?php echo e($row['slugable']['slug']); ?>"><img src="<?php echo e(cdn(get_image($row['mediable'],'event-card'))); ?>" alt="<?php echo e($until); ?>"/></a>
                        <?php endif; ?>

                        <div class="box-text">
                           <h3><a href="<?php echo e($row['slugable']['slug']); ?>"><?php echo e($until); ?></a></h3>
                           <span class="date"></span>
                           <?php if($row['view_tpl'] == 'event_free'): ?>
                           <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--sm btn--secondary">enroll for free</a>
                           <?php elseif($row['view_tpl'] == 'event_free_coupon'): ?>
                           <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--sm btn--secondary">course details</a>
                           <?php endif; ?>

                        </div>
                     </div>

                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>


   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   <?php endif; ?>


   <?php if(isset($elearningFree)): ?>
   <?php $__currentLoopData = $elearningFree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bcatid => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
   <?php //dd($bcateventids); ?>

   <section class="section-text-carousel background event-background">
      <div class="container container--md">
         <div class="row-text-carousel clearfix">
            <div class="text-column">
               <div class="text-area">

                  <h2><?php echo e($category['name']); ?></h2>
                  <?php //dd($until); ?>
                  <?php if($category['description'] != ''): ?>
                  <?php if($category['hours']): ?><span class="duration"><img class="replace-with-svg" src="<?php echo e(cdn('/theme/assets/images/icons/Start-Finish.svg')); ?>" alt=""/><?php echo e($category['hours']); ?></span><?php endif; ?>
                  <?php endif; ?>
                  <p><?php echo $category['description']; ?></p>
               </div>
            </div>
            <div class="carousel-column">
               <div class="carousel-wrapper">
                  <div class="boxes-carousel owl-carousel">
                     <?php $lastmonth = ''; ?>
                     <?php $__currentLoopData = $category['events']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                     <div class="slide">
                     <?php

                           $string = $row['title'];
                            if( strpos($string, ',') !== false ) {
                              $until = substr($string, 0, strrpos($string, ","));
                            }
                            else {
                               $until = $string;
                               }

                               //dd($row);

                               ?>
                        <?php if( isset($row['mediable']) && isset($row['slugable'])): ?>
                        <a href="<?php echo e($row['slugable']['slug']); ?>"><img src="<?php echo e(cdn(get_image($row['mediable'],'event-card'))); ?>" alt="<?php echo e($until); ?>"/></a>
                        <?php endif; ?>

                        <div class="box-text">
                           <h3><a href="<?php echo e($row['slugable']['slug']); ?>"><?php echo e($until); ?></a></h3>

                           <?php if(isset($header_menus['elearning_card']['data']['slugable']) ): ?><a href="<?php echo e($header_menus['elearning_card']['data']['slugable']['slug']); ?>" class="location"> VIDEO E-LEARNING COURSES</a><?php endif; ?>
                           <span class="date"></span>
                           <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--sm btn--secondary">enroll for free</a>
                        </div>
                     </div>

                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.section-text-carousel.section--blue-gradient -->
   </section>


   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
   <?php endif; ?>

   <section class="section--partners hidden-xs">
      <div class="container container--md">
      <?php //dd($homeBrands); ?>
         <?php if(isset($homeBrands) && count($homeBrands) > 0): ?>

         <h2 class="section-title">Knowcrunch is trusted by hundreds of companies</h2>

         <div class="row row-flex row-flex-0">
            <?php
               //$rand_keys = array_rand($homeBrands, 6);
               //   echo print_r($rand_keys);
               //  echo print_r($homeBrands[7]['image']);

               ?>
            <?php $__currentLoopData = $homeBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php //dd($key);
                if($key['medias'] != null){
                   // dd($key->medias);
                   $path = url($key['medias']['path'].$key['medias']['original_name']);
                   //dd($path);
                }else{
                    $path = '';
                }
             ?>
            <div class="col">
               <div class="partners-set">
                  <img alt="<?php echo e($key['name']); ?>" title="<?php echo e($key['name']); ?>" src="<?php echo e($path); ?>" width="150" height="77" align=""/>
               </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         </div>
         <div class="view-more">
            <a href="/they-trust-us">See more</a>
         </div>
         <?php endif; ?>
         <hr>
         <?php if(isset($homeLogos) && isset($homeLogos[0])): ?>
         <h2 class="section-title">Knowcrunch mentioned in the media</h2>
         <div class="row row-flex row-flex-0">



            <?php $__currentLoopData = $homeLogos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
               if($key['medias'] != null){
                $path = url($key['medias']['path'].$key['medias']['original_name']);
             }else{
                 $path = '';
             }
             ?>

            <div class="col">
               <div class="partners-set">
               <img alt="<?php echo e($key['name']); ?>" title="<?php echo e($key['name']); ?>" src="<?php echo e($path); ?>" width="120" height="110" align=""/>
               </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         </div>
         <div class="view-more">
            <a href="/regularly-mentioned-in-media">See more</a>
         </div>
         <?php endif; ?>
      </div>
   </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/home/homepage.blade.php ENDPATH**/ ?>
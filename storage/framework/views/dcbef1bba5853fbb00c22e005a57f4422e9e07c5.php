
<?php $__env->startSection('content'); ?>

<main id="main-area" class="search-results-page-main"  role="main">
<script type="application/ld+json">
                {
                    "@context": "http://schema.org/",
                    "@type": "SearchResultsPage",
                    "name": "Search results",
                    "description":"Search results",
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": "http://knowcrung.com/search?s={s}",
                        "query-input": "required name=search_term"
                    }
                }
                </script>
   <div class="container">
   <div class="search-results-head">
      <h1 class="search-results-title">Search results for <span><?php echo e($search_term); ?></span></h1>
      <?php
         $results = 0;

         if(!empty($list)){
             $results += count($list);
         }
         /*
         if(isset($instructors)){
             $results += count($instructors);
         }*/

         ?>
      <?php if($results > 0 ): ?>
      <p class="search-results-text"><span><?php echo e($results); ?> result(s) </span> found containing the term <span><?php echo e($search_term); ?>.</span></p>
      <?php else: ?>
      <p class="search-results-text"><strong><?php echo e($results); ?> result(s) </strong> were found containing the term <strong><?php echo e($search_term); ?></strong>. Try again.</p>
      <?php endif; ?>
   </div>
   <!-- /.search-results-heading -->
   <?php if($results > 0): ?>
   <div class="search-results-wrapper">
   <?php echo empty($list)?>
   <?php if(count($list) > 0): ?>
      <div class="section section--dynamic-learning">

         <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <?php if($row['view_tpl'] != 'elearning_event' && $row['view_tpl'] != 'elearning_greek'): ?>
         <?php

                $chmonth = date('m', strtotime($row['published_at']));
                $month = date('F Y', strtotime($row['published_at']));


                $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                    return $cartItem->id === $row->id;
                });

                ?>
         <?php
            $location = [];
            $eventtype = [];
            $advancedtag = 0;
            if (isset($row->categories) && !empty($row->categories)) :
                foreach ($row->categories as $category) :
                    if ($category->depth != 0 && $category->parent_id == 9) {
                         $location=$category;
                    }
                    if ($category->depth != 0 && $category->parent_id == 12) {
                         $eventtype=$category;
                    }
                    if ($category->id == 117) {
                        $advancedtag = 1;
                        $advancedtagslug = $category->slug;
                    }
                endforeach;
            endif;
            ?>

            <?php

            $loc = '';
            if(count($row['city']) != 0){
               $loc = $row['city'][0]['name'];
            }


            $search1 = strpos(trim(strtolower($loc)),trim(strtolower($search_term)));
            $search2 = strpos(trim(strtolower($row['title'])),trim(strtolower($search_term)));
           // $search3 = strpos(trim(strtolower($lvalue['header'])),trim(strtolower($search_term)));
           // $search4 = false;
            //dd($row);

            foreach($row['summary1'] as $sum){
                if($sum['section'] == 'date'){
                    $exp = $sum['title'];
                }
            }


            if(isset($row['hours'])){
                $duration = $row['hours'];

            }else{
                foreach($row['summary1'] as $sum){
                    if($sum['section'] == 'duration'){
                        $duration = explode(' hours', $sum['title']);
                        $duration = $duration[0];
                        //dd($duration);
                    }
                }
            }

            ?>

         <!-- ./dynamic-learning--subtitle -->
         <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
            <div class="item">
               <div class="left">
                  <h2 class="<?php if($search2!==false): ?> search-highlight <?php endif; ?>"><?php echo e($row['title']); ?></h2>
                  <div class="bottom">
                     <?php if(count($row['city']) != 0): ?>   <a href="<?php echo e($row['city'][0]['slugable']['slug']); ?>" class="location" title="<?php echo e($row['city'][0]['name']); ?>">
                     <img width="20" src="/theme/assets/images/icons/marker.svg" alt=""> <span class="<?php if($search1!==false): ?> search-highlight <?php endif; ?>"> <?php echo e($row['city'][0]['name']); ?> </span></a> <?php else: ?> City <?php endif; ?>
                     <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""><?php if(isset($exp)): ?> <?php echo e($exp); ?> <?php else: ?> Date <?php endif; ?></div>
                     <div class="expire-date"><img width="20" src="/theme/assets/images/icons/Times.svg" alt=""><?php echo e($duration); ?> h</div>
                  </div>
               </div>
               <div class="right">
                  <?php  if (count($row['ticket']) != 0) {
                     $price = $row['ticket'][0]['pivot']['price'];

                     }
                     else { $price = 0; } ?>

                  <?php if($row['view_tpl'] == 'elearning_pending'): ?>
                     <div class="price">Pending</div>
                  <?php else: ?>
                     <div class="price">from €<?php echo e($price); ?></div>
                  <?php endif; ?>
                  <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--secondary btn--md">Course Details</a>
               </div>
            </div>
            <!-- ./item -->
            <?php else: ?>
            <!-- ./dynamic-learning--subtitle -->
            <div class="dynamic-courses-wrapper">
               <div class="item">
                  <div class="left">
                     <h2><?php echo e($row['title']); ?></h2>
                     <div class="expire-date"><img width="20" src="/theme/assets/images/icons/Times.svg" alt=""><?php echo e($duration); ?> h</div>
                  </div>
                  <div class="right">

                     <?php
                    if(count($row['ticket']) != 0)
                    {
                        $price = $row['ticket'][0]['pivot']['price'];
                    }
                        else { $price = 0; } ?>
                     <div class="price">from €<?php echo e($price); ?></div>
                     <a href="<?php echo e($row['slugable']['slug']); ?>" class="btn btn--secondary btn--md">Course Details</a>
                  </div>
                  <!-- ./item -->
               </div>
               <?php endif; ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <!-- ./dynamic-courses-wrapper -->

         </div>
         <?php endif; ?>
         <!--
         <?php if(isset($instructors)): ?>
         <div class="row row-flex row-flex-23 instructors-results">
            <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lkey => $lvalue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php


               $search1 = strpos(trim(strtolower($lvalue['title'])),trim(strtolower($search_term)));
               $search2 = strpos(trim(strtolower($lvalue['subtitle'])),trim(strtolower($search_term)));
               $search3 = strpos(trim(strtolower($lvalue['header'])),trim(strtolower($search_term)));
               $search4 = false;


            ?>

            <div class="col-3 col-sm-6 col-xs-12">
               <div class="card instructor-box">
                  <div class="instructor-inner">
                     <div class="profile-img">
                        <?php if(isset($lvalue['featured'][0]['media'])): ?>
                        <a href="<?php echo e($lvalue->slug); ?>" title="<?php echo e($frontHelp->pField($lvalue, 'title')); ?>"><img src="<?php echo e($frontHelp->pImg($lvalue, 'instructors-testimonials')); ?>" alt="<?php echo e($frontHelp->pField($lvalue, 'title')); ?> <?php echo e($lvalue['subtitle']); ?>"></a>
                        <?php endif; ?>
                     </div>
                     <h3><a href="<?php echo e($lvalue->slug); ?>"><?php echo e($lvalue['title']); ?> <?php echo e($lvalue['subtitle']); ?></a></h3>
                     <?php if(isset($lvalue['header'])): ?>
                     <p ><?php echo e($lvalue['header']); ?>, <a target="_blank" href="<?php echo e($lvalue['ext_url']); ?>"><?php if($lvalue['c_fields']['simple_text'][1]['value'] != ''): ?><?php echo e($lvalue['c_fields']['simple_text'][1]['value']); ?> <?php endif; ?></a>.</p>
                     <?php endif; ?>
                     <ul class="social-wrapper">
                        <?php if(isset($lvalue['c_fields']['simple_text'][2]) && $lvalue['c_fields']['simple_text'][2]['value'] != ''): ?>
                        <li><a target="_blank" href="<?php echo e($lvalue['c_fields']['simple_text'][2]['value']); ?>"><img src="/theme/assets/images/icons/social/Facebook.svg" width="16" alt="Visit"></a></li>
                        <?php endif; ?>
                        <?php if(isset($lvalue['c_fields']['simple_text'][4]) && $lvalue['c_fields']['simple_text'][4]['value'] != ''): ?>
                        <li><a target="_blank" href="<?php echo e($lvalue['c_fields']['simple_text'][4]['value']); ?>"><img src="/theme/assets/images/icons/social/Instagram.svg" width="16" alt="Visit"></a></li>
                        <?php endif; ?>
                        <?php if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != ''): ?>
                        <li><a target="_blank" href="#"><img src="/theme/assets/images/icons/social/Pinterest.svg" width="16" alt="Visit"></a></li>
                        <?php endif; ?>
                        <?php if(isset($lvalue['c_fields']['simple_text'][3]) && $lvalue['c_fields']['simple_text'][3]['value'] != ''): ?>
                        <li><a target="_blank" href="<?php echo e($lvalue['c_fields']['simple_text'][3]['value']); ?>"><img src="/theme/assets/images/icons/social/Twitter.svg" width="16" alt="Visit"></a></li>
                        <?php endif; ?>
                     </ul>
                  </div>
                  <!-- /.instructor-inner -->
                  <!--
               </div>
               <!-- /.instructor-box -->
            <!--</div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!-- /.col-3.col-sm-12 -->
         <!--</div>
         <?php endif; ?>
         -->
         <!-- /.row -->
      </div>
      <!-- /.search-results-wrapper -->
   </div>
   <?php endif; ?>
   <!-- /.container -->
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/search/search_results.blade.php ENDPATH**/ ?>
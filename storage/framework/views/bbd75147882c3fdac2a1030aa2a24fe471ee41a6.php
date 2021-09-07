
<?php $__env->startSection('metas'); ?>
    <title><?php echo e($title); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<main id="main-area" role="main">
   <div class="section section--dynamic-learning">
      <div class="container">
         <div class="dynamic-learning--title">
            <?php $elern = false; $diplomas = false; $certificates = false; ?>
            <?php //dd(count($type)); ?>
            <?php if(isset($delivery)): ?>
            <?php
               if( $delivery['slugable']['slug'] === 'video-on-demand-courses'){$elern = true; }
               ?>
            <h1 >
            <?php echo e($delivery->name); ?>

            </h1>
            <p><?php echo e($delivery->description); ?></p>
            <?php elseif(isset($city)): ?>
            <h1 ><?php echo e($city['name']); ?></h1>
            <p><?php echo e($city['description']); ?></p>
            <?php else: ?>
            <?php
               if( $type['slugable']['slug'] === 'diplomas'){$diplomas = true; }
               if( $type['slugable']['slug'] === 'certificates'){$certificates = true; }
               ?>
            <h1 >

                <?php echo e($type->name); ?>


            </h1>
            <p><?php echo e($type->description); ?></p>
            <?php endif; ?>

         </div>
         <!-- ./dynamic-learning--title -->
         <?php if(!$elern): ?>
         <div class="control-wrapper-filters">
            <div class="filters">
               <a href="#upcoming" class="active">upcoming</a>
               <a href="#past">past</a>
            </div>
         </div>
         <?php endif; ?>
         <!-- ./dynamic-learning--subtitle -->
         <?php
            $countcompl = 0;
            $countopen = 0;
            $countsold = 0;
            ?>
         <div class="filters-wrapper">

            <div id="upcoming" class="filter-tab active-tab">

               <?php if(isset($openlist) && count($openlist) > 0): ?>
                  <?php
                     $countopen = count($openlist);
                     $lastmonth1 = '';
                  ?>

                  <?php $__currentLoopData = $openlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <?php if($key===0): ?>
                        <div style="height:100px">
                        </div>
                     <?php endif; ?>
                     <?php //dd($row['expiration']); ?>
                     <?php if($row->view_tpl == 'elearning_event' || $row->view_tpl == 'elearning_greek' || $row->view_tpl == 'elearning_free' || $row->view_tpl == 'elearning_pending'): ?>
                     <div class="dynamic-courses-wrapper">
                        <div class="item">
                           <div class="left">
                                <?php
                                    if(isset($row['slugable']['slug'])){
                                        $slug = $row['slugable']['slug'];
                                    }else{
                                        $slug = '';
                                    }
                                ?>
                              <h2><a href="<?php echo e($slug); ?>"><?php echo e($row->title); ?></a></h2>
                              <div class="bottom">
                                 <?php if($row->summary1->where('section','date')->first()): ?>
                                 <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> <?php echo e($row->summary1->where('section','date')->first()->title); ?>  </div>
                                 <?php endif; ?>
                                 <?php if($row->summary1->where('section','duration')->first()): ?>
                                 <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""><?php echo e($row->summary1->where('section','duration')->first()->title); ?></div>
                                 <?php endif; ?>
                              </div>
                           </div>
                           <div class="right">
                              <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                 $price = $row['ticket'][0]['pivot']['price'];

                                 }
                                 else { $price = 0; } ?>
                              <?php if($row->view_tpl == 'elearning_free'): ?>
                              <div class="price">free</div>
                              <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">Enroll For Free</a>
                              <?php elseif($row->view_tpl == 'elearning_pending'): ?>
                              <div class="price">Pending</div>
                              <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">Course Details</a>
                              <?php else: ?>
                              <div class="price">from €<?php echo e($price); ?></div>
                              <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">Course Details</a>
                              <?php endif; ?>
                           </div>
                           <!-- ./item -->
                        </div>
                     </div>
                     <?php else: ?>
                        <?php
                           $chmonth = date('m', strtotime($row->published_at));
                           $month = date('F Y', strtotime($row->published_at));

                           $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                              return $cartItem->id === $row->id;
                           });

                        ?>

                        <?php if($chmonth != $lastmonth1): ?>
                           <?php $lastmonth1 = $chmonth;?>
                           <div class="dynamic-learning--subtitle">
                              <h2><?php echo e($month); ?></h2>
                           </div>
                        <?php endif; ?>
                        <div class="dynamic-courses-wrapper <?= (!$elern) ? 'dynamic-courses-wrapper--style2' : ''; ?>">
                           <div class="item">
                              <div class="left">
                                  <?php
                                  if(isset($row['slugable']['slug'])){
                                      $slug = $row['slugable']['slug'];
                                  }else{
                                    $slug = '';
                                  }
                                  ?>
                                 <h2><a href="<?php echo e($slug); ?>"><?php echo e($row->title); ?></a></h2>
                                 <div class="bottom">
                                    <?php if(isset($row['city'])): ?>
                                        <?php $__currentLoopData = $row['city']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($city->slugable->slug); ?>" class="city " title="<?php echo e($city->name); ?>">
                                            <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt=""><?php echo e($city->name); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                    <?php if($row->summary1->where('section','date')->first()): ?>
                                 <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> <?php echo e($row->summary1->where('section','date')->first()->title); ?>  </div>
                                 <?php endif; ?>
                                 <?php if($row->summary1->where('section','duration')->first()): ?>
                                 <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""><?php echo e($row->summary1->where('section','duration')->first()->title); ?></div>
                                 <?php endif; ?>



                                 </div>
                              </div>
                              <?php //dd($row['ticket'][0]['pivot']['price']); ?>
                              <div class="right">
                                 <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                    $price = $row['ticket'][0]['pivot']['price'];

                                    }
                                    else { $price = 0; }
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 <?php //dd($row['status']); ?>
                                 <?php if(isset($row['status'])): ?>
                                    <?php $etstatus = $row['status']; ?>
                                 <?php endif; ?>



                                 <?php if($row->view_tpl == 'event_free'): ?>
                                    <div class="price">free</div>
                                    <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">Enroll For Free</a>

                                 <?php elseif($row->view_tpl == 'event_free_coupon'): ?>
                                    <div class="price">free</div>
                                    <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">course details</a>
                                 <?php else: ?>
                                    <?php if($etstatus == 0 && $price > 0): ?>
                                    <div class="price">from €<?php echo e($price); ?></div>
                                    <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md">Course Details</a>
                                    <?php else: ?>
                                    <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md btn--sold-out">sold out</a>
                                    <?php endif; ?>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>

                     <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


               <?php else: ?>



               <h2> No available courses for now </h2>
               <?php endif; ?>

            </div>

            <div id="past" class="filter-tab">
               <?php if(isset($completedlist) && count($completedlist) > 0): ?>
                  <?php
                     $countopen = count($completedlist);
                     $lastmonth1 = '';
                  ?>

               <?php $__currentLoopData = $completedlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($row->view_tpl != 'elearning_event' && $row->view_tpl != 'elearning_greek' && $row->view_tpl != 'elearning_free' && $row->view_tpl != 'elearning_pending'): ?>
                     <?php
                        $chmonth = date('m', strtotime($row->published_at));
                        $month = date('F Y', strtotime($row->published_at));

                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                           return $cartItem->id === $row->id;
                        });

                     ?>

                     <?php if($chmonth != $lastmonth1): ?>
                        <?php $lastmonth1 = $chmonth;?>
                        <div class="dynamic-learning--subtitle">
                           <h2><?php echo e($month); ?></h2>
                        </div>
                     <?php endif; ?>

                     <div class="dynamic-courses-wrapper dynamic-courses-wrapper--style2">
                           <div class="item">
                              <div class="left">
                                <?php
                                    if(isset($row['slugable']['slug'])){
                                        $slug = $row['slugable']['slug'];
                                    }else{
                                        $slug = '';
                                    }
                                ?>
                                 <h2><a href="<?php echo e($slug); ?>"><?php echo e($row->title); ?></a></h2>
                                 <div class="bottom">
                                 <?php if(isset($row['city'])): ?>
                                        <?php $__currentLoopData = $row['city']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($city->slugable->slug); ?>" class="city " title="<?php echo e($city->name); ?>">
                                            <img width="20" class="replace-with-svg" src="/theme/assets/images/icons/marker.svg" alt=""><?php echo e($city->name); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                    <?php if($row->summary1->where('section','date')->first()): ?>
                                 <div class="duration"><img width="20" src="/theme/assets/images/icons/icon-calendar.svg" alt=""> <?php echo e($row->summary1->where('section','date')->first()->title); ?>  </div>
                                 <?php endif; ?>
                                 <?php if($row->summary1->where('section','duration')->first()): ?>
                                 <div class="expire-date"><img class="replace-with-svg" width="20" src="/theme/assets/images/icons/Start-Finish.svg" alt=""><?php echo e($row->summary1->where('section','duration')->first()->title); ?></div>
                                 <?php endif; ?>

                                 </div>
                              </div>
                              <div class="right">
                                 <?php  if (isset($row['ticket'][0]['pivot']['price'])) {
                                    $price = $row['ticket'][0]['pivot']['price'];

                                    }
                                    else { $price = 0; }
                                 ?>
                                 <?php $etstatus = 0 ?>
                                 <a href="<?php echo e($slug); ?>" class="btn btn--secondary btn--md btn--completed">completed</a>
                              </div>
                           </div>
                        </div>

                  <?php endif; ?>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

               <?php endif; ?>
            </div>

         </div>




      </div>



   </div>

   <!-- /#main-area -->
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/pages/category.blade.php ENDPATH**/ ?>
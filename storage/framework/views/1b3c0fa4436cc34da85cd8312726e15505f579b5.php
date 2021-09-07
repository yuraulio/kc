<!-- SEATS -->


<?php
   $title = '';
   $body = '';
   if(isset($sections['tickets'])){
      $title = $sections['tickets']->first()->title;
      $body = $sections['tickets']->first()->description;
   }
?>

<section id="seats" class="section-tickets">
   
   <div class="container">
   
      <h2 class="section-title"><?php echo e($title); ?></h2>

      <div class="row row-flex row-flex-15">
         
            <?php $early = false;?>
         <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         <?php 
         
            $options = json_decode($ticket['pivot']['options'],true); 
            
          
            if($ticket['type'] == 'Early Bird' && $ticket['pivot']['quantity'] > 0){
               
               $early = true;
            }else if($ticket['type'] == 'Early Bird'){
               continue;
            }

            if($ticket['type'] == 'Special' && $early){
               continue;
            } 

            if($ticket['type'] == 'Sponsored'){
               continue;
            } 
            

         ?>
         <div class="col-4 col-sm-12">
       
            <div class="ticket-box-wrapper">
               <div class="ticket-box">
                  <h3 class="<?php if($ticket['type'] != 'Alumni'): ?> special-ticket <?php endif; ?>"><?php echo e($ticket['type']); ?> <span> €<?php echo e($ticket['pivot']['price']); ?> </span></h3>
                  <div class="ticket-box-content">
                     <ul class="seat-features">

                        <?php $__currentLoopData = (array) json_decode($ticket['pivot']['features']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <li><?php echo e($feature); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                     </ul>
                  </div>
                  
                  <div class="ticket-box-price">
                     <span class="ticket-price hidden-xs">€<?php echo e($ticket['pivot']['price']); ?></span>
                     <span class="ticket-infos"> <?php echo e($ticket['subtitle']); ?></span>
                    
                     <span class="ticket-infos hidden-xs"><?php if($ticket['type'] != 'Alumni' && ($event->view_tpl!='elearning_event')): ?> <?php echo e($ticket['pivot']['quantity']); ?> seats remaining <?php else: ?> &nbsp; <?php endif; ?></span> 
                  </div>
                  <div class="ticket-box-actions">
                     <?php if($ticket['type'] == 'Regular' && $early): ?>
                        <?php if($ticket['pivot']['quantity'] <= 0): ?>
                           <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                        <?php else: ?>
                           <div class="btn btn--lg btn--secondary btn--completed" >AVAILABLE SOON</div>
                        <?php endif; ?>
                     <?php else: ?>
                        <?php if(isset($options['dropdown']) && $options['dropdown']): ?>
                           <?php if($ticket['pivot']['quantity'] <= 0): ?>
                              <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                           <?php else: ?>

                              <div class="ticket-actions-wrapper">
                                 <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                 <ul class="tickets-dropdown">
                                    <?php if($ticket['type'] != 'Alumni'): ?> 
                                    <li><a href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 1 ])); ?>" class="btn btn-add btn--lg btn--primary">UNEMPLOYED</a></li>
                                    <li><a href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 2 ])); ?>" class="btn btn-add btn--lg btn--primary">STUDENT</a></li>
                                    <li><a href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 5 ])); ?>" class="btn btn-add btn--lg btn--primary">GROUP</a></li>
                                    <?php endif; ?>
                                 </ul>
                              </div>
                           <?php endif; ?>
                        <?php else: ?>
                           <?php if($ticket['pivot']['quantity'] <= 0): ?>
                              <div class="btn btn--lg btn--secondary btn--sold-out">SOLD-OUT</div>
                           <?php else: ?>
                              <?php if($ticket['pivot']['price'] == 0): ?>
                                 <div class="ticket-actions-wrapper">
                                    <a href="#" class="btn btn--lg btn--primary btn-ticket--dropdown">ENROLL NOW</a>
                                    <ul class="tickets-dropdown">
                                       <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 3 ])); ?>">KNOWCRUNCH ALUMNI</a></li>
                                       <li><a class="btn btn-add btn--lg btn--primary" title="ENROLL NOW" href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 0 ])); ?>">OTHER</a></li>
                                    </ul>
                                 </div>
                                 <?php else: ?>
                                    <?php if($ticket['type'] == 'Alumni'): ?>
                                       <div class="ticket-actions-wrapper">
                                          <a href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 3 ])); ?>" class="btn btn-add btn--lg btn--primary ">ENROLL NOW</a>
                                       </div>                                      
                                    <?php else: ?>
                                       <div class="ticket-actions-wrapper">
                                          <a class="btn btn-add btn--lg btn--primary"  title="ENROLL NOW" href="<?php echo e(route('cart.add-item', [ $event->id, $ticket['id'], 0 ])); ?>">ENROLL NOW</a>
                                       </div>
                                    <?php endif; ?>

                              <?php endif; ?>
                           <?php endif; ?>
                        <?php endif; ?>
                     <?php endif; ?>
                  </div>
                
               </div>
            </div>
         </div>

                    
            
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
        
      </div>
   </div>
</section>

<!-- SEATS END --><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/event/partials/seats.blade.php ENDPATH**/ ?>
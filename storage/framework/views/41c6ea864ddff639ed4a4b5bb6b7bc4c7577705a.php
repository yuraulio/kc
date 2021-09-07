<div class="mobile-menu">
   <div class="mob-menu-inner">
      <div class="search-wrapper">
      <form method="get" action="search/term">
          <?php echo e(csrf_field()); ?>

            <input type="text" name="search_term" placeholder="Search KnowCrunch">
            <button type="submit"><img src="/theme/assets/images/icons/icon-magnifier.svg" alt=""/></button>
         </form>
      </div>
      <div class="menu-wrapper">
         <ul class="mob-menu">
            <?php if(!empty($header_menus)): ?>
            <?php $__currentLoopData = $header_menus['menu']['Header']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="nav-item uppercase-item">
            <a title="<?php echo e($row['data']['name']); ?>" href="<?php echo e($row['data']['slugable']['slug']); ?>"><?php echo e($row['data']['name']); ?></a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(Auth::check()): ?>
            <li class="nav-item">
            <li  class="account-menu"><a href="/myaccount">Account</a></li>

            <?php $cartitems = Cart::content()->count(); ?>
            <?php if($cartitems > 0): ?>
               <li class="cart-menu">
                  <a href="/cart" title="Cart">Cart<span class="cart-number-mobile <?php if(Auth::check()): ?> loged <?php endif; ?>"><?php echo e(Cart::content()->count()); ?></span>
                  </a>
               </li>
            <?php endif; ?>

            <li class="nav-item">
               <a href="<?php echo e(url('logout')); ?>">Sign out</a>
            </li>
            <?php else: ?>
            <li class="account-menu">
				   <a href="javascript:void(0)" title="Superhero Login">Account</a>
            </li>
            <?php $cartitems = Cart::content()->count(); ?>
            <?php if($cartitems > 0): ?>
               <li class="cart-menu">
                  <a href="/cart" title="Cart">Cart<span class="cart-number-mobile <?php if(Auth::check()): ?> loged <?php endif; ?>"><?php echo e(Cart::content()->count()); ?></span>
                  </a>
               </li>
            <?php endif; ?>
            <?php endif; ?>

         </ul>
      </div>
   </div>
   <!-- /.mobile-menu -->
</div>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/mobile_menu.blade.php ENDPATH**/ ?>
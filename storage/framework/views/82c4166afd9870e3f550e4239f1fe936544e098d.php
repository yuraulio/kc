<?php $frontHelp = app('Library\FrontendHelperLib'); ?>



<header id="header" >
				<div class="container clearfix">
					<div class="logo-area">
						<a href="/" class="logo">Know Crunch</a>
					</div>
					<div class="menu-area xs-flex">

					<?php $cartitems = Cart::content()->count(); ?>
					<?php if($cartitems > 0): ?>
					<span class="cart-menu xs-cart">
						<a href="/cart" title="Cart"><img src="<?php echo e(cdn('/theme/assets/images/icons/icon-cart.svg')); ?>" width="18" alt="Cart">
							<!--  <span class="badge defaultCount"><?php echo e(Cart::content()->count()); ?></span>-->
						</a>
					</span>
						<?php endif; ?>

						<button class="hamburger hamburger--spin mob-menu-btn" type="button">

						  	<span class="hamburger-box">
						    	<span class="hamburger-inner"></span>
						  	</span>
						</button>

						<div class="header-actions clearfix">
							<ul class="actions-list">
                                <?php $cartitems = Cart::content()->count(); ?>
                                <?php if($cartitems > 0): ?>
								    <li class="cart-menu">
                                        <a href="/cart" title="Cart"><img src="<?php echo e(cdn('/theme/assets/images/icons/icon-cart.svg')); ?>" width="18" alt="Cart">
                                          <!--  <span class="badge defaultCount"><?php echo e(Cart::content()->count()); ?></span>-->
                                        </a>
                                    </li>
								<?php endif; ?>

                                <?php if(Sentinel::check()): ?>

                                <li class="account-menu">
									<a href="javascript:void(0)" title="Superhero Login"><img src="<?php echo e(cdn('/theme/assets/images/icons/knowcrunch-superhero-icons-login.svg')); ?>"  alt="Superhero Login"></a>
                                        <div class="account-submenu">
                                            <ul>
                                                <li class="account-menu"><a href="/myaccount">Account</a></li>
                                                <li><a href="<?php echo e(url('logout')); ?>">Sign Out</a></li>
                                            </ul>
                                        </div>

                                    </li>

                                <?php else: ?>

                                <li class="account-menu">
									<a href="javascript:void(0)" title="Superhero Login"><img src="<?php echo e(cdn('/theme/assets/images/icons/knowcrunch-superhero-icons-login.svg')); ?>" width="18" alt="Superhero Login"></a>

                                </li>
                                <?php endif; ?>




                                <li class="header-search-area">
									<a href="javascript:void(0)" title="Search" class="search-toggle"><img src="<?php echo e(cdn('/theme/assets/images/icons/icon-magnifier.svg')); ?>" alt="Search"></a>
									<div class="header-search-wrapper">
										<form method="get" action="search/term">
                                        <?php echo e(csrf_field()); ?>

											<input id="sat" type="text" name="search_term"  class="search-input" placeholder="Search">
										</form>
									</div>


                                </li>


							</ul>
						</div>

						<ul class="main-menu">

                            <?php if(!empty($filter_type)): ?>
                                <?php $__currentLoopData = $filter_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a title="<?php echo e($row->name); ?>" href="<?php echo e($row->slug); ?>"><?php echo e($row->name); ?></a>
                                    </li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<li>
								<a title="Corporate Training" href="/corporate-training">Corporate Training</a>
								</li>
                            <?php endif; ?>
							<!--<li><a href="#">In-class courses</a></li>
							<li><a href="#">E-learning Courses</a></li>
							<li><a href="#">Corporate training</a></li>-->
						</ul>
					</div>
				</div>

			</header>

<?php if(Request::is('/') ): ?>
<script>

    document.getElementById('header').classList.add('header-transparent');
</script>
<?php endif; ?>

<?php echo $__env->make('flash::message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/header_consent.blade.php ENDPATH**/ ?>
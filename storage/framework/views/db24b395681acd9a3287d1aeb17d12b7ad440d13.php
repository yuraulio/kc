

<?php $__env->startSection('title'); ?>
<title>Error 419</title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message_page'); ?>
<main id="main-area" class="no-pad-top" role="main">
				<section class="section-text-img-blue">
					<div class="container">
						<div class="columns-wrapper">
							<div class="row row-flex">
								<div class="col-7 col-sm-12">
									<div class="text-area">
										<h1>Inactivity User</h1>
										<h2>Error code: 419.</h2>
										<p>Here are some helpful links instead: </p>
										<p><a href="/" class="dark-bg">Homepage</a><br/>
										<a href="/e-learning-courses" class="dark-bg">In-class courses</a><br/>
										<a href="/in-class-courses" class="dark-bg">E-learning courses</a><br/>
										<a href="/contact" class="dark-bg">Contact us</a></p>
									</div>
								</div>
								<div class="col-5 col-sm-12">
									<div class="icon-wrapper">
										<img src="<?php echo e(cdn('/theme/assets/images/icons/404-page.svg')); ?>" alt="Error 404"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
            </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/errors/419.blade.php ENDPATH**/ ?>
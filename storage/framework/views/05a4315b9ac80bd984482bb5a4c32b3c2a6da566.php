<!doctype html>
<html lang="en" class="no-js">
   <head>
      <base href="<?php echo URL::to('/'); ?>/" target="_self" />
      <meta charset="UTF-8">
      <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <?php echo $__env->make('theme.layouts.favicons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->make('theme.layouts.header_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </script>
   </head>
   <body>
      <div class="page-wrapper non-pointer">
         <div  class="login-popup-wrapper active">
            <div id="login-popup" class="login-popup">
               <div class="heading">
                  <p class="text-center">Seems you are trying to access your account from a new device or browser. Enter the code you will get by SMS.</p>
               </div>
					<?php if(\Session::has('errors')): ?>
               <div class="alert-outer">
                  <div class="alert-wrapper error-alert">
                     <div class="alert-inner">
                        <p id="account-error"><?php echo \Session::get('errors'); ?></p>
                     </div>
                  </div>
                  <!-- /.alert-outer -->
               </div>
					<?php endif; ?>
               <form method="post" action="/smsVerification">

					<?php echo e(csrf_field()); ?>

						<div class="input-wrapper input-wrapper--text input-wrapper--email">
							
							<input type="text" onblur="this.placeholder = '4 digit code'" onfocus="this.placeholder = ''" placeholder="4 digit code" id="sms" name="sms">
						</div>
						<input name="cookie_value" value="<?php echo e($slug); ?>" hidden>
						<input type="submit"  value="Verify">
                  </br>
						<input id="logout-sms" class="btn btn--lg btn--primary top-15" type="button" onclick="" value="LOGOUT">
					</form>
            </div>
            <!-- ./login-popup -->
         </div>
         <!-- ./login-popup-wrapper -->
         <!--<script src="<?php echo e(URL::to('/assets/colorbox/jquery.colorbox.js')); ?>">></script>-->
      </div>
      <?php echo $__env->make('theme.layouts.footer_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
     

   <script>

      $("#logout-sms").click(function(){
         window.location="<?php echo e(route('logout')); ?>";
      })

   </script>

   </body>
</html><?php /**PATH C:\laragon\www\kcversion8\resources\views/theme/layouts/sms_layout.blade.php ENDPATH**/ ?>
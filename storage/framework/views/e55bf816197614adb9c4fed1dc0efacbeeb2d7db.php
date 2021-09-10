

<?php $__env->startSection('metas'); ?>

   <?php echo $page->metable->getMetas(); ?>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<meta name="robots" content="NOINDEX,NOFOLLOW">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<main id="main-area" class="with-hero" role="main">
   <?php if(!Auth::user()): ?>
   <div  class="login-popup-wrapper-subscription">
      <div id="login-popup" class="login-popup">
         <a href="#" class="close-btn"><img width="26" src="<?php echo e(cdn('theme/assets/images/icons/icon-close.svg')); ?>" class="replace-with-svg" alt="Close"></a>
         <div class="heading">
            <span>Account login</span>
            <p>Access your courses, schedule & files.</p>
         </div>
         <div class="alert-outer" hidden>
            <div class="alert-wrapper error-alert">
               <div class="alert-inner">
                  <p id="account-error"></p>
                  
               </div>
            </div>
            <!-- /.alert-outer -->
         </div>
         <form autocomplete="off" class="login-form">
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
               <span class="icon"><img width="14" src="<?php echo e(cdn('/theme/assets/images/icons/icon-email.svg')); ?>" alt=""></span>
               <input type="text" placeholder="Email" id="email-sub" autocomplete="off">
            </div>
            <div class="input-wrapper input-wrapper--text">
               <span class="icon"><img width="10" src="<?php echo e(cdn('/theme/assets/images/icons/icon-lock.svg')); ?>" alt=""></span>
               <input type="password" placeholder="Password" id="password-sub" autocomplete="off">
            </div>
            <div class="form-group">
               <label for="remember-me"><input id="remember-me-sub" type="checkbox">Remember me</label>
               
            </div>
            <input type="button" onclick="loginAjaxSubscription()" value="LOGIN">
         </form>
      </div>
      <!-- ./login-popup -->
      <div id="forgot-pass-input" class="login-popup" hidden>
         <a href="#" class="close-btn"><img width="26" src="<?php echo e(cdn('theme/assets/images/icons/icon-close.svg')); ?>" class="replace-with-svg" alt="Close"></a>
         <div class="heading">
            <span>Change your Password</span>
            <p>Use your account email to change your password</p>
         </div>
         
         <form autocomplete="off" class="login-form">
            <?php echo csrf_field(); ?>

            <div id="error-mail" class="alert-outer" hidden>
               <div class="alert-wrapper error-alert">
                  <div class="alert-inner">
                     <p id="message-error"></p>
                  </div>
               </div>
               <!-- /.alert-outer -->
            </div>
            <div id="success-mail" class="alert-outer" hidden>
               <div class="container">
                  <div class="alert-wrapper success-alert">
                     <div class="alert-inner">
                        <p id="message-success"></p>
                     </div>
                  </div>
               </div>
               <!-- /.alert-outer -->
            </div>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
               <div class="input-safe-wrapper">	
                  <span class="icon"><img width="14" src="<?php echo e(cdn('/theme/assets/images/icons/icon-email.svg')); ?>" alt=""></span>
                  <input type="email"  placeholder="Email" name="email" id="email-forgot" class="required"> 
               </div>
            </div>
            <button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Change</button>
         </form>
      </div>
      <!-- ./login-popup -->
   </div>
   <!-- ./login-popup-wrapper -->
   <?php endif; ?>
   <?php if(!empty($page['medias'])): ?>
   <section class="section-hero" style="background-image:url(<?php echo e(cdn(get_image($page['medias'], 'header-image'))); ?>)">
      <div class="overlay"></div>
      <div class="container">
         <div class="hero-message pad-r-col-6">
         <h1><?php echo e($page['name']); ?></h1>
                    <h2><?php echo e($page['title']); ?></h2>
         </div>
      </div>
   </section>
   <?php else: ?>
   <section class="section-hero section-hero-small section-hero-blue-bg">
      <div class="container">
         <div class="hero-message">
         <h1><?php echo e($page['name']); ?></h1>
                    <h2><?php echo e($page['title']); ?></h2>
         </div>
      </div>
   </section>
   <?php endif; ?>
   <section class="form-section form-section-sub">
      <div class="container">
         <div class="row">
            <div class="col6 col-sm-12">
               <div class="text-area">
               <?php echo $page['content']; ?>

               </div>
            </div>
            <div class="col6 col-sm-12">
               <div class="form-area-wrapper">
                  <div class="form-wrapper blue-form sub-blue-form">
                     <form method="GET" action="/myaccount/subscription/<?php echo e($event); ?>/<?php echo e($plan); ?>" id="doall" novalidate>
                        <h3 class="form-h3 subscription">Get full access for one year</h3>
                        <ul class="subs-page-list">
                           <li>
						   		<img class="replace-with-svg" width="20" src="<?php echo e(cdn('/theme/assets/images/icons/checkmark-sqaure.svg')); ?>" alt=""> <span class="subs-page-span">Access to presentations</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="<?php echo e(cdn('/theme/assets/images/icons/checkmark-sqaure.svg')); ?>" alt=""> <span class="subs-page-span"> Access to bonus files</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="<?php echo e(cdn('/theme/assets/images/icons/checkmark-sqaure.svg')); ?>" alt=""> <span class="subs-page-span"> Access to videos</span>
                           </li>
                           <li>
						   <img class="replace-with-svg" width="20" src="<?php echo e(cdn('/theme/assets/images/icons/checkmark-sqaure.svg')); ?>" alt=""> <span class="subs-page-span"> Personal notes</span>
                           </li>
                        </ul>
                        <div class="submit-area-custom">
                           <button  type="button" class="btn btn--md btn--primary subscription-enroll">SUBSCRIBE NOW</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- /.form-section -->
   </section>
   <section class="">
      <div class="content-wrapper">
         <div class="tabs-wrapper fixed-tab-controls">
            <div class="tabs-content">
               <div class="tab-content-wrapper tab-no-pad active-tab">
                  <div class="container">
                     <div class="testimonial-carousel-wrapper hidden-xs">
                      
                        <div class="video-carousel-big owl-carousel">
                           <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php

                                 if(!$video['video_url']){
                                    continue;
                                 }
                                 $urlArr = explode("/",$video['video_url']);
                                 $urlArrNum = count($urlArr);

                                 // YouTube video ID
                                 $youtubeVideoId = $urlArr[$urlArrNum - 1];

                                 // Generate youtube thumbnail url
                                 $thumbURL = 'https://img.youtube.com/vi/'.$youtubeVideoId.'/mqdefault.jpg';
                                 ?>
                              <div class="slide">
                                 <a data-fancybox href="<?php echo e($video['video_url']); ?>"><img src="<?php echo e($thumbURL); ?>" alt=""/></a>
                              </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <!-- /.testimonial-carousel-wrapper -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="">
      <div id="testimonials" class="tab-content-wrapper tab-no-pad">
         <div class="content-wrapper">
            <div class="tabs-wrapper fixed-tab-controls">
               <div class="tabs-content">
                  <div class="tab-content-wrapper tab-no-pad active-tab">
                     <div class="user-testimonial-wrapper">
                        <div class="container">
                           <div class="user-testimonial-big owl-carousel">
                              <?php if(!empty($testimonials)): ?>
                              <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <?php if($row['video_url']): ?>
                                 <?php continue;?>
                           <?php endif; ?>
                           <div class="slide">
                              <div class="testimonial-box">
                                 <div class="author-infos">
                                    <div class="author-img">
                                       <img src="<?php echo e(cdn(get_image($row['mediable'],'users'))); ?>" alt="<?php echo $row['name']; ?>">
                                    </div>
                                    <span class="author-name">
                                    <?php echo $row['name']; ?> <?php echo $row['lastname']; ?></span>
                                    <span class="author-job"><?php echo $row['title']; ?></span>
                                 </div>
                                 <div class="testimonial-text">
                                    <?php
                                          $rev = $row['testimonial'];
                                          $rev = str_replace('"','',$rev);
                                    ?>
                                    <?php echo $row['testimonial']; ?>

                                 </div>
                              </div>
                              <script type="application/ld+json">
														{
														  "@context": "https://schema.org/",
														  "@type": "UserReview",
														  "itemReviewed": {
														    "@type": "Course",
                                                            "provider": "Know Crunch",
														    "image": "",
														    "name": "<?php echo $page['title']; ?>",
                                                            "description": "<?php echo $page['subtitle']; ?>"
														  },
														  "reviewRating": {
														    "@type": "Rating",
														    "ratingValue": "5"
														  },
														  "name": "<?php echo $page['title']; ?>",
														  "author": {
														    "@type": "Person",
														    "name": "<?php echo $row['name']; ?> <?php echo $row['lastname']; ?>"
														  },
														  "reviewBody": "<?php echo $rev; ?>",
														  "publisher": {
														    "@type": "Organization",
														    "name": "KnowCrunch"
														  }
														}
													</script>

                              <!-- /.slide -->
                           </div>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
   document.getElementById('header').classList.add('header-transparent');
</script>
<script type="text/javascript">
   //login function function
   $(".subscription-enroll").click(function() {
   
   	<?php if(!Auth::user()): ?>
   		$('.login-popup-wrapper-subscription').addClass('active')
   <?php else: ?>
   
   $('#doall').submit();
   
   	<?php endif; ?>
   
   	//myaccount/subscription/1350/2
   
   });
   
   $('.close-btn').click(function(e){
   	e.preventDefault();
   	$('.login-popup-wrapper-subscription').removeClass('active')
   })
   
   <?php if(!Auth::user()): ?>
   function loginAjaxSubscription(){
       var email = $('#email-sub').val();
       var password = $('#password-sub').val();
       var remember = document.getElementById("remember-me-sub").checked;
      
       if (email.length > 4 && password.length > 4) {
       $.ajax({ url: routesObj.baseUrl+"studentlogin", type: "post",
               data: {email:email, password:password, remember:remember},
               success: function(data) {
                   //console.log(data);
                   
                   switch (data.status) {
                       case 0:
                           if (data.message.length > 0) {
   
                               var p = document.getElementById('account-error').textContent = data['message'];
                             /*  var img = document.createElement('img');
                               img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                               img.setAttribute('alt',"Info Alert" )
   
                               $('#account-error').append(img);*/
                           //	console.log(p);
                               $('.alert-outer').show()
   
                           } else {
                             
   
                           }
                           break;
                       case 1:
                         
   			location.reload();
                           /*setTimeout( function(){
                               window.location.replace(data.redirect);
                           }, 1000 );*/
   
                           break;
   
                       default:
                           shakeModal();
                           break;
                   }
                   
              
      
               },
               error: function(data) {
                   //shakeModal();
               }
           });
   
           }
           else {
             //  shakeModal();
   
           }
   
   
   }
   <?php endif; ?>
   
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('theme.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\kcversion8\resources\views/admin/static_tpls/subscription-template/frontend.blade.php ENDPATH**/ ?>
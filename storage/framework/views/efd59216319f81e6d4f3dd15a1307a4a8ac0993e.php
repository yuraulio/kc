<!doctype html>
<html lang="en" class="no-js">
<head>
<base href="<?php echo URL::to('/'); ?>/" target="_self" />
<meta charset="UTF-8">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="theme/assets/css/old.css" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/normalize.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/jquery.mCustomScrollbar.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/jquery-ui.css')); ?>">
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/grid.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/grid-flex.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/global.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/main.css')); ?>" />
<link rel="stylesheet" href="<?php echo e(cdn('theme/assets/css/fontawesome/css/kcfonts.css')); ?>" />

</head>

<body>
<div class="page-wrapper">

<div  class="login-popup-wrapper " style="opacity:1; display:block; pointer-events:all">



    <div id="login-popup" class="login-popup" style="margin:0 auto;">
        <a href="javascript:void(0)" class="close-btn close"><img width="26" src="<?php echo e(cdn('theme/assets/images/icons/icon-close.svg')); ?>" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
            <h2>Change password</h2>
        </div>
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
				<span class="icon"><img width="10" src="<?php echo e(cdn('/theme/assets/images/icons/icon-lock.svg')); ?>" alt=""></span>
				<input type="password" placeholder="New password" id="password">
            </div>
            <div class="input-wrapper input-wrapper--text">
                <span class="icon"><img width="10" src="<?php echo e(cdn('/theme/assets/images/icons/icon-lock.svg')); ?>" alt=""></span>
                <input type="password" placeholder="Confirm new password" id="confirm-password">
            </div>
</br>
            <input type="button" class="btn btn--lg btn--secondary change-password" value="CHANGE">
        
    </div><!-- ./login-popup -->

</div><!-- ./login-popup-wrapper -->

</div>

<script src="<?php echo e(cdn('/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(cdn('/theme/assets/js/new_js/jquery-3.4.1.min.js')); ?>" ></script>
<script src="<?php echo e(cdn('/theme/assets/js/new_js/jquery-ui.js')); ?>"></script>
<script src="<?php echo e(cdn('/theme/assets/js/new_js//plugins.js')); ?>"></script>
<script src="<?php echo e(cdn('/theme/assets/js/new_js//main.js')); ?>"></script>


<script>
$.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).on('click', '.change-password', function(e){
        $('#error-mail').hide()
        $('#success-mail').hide()
        //console.log($(".change-password-form").serialize());
		var pass = document.getElementById('password').value
        var passConf = document.getElementById('confirm-password').value
        
       //console.log(window.location.href)
	   
	   var url = (window.location.href).split("/");
	 
        
            $.ajax({ url: 'myaccount/reset/' + url[5] + '/' + url[6], type: "post",
                    
                    data: {"password": pass,"password_confirmation": passConf},
                    success: function(data) {
                        
                        
                        if(data['success']){
                            $('#success-mail').show()
                            var p = document.getElementById('message-success').textContent = data['message'];
                        }else if (!data['pass_confirm']){
							$('#error-mail').show()
                            var p = document.getElementById('message-error').textContent = data['message'];
						}else{
                            $('#error-mail').show()
                            var p = document.getElementById('message-error').textContent = data['message'];

                        }

						if(data['pass_confirm']){
							setTimeout( function(){
								window.location.replace('/');
							}, 1000 );
						}

                    },
                
                });
        

    })


	$(document).on('click', '.close', function(e){
		window.location.replace('/')
	});

</script>


</body>
</html>

<?php /**PATH C:\laragon\www\kcversion8\resources\views/auth/passwords/complete.blade.php ENDPATH**/ ?>
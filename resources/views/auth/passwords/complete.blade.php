<!doctype html>
<html lang="en" class="no-js">
<head>
<base href="{!! URL::to('/') !!}/" target="_self" />
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="theme/assets/css/old.css" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/normalize.css') }}" />
<link rel="stylesheet" href="{{cdn('theme/assets/css/jquery.mCustomScrollbar.css')}}" />
<link rel="stylesheet" href="{{cdn('theme/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" href="{{ cdn('theme/assets/css/grid.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/grid-flex.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/global.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/main.css') }}" />
<link rel="stylesheet" href="{{ cdn('theme/assets/css/fontawesome/css/kcfonts.css') }}" />

</head>

<body>
<div class="page-wrapper">

<div  class="login-popup-wrapper reset-password" style="opacity:1; display:block; pointer-events:all">



    <div id="login-popup" class="login-popup" style="margin:0 auto;">
        <a href="javascript:void(0)" class="close-btn close"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
        <div class="heading">
            <h2>Create/reset Password</h2>
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
            <label> New password <span class="required">(*)</span></label> <span data-id="password" class="icon"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
            	<input type="password" id="password">
            </div>
            </br>
            <label> Retype new password <span class="required">(*)</span></label><span data-id="confirm-password" class="icon"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
            <div class="input-wrapper input-wrapper--text">    
                <input type="password" id="confirm-password">
            </div>
          
</br>
            <input type="button" class="btn btn--lg btn--secondary change-password" value="CREATE/RESET PASSWORD">
        
    </div><!-- ./login-popup -->

</div><!-- ./login-popup-wrapper -->

</div>

<script src="{{cdn('/theme/assets/js/new_js/vendor/modernizr-3.7.1.min.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js/jquery-3.4.1.min.js')}}" ></script>
<script src="{{cdn('/theme/assets/js/new_js/jquery-ui.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js//plugins.js')}}"></script>
<script src="{{cdn('/theme/assets/js/new_js//main.js')}}"></script>


<script>
$.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $(document).on('click', '.change-password', function(e){
        $('#error-mail').hide()
        $('#success-mail').hide()
        //console.log($(".change-password-form").serialize());
		var pass = document.getElementById('password').value
        var passConf = document.getElementById('confirm-password').value
        
       //console.log(window.location.href)
	    @if(isset($create) && $create)
            var url = "{{ route('create.store',$slug) }}";
        @else
            var url = (window.location.href).split("/");
            url = 'myaccount/reset/' + url[5] + '/' + url[6]
        @endif
	   
        
        $.ajax({ url:url , type: "post",
                
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
						window.location.replace('/myaccount');
					}, 1000 );
				}

            },
            
        });
        

    })


	$(document).on('click', '.close', function(e){
		window.location.replace('/')
	});

</script>

<script>

    $('.icon').click(function(){
        let input = $(`#${$(this).data('id')}`);

        if(input.attr('type') === "password"){
            input.attr('type','text')

            $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password-active.svg')}}");


        }else{
            input.attr('type','password')
            $(this).find('img').attr('src', "{{cdn('/theme/assets/images/icons/eye-password.svg')}}");
        }

    })

</script>

</body>
</html>


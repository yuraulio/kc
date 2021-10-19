<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>KnowCrunch Billing</title>
	@include('theme.layouts.favicons')	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">	
	<link rel="stylesheet" type="text/css" href="{{cdn('new_cart/css/style.css')}}">

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-ML7649C');
	</script>
	<!-- End Google Tag Manager -->


</head>
<body>

	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C"height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->

	<!---------------- header start --------------->
	<header>
		<div class="container">
			<div class="header-wrap">
				<div class="header-logo">
					<a href="{{url('/')}}"><img src="{{cdn('new_cart/images/kc-logo.svg')}}" alt="KC Logo"></a>
				</div>
				<div class="d-flex align-items-center strip-ssl-wrap">
					<div class="d-flex justify-content-between ssl-lock-wrp">
						<img src="{{cdn('new_cart/images/ssl.svg')}}" alt="ssl" width="21px" height="21px">
						<img src="{{cdn('new_cart/images/lock.svg')}}" alt="lock" width="16px" height="20px">
					</div>
						<img src="{{cdn('new_cart/images/powered-by-stripe.svg')}}" alt="Powered By Stripe" width="119px" height="28px">					
				</div>
			</div>
		</div>

@if(!Auth::check())
<div  class="login-popup-wrapper">



<div id="login-popup" class="login-popup">
	<a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
	<div class="heading">
		<span>Account login</span>
		<p>Access your courses, schedule & files.</p>
	</div>
	<div class="alert-outer" hidden>
				
					<div class="alert-wrapper error-alert">
						<div class="alert-inner">
							<p id="account-error"></p>
						{{--<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>--}}
						</div>
				</div>
			<!-- /.alert-outer -->
	</div>
	<form autocomplete="off" class="login-form">
		<div class="input-wrapper input-wrapper--text input-wrapper--email">
			<span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
			<input type="text" placeholder="Email" id="emaill" autocomplete="off">
		</div>
		<div class="input-wrapper input-wrapper--text">
			<span class="icon"><img width="10" src="{{cdn('/theme/assets/images/icons/icon-lock.svg')}}" alt=""></span>
			<input type="password" placeholder="Password" id="password" autocomplete="off">
		</div>
		<div class="form-group">
			<label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
			{{--<a id="forgot-pass" href="javascript:void(0)">Forgot password?</a>--}}
		</div>
		<input type="button" onclick="loginAjaxNew()" value="LOGIN">
	</form>
</div><!-- ./login-popup -->

<div id="forgot-pass-input" class="login-popup" hidden>
	<a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
	<div class="heading">
	<span>Change your Password</span>
		<p>Use your account email to change your password</p>
	</div>
	{{--<form method="post" action="/myaccount/reset" autocomplete="off" class="validate-form change-password-form"> --}}
	<form autocomplete="off" class="login-form">
	{!!csrf_field()!!}

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
			<span class="icon"><img width="14" src="{{cdn('/theme/assets/images/icons/icon-email.svg')}}" alt=""></span>
			<input type="email"  placeholder="Email" name="email" id="email-forgot" class="required"> 
		</div>
		</div>
	   
		<button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Change</button>
	</form>
</div><!-- ./login-popup -->
</div><!-- ./login-popup-wrapper -->
@endif
	</header>
	<!---------------- header end --------------->

	

    @yield('content')


    <footer>
		<div class="bank-details text-center">
			<img src="{{cdn('new_cart/images/amex.svg')}}" alt="Amex" width="58px" height="37px">
			<img src="{{cdn('new_cart/images/mastercard.svg')}}" alt="Mastercard" width="49px" height="38px">
			<img src="{{cdn('new_cart/images/visa.svg')}}" alt="Visa" width="58px" height="37px">
			<img src="{{cdn('new_cart/images/discover.svg')}}" alt="Discover" width="58px" height="37px">
			<img src="{{cdn('new_cart/images/china-unionpay.svg')}}" alt="Unionpay" width="59px" height="37px">
		</div>
		<div class="address text-center">
			KnowCrunch Inc., 2035 Sunset Lake Road, Delaware, USA.
		</div>		
	</footer>
	<!---------------- footer end--------------->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>	
	<script src="{{cdn('new_cart/js/jquery.validate.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>
	<script src="{{cdn('new_cart/js/additional-methods.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>	
	<script src="{{cdn('new_cart/js/validation.js')}}" type="text/javascript" charset="utf-8" async defer></script>					
	<script src="{{cdn('new_cart/js/script.js')}}" type="text/javascript" charset="utf-8" async defer></script>
    <script src="{{cdn('new_cart/js/cart.js')}}" type="text/javascript" charset="utf-8" async defer></script>

	@if (!Auth::check())

	<script>

var routesObj = {
    baseUrl : '{{ URL::to("/") }}/'
};

  $('.login-link').click(function(e) {
      //  e.preventDefault();

        $('.login-popup-wrapper').addClass('active');
    });

    $('.login-popup-wrapper .close-btn').click(function(e) {
        e.preventDefault();
        
        $('.login-popup-wrapper').removeClass('active');
    });


	function loginAjaxNew(){
    var email = $('#emaill').val();
    var password = $('#password').val();
    var remember = document.getElementById("remember-me").checked;

    if (email.length > 4 && password.length > 4) {
    $.ajax({ url: routesObj.baseUrl+"studentlogin", type: "post",
            data: {email:email, password:password, remember:remember},
			headers: {
    	    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	 			},
            success: function(data) {
                

                switch (data.data.status) {
                    case 0:
                        if (data.message.length > 0) {

                            var p = document.getElementById('account-error').textContent = data['message'];
                          //  var img = document.createElement('img');
                           // img.setAttribute('src',"/theme/assets/images/icons/alert-icons/icon-error-alert.svg" )
                          //  img.setAttribute('alt',"Info Alert" )

                            //$('#account-error').append(img);
                        //	console.log(p);
                            $('.alert-outer').show()

                        } else {


                        }
                        break;
                    case 1:
						console.log('gfds');
                        setTimeout( function(){
                            window.location.reload();;
                        }, 1000 );

                        break;

                    default:
                        
                        break;
                }



            },
            error: function(data) {
                
            }
        });

        }
        else {
          //  shakeModal();

        }


}
		</script>





@endif


@if($tigran)

      <script>

         @foreach($tigran as $key => $ti)
            dataLayer.push({"{{$key}}": "{{$ti}}"})
         @endforeach

      </script>

   @endif

@stack('scripts')

</body>
</html>
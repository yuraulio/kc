<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Knowcrunch Billing</title>
	@include('theme.layouts.favicons')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	{{--<link rel="stylesheet" type="text/css" href="{{cdn('new_cart/css/style.css')}}">--}}
	<link href="{{ cdn(mix('new_cart/version/style_ver.css')) }}" rel="stylesheet" media="all" />

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-ML7649C');
	</script>
	<!-- End Google Tag Manager -->

	{{--<script src="https://js.stripe.com/v3/"></script>--}}

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

				@if(!$eventFree)
				<div class="d-flex align-items-center strip-ssl-wrap">
					<div class="d-flex justify-content-between ssl-lock-wrp">
						<img src="{{cdn('new_cart/images/ssl.svg')}}" alt="ssl" width="21px" height="21px">
						<img src="{{cdn('new_cart/images/lock.svg')}}" alt="lock" width="16px" height="20px">
					</div>
						<img src="{{cdn('new_cart/images/powered-by-stripe.svg')}}" alt="Powered By Stripe" width="119px" height="28px">
				</div>
				@endif
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
	<label>Email <span class="required">(*)</span></label>
            <div class="input-wrapper input-wrapper--text input-wrapper--email">
                <input type="text"  id="emaill" autocomplete="off">

            </div>

            </br>

            <label> Password <span class="required">(*)</span></label><span data-id="password" class="icon"><img width="20" src="{{cdn('/theme/assets/images/icons/eye-password.svg')}}" alt="">Show</span>
            <div class="input-wrapper input-wrapper--text">
                <input type="password"  id="password" autocomplete="off">
            </div>


		<div class="form-group">
			<label for="remember-me"><input id="remember-me" type="checkbox">Remember me</label>
			{{--<a id="forgot-pass" href="javascript:void(0)">Forgot password?</a>--}}
		</div>
		<input type="button" onkeypress="keyPress(event)" onclick="loginAjaxNew()" value="LOGIN">
	</form>
</div><!-- ./login-popup -->

<div id="forgot-pass-input" class="login-popup" hidden>
	<a href="#" class="close-btn"><img width="26" src="{{cdn('theme/assets/images/icons/icon-close.svg')}}" class="replace-with-svg" alt="Close"></a>
	<div class="heading">
	<span>Password</span>
		<p>Use your account email to create or change your password.</p>
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

		<button type="button" class="btn btn--lg btn--secondary change-password"  value="Change">Create / Change</button>
	</form>
</div><!-- ./login-popup -->
</div><!-- ./login-popup-wrapper -->
@endif
	</header>
	<!---------------- header end --------------->



    @yield('content')


    <footer>
	@if(!$eventFree)
		{{--<div class="bank-details text-center">
			<img loading="lazy" src="{{cdn('new_cart/images/amex.svg')}}" alt="Amex" title="Amex" width="58px" height="37px">
			<img loading="lazy" src="{{cdn('new_cart/images/mastercard.svg')}}" alt="Mastercard" title="Mastercard" width="49px" height="38px">
			<img loading="lazy" src="{{cdn('new_cart/images/visa.svg')}}" alt="Visa" title="Visa" width="58px" height="37px">
			<img loading="lazy" src="{{cdn('new_cart/images/discover.svg')}}" alt="Discover" title="Discover" width="58px" height="37px">
			<img loading="lazy" src="{{cdn('new_cart/images/china-unionpay.svg')}}" alt="Unionpay" title="Unionpay" width="59px" height="37px">
		</div>--}}
		@endif
		<div class="address text-center">
			Knowcrunch Inc., 2035 Sunset Lake Road, Delaware, USA.
		</div>
	</footer>
	<!---------------- footer end--------------->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="{{cdn('new_cart/js/jquery.validate.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>
	<script src="{{cdn('new_cart/js/additional-methods.min.js')}}" type="text/javascript" charset="utf-8" async defer></script>
	<script src="{{cdn('new_cart/js/validation.js')}}" type="text/javascript" charset="utf-8" async defer></script>
	<script src="{{cdn(mix('new_cart/js/script_new_cart_ver.js'))}}" type="text/javascript" charset="utf-8" async defer></script>
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
                        if (data.data.message.length > 0) {

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
                        //setTimeout( function(){
                            window.location.reload();;
                        //}, 1000 );

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

$(document).keyup(function(event){

    if($('.login-popup-wrapper').hasClass('active')){

        if(event.keyCode == 13){
            loginAjaxNew()
        }
    }


})
		</script>





@endif


@if(isset($tigran) && !env('APP_DEBUG'))

      <script>
		$(document).ready(function(){
         @foreach($tigran as $key => $ti)
			@if($key == 'price')
				dataLayer.push({"{{$key}}": {{$ti}}})
			@else
				dataLayer.push({"{{$key}}": $.parseHTML("{{$ti}}")[0].data})
			@endif
         @endforeach
		})
      </script>

   @endif

   	<script>

		function alphabetizeList(listField) {
    		var sel = $(listField);
    		var selected = sel.val(); // cache selected value, before reordering
    		var opts_list = sel.find('option');
    		opts_list.sort(function (a, b) {
    		    return $(a).text().trim() > $(b).text().trim() ? 1 : -1;
    		});
    		sel.html('').append(opts_list);
    		sel.val(selected); // set cached selected value
		}

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

@stack('scripts')

</body>
</html>

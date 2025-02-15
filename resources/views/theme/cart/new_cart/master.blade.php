<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Knowcrunch Billing</title>
	@include('theme.layouts.favicons')
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
	<link href="{{ cdn(mix('new_cart/css/new_cart.css')) }}" rel="stylesheet" media="all" />

	<!-- Google Tag Manager -->
  @if(!config('app.debug'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-ML7649C');
    </script>
{{--  @elseif(config('app.env') == "development")--}}
{{--    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':--}}
{{--      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],--}}
{{--      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=--}}
{{--      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);--}}
{{--      })(window,document,'script','dataLayer','GTM-MLLXRGTK');</script>--}}
  @endif
	<!-- End Google Tag Manager -->
</head>
<body>

	<!-- Google Tag Manager (noscript) -->
  @if(!config('app.debug'))
    <noscript>
      <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-ML7649C" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
  @elseif(config('app.env') == "development")
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MLLXRGTK"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  @endif
	<!-- End Google Tag Manager (noscript) -->

	<!---------------- header start --------------->
	<header>
    @include('new_web.layouts.tickers')
		<div class="container" id="checkout-header">
			<div class="header-wrap">
				<div class="header-logo">
					<a href="{{ config('app.url') }}"><img src="{{cdn('new_cart/images/kc-logo.svg')}}" alt="KC Logo"></a>
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

            <br/>

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
  <script src="{{cdn(mix('js/new_cart_app.js'))}}" type="text/javascript" charset="utf-8"></script>

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


@if(isset($tigran) && !config('app.debug'))

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
<script src="{{ cdn(mix('theme/assets/js/marquee3k.js')) }}" > </script>
<script>
  setTimeout(function(){

    Marquee3k.init()

  },500);
</script>
@stack('scripts')
@stack('components-scripts')
<style>
  /* Ticker */
  .carouselTicker-wrap{
  background-color: #f53000;
  }
  #header.has_ticker {
  top: 47px;
  }

  #header.has_countdown {
  top: 92px;
  }

  #header.has_ticker.has_countdown{
  top: 141px;
  }

  /* marquee ticker */
  .marquee3k__copy {
  padding: 0.65rem;
  }

  .carouselTicker-wrap p,
  .carouselTicker-wrap h1,
  .carouselTicker-wrap h2,
  .carouselTicker-wrap h3,
  .carouselTicker-wrap h4,
  .carouselTicker-wrap h5,
  .carouselTicker-wrap a,
  .carouselTicker-wrap a:visited
  {
  color: #fff !important;
  display: inline;
  }

</style>
</body>
</html>

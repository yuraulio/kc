<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>KnowCrunch Billing</title>
	<link rel="icon" type="image/png" href="{{cdn('new_cart/images/logo_favicon.png')}}">		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">	
	<link rel="stylesheet" type="text/css" href="{{cdn('new_cart/css/style.css')}}">
</head>
<body>
	<!---------------- header start --------------->
	<header>
		<div class="container">
			<div class="header-wrap">
				<div class="header-logo">
					<a href="javascript:;"><img src="{{cdn('new_cart/images/kc-logo.svg')}}" alt="KC Logo"></a>
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

	@stack('scripts')

</body>
</html>
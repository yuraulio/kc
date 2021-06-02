@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<!-- @inject('cFieldLib', 'Library\CustomFieldHelperLib') -->
<head>
<style>
@import url('https://fonts.googleapis.com/css?family=Space+Mono:400,400i,700,700i');

*{
  box-sizing:border-box;
  font-family: 'Space Mono', monospace;
}

body, html{
  margin:0;
  padding:0;
  height: 100%;
  width: 100%;
}
/* body{
  display: flex;
  justify-content: center;
  align-items: center;
  display: -webkit-flex;
  -webkit-align-items: center;
  -webkit-justify-content: center;
   background-image: linear-gradient(to right top, #a2beeb, #8ecfee, #8edde5, #a4e7d5, #c7eec7);
  flex-direction: column;
  -webkit-flex-direction: column;
} */
.title {
    margin-bottom: 30px;
    color: #162969;
}


.card{
width: 320px;
height: 190px;
  -webkit-perspective: 600px;
  -moz-perspective: 600px;
  perspective:600px;
  
}

.card__part{
  box-shadow: 1px 1px #aaa3a3;
    top: 0;
  position: absolute;
z-index: 1000;
  left: 0;
  display: inline-block;
    width: 320px;
    height: 190px;
    background-image: url('https://image.ibb.co/bVnMrc/g3095.png'), linear-gradient(to right bottom, #fd696b, #fa616e, #f65871, #f15075, #ec4879); /*linear-gradient(to right bottom, #fd8369, #fc7870, #f96e78, #f56581, #ee5d8a)*/
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    border-radius: 8px;
   
    -webkit-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -moz-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -ms-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -o-transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transition: all .5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    -webkit-transform-style: preserve-3d;
    -moz-transform-style: preserve-3d;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
}

.card__front{
  padding: 18px;
-webkit-transform: rotateY(0);
-moz-transform: rotateY(0);
}

.card__back {
  padding: 18px 0;
-webkit-transform: rotateY(-180deg);
-moz-transform: rotateY(-180deg);
}

.card__black-line {
    margin-top: 5px;
    height: 38px;
    background-color: #303030;
}

.card__logo {
    height: 16px;
}

.card__front-logo{
      position: absolute;
    top: 18px;
    right: 18px;
}
.card__square {
    border-radius: 5px;
    height: 30px;
}

.card_numer {
    display: block;
    width: 100%;
    word-spacing: 4px;
    font-size: 20px;
    letter-spacing: 2px;
    color: #fff;
    text-align: center;
    margin-bottom: 20px;
    margin-top: 20px;
}

.card__space-75 {
    width: 75%;
    float: left;
}

.card__space-25 {
    width: 25%;
    float: left;
}

.card__label {
    font-size: 10px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.8);
    letter-spacing: 1px;
}

.card__info {
    margin-bottom: 0;
    margin-top: 5px;
    font-size: 16px;
    line-height: 18px;
    color: #fff;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.card__back-content {
    padding: 15px 15px 0;
}
.card__secret--last {
    color: #303030;
    text-align: right;
    margin: 0;
    font-size: 14px;
}

.card__secret {
    padding: 5px 12px;
    background-color: #fff;
    position:relative;
}

.card__secret:before{
  content:'';
  position: absolute;
top: -3px;
left: -3px;
height: calc(100% + 6px);
width: calc(100% - 42px);
border-radius: 4px;
  background: repeating-linear-gradient(45deg, #ededed, #ededed 5px, #f9f9f9 5px, #f9f9f9 10px);
}

.card__back-logo {
    position: absolute;
    bottom: 15px;
    right: 15px;
}

.card__back-square {
    position: absolute;
    bottom: 15px;
    left: 15px;
}

.card:hover .card__front {
    -webkit-transform: rotateY(180deg);
    -moz-transform: rotateY(180deg);

}

.card:hover .card__back {
    -webkit-transform: rotateY(0deg);
    -moz-transform: rotateY(0deg);
}

.card{
    margin-top: 20vh;
    margin-bottom: 20vh;
}
</style>

</head>
<?php //dd($card); ?>
<div class="page-helper contact-help mob-help" id="cart-page"></div>
	<div id="main-content-body" style="margin-top:10%;" class="cart-page">

	<div style="" class="container">
		

		<div class='row'>
		
			<div style="width:50%;" class='col-md-6 col-xl-6 col-sm-6'>
        <h1>Add card</h1>
          <form action="{!!route('card.store')!!}" method="post" id="payment-form">
            {{ csrf_field() }}
            <input type="hidden" name="currentcard" value="0">
            <div class="form-row">
              <label for="card-element">
              Credit or debit card
              </label>
              <div id="card-element">
              <!-- A Stripe Element will be inserted here. -->

              </div>

              <!-- Used to display Element errors. -->
              <div id="card-errors" role="alert"></div>
            </div>

            <button>Save</button>
          </form>
			</div>

			

		</div>

	</div>
@endsection
@section('scripts')
	
	<script src="https://js.stripe.com/v3/"></script>
	
	<script>
	$(document).ready(function(){
		var stripe = Stripe('pk_test_51IdYeZHnPmfgPmgKTof5KXhKD57pWeafdfoG3FW4z2LF88vmVyB66LKSt0jufnTNPEUpMW9MZ0RpsJcSsli07OsQ00A28AoS5Z');
		var elements = stripe.elements();
		
		// Custom styling can be passed to options when creating an Element.
		var style = {
		base: {
			// Add your base input styles here. For example:
			fontSize: '16px',
			color: '#32325d',
		},
		};

		// Create an instance of the card Element.
		var card = elements.create('card', {style: style});

		// Add an instance of the card Element into the `card-element` <div>.
		card.mount('#card-element');
		

	
			// Create a token or display an error when the form is submitted.
			var form = document.getElementById('payment-form');
			form.addEventListener('submit', function(event) {
			event.preventDefault();

			stripe.createToken(card).then(function(result) {
				if (result.error) {
				// Inform the customer that there was an error.
				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = result.error.message;
				} else {
					//alert(result.token)
				// Send the token to your server.
				stripeTokenHandler(result.token);
				}
			});
			});
		

		function stripeTokenHandler(token) {
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');
		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);

		// Submit the form
		form.submit();
		}

	});


	
	</script>
@stop
<script>





</script>
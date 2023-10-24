@extends('theme.cart.new_cart.master')

@section('content')

<style>
	/* Variables */
* {
  box-sizing: border-box;
}


#payment-form {
  /* width: 100vw; */
  /* min-width: 500px; */
  align-self: center;
  /* box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
    0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07); */
  border-radius: 7px;
  /* padding: 40px; */
}

.hidden {
  display: none;
}

#iban-element {
	width: -webkit-fill-available;
	width: -moz-available;
}

#payment-message {
  color: rgb(105, 115, 134);
  font-size: 16px;
  line-height: 20px;
  padding-top: 12px;
  text-align: center;
}

#payment-element {
  margin-bottom: 24px;
}

/* Buttons and links */
#payment-form button {
  background: #5469d4;
  font-family: Arial, sans-serif;
  color: #ffffff;
  border-radius: 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
  width: 100%;
}
#payment-form button:hover {
  filter: contrast(115%);
}
#payment-form button:disabled {
  opacity: 0.5;
  cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}
.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}
.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #5469d4;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #5469d4;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media only screen and (max-width: 600px) {
  form {
    width: 80vw;
    min-width: initial;
  }
}
</style>

<!---------------- checkout progress-bar start --------------->
<div class="suscription checkout-step">
		<div class="container">
			<ul>
				<li><span class="counter">1.</span><i>Billing</i></li>
				<li class="active"><span class="counter">2.</span><i>Checkout</i></li>
			</ul>
		</div>
	</div>
<!---------------- checkout progress-bar end --------------->
@if(\Session('dperror'))
   <div class="alert-outer">
			<div class="container">
				<div class="alert-wrapper error-alert">
					<div class="alert-inner">

						<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">{{\Session('dperror')}}.</p>
						<a href="javascript:void(0)" class="close-alert"><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-close-alert.svg')}}" alt="Close Alert"/></a>
					</div>
				</div>
			</div>
		<!-- /.alert-outer -->
		</div>
    <?php Session::forget('dperror') ?>
   @endif
<div class="form-wrap">
	<div class="container padding-no">
        <h1 class="hidden-xs">Checkout</h1>
			<div class="row">

        	<div class="col-md-6 col-xl-6 selection-order">
			<h1 class="hidden-lg">Checkout</h1>

				<div class="checkout-full-wrap">

				
					<form id="checkout-form" action="{{route('subscription.store',[$event->title,$plan->name])}}" method="post">
						@csrf

						
						<input type="hidden" id="payment_method" name="payment_method" value="">
					</form>
					

					<h2>Payment method</h2>

					<div class="tab card-information flex-container">
						<button type="button" class="tablinks btn-4 btn-outline-dark Tab active" onclick="openPaymentMethod(event, 'card')">
							<img src="{{cdn('new_cart/images/credit-card.png')}}" width="30px" height="20px" class="without-hover" alt="">	
							<p class="payment-title p-TabLabel">DEBIT / CREDIT CARD</p>	
						</button>
						<button type="button" class="tablinks btn-4 btn-outline-dark Tab" onclick="openPaymentMethod(event, 'digital-wallets')">
							<img src="{{cdn('new_cart/images/wallet.png')}}" width="30px" height="20px" class="without-hover" alt="">	
							<p class="payment-title p-TabLabel">APPLE / GOOGLE WALLET</p>
						</button>
						<button type="button" class="tablinks btn-4 btn-outline-dark Tab" onclick="openPaymentMethod(event, 'sepa')">
						<img src="{{cdn('new_cart/images/sepa-2.svg')}}" width="30px" height="20px" class="without-hover" alt="">	
							<p class="payment-title p-TabLabel">SEPA DIRECT DEBIT</p>
						</button>
					</div>

					<div id="card" class="tabcontent card-information" style="display:block;">

							<div class="card-info">

								<p class="info">Submit your card data and proceed securely with your transaction. We do not store your card's data. </p>
								
								<div class="card-input"><div id="card-element"></div></div>
								
								<div class="form-row my-5 align-items-center prev-next-wrap">
									<div class="d-flex align-items-center previous-participant-link">
										<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
										<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
										<a href="/billing" class="link-color">Previous: Billing</a>
									</div>
									<button id="pay-now" type="button" class="btn btn-3 checkout-button-primary">Pay now</button>
								</div>
							</div>

						</div>

						<div id="digital-wallets" class="tabcontent card-information">

							<!-- DIGITAL WALLETS BUTTON  START START START -->

							<div class="card-info">
							<p class="info">Choose your preferred digital wallet and proceed securely with your transaction. </p>
								<!-- <div id="payment-request-button"></div> -->
								<!-- <div id="error-message"></div> -->
								<div class="form-row my-5 align-items-center prev-next-wrap">
									<div class="d-flex align-items-center previous-participant-link">
										<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
										<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
										<a href="/billing" class="link-color">Previous: Billing</a>

										
									</div>
									<div id="payment-request-button"><!-- A Stripe Element will be inserted here. --></div>
									
								</div>
								
							</div>

							<!-- DIGITAL WALLETS BUTTON  END END END -->

						</div>

						<div id="sepa" class="tabcontent card-information">
							<form action="/charge" method="post" class="card-info" id="payment-form">

								<p class="info">Please provide your IBAN so we can directly debit your EU bank account and ensure a secure transaction. Kindly note that a SEPA payment may take up to 14 days to process. Your access will be granted once we have confirmation of your payment in our system. </p>
								
								<div class="">
									<div class="">
									<label for="accountholder-name">
										Bank account
									</label>
									<input
										class="card-input-cardholder"
										id="accountholder-name"
										name="accountholder-name"
										placeholder="Please enter your account name as registered with your bank."
										required
									/>
									</div>

									<div class="col d-none">
										<label for="email">
											Email Address
										</label>
										
										<input
											id="email"
											name="email"
											type="email"
											placeholder="email"
											value="{{$pay_bill_data['billemail']}}"
											required
										/>
									</div>
								</div>

								<div class="">
									<div class="">
										<!--
										Using a label with a for attribute that matches the ID of the
										Element container enables the Element to automatically gain focus
										when the customer clicks on the label.
										-->
										<label for="iban-element1">
										IBAN
										</label>
										<div id="iban-element" class="card-input"><!-- A Stripe Element will be inserted here. --></div>
									</div>

									
								</div>
								

								<div class="form-row my-3 align-items-center prev-next-wrap">
									<div class="d-flex align-items-center previous-participant-link">
										<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
										<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
										<a href="/billing" class="link-color">Previous: Billing</a>
									</div>
									<button id="submit-button" data-secret="" class="btn btn-3 checkout-button-primary">Pay Now</button>
								</div>


								<!-- Display mandate acceptance text. -->
								<div id="mandate-acceptance">
							
								</div>
								<!-- Used to display form errors. -->
								<div id="error-message" role="alert"></div>
							</form>
						</div>
				
				</div>
			</div>
			@include('theme.cart.new_cart.subscription.selection')
		</div>
	</div>
</div>
@stop



@push('scripts')
<script src="https://js.stripe.com/v3/"></script>

<script>

    const stripe = Stripe('{{$stripe_key}}',{
		locale: 'en',
		apiVersion: "2022-11-15",
	});

	function openPaymentMethod(evt, paymentMethod) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(paymentMethod).style.display = "block";
		evt.currentTarget.className += " active";
	}

    const elements = stripe.elements({
    	fonts: [
    	    {
					family: 'Foco',
					src: `url("/new_cart/font/Foco-Light.woff2") format("woff2"),
							url("/new_cart/font/Foco-Light.woff") format("woff")`,
					weight: '100',
			   	},
    	]
	});
    var cardElement = elements.create('card',{

		style: {
           base: {
            	fontSize: '18px',
				fontFamily: 'Foco',
          },
        },

        hidePostalCode: true,
    });

    cardElement.mount('#card-element');

	const cardButton = document.getElementById('pay-now');
	cardButton.addEventListener('click', async (e) => {
        $("#card-error").remove();

        $("#pay-now").prop('disabled',true);

    	const { paymentMethod, error } = await stripe.createPaymentMethod(
    	    'card', cardElement
    	);

    	if (error) {
    	    // Display "error.message" to the user...
            $('<p id="card-error">Enter valid data.</p>').insertAfter('.card-input')
            $("#pay-now").prop('disabled',false);
    	} else {
			$('#payment_method').val(paymentMethod.id);

			$("#checkout-form").submit();

    	}
	});
	

	//DIGITAL WALLET
	const paymentRequest = stripe.paymentRequest({
		country: 'US',
		currency: 'eur',
		total: {
			label: @json($event->title),
			amount: 20000,
		},
		requestPayerName: false,
		requestPayerEmail: false,
	});

	updateAmount()


	const elements2 = stripe.elements();
	const prButton = elements2.create('paymentRequestButton', {
		paymentRequest: paymentRequest,
	});

	(async () => {
	// Check the availability of the Payment Request API first.
	const result = await paymentRequest.canMakePayment();
	

	if (result) {

		prButton.mount('#payment-request-button');
	} else {

		document.getElementById('payment-request-button').style.display = 'none';
	}
	})();

	paymentRequest.on('paymentmethod', async (ev) => {
		

		//await apiRequest('/walletPaySubscription/', ev.paymentMethod.id)
		await apiRequest(`/walletPaySubscription`, ev.paymentMethod.id)

		

	});

	async function apiRequest(url, payment_method){
		$("#card-error").remove();
		let a;

		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: url,
			async:false,  
			data:{
				payment_method: payment_method,
				event: @json($event->title),
				plan: '{{$plan->name}}'
			},  
			success: function(data) {
				//a = data
				window.location = data
			},
			error: function(data){

				location.reload()

			}
		})
		
		return a;
	}

	//END DIGITAL WALLET

	//SEPA
	const elements3 = stripe.elements({
		fonts: [
    	    {
					family: 'Foco',
					src: `url("new_cart/font/Foco-Light.woff2") format("woff2"),
							url("new_cart/font/Foco-Light.woff") format("woff")`,
					weight: '100',
			   	},
    	]
	});
	const style = {
		base: {
			color: '#32325d',
			fontFamily: 'Foco',
			fontSize: '18px',
			'::placeholder': {
				color: '#aab7c4'
			},
			':-webkit-autofill': {
				color: '#32325d',
			},
		},
		invalid: {
			color: '#fa755a',
			iconColor: '#fa755a',
			':-webkit-autofill': {
			color: '#fa755a',
			},
		},
		};

		const options = {
			style,
			supportedCountries: ['SEPA'],
			// Elements can use a placeholder as an example IBAN that reflects
			// the IBAN format of your customer's country. If you know your
			// customer's country, we recommend passing it to the Element as the
			// placeholderCountry.
			placeholderCountry: 'GR',
		};

		// Create an instance of the IBAN Element
		const iban = elements3.create('iban', options);

		// Add an instance of the IBAN Element into the `iban-element` <div>
		iban.mount('#iban-element');

		const form = document.getElementById('payment-form');
		const accountholderName = document.getElementById('accountholder-name');
		const email = document.getElementById('email');
		const submitButton = document.getElementById('submit-button');

		if($(email).val() == ''){
			$(email).val('{{$cur_user["email"]}}')
		}
		

		form.addEventListener('submit', async (event) => {
			event.preventDefault();
			$('#error-message').text('')
			submitButton.disabled = true;

			const { paymentMethod, error1 } = await stripe.createPaymentMethod({
					type: 'sepa_debit',
					sepa_debit: iban,
					billing_details: {
							name: accountholderName.value,
							email: email.value,
						},
				});

			if(!paymentMethod){
				submitButton.disabled = false;
			}

			return_data = await createIntent('/createSepaSubscription', paymentMethod.id)

			var clientSecret = document.getElementById('submit-button');
			
			clientSecret = $(clientSecret).attr('data-secret')


			if(clientSecret != ''){
				stripe.confirmSepaDebitPayment(
					clientSecret,
					{
						payment_method: {
							sepa_debit: iban,
							billing_details: {
								name: accountholderName.value,
								email: email.value,
							},
						}, 
					}).then(function(result){

						if(result.error){
							
							submitButton.disabled = false;
						}
						if(result.paymentIntent){

							window.location.href = return_data.return_url;
						}
					});
			}else{
				submitButton.disabled = false;
				$('#error-message').css('color', 'red')
				$('#error-message').text('Please try again!!')
			}
			
			
			


		});


		async function createIntent(url, payment_method){
			let data1 = {};
			let return_url;

			$.ajax({
				headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST',
				url: url,
				async:false,  
				data:{
					payment_method: payment_method,
					event: '{{$event->title}}',
					plan: '{{$plan->name}}'
				},  
				success: function(data) {
					
					$('#submit-button').attr('data-secret', JSON.parse(data)['clientSecret'])
					
					data1['return_url'] = JSON.parse(data)['return_url']
					
				}
			})
		
			return data1;
		}
											
	


	//END SEPA


	$(".close-alert").on("click", function () {

		$('.alert-outer').hide()

	});

	$('form').submit(function() {
	$("#pay-now").prop('disabled',true);
	});

	async function updateAmount(){
		total = await getTotalCart()
		
		paymentRequest.update({
			total: {
				label: @json($event->title),
				amount: Math.round(total),
			},
			
		});
	}

	async function getTotalCart(){
		let total = null;

		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: '/getTotalCartSubscription',
			async:false,    
			data:{
				plan: '{{$plan->name}}',
			},
			success: function(data) {
				total = data
			}
		})

		//console.log('return from func: ', total)
		return total;

	}

</script>



@endpush

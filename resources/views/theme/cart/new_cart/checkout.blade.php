@extends('theme.cart.new_cart.master')

@section('content')

<!---------------- checkout progress-bar start --------------->
<div class="checkout-step">
		<div class="container">
			<ul>
				<li><span class="counter">1.</span><i>Participant(s)</i></li>
				<li><span class="counter">2.</span><i>Billing</i></li>
				<li class="active"><span class="counter">3.</span><i>Checkout</i></li>
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
        	<?php

                $availableInstallments = $installments;
                $installments = [];

                $count = 1;
                if($availableInstallments != 0){

                    while($count <= $availableInstallments){
                        if($count == 1){
                            $installments[$count] = $price;

                        }else if($count > 1){
                            $installments[$count] = round($price / $count, 2);
                        }
                        $count++;
                    }

                }

                foreach($installments as $inst_num => $installment){
                    if($inst_num != 0){
                        if($installment - floor($installment)>0){
                            $installments[$inst_num] = number_format($installments[$inst_num] , 2 , '.', ',');
                        }else{
                            $installments[$inst_num] = number_format($installments[$inst_num] , 0 , '.', ',');
                        }
                    }

                }

        	?>
        	<div class="col-md-6 col-xl-6 selection-order">
			<h1 class="hidden-lg">Checkout</h1>

				<div class="checkout-full-wrap">
					<h2>Payment flexibility</h2>
					<p {{--class="my-4"--}}>Some international credit cards and AMEX cards do not accept installments.</p>
					<form id="checkout-form" action="{{route('userPaySbt')}}" method="post">
						@csrf
						<div class="radio-group">
                            @if(isset($installments) && !empty($installments) && count($installments) > 1)
                                @foreach($installments as $inst_num => $installment)
                                    @if($inst_num == 1)
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio{{$inst_num}}" name="installments" value="{{$inst_num}}" checked="" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio{{$inst_num}}"></label><p id="inst{{$inst_num}}">I will pay in full: €{{$showPrice}}</p>
                                        </div>
                                    @else
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio{{$inst_num}}" name="installments" value="{{$inst_num}}" class="custom-control-input">
                                            <label class="custom-control-label" for="customRadio{{$inst_num}}"></label><p id="inst{{$inst_num}}">I will pay in {{$inst_num}} installments: {{$inst_num}}x €{{$installment}}</p>
                                        </div>
                                    @endif

                                @endforeach
                            @endif

						</div>

						<div class="card-info">
							<!-- <div id="payment-request-button"></div> -->
							<!-- <div id="error-message"></div> -->
							<div id="payment-request-button">
							<!-- A Stripe Element will be inserted here. -->
							</div>
							<hr>
						</div>
						
						<div class="card-info">
							
							<h2>Card information</h2>
							
							<div class="card-input">
    		                    <div id="card-element"></div>
							
								
							</div>

							<p>We do not store your card’s information.</p>
							<div class="form-row my-5 align-items-center prev-next-wrap">
								<div class="d-flex align-items-center previous-participant-link">
									<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
									<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
									<a href="/billing" class="link-color">Previous: Billing</a>
								</div>
								<button id="pay-now" type="button" class="btn btn-3 checkout-button-primary">Pay now</button>
							</div>
						</div>
    		            <input type="hidden" id="payment_method_id" name="payment_method_id" value="{{$pay_methods['id']}}">
						<input type="hidden" id="payment_method" name="payment_method" value="">
					</form>



				<hr>

				<form action="/charge" method="post" id="payment-form">
					<div class="form-row inline">
						<div class="col">
						<label for="accountholder-name">
							Name
						</label>
						<input
							id="accountholder-name"
							name="accountholder-name"
							placeholder="Jenny Rosen"
							required
						/>
						</div>

						<div class="col">
						<label for="email">
							Email Address
						</label>
						<input
							id="email"
							name="email"
							type="email"
							placeholder="jenny.rosen@example.com"
							required
						/>
						</div>
					</div>

					<div class="form-row">
						<!--
						Using a label with a for attribute that matches the ID of the
						Element container enables the Element to automatically gain focus
						when the customer clicks on the label.
						-->
						<label for="iban-element1">
						IBAN
						</label>
						<div id="iban-element">
						<!-- A Stripe Element will be inserted here. -->
						</div>
					</div>

					<!-- Add the client_secret from the PaymentIntent as a data attribute   -->
					<button id="submit-button" data-secret="">Submit Payment</button>

					<!-- Display mandate acceptance text. -->
					<div id="mandate-acceptance">
						By providing your payment information and confirming this payment, you
						authorise (A) asd and Stripe, our payment service provider
						and/or PPRO, its local service provider, to send instructions to your
						bank to debit your account and (B) your bank to debit your account in
						accordance with those instructions. As part of your rights, you are
						entitled to a refund from your bank under the terms and conditions of
						your agreement with your bank. A refund must be claimed within 8 weeks
						starting from the date on which your account was debited. Your rights
						are explained in a statement that you can obtain from your bank. You
						agree to receive notifications for future debits up to 2 days before
						they occur.
					</div>
					<!-- Used to display form errors. -->
					<div id="error-message" role="alert"></div>
				</form>






				</div>
			</div>
			@include('theme.cart.new_cart.selection')
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

	const elements = stripe.elements({
    	fonts: [
    	    {
					family: 'Foco',
					src: `url("new_cart/font/Foco-Light.woff2") format("woff2"),
							url("new_cart/font/Foco-Light.woff") format("woff")`,
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
			$('<p id="card-error">Enter valid data.</p>').insertAfter('.card-input')
			$("#pay-now").prop('disabled',false);
    	} else {

			$('#payment_method').val(paymentMethod.id);

			$("#checkout-form").submit();

    	}

	});


	$(".close-alert").on("click", function () {

		$('.alert-outer').hide()

	});


	$('form').submit(function() {
	$("#pay-now").prop('disabled',true);
	});


	//DIGITAL WALLET
	const paymentRequest = stripe.paymentRequest({
	country: 'US',
	currency: 'eur',
	total: {
		label: @json($productName),
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

	// prButton.addEventListener("click", async (event) => {
	// 	alert('asd')
		

	// 	total = await getTotalCart();
	// 	console.log('total: ', total)

	// 	paymentRequest.update({
			
	// 		total: {
	// 			label: 'Demo total',
	// 			amount: total,
	// 		},
			
	// 	});


	// });

	paymentRequest.on('paymentmethod', async (ev) => {

		$('#payment_method').val(ev.paymentMethod.id);

		await apiRequest('/walletPay', ev.paymentMethod.id)


		// if (confirmError) {
		// 	// Report to the browser that the payment failed, prompting it to
		// 	// re-show the payment interface, or show an error message and close
		// 	// the payment interface.
		// 	ev.complete('fail');
		// } else {
		// 	alert('success')
		// 	// Report to the browser that the confirmation was successful, prompting
		// 	// it to close the browser payment method collection interface.
		// 	ev.complete('success');
			
		// 	// Check if the PaymentIntent requires any actions and, if so, let Stripe.js
		// 	// handle the flow. If using an API version older than "2019-02-11"
		// 	// instead check for: `paymentIntent.status === "requires_source_action"`.
		// 	if (paymentIntent.status === "requires_action") {
		// 	// Let Stripe.js handle the rest of the payment flow.
		// 	const {error} = await stripe.confirmCardPayment(clientSecret);
		// 	if (error) {
		// 		// The payment failed -- ask your customer for a new payment method.
		// 	} else {
		// 		// The payment has succeeded.
		// 	}
		// 	} else {
		// 	// The payment has succeeded.
		// 	}
		// }
	});

	//END DIGITAL WALLET

	fetchIntent('/createSepa')

	//SEPA
	const elements3 = stripe.elements();
	const style = {
		base: {
			color: '#32325d',
			fontSize: '16px',
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
			placeholderCountry: 'DE',
		};

		// Create an instance of the IBAN Element
		const iban = elements3.create('iban', options);

		// Add an instance of the IBAN Element into the `iban-element` <div>
		iban.mount('#iban-element');

		const form = document.getElementById('payment-form');
		const accountholderName = document.getElementById('accountholder-name');
		const email = document.getElementById('email');
		const submitButton = document.getElementById('submit-button');
		const clientSecret = submitButton.dataset.secret;

		form.addEventListener('submit', (event) => {
		event.preventDefault();
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
			}
		);
		});


		async function fetchIntent(url){
		let a;
		let installments = 1
		installments = $('input[type=radio][name=installments]:checked').val();

		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: url,
			async:false,  
			data:{
				
			},  
			success: function(data) {
				
				$('#submit-button').attr('data-secret', JSON.parse(data)['clientSecret'])
				
				
			}
		})
		
		return a;
	}
											
	


	//END SEPA



	async function apiRequest1(url){
		
		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: url,
			async:false,  
			data:{},  
			success: function(data) {
				
				console.log(data)

				
			}
		})
		
		return a;
	}

	async function apiRequest(url, payment_method){
		let a;
		let installments = 1
		installments = $('input[type=radio][name=installments]:checked').val();

		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: url,
			async:false,  
			data:{
				installments: installments,
				payment_method_id: "{{$pay_methods['id']}}",
				payment_method: payment_method
			},  
			success: function(data) {
				
				//a = data
				window.location = data
			}
		})
		
		return a;
	}

	async function getTotalCart(){
		let total = null;
		let installments = $('input[type=radio][name=installments]:checked').val();

		$.ajax({
			headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			method: 'POST',
			url: '/getTotalCart',
			async:false,    
			data:{
				installments: installments,
				payment_method_id: "{{$pay_methods['id']}}"
			},
			success: function(data) {
				total = data
			}
		})

		//console.log('return from func: ', total)
		return total;

	}


	$('input[type=radio][name=installments]').change(function() {
		updateAmount()
	});

	async function updateAmount(){
		total = await getTotalCart()
		
		paymentRequest.update({
			total: {
				label: @json($productName),
				amount: Math.round(total),
			},
			
		});
	}


</script>

@if(isset($tigran) && !env('APP_DEBUG'))
<script>
$(document).ready(function(){
		$("#pay-now").click(function(){
			dataLayer.push({'Event_ID':"{{$tigran['Event_ID']}}", 'event': 'AddPaymentInfo', 'Product_id' : "{{$tigran['Product_id']}}", 'Price': "{{$tigran['Price']}}",'ProductCategory':"{{$tigran['ProductCategory']}}","product":"product"});
		})

		
})
</script>

<script type="text/javascript">
$(document).ready(function(){
dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
dataLayer.push({
    'event': 'checkout',
    'ecommerce': {
      'checkout': {
		'actionField': {'step': 1, 'option': 'Stripe'},
  		'products': [{
			'name': $.parseHTML("{{ $tigran['ProductName'] }}")[0].data,
        	'id': "{{$tigran['Product_id']}}",
        	'price': "{{$tigran['Price']}}",
        	'brand': 'Knowcrunch',
        	'category': "{{$tigran['ProductCategory']}}",
  		  	'quantity': "{{$totalitems}}"
       	}]
     }
   }
});

dataLayer.push({
    'event': 'add_payment_info',
    'currency': 'EUR',
	'value': "{{$tigran['Price']}}",
	'items': [{
		'item_id': "{{$tigran['Product_id']}}",
		'item_name': $.parseHTML("{{ $tigran['ProductName'] }}")[0].data,
		'item_brand': 'Knowcrunch',
		'item_category': "{{$tigran['ProductCategory']}}",
		'price': "{{$tigran['Price']}}",
		'quantity': "{{$totalitems}}"
	}]
});

})
</script>



@endif



@endpush

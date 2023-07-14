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
							<div id="express-checkout-element">
  <!-- Express Checkout Element will be inserted here -->
</div>
<div id="error-message">
  <!-- Display error message to your customers here -->
</div>
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

			console.log('payment: ',paymentMethod.id)
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

	


// 	const paymentRequest = stripe.paymentRequest({
// 		country: 'GR',
// 		currency: 'eur',
// 		total: {
// 			label: 'Demo total',
// 			amount: 1099,
// 		},
// 		requestPayerName: true,
// 		requestPayerEmail: true,
// 	});

// 	//const elements = stripe.elements();
// const prButton = elements.create('paymentRequestButton', {
//   paymentRequest,
// });

// (async () => {
//   // Check the availability of the Payment Request API first.
//   const result = await paymentRequest.canMakePayment();
//   if (result) {
//     prButton.mount('#payment-request-button');
//   } else {
//     document.getElementById('payment-request-button').style.display = 'none';
//   }
// })();



// $('#primary').on('click', async (event) => {
// 	alert('HERE')
	

// 	event.resolve()

// });


const paymentRequest = stripe.paymentRequest({
  country: 'GR',
  currency: 'eur',
  total: {
    label: 'Demo total',
    amount: 20000,
  },
  requestPayerName: false,
  requestPayerEmail: false,
});

updateAmount()

const elements2 = stripe.elements();
const prButton = elements.create('paymentRequestButton', {
  paymentRequest,
});

(async () => {
  // Check the availability of the Payment Request API first.
  const result = await paymentRequest.canMakePayment();
  if (result) {
	console.log('1')
    prButton.mount('#payment-request-button');
  } else {
	console.log('2')
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


	console.log('//////')

		console.log(ev)
		$('#payment_method').val(ev.paymentMethod.id);

	console.log('//////')
	await apiRequest('/walletPay', ev.paymentMethod.id)

	
// 	clientSecret = await apiRequest1('/pay')
//   // Confirm the PaymentIntent without handling potential next actions (yet).
//   const {paymentIntent, error: confirmError} = await stripe.confirmCardPayment(
//     clientSecret,
//     {payment_method: ev.paymentMethod.id},
//     {handleActions: false}
//   );


  if (confirmError) {
    // Report to the browser that the payment failed, prompting it to
    // re-show the payment interface, or show an error message and close
    // the payment interface.
    ev.complete('fail');
  } else {
    // Report to the browser that the confirmation was successful, prompting
    // it to close the browser payment method collection interface.
    ev.complete('success');
	
    // Check if the PaymentIntent requires any actions and, if so, let Stripe.js
    // handle the flow. If using an API version older than "2019-02-11"
    // instead check for: `paymentIntent.status === "requires_source_action"`.
    if (paymentIntent.status === "requires_action") {
      // Let Stripe.js handle the rest of the payment flow.
      const {error} = await stripe.confirmCardPayment(clientSecret);
      if (error) {
        // The payment failed -- ask your customer for a new payment method.
      } else {
        // The payment has succeeded.
      }
    } else {
      // The payment has succeeded.
	  alert('asd')
    }
  }
});


const options = {
  mode: 'payment',
  amount: 1099,
  currency: 'eur',
  // Customizable with appearance API.

};

// Set up Stripe.js and Elements to use in checkout form
const elements1 = stripe.elements(options);

// Create and mount the Express Checkout Element
const expressCheckoutElement = elements1.create('expressCheckout');
expressCheckoutElement.mount('#express-checkout-element');

const handleError = (error) => {
  const messageContainer = document.querySelector('#error-message');
  messageContainer.textContent = error.message;
}

//let clientSecret = 'pi_3NT2kYHnPmfgPmgK1LZvCEM1_secret_F6i5DNN81iu3tig6fn7EE1QPH';
console.log('pre confirm')
	

	expressCheckoutElement.on('click', async (event) => {
		console.log(event)
		console.log('on click')
		let total = '123';

		total = await getTotalCart();

		console.log('total: ', total)
		elements1.update({amount: parseInt(total)});
		

		event.resolve()

	});

	expressCheckoutElement.on('confirm', async (event) => {
		console.log(event)
	})



	// expressCheckoutElement.on('confirm', async (event) => {
	// 	console.log('before create payment method')


		
	// 	// const { paymentIntent, paymentMethod } = event;

	// 	const {error, paymentMethod} = await stripe.createPaymentMethod({
	// 		type: event.expressPaymentType,
	// 		link: {},
			
	// 	})

	// 	console.log(paymentMethod)



	// 	// console.log(error,payme1)
	
	// 	//console.log('test')

	// 		// clientSecret = await apiRequest1('/pay')

	// 		// await stripe
	// 		// 	.confirmCardPayment(clientSecret, {
	// 		// 		payment_method: {
	// 		// 		card: elements1,
	// 		// 			billing_details: {
	// 		// 				name: 'Jenny Rosen',
	// 		// 			},
	// 		// 		},
	// 		// 	})
	// 		// 	.then(function(result) {
	// 		// 		console.log('results from wwhere')
	// 		// 		console.log(result)
	// 		// 		// Handle result.error or result.paymentIntent
	// 		// 	});

			

			



		
		
    // 	// if (error) {
	// 	// 	handleError(error)
			
			
    // 	// } else {

	// 	// 	console.log('payment: ',paymentMethod.id)
	// 	// 	$('#payment_method').val(paymentMethod.id);

			

    // 	// }



	// 	//await apiRequest('/walletPay')
	// 	event.resolve('completed')
		
	// 	/*
	// 	// Send the PaymentMethod ID to your server for additional logic and attach the PaymentMethod
	// 	let clientSecret = await apiRequest('/walletPay');

	// 	console.log('test4', clientSecret)
	// 	//clientSecret = 'pi_3NT3nwHnPmfgPmgK04ihvAea_secret_ImoqA2VsQUYX77YMQ88mUnRrJ'

	// 	console.log(clientSecret)

	// 	// Confirm the PaymentIntent
	// 	const {error: confirmError} = await stripe.confirmPayment({
	// 		elements,
	// 		clientSecret,
	// 		confirmParams: {
	// 		return_url: 'https://example.com/order/123/complete',
	// 		},
	// 	});

	// 	console.log('test5')

	// 	if (confirmError) {
	// 		// This point is only reached if there's an immediate error when
	// 		// confirming the payment. Show the error to your customer (for example, payment details incomplete)
	// 		const messageContainer = document.querySelector('#error-message');
	// 		messageContainer.textContent = error.message;
	// 	} else {
	// 		// The payment UI automatically closes with a success animation.
	// 		// Your customer is redirected to your `return_url`.
	// 	}
	// 	*/
		
	// });

	async function apiRequest1(url){
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
				payment_method: ''
			},  
			success: function(data) {
				
				a = data
				
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

console.log('return from func: ', total)
		return total;

	}


	$('input[type=radio][name=installments]').change(function() {
		updateAmount()

	});

	async function updateAmount(){
		total = await getTotalCart()
		console.log('total: ', Math.round(total))
		
		paymentRequest.update({
			total: {
				label: 'Demo total',
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

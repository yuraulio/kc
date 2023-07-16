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


	//DIGITAL WALLET
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
			alert('has error')
			// Report to the browser that the payment failed, prompting it to
			// re-show the payment interface, or show an error message and close
			// the payment interface.
			ev.complete('fail');
		} else {
			alert('success')
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
			}
		}
	});

	//END DIGITAL WALLET



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

		//console.log('return from func: ', total)
		return total;

	}


	$('input[type=radio][name=installments]').change(function() {
		updateAmount()
	});

	async function updateAmount(){
		console.log('111111')
		total = await getTotalCart()
		console.log('total: ', Math.round(total))
		
		paymentRequest.update({
			total: {
				label: 'Demo total',
				amount: Math.round(total),
			},
			
		});
		cosnole.log('22222')
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

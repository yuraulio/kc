@extends('theme.cart.new_cart.master')

@section('content')

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

						<div class="card-info">
							<!-- <div id="payment-request-button"></div> -->
							<div id="error-message">
							<!-- Display error message to your customers here -->
							</div>
							<div id="payment-request-button">
							<!-- A Stripe Element will be inserted here. -->
							</div>
							<hr>
						</div>

						<div class="card-info">
							<h2>Payment Information</h2>
							<div class="card-input">
    		                    <div id="card-element"></div>
							</div>
							<p>We do not store your card’s information.</p>
							<div class="form-row my-5 align-items-center prev-next-wrap">
								<div class="d-flex align-items-center previous-participant-link">
									<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
									<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
									<a href="/myaccount/subscription/{{$event->title}}/{{$plan->name}}" class="link-color">Previous: Billing</a>
								</div>
								<button id="pay-now" type="button" class="btn btn-3 checkout-button-primary do-checkout-subscription">Pay now</button>
							</div>
						</div>
						<input type="hidden" id="payment_method" name="payment_method" value="">
					</form>
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
		console.log('enabled')
		prButton.mount('#payment-request-button');
	} else {
		console.log('disabled')
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

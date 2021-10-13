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
	<div class="container">			
        <h1>Checkout</h1>				
			<div class="row">
        	
        	<div class="col-md-6 col-xl-6">
				<div class="checkout-full-wrap">												
					
					<form id="checkout-form" action="{{route('subscription.store',[$event->title,$plan->name])}}" method="post">
						@csrf
				
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

    	const { paymentMethod, error } = await stripe.createPaymentMethod(
    	    'card', cardElement
    	);

    	if (error) {
    	    // Display "error.message" to the user...
    	} else {
			$('#payment_method').val(paymentMethod.id);

			$("#checkout-form").submit();

    	}
	});


$(".close-alert").on("click", function () {

	$('.alert-outer').hide()

});

</script>



@endpush
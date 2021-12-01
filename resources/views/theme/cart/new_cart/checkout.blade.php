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
	<div class="container">			
        <h1>Checkout</h1>				
			<div class="row">
        	<?php
   
                $instOne = $price;
        	    $instTwo = round($price / 2, 2);
        	    $instThree = round($price / 3, 2);

				//$instTwo = number_format($instTwo , 2 , '.', '');
				//$instThree = number_format($instThree , 2 , '.', ',');


				if($instOne - floor($instOne)>0){
                    $instOne = number_format($instOne , 2 , '.', ',');
                }else{
                    $instOne = number_format($instOne , 0 , '.', ',');
                }

                if($instTwo - floor($instTwo)>0){
                    $instTwo = number_format($instTwo , 2 , '.', ',');
                }else{
                    $instTwo = number_format($instTwo , 0 , '.', ',');
                }

                if($instThree - floor($instThree)>0){
                    $instThree = number_format($instThree , 2 , '.', ',');
                }else{
                    $instThree = number_format($instThree , 0 , '.', ',');
                }

        	?>
        	<div class="col-md-6 col-xl-6">
				<div class="checkout-full-wrap">												
					<h2>Payment flexibility</h2>
					<p {{--class="my-4"--}}>Some international credit cards and AMEX cards do not accept installments.</p>
					<form id="checkout-form" action="{{route('userPaySbt')}}" method="post">
						@csrf
						<div class="radio-group">
							<div class="custom-control custom-radio mb-3">
								<input type="radio" id="customRadio1" name="installments" value="1" checked="" class="custom-control-input">
								<label class="custom-control-label" for="customRadio1"></label><p id="inst1">I will pay in full: €{{$showPrice}}</p>
							</div>
							<div class="custom-control custom-radio mb-3">
								<input type="radio" id="customRadio2" name="installments" value="2" class="custom-control-input">
								<label class="custom-control-label" for="customRadio2"></label><p id="inst2">I will pay in 2 installments: 2x €{{$instTwo}}</p>
							</div>
							<div class="custom-control custom-radio mb-3">
								<input type="radio" id="customRadio3" name="installments" value="3" class="custom-control-input">
								<label class="custom-control-label" for="customRadio3"></label><p id="inst3">I will pay in 3 installments: 3x €{{$instThree}}</p>
							</div>
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
    	const { paymentMethod, error } = await stripe.createPaymentMethod(
    	    'card', cardElement
    	);

    	if (error) {
			$('<p id="card-error">Enter valid data.</p>').insertAfter('.card-input')
    	} else {
			$('#payment_method').val(paymentMethod.id);

			$("#checkout-form").submit();

    	}
	});


$(".close-alert").on("click", function () {

	$('.alert-outer').hide()

});

</script>

@if(isset($tigran) && !env('APP_DEBUG'))
<script>

		$("#pay-now").click(function(){
			dataLayer.push({'Event_ID':"{{$tigran['Event_ID']}}", 'event': 'Add Payment Info', 'Product_id' : "{{$tigran['Product_id']}}", 'Price': "{{$tigran['Price']}}",'ProductCategory':"{{$tigran['ProductCategory']}}","product":"product"});	
		})

</script>

<script>

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
        	'brand': 'KnowCrunch',
        	'category': "{{$tigran['ProductCategory']}}",
  		  	'quantity': "{{$totalitems}}"
       	}]
     }
   }
});

</script>



@endif



@endpush
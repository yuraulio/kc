<div class="col-md-6 col-xl-6 padding-no selection-background">
					<div class="checkout-selection-wrap">
						
                        @foreach (Cart::content() as $item)
                        <div class="remove-from-cart">
							<a href="remove/{{ $item->rowId }}">Remove from cart <img src="{{cdn('new_cart/images/close-green.svg')}}" width="9px" height="10px"  class="without-hover">
								<img src="{{cdn('new_cart/images/close-green2.svg')}}" width="9px" height="10px" class="with-hover"></a>
						</div>
						<h2>My selection:</h2>
                    
						    <h3>{{ $item->name }}</h3>	
						    @if($duration)<datetime="YYYY-MM-DDThh:mm:ssTZD"><span class="datetime">{!!$duration!!}</span></datetime="YYYY-MM-DDThh:mm:ssTZD">@endif
						    <div class="checkout-price-wrap">
						    	<div class="checkout-price">
									@if($type=='waiting')
									<span class="waiting"> Waiting list. </span>
									@else
						    		<p>Price:</p><span>@if(is_numeric($price)) €{{$oldPrice}} @else {{$price}} @endif</span>
									@endif
						    	</div>
								@if(!$eventFree)
						    		<div class="checkout-participant">
						    			<p>Participant(s):</p><span>{{$totalitems}}</span>
						    		</div>
	
									@if(isset($priceOf))
									<div class="checkout-participant price-of">
						    			<p>Special offer:</p><span>{{$priceOf}}</span>
						    		</div>
									@endif
	
									@if(isset($savedPrice))
									<div class="checkout-participant">
						    			<p>You save:</p><span>{{$savedPrice}}</span>
						    		</div>
									@endif
	
						    		<div class="checkout-total">
						    			<p class="mb-0">Total amount:</p><span class="color-secondary checkout-total-amount">@if(is_numeric($price)) €{{$showPrice}} @else {{$price}} @endif </span>
						    		</div>
								@endif
						    </div>
                        @endforeach
						
						<div class="checkout-coupon-code-wrap">
							@if(!\Session::get('coupon_code') && !isset($show_coupon))			
							<h2 class="mb-0">I have a coupon:</h2>					
							<form class="checkout-fields d-flex justify-content-between mt-3 align-items-start coupon-form">	
												
								<div class="checkout-input-groups">
									<input id="coupon" type="text" name="" class="form-control">
									<label class="coupon-code-validation-message mt-1"> Invalid coupon code, please try again. </label>
								</div>								
								<button type="button" class="btn btn-2 checkout-button-coupon">apply</button>

								
							</form>		

							@elseif(!isset($show_coupon))
								<h2 class="mb-0">I have a coupon:</h2>	
								<form class="checkout-fields d-flex justify-content-between mt-3 align-items-start">
									<div class="checkout-input-groups">
										{{--<input readonly id="coupon" type="text" name="" value="{{Session::get('coupon_code')}}" class="form-control input-coupon-successfull">--}}
										<p id="coupon" class="form-control input-coupon-successfull"> {{Session::get('coupon_code')}} </p>
									</div>
									<button type="button" class="btn btn-2 checkout-button-coupon active">apply</button>
									
								</form>
								<label class="mt-1 coupon-successfull-message"> Enjoy, your code was applied successfully.</label>

							@endif
							
							{{--<div id="couponDialog">
							   <div class="alert-wrapper">
							      <div class="alert-inner">

							      </div>

											<!-- /.alert-outer -->
								</div>
							</div>--}}
						</div>
						
					</div>
				</div>

@push('scripts')

	@if(!\Session::get('coupon_code') && !isset($show_coupon))
		<script id="script-check-coupon">
			$('.checkout-button-coupon').click(function(){

				$(".coupon-code-validation-message").hide();

				let couponCode = '';

				if($("#coupon").val()){
					couponCode = $("#coupon").val().toUpperCase();
				}

   				$.ajax({ url: 'checkCoupon/{{$eventId}}', type: "post",
				   	headers: {
    	    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	 			},
					data:{'coupon': couponCode, 'price': "{{$price / $totalitems}}",'totalItems':"{{$totalitems}}"} ,
   					success: function(data) {

		  				if(data['success'] == true){
							
							//$('.checkout-price span').text('€' + "{{ $item->qty }}" * data['new_price']);
							$('.checkout-total-amount').text('€' + data['new_price']);

							$("#inst1").html('I will pay in full: €' +  data['new_price']);
							$("#inst2").text('I will pay in 2 installments: 2x €' + data['newPriceInt2']);
							$("#inst3").text('I will pay in 3 installments: 3x €' +  data['newPriceInt3']);
							$("#inst4").text('I will pay in 4 installments: 4x €' +  data['newPriceInt4']);

							//let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

							//$('.coupon-code-validation-message').text('Success! Your coupon has been accepted.');
							//$('.coupon-code-validation-message').addClass('coupon-successfull');
							//$('.checkout-button-coupon').remove();
							$('.checkout-button-coupon').addClass('active');
							//$("#coupon").prop("readonly", true);
							//$("#coupon").addClass('input-coupon-successfull');
							$("#coupon").remove();
							$('.checkout-input-groups').
							append(`<p id="coupon" class="form-control input-coupon-successfull">` + data['coupon_code'] + `</p>`)

							//$('.checkout-coupon-code-wrap').empty()
							$('<label class="mt-1 coupon-successfull-message"> Enjoy, your code was applied successfully.</label>').insertAfter('.coupon-form')
	
							$(`<div class="checkout-participant price-of">
						    		<p>Special offer:</p><span>`+ data['priceOf'] +`</span>
						    	</div>`).insertBefore(".checkout-total");
								
							$(`<div class="checkout-participant price-of">
						    		<p>You save:</p><span>`+ data['savedPrice'] +`</span>
						    	</div>`).insertBefore(".checkout-total");


							$("#script-check-coupon").remove();
					

		  				}else if(data['success'] == false){

							 /*let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

							 $("#couponDialog .alert-inner").empty();
							 $("#couponDialog .alert-inner").append(p);

							 $("#couponDialog .alert-wrapper").addClass('error-alert')
							 $("#couponDialog").css("display", "block")
							 $("body").css("overflow-y", "hidden")*/

							 $(".coupon-code-validation-message").show();

		  				}

		  				/*setTimeout(function() {
							  
							$("#couponDialog").css("display", "none")
							$("#couponDialog .alert-wrapper").removeClass('error-alert')
							$("#couponDialog .alert-wrapper").removeClass('success-alert')
							$("body").css("overflow-y", "auto")
		   				}, 2000);*/

   					}
				});

			})
		</script>
    @endif

@endpush
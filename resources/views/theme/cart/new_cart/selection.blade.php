<div class="col-md-6 col-xl-6">
					<div class="checkout-selection-wrap">
						
                        @foreach (Cart::content() as $item)
                        <div class="remove-from-cart">
							<a href="remove/{{ $item->rowId }}">Remove from cart <img src="{{cdn('new_cart/images/close-green.svg')}}" width="9px" height="10px"  class="without-hover">
								<img src="{{cdn('new_cart/images/close-green2.svg')}}" width="9px" height="10px" class="with-hover"></a>
						</div>
						<h2>My selection:</h2>
                    
						    <h3>{{ $item->name }}</h3>	
						    @if($duration)<datetime="YYYY-MM-DDThh:mm:ssTZD">Sep14 - Dec 4, 2021</datetime="YYYY-MM-DDThh:mm:ssTZD">@endif
						    <div class="checkout-price-wrap">
						    	<div class="checkout-price">
						    		<p>Price:</p><span>@if(is_numeric($price)) €{{$price}} @else {{$price}} @endif</span>
						    	</div>
						    	<div class="checkout-participant">
						    		<p>Participant(s):</p><span>1</span>
						    	</div>
						    	<div class="checkout-total">
						    		<p class="mb-0">Total amount:</p><span class="color-secondary checkout-total-amount">@if(is_numeric($price)) €{{$price}} @else {{$price}} @endif </span>
						    	</div>
						    </div>
                        @endforeach
						@if(!\Session::get('coupon_code') && !isset($show_coupon))
						<div class="checkout-coupon-code-wrap">
							<h2 class="mb-0">I have a coupon:</h2>							
							<form class="checkout-fields d-flex justify-content-between mt-3 align-items-start">								
								<div class="checkout-input-groups">
									<input id="coupon" type="text" name="" class="form-control">
									<label class="coupon-code-validation-message d-block mt-1"> Enter a valid coupon code</label>
								</div>								
								<button type="button" class="btn btn-2 checkout-button-coupon">apply</button>
							</form>		
							
							<div id="couponDialog">
							   <div class="alert-wrapper">
							      <div class="alert-inner">

							      </div>

											<!-- /.alert-outer -->
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>

@push('scripts')

	@if(!\Session::get('coupon_code') && !isset($show_coupon))
		<script>



			$('.checkout-button-coupon').click(function(){
	

   				$.ajax({ url: 'checkCoupon/{{$eventId}}', type: "post",
				   	headers: {
    	    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	 			},
					data:{'coupon': $("#coupon").val()} ,
   					success: function(data) {
		  				if(data['success']){
							
							$('.checkout-price span').text('€' + "{{ $item->qty }}" * data['new_price']);
							$('.checkout-total-amount').text('€' + "{{ $item->qty }}" * data['new_price']);

							$("#inst1").html('I will pay in full: €' + "{{ $item->qty }}" * data['new_price']);
							$("#inst2").text('I will pay in 2 installments: 2x €' + "{{ $item->qty }}" * data['newPriceInt2']);
							$("#inst3").text('I will pay in 3 installments: 3x €' + "{{ $item->qty }}" * data['newPriceInt3']);

							let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-success-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

							$("#couponDialog .alert-inner").empty();
							$("#couponDialog .alert-inner").append(p);

							$("#couponDialog .alert-wrapper").addClass('success-alert')
							$("#couponDialog").css("display", "block")
							$("body").css("overflow-y", "hidden")

							$('.coupon-submit').css("display", "none")
							$("#coupon").prop("readonly", true);

							$('.checkout-coupon-code-wrap').empty()

		  				}else{

							 let p = `<p><img src="{{cdn('/theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message']+ `</p>`

							 $("#couponDialog .alert-inner").empty();
							 $("#couponDialog .alert-inner").append(p);

							 $("#couponDialog .alert-wrapper").addClass('error-alert')
							 $("#couponDialog").css("display", "block")
							 $("body").css("overflow-y", "hidden")

		  				}

		  				setTimeout(function() {
							$("#couponDialog").css("display", "none")
							$("#couponDialog .alert-wrapper").removeClass('error-alert')
							$("#couponDialog .alert-wrapper").removeClass('success-alert')
							$("body").css("overflow-y", "auto")
		   				}, 2000);

   					}
				});

			})
		</script>
	@endif

@endpush
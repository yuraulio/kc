<div class="col-md-6 col-xl-6">
					<div class="checkout-selection-wrap">
						
                        
                        <div class="remove-from-cart">
							<a href="/myaccount">Remove from cart <img src="{{cdn('new_cart/images/close-green.svg')}}" width="9px" height="10px"  class="without-hover">
								<img src="{{cdn('new_cart/images/close-green2.svg')}}" width="9px" height="10px" class="with-hover"></a>
						</div>
						<h2>My selection:</h2>
                    
						    <h3>{{$event->title}}</h3>	
						    @if(isset($duration))<datetime="YYYY-MM-DDThh:mm:ssTZD">Sep14 - Dec 4, 2021</datetime="YYYY-MM-DDThh:mm:ssTZD">@endif
						    <div class="checkout-price-wrap">
						    	<div class="checkout-price">
						    		<p>Subscription: </p><span>{{$plan->name}}</span>
						    	</div>
						    	<div class="checkout-participant">
						    		<p>Billing period:</p><span>{{$plan->period()}}</span>
						    	</div>
						    	<div class="checkout-total">
						    		<p class="mb-0">Total amount:</p><span class="color-secondary checkout-total-amount">€{{$plan->cost}} </span>
						    	</div>
						    </div>
                      
						
					</div>
				</div>

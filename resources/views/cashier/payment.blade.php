@extends('cashier.master')
@section('content')
<div class="checkout-step">
   <div class="container">
      <ul>
         <li><span class="counter">1.</span><i>Participant(s)</i></li>
         <li><span class="counter">2.</span><i>Billing</i></li>
         <li class="active"><span class="counter">3.</span><i>Checkout</i></li>
      </ul>
   </div>
</div>
<div class="form-wrap">
   <div class="container">
   <h1> Order Summary </h1>
      <div class="row">
         <div class="col-md-6 col-xl-6">
            <div class="checkout-full-wrap">
               <div class="form-wrap md:flex md:justify-center md:items-center">
                  <div class="w-full max-w-lg">
                     <div class="row">
                        <div id="app" >
                           
                           <!-- Status Messages -->
                           <p class="flex items-center bg-red-100 border border-red-200 px-5 py-2 rounded-lg text-red-500" v-if="errorMessage">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="flex-shrink-0 w-6 h-6">
                                 <path class="fill-current text-red-300" d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20z"/>
                                 <path class="fill-current text-red-500" d="M12 18a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.9c-.13 1.2-1.88 1.2-2 0l-.5-5a1 1 0 0 1 1-1.1h1a1 1 0 0 1 1 1.1l-.5 5z"/>
                              </svg>
                              <span class="ml-3">@{{ errorMessage }}</span>
                           </p>
                       
                              <div v-if="paymentIntent.status === 'succeeded'">
                                 <h2 class="text-xl mb-4 text-gray-600">
                                    Payment Successful
                                 </h2>
                                 <p class="mb-6">
                                    This payment was successfully confirmed.
                                 </p>
                              </div>
                              <div v-else-if="paymentIntent.status === 'processing'">
                                 <h2 class="text-xl mb-4 text-gray-600">
                                    Payment Processing
                                 </h2>
                                 <p class="mb-6">
                                    This payment is currently processing. Refresh this page from time to time to see its status.
                                 </p>
                              </div>
                              <div v-else-if="paymentIntent.status === 'canceled'">
                                 <h2 class="text-xl mb-4 text-gray-600">
                                    Payment Cancelled
                                 </h2>
                                 <p class="mb-6">
                                    This payment was cancelled.
                                 </p>
                              </div>
                              <div v-else>
                                 <div v-if="paymentIntent.status === 'requires_payment_method'" class="mb-3">
                                    <!-- Instructions -->
                                    <h2 class="text-xl mb-4 text-gray-600">
                                       Confirm Your Payment
                                    </h2>
                                    <p class="mb-6">
                                       A valid payment method is needed to process your payment. Please confirm your payment by filling out your payment details below.
                                    </p>
                                    <!-- Payment Method -->
                                
                                    <!-- Name -->
                                    <label v-if="seen" for="name" class="inline-block text-sm text-gray-700 font-semibold mb-2">
                                    Full name
                                    </label>
                                    <input
                                       v-if="seen"
                                       id="name"
                                       type="text" placeholder="Jane Doe"
                                       required
                                       class="inline-block bg-gray-100 border border-gray-300 rounded-lg w-full px-4 py-3 mb-3 focus:outline-none"
                                       v-model="name"
                                       />
                                    <!-- E-mail Address -->
                                    <label v-if="seen" for="email" class="inline-block text-sm text-gray-700 font-semibold mb-2">
                                    E-mail address
                                    </label>
                                    <input
                                       v-if="seen"
                                       id="email"
                                       type="text" placeholder="jane@example.com"
                                       required
                                       class="inline-block bg-gray-100 border border-gray-300 rounded-lg w-full px-4 py-3 mb-3 focus:outline-none"
                                       v-model="email"
                                       />
                                    <div v-if="paymentElement">
                                       <!-- Stripe Payment Element -->
                                       <label for="payment-element" class="inline-block text-sm text-gray-700 font-semibold mb-2">
                                       Payment details
                                       </label>
                                       <div id="payment-element" ref="paymentElement" class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-6"></div>
                                    </div>
                                   
                                 </div>

                                 <p class="summary">This is the summary of your transaction with your card. 
                                     Please check and confirm before you pay.
                                 </p>

                                 <div class="form-row my-5 align-items-center prev-next-wrap">									
								<div class="d-flex align-items-center previous-participant-link">
									<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
									<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
									<a href="/checkout" class="link-color">Return to previous page</a>
								</div>
							</div>
                                 <button
                                    class="py-3 btn btn-3 checkout-button-primary stripe-payment-button"
                                    :class="{ 'bg-blue-400': isPaymentProcessing, 'bg-blue-600': ! isPaymentProcessing }"
                                    @click="confirmPaymentMethod"
                                    :disabled="isPaymentProcessing"
                                    >
                                 <span v-if="isPaymentProcessing">
                                 Processing...
                                 </span>
                                 <span v-else>
                                 Confirm your payment
                                 </span>
                                 </button>
                              </div>
                          
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-6 col-xl-6">
					<div class="checkout-selection-wrap">
						
                        @foreach (Cart::content() as $item)
                            <div class="remove-from-cart">
						    	<a href="/summary/remove/{{ $item->rowId }}">Remove from cart <img src="{{cdn('new_cart/images/close-green.svg')}}" width="9px" height="10px"  class="without-hover">
						    		<img src="{{cdn('new_cart/images/close-green2.svg')}}" width="9px" height="10px" class="with-hover"></a>
						    </div>
						    <h2>My selection:</h2>
                    
						    <h3>{{ $item->name }}</h3>	
						    @if($duration)<datetime="YYYY-MM-DDThh:mm:ssTZD"><span class="datetime">{{$duration}}</span></datetime="YYYY-MM-DDThh:mm:ssTZD">@endif
						    <div class="checkout-price-wrap">
						    	

						    	<div class="checkout-total">
						    		<p class="mb-0">Amount to be paid:</p><span class="color-secondary checkout-total-amount"> {{$price}} </span>
						    	</div>
						    </div>
                        @endforeach
						
						
						
					</div>
				</div>
      </div>
   </div>
</div>
<script>
   window.stripe = Stripe('{{ $stripeKey }}',{
   locale: 'en',
   });
   
   new Vue({
       el: '#app',
   
       data() {
           return {
               paymentIntent: @json($paymentIntent),
               paymentMethod: null,
               name: '{{ optional($customer)->firstname  }}' + ' {{ optional($customer)->lastname  }}',
               email: '{{ optional($customer)->stripeEmail() }}',
               paymentElement: null,
               remember: false,
               isPaymentProcessing: false,
               errorMessage: '{{ $errorMessage }}',
               seen:false
           }
       },
   
       mounted: function () {
           this.configurePayment(this.paymentIntent);
           this.configureStripeElements();
       },
   
       computed: {
           paymentMethodTitle() {
               return this.paymentMethod ? this.paymentMethod.title : '';
           },
   
           paymentMethods() {
               const methods = [
                   { title: 'Card', type: 'card', remember: true, redirects: false, element: 'card' },
                   { title: 'Alipay', type: 'alipay' },
                   { title: 'BECS Direct Debit', type: 'au_becs_debit', remember: true, redirects: false, element: 'auBankAccount' },
                   { title: 'Bancontact', type: 'bancontact', remember: true },
                   { title: 'EPS', type: 'eps', element: 'epsBank' },
                   { title: 'Giropay', type: 'giropay' },
                   { title: 'iDEAL', type: 'ideal', remember: true, element: 'idealBank' },
                   { title: 'SEPA Debit', type: 'sepa_debit', remember: true, redirects: false, element: 'iban', options: { supportedCountries: ['SEPA'] }}
               ].map(paymentMethod => {
                   return { remember: true, redirects: true, options: {}, ...paymentMethod }
               })
   
               return methods.filter(method => this.paymentIntent.payment_method_types.includes(method.type))
           }
       },
   
       methods: {
           configurePayment: function (paymentIntent) {
   
               // Set the payment intent object...
               this.paymentIntent = paymentIntent;
   
               // Set the allowed payment methods based on the payment method types of the intent...
               const paymentMethodTypes = paymentIntent.payment_method_types;
   
               // If the previously set payment method isn't available anymore,
               // update it to either the current one or the first available one...
               if (this.paymentMethod === null || ! paymentMethodTypes.includes(this.paymentMethod.type)) {
                   const type = this.paymentMethod === null
                       ? ('{{ $paymentMethod }}' ? '{{ $paymentMethod }}' : paymentMethodTypes[0])
                       : (((this.paymentIntent || {}).payment_method || {}).type ?? paymentMethodTypes[0]);
   
                   this.paymentMethod = this.paymentMethods.filter(
                       paymentMethod => paymentMethod.type === type
                   )[0];
               }
   
              
   
           },
   
           configureStripeElements: function () {
               // Stripe Elements are only needed when a payment method is required.
               if (this.paymentIntent.status !== 'requires_payment_method') {
                   return;
               }
   
               // Create the Stripe element based on the currently selected payment method...
               if (this.paymentMethod.element) {
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
                   
                   this.paymentElement = elements.create(
                       'card',{
                           style: {
                              base: {
                               	fontSize: '18px',
                     		fontFamily: 'Foco',
                             },
                           },
                       
                           hidePostalCode: true,
   
                       }
                   );
               }  else {
                   this.paymentElement = null;
               }
   
               if (this.paymentElement) {
                   this.$nextTick(() => {
                       // Clear the payment element first, otherwise Stripe Elements will emit a warning...
                       document.getElementById("payment-element").innerHTML = "";
   
                       this.paymentElement.mount('#payment-element');
                   })
               }
           },
   
           confirmPaymentMethod: function () {
               this.isPaymentProcessing = true;
               this.errorMessage = '';
   
               const secret = this.paymentIntent.client_secret;
               let data = {
                   setup_future_usage: this.paymentMethod.remember && this.remember
                       ? 'off_session'
                       : null,
                   
                   //setup_future_usage: 'off_session',
                   payment_method: {
                       billing_details: { name: this.name, email: this.email },
                   },
                   
               };
               let paymentPromise;
   
               // Set a return url to redirect the user back to the payment
               // page after handling the off session payment confirmation.
               if (this.paymentMethod.redirects) {
                   data.return_url = '{{ route('payment.secure', $input) }}';
               }
   
               if (this.paymentMethod.type === 'card') {
                   if (this.paymentIntent.status === 'requires_payment_method') {
                       data.payment_method.card = this.paymentElement;
                   } else if (this.paymentIntent.status === 'requires_action') {
                       data.payment_method = this.paymentIntent.payment_method.id;
                   }
   
                   paymentPromise = stripe.confirmCardPayment(secret, data);
               } else if (this.paymentMethod.type === 'alipay') {
                   paymentPromise = stripe.confirmAlipayPayment(secret, data);
               } else if (this.paymentMethod.type === 'au_becs_debit') {
                   if (this.paymentIntent.status === 'requires_payment_method') {
                       data.payment_method.au_becs_debit = this.paymentElement;
                   }
   
                   paymentPromise = stripe.confirmAuBecsDebitPayment(secret, data);
               }  else if (this.paymentMethod.type === 'bancontact') {
                   paymentPromise = stripe.confirmBancontactPayment(secret, data);
               } else if (this.paymentMethod.type === 'eps') {
                   if (this.paymentIntent.status === 'requires_payment_method') {
                       data.payment_method.eps = this.paymentElement;
                   }
   
                   paymentPromise = stripe.confirmEpsPayment(secret, data);
               }  else if (this.paymentMethod.type === 'giropay') {
                   paymentPromise = stripe.confirmGiropayPayment(secret, data);
               } else if (this.paymentMethod.type === 'ideal') {
                   if (this.paymentIntent.status === 'requires_payment_method') {
                       data.payment_method.ideal = this.paymentElement;
                   }
   
                   paymentPromise = stripe.confirmIdealPayment(secret, data);
               } else if (this.paymentMethod.type === 'sepa_debit') {
                   if (this.paymentIntent.status === 'requires_payment_method') {
                       data.payment_method.sepa_debit = this.paymentElement;
                   }
   
                   paymentPromise = stripe.confirmSepaDebitPayment(secret, data);
               }
               
               
               paymentPromise.then(result => this.confirmCallback(result));
               
           },
   
           confirmCallback: function (result) {
               this.isPaymentProcessing = false;
   
               if (result.error) {
                   if (result.error.code === '{{ Stripe\ErrorObject::CODE_PARAMETER_INVALID_EMPTY }}') {
                       this.errorMessage = 'Please provide your name and e-mail address.';
                   } else {
                       this.errorMessage = result.error.message;
                   }
   
                   if (result.error.payment_intent) {
   
                       const dataa = {'input':"{{$input}}", 'paymentIntent':result.paymentIntent};
                       axios.post('{{route("payment.secure")}}', dataa)
                           .then(response => element.innerHTML = response.data.id);
                       
   
                       this.configurePayment(result.error.payment_intent);
   
                       this.configureStripeElements();
                   }
               } else {
   
                   //this.configurePayment(result.paymentIntent);
   
                   const dataa = {'input':"{{$input}}", 'paymentIntent':result.paymentIntent};
                   axios.post('{{route("payment.secure")}}', dataa)
                       .then(function(response){
   
                           if(response.data.success == true){
                               window.location.href = response.data.redirect;
                           }
   
                       });
               }
           },
   
           goBack: function () {
               const button = this.$refs.goBackButton;
               const redirect = new URL(button.dataset.redirect);
   
               redirect.searchParams.append(
                   'success', this.paymentIntent.status === 'succeeded' ? 'true' : 'false'
               );
   
               if (this.errorMessage) {
                   redirect.searchParams.append('message', this.errorMessage);
               }
   
               window.location.href = redirect;
           },
       },
   })
</script>
@stop
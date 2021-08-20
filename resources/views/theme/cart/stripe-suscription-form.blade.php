<div class="col12  invoice-fields">
   <div class="row">
      <div class="col12">
         <img src='/theme/assets/images/stripe/powered_by_stripe.png'>
      </div>
   </div>
   </br>
   <div class="col12  installments-fields">
      <input type="hidden" id="payment_method_id" name="payment_method_id" value="1">
      <!-- /.col12.hidden-fields-actions.installments-fields -->
   </div>
   <?php //dd($default_card); ?>
   @if(!$default_card)
   <?php //dd($default_card); ?>
   <div class="col12">
         <h4>Card Information</h4>
         <?php //dd($default_card); ?>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Brand: <span id="brand" class="ticket-coupon">No card</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Last four: <span id="last4" class="ticket-coupon">-</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire month: <span id="exp_month" class="ticket-coupon">-</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire year <span id="exp_year" class="ticket-coupon">-</span></label>
         </div>
         
         <!-- <div class="label-wrapper">
            <label for="radio-installment-full-control">CVV: 
               <input id="cvv" type="text" value="" placeholder="CVV" required></input>
            </label>
         </div> -->
      </div>
   <div class="row cardForm">


      <div id="addCardBtn" class="col12">
         <button type="button" id="addCard" class="btn btn--secondary btn--sm">Add New Card</button>
      </div>

   </div>
   @else
   <div class="row">
      <div class="col12">
         <h4>Card Information</h4>
         <?php //dd($default_card); ?>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Brand: <span id="brand" class="ticket-coupon">{{$default_card['brand']}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Last four: <span id="last4" class="ticket-coupon">{{$default_card['last4']}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire month: <span id="exp_month" class="ticket-coupon">{{$default_card['exp_month']}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire year <span id="exp_year" class="ticket-coupon">{{$default_card['exp_year']}}</span></label>
         </div>
         
         <!-- <div class="label-wrapper">
            <label for="radio-installment-full-control">CVV: 
               <input id="cvv" type="text" value="" placeholder="CVV" required></input>
            </label>
         </div> -->
      </div>

      

      <div id="addCardBtn" class="col12">
         <button type="button" id="addCard" class="btn btn--secondary btn--sm">Add New Card</button>
      </div>

     
   @endif
   <div id="container" class="col12">
         
   </div>
</div>
<!--  <div class='form-row'>
   <div class='col-md-12 form-group'>
   <button class='form-control btn btn-primary submit-button' type='submit'>Pay »</button>
   </div>
   </div> -->
<script>
   function cvv(input) {
      
   
      if(isNaN(input.value)){
         input.value = '';
      }
   
   }
   
   
   function cardNo(input) {
      
   
      if(isNaN(input.value)){
         input.value = '';
      }
   
   }
   
   function month(input) {
   
   
      if(isNaN(input.value)){
         input.value = '';
      }
   
      if(input.value.length == 1 && (input.value >= 2 || input.value < 0)){
         input.value = ''
      }else if(input.value.length == 2 && (input.value <= 0 || input.value > 12)){
         input.value = ''
      }else if(input.value.length >= 3){
         input.value = ''
      }else if(input.value == ''){
         input.value = ''
      }
   
   }
   
   function year(input) {
   
   
      if(isNaN(input.value)){
         input.value = '';
      }
   
      if(input.value.length == 1 && input.value != 2){
         input.value = ''
      }else if(input.value.length == 2 && (input.value < 20 || input.value >=30)){
         input.value = ''
      }else if(input.value.length >= 4 && (input.value < 2020 || input.value >=3000)){
         input.value = ''
      }else if(input.value.length < 4){
         document.getElementById('checkout-button').disabled = true;
      }else{
         document.getElementById('checkout-button').disabled = false;
      }
   
   }
   
</script>


<script>
    $(document).on('click', '#addCard', function(e){
      
      /*$('<script>')
       .attr('src', 'https://js.stripe.com/v3/')
       .attr('id', 'stripe-js')
       .appendTo('head');*/

   
      
      $('#addCard').prop('disabled', true);
      $('.msg_save_card').remove();
      $('#container').append(`
         <input id="card-holder-name" type="text">
         <!-- Stripe Elements Placeholder -->
         <div id="card-element"></div>
         <button id="card-button" type="button" class="btn btn--secondary btn--sm" data-secret="{{ Auth::user()->createSetupIntent()->client_secret }}">
             Update Payment Method
         </button>`)


         $('<script>')
       .text(`var stripe = Stripe('{{$stripe_key}}',{locale: 'en'});
               var elements = stripe.elements();
               var cardElement = elements.create('card',{
                  style: {
                     base: {
      
                        fontSize: '18px',
      
                     },
                  },
                  hidePostalCode: true,
                  });
               cardElement.mount('#card-element');`)

       .attr('id', 'stripe-form')
       .appendTo('head');

     

      
   })

  

</script>

<script>


   $(document).on('click',"#card-button",async (e) => {
      var cardHolderName = document.getElementById('card-holder-name');
      var cardButton = document.getElementById('card-button');
      var clientSecret = cardButton.dataset.secret;
      let { setupIntent, error } = await stripe.confirmCardSetup(
           clientSecret, {
               payment_method: {
                   card: cardElement,
                   billing_details: { name: cardHolderName.value }
               }
           }
       ).then(function (result) {
            if (result.error) {
               console.log('error = ', result.error)
                //$('#card-errors').text(result.error.message)
                //$('button.pay').removeAttr('disabled')
            } else {
               paymentMethod = result.setupIntent.payment_method
               $('button').prop('disabled', true);
               $.ajax({
                  type:'POST',
                  url:'card/store_from_payment',
                  headers: {
                   'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                  },
                  data:{ 'payment_method' : paymentMethod},
                  success:function(data) {
                 
                     if(data['success']){
                        data = JSON.stringify(data['card'])
                        data = JSON.parse(data)


                        $('#brand').text(data['brand'])
                        $('#last4').text(data['last4'])
                        $('#exp_month').text(data['exp_month'])
                        $('#exp_year').text(data['exp_year'])

                        $("#stripe-form").remove();
                     $("#stripe-js").remove();

                        $('#container').children().remove();

                        $('#container').append(`<p class="normal msg_save_card"> Successfully added card!!</p>`)
                        $('#addCard').prop('disabled', false);
                        $('button').prop('disabled', false);
                     }else{
                        let message = `<img src="{{cdn('theme/assets/images/icons/alert-icons/icon-error-alert.svg')}}" alt="Info Alert">` + data['message'];
                        $("#card-message").html( message)

                        var favDialogCard = document.getElementById('favDialogCardNumberFailed');
                        favDialogCard.style.display = "block";

                        $('#addCard').prop('disabled', false);
                        $('button').prop('disabled', false);
                        $("#stripe-form").remove();
                     $("#stripe-js").remove();
                     }

                     

                  },
           
               });
            }
         });
               
   })
  



</script>
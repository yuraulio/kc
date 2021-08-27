<div id="cardInformation" class="col12 <?= ($default_card) ? '' : 'd-none'; ?>">
    <h4>Card Information</h4>
        <!-- Card body -->
        <div class="card-body">


            <div class="row align-items-center">
                <div class="col-auto">
                    <!-- Avatar -->
                    <?php //dd($default_card); ?>
                    @if($default_card && $default_card->brand == 'visa')
                        <?php $brand = 'Visa' ?>
                        <img style="padding:10px" id="icon_card" alt="Image placeholder" src="{{ asset('argon') }}/img/icons/cards/visa.svg">
                    @else
                        <?php $brand = 'Mastercard' ?>
                        <img style="padding:10px" id="icon_card" alt="Image placeholder" src="{{ asset('argon') }}/img/icons/cards/master.svg">
                    @endif

                </div>
                <div class="col ml--2">
                    <b id="brand-sec" style="margin-right:2rem;" class="mb-0"> {{$brand}}</b>
                    <b style="margin-right:0.5rem;" class="mb-0"> **** </b>
                    <b id="last4" class="mb-0">@if($default_card) {{$default_card->last4}} @endif</b>

                    <p class="text-sm text-muted mb-0"><span style="margin-right:0.8rem;">Expires: </span><span id="exp_month">@if($default_card) {{$default_card->exp_month}}@endif</span>/<span id="exp_year">@if($default_card){{$default_card->exp_year}} @endif</span></p>

                </div>
            </div>

        </div>

</div>


<div id="add_card_btn" class="col12 <?= (!$default_card) ? 'd-none' : '';?>">
    <div id="addCardBtn" class="col12">
        <button type="button" id="addCard" class="btn btn--secondary btn--sm">Add New Card</button>
    </div>
</div>


<div id="container" class="col12 <?= ($default_card) ? 'd-none' : '';?>"></div>



</div>


<script>
   $(document).ready(function() {

      if($('#last4').text() == ''){
          $( "#addCard" ).click();
      }

   });
</script>

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

                    stripeUserId = data.id;

                     if(data['success']){
                        data = JSON.stringify(data['card'])
                        data = JSON.parse(data)

                        $('#cardInformation').removeClass('d-none')


                        $('#brand').text(data['brand'])
                        $('#last4').text(data['last4'])
                        $('#exp_month').text(data['exp_month'])
                        $('#exp_year').text(data['exp_year'])

                        if(data['brand'] == 'visa'){
                            $('#icon_card').attr('src', "argon/img/icons/cards/visa.svg")
                            $('#brand-sec').text('Visa')
                        }else{
                            $('#icon_card').attr('src', "argon/img/icons/cards/master.svg")
                            $('#brand-sec').text('Mastercard')
                        }



                        $('#add_card_btn').removeClass('d-none')


                        $("#stripe-form").remove();
                        $("#stripe-js").remove();

                        $('#container').children().remove();


                        $('#container').append(`<p class="normal msg_save_card"> Successfully added card!!</p>`)

                        setTimeout(function(){

                            $msg_save_card = $('.msg_save_card')

                            $msg_save_card.hide('slow', function(){ $msg_save_card.remove(); });

                            }, 4000);

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


@if(Auth::check())
<script>
    var count = 0;
    var stripeUserId = '{{ Auth::user()->createSetupIntent()->client_secret }}';
    $(document).on('click', '#addCard', function(e){

       $('#container').removeClass('d-none')



      $('#addCard').prop('disabled', true);
      $('.msg_save_card').remove();
       $('#container').append(`
          <input id="card-holder-name" type="text" placeholder="Cardholder Name">
          <!-- Stripe Elements Placeholder -->
          <div id="card-element"></div>
          <button id="card-button" type="button" class="btn btn--secondary btn--sm" data-secret="${stripeUserId}">
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



        //$('#card-button').attr('data-secret', '{{ Auth::user()->createSetupIntent()->client_secret }}')




   })



</script>

@endif
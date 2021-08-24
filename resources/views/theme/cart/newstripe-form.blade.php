<div class="col12  invoice-fields">
   <div class="row">
      <div class="col12">
      <img src='/theme/assets/images/stripe/powered_by_stripe.png'>
      </div>
   </div>
</br>
<div class="col12 col-sm-12">
   <div class="stripe-wrapper">
      <div class="">

         <div id="debit" >
            <label class="stripe" for="radio-debit-card-control">Debit/Credit card</label>
            <span>Up to 2 installments (some international credit cards and AMEX cards do not accept installments).</span>
         </div>
      </div>

   </div>
</div>
<div class="stripe-errors panel hidden"></div>

   <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <label> Card number <span>*</span></label>
         <input onblur="this.placeholder = 'Card Number'" autocomplete='off' onfocus="this.placeholder = ''" placeholder="Card Number" onkeyup="cardNo(this)"  type='text' name="card_no" id="card_no">
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <label>CVV <span>*</span></label>
         <input onblur="this.placeholder = 'e.g. 311'" autocomplete='off' onfocus="this.placeholder = ''" placeholder='e.g. 311' onkeyup="cvv(this)"  type='text' name="cvvNumber" id="cvvNumber">
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <label>Expiration month <span>*</span></label>

            <input onblur="this.placeholder = 'MM'" onfocus="this.placeholder = ''"  placeholder='MM' onkeyup="month(this)" type='text' name="ccExpiryMonth" id="ccExpiryMonth" required>

      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
         <label>Expiration year <span>*</span></label>

            <input onblur="this.placeholder = 'YYYY'" onfocus="this.placeholder = ''" placeholder='YYYY' type='text' maxlength="4" size="4" onkeyup="year(this)"  name="ccExpiryYear" id="ccExpiryYear" required>

      </div>
   </div>
</div>
<div class='form-row'>
   <div class='col-md-12 form-group'>
      <button class='form-control btn btn-primary button'>Pay »</button>
   </div>
</div>


   <script>

$('#radio-installment-3-control').click(function()
   {
      $(".active").removeClass("active");

        //return false;
    });

$('#radio-installment-2-control').click(function()
   {
      $(".active").removeClass("active");

        //return false;
    });

    $('#radio-installment-full-control').click(function()
   {
      $(".active").removeClass("active");

        //return false;
    });

</script>
<script>



$('#payment-form button').on('click', function() {
	var form = $('#payment-form');
	var submit = form.find('button');
	var submitInitialText = submit.text();


	submit.attr('disabled', 'disabled').text('Just one moment...')

	Stripe.card.createToken(form, function(status, response) {
		var token;
		if(response.error){
			form.find('.stripe-errors').text(response.error.message).show();
		}else{
         token = response.id;
         form.append($('<input type="hidden" name="token" >').val(token));
      }
	});
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

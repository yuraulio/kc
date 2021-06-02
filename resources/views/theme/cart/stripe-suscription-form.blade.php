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
   @if(!isset($default_card))
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
      <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
   
         <input class='form-control ammount' type='hidden' name="amount" value="{{ Cart::instance('default')->subtotal() }}">
      </div> -->

      <div id="addCardBtn" class="col12">
         <button type="button" id="addCard" class="btn btn--secondary btn--sm">Add New Card</button>
      </div>

      <div id="container" class="col12">
         
      </div>
   </div>
   @else
   <div class="row">
      <div class="col12">
         <h4>Card Information</h4>
         <?php //dd($default_card); ?>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Brand: <span id="brand" class="ticket-coupon">{{$default_card->brand}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Last four: <span id="last4" class="ticket-coupon">{{$default_card->last_four}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire month: <span id="exp_month" class="ticket-coupon">{{$default_card->exp_month}}</span></label>
         </div>
         <div class="label-wrapper">
            <label for="radio-installment-full-control">Expire year <span id="exp_year" class="ticket-coupon">{{$default_card->exp_year}}</span></label>
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

      <div id="container" class="col12">
         
      </div>
   @endif
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
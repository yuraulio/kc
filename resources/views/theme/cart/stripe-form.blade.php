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
<div class="col12  installments-fields">
    <?php
        $instOne = Cart::instance('default')->subtotal();
        $instTwo = round($instOne / 2, 2);
        $instThree = round($instOne / 3, 2);

        ?>
    <div class="custom-radio-wrapper small-radio">
        <div class="custom-radio-box active children-radio">
        <div class="crb-wrapper">
            <input id="radio-installment-full-control"  checked="checked"  type="radio" name='installments' value='1' data-fieldset-target="installments-fields" >
            <span></span>
        </div>
        <div class="label-wrapper">
            <label for="radio-installment-full-control">Payment in full <span class="ticket-coupon">€{{$instOne}}</span>.</label>
        </div>
        </div>
        <div class="custom-radio-box children-radio">
        <div class="crb-wrapper">
            <input id="radio-installment-2-control" name='installments' value='2' type="radio" data-fieldset-target="installments-fields" >

            <span></span>
        </div>
        <div class="label-wrapper">
            <label for="radio-installment-2-control">2 installments: <span class="ticket-coupon-inst2">2x €{{$instTwo}}</span>.</label>

        </div>
        </div>
        <div class="custom-radio-box children-radio">
        <div class="crb-wrapper">
            <input id="radio-installment-3-control" name='installments' value='3' type="radio" data-fieldset-target="installments-fields" >

            <span></span>
        </div>
        <div class="label-wrapper">
            <label for="radio-installment-3-control">3 installments: <span class="ticket-coupon-inst3">3x €{{$instThree}}</span>.</label>

        </div>
        </div>

    </div>


    <input type="hidden" id="payment_method_id" name="payment_method_id" value="1">

    <!-- /.col12.hidden-fields-actions.installments-fields -->
</div>


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

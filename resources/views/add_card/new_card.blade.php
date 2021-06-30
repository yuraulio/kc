
<form method="POST" action="{{ route('add.paymentMethod') }}" class="card-form mt-3 mb-3">
    @csrf
    <input type="hidden" name="payment_method" class="payment-method">

<input id="card-holder-name" type="text">

<!-- Stripe Elements Placeholder -->
<div id="card-element"></div>

<button id="card-button" type="button" data-secret="{{ $intent->client_secret }}">
    Update Payment Method
</button>
</form>
<script src="https://js.stripe.com/v3/"></script>

<script>

    let st = "{{$stripe_key}}"
    const stripe = Stripe(st);

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');


    const cardHolderName = document.getElementById('card-holder-name');
const cardButton = document.getElementById('card-button');
const clientSecret = cardButton.dataset.secret;

cardButton.addEventListener('click', async (e) => {
    const { setupIntent, error } = await stripe.confirmCardSetup(
        clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: cardHolderName.value }
            }
        }
    ).then(function (result) {
            if (result.error) {
                $('#card-errors').text(result.error.message)
                $('button.pay').removeAttr('disabled')
            } else {
                paymentMethod = result.setupIntent.payment_method
                $('.payment-method').val(paymentMethod)
                $('.card-form').submit()
            }
        });

});
</script>
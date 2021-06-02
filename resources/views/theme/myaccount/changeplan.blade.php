@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<!-- @inject('cFieldLib', 'Library\CustomFieldHelperLib') -->

</head>
<?php //dd($card); ?>
<div class="page-helper contact-help mob-help" id="cart-page"></div>
	<div id="main-content-body" style="margin-top:10%;" class="cart-page">

	<div style="" class="container">
		

		<div class='row'>
		
			<div style="width:50%;" class='col-md-6 col-xl-6 col-sm-6'>
			<h1>Change Plan</h1>
				<form action="{!!route('plan.store')!!}" method="post" id="payment-form">
					{{ csrf_field() }}
					<input type="hidden" name="subscription_id" value="{{$subscription_id}}">
               @foreach($plans as $key => $plan)
               <?php //dd($plan); ?>
                  <div class="form-row">
                     <input type="radio" name="plan" id="plan" value="{{$plan['stripe_plan']}}" 
                     <?php if($plan['stripe_plan'] == $current_plan){
                        echo 'disabled = true';
                     } ?>>
                     <label for="male">{{$plan['name']}} <?php if($plan['stripe_plan'] == $current_plan)
                        echo ' (Current plan)';
                      ?></label><br>
                  </div>
               @endforeach

					<button>Submit</button>
				</form>
			</div>

			

	</div>
@endsection
@section('scripts')


	
	</script>
@stop
<script>





</script>
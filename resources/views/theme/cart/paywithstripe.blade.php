@extends('theme.layouts.master')
@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@inject('cFieldLib', 'Library\CustomFieldHelperLib')
<div class="page-helper contact-help mob-help" id="cart-page"></div>
	<div id="main-content-body" class="cart-page">

	<div class="container">
		<div class="page-header">
		    <h1>Payment</h1>
		    @if(isset($info) && isset($info['message']) && $info['message'] != '')

			<div class="pgCont {{ $info['statusClass'] }}">
				<?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?>
				<?php $res = json_decode($info['transaction']['payment_response'], true); ?>
                {!! $info['message'] !!}
                <span class="hidden"><strong>Transaction: {{ $info['transaction']['id'] }}<br />Message: {{ $res['status'] }}</strong></span>
            </div>

            @endif

		</div>

 <div class='row'>
 <div class='col-md-4'></div>
 <div class='col-md-4'>
 	 <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!!route('addmoney.stripe')!!}" >
	{{ csrf_field() }}
	@include('theme.cart.stripe-form')
	 </form>
 </div>
 <div class='col-md-4'></div>
 </div>
</div>

 </div>
</div>
@endsection
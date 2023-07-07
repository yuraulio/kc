@extends('theme.cart.new_cart.master')

@section('content')

<!---------------- checkout progress-bar start --------------->
<div class="checkout-step">
		<div class="container">		
			<ul>
				<li><span class="counter">1.</span><i>Participant(s)</i></li>
				<li class="active"><span class="counter">2.</span><i>Billing</i></li>
				<li><span class="counter">3.</span><i>Checkout</i></li>
			</ul>
		</div>
	</div>
<!---------------- checkout progress-bar end --------------->	
<div class="form-wrap">
		<div class="container padding-no">			
			<h1 class="hidden-xs">Billing</h1>			
			<div class="row">
				<!---------------- billing form start--------------->
				<div class="col-md-6 col-xl-6 selection-order">
				<h1 class="hidden-lg">Billing</h1>		
					<div class="billing-full-wrap">
						<form action="billing" method="post" name="billing">
						@csrf
						<div class="form-wrp box" id="clone-box">
							<p class="validation-info">Fields marked with an asterisk <span class="checkout-required-data">(*)</span> are required.</p>
							<div class="form-row">
								<div class="col-md-12 mb-4">
									<label class="input-label">Company name (for invoice) or personal full name (for receipt) <span class="checkout-required-data">(*)</span> </label>
									<input type="text" name="billname" class="form-control" value="{{old('billname',$billname)}}" placeholder="" aria-describedby="inputGroupPrepend3" required="">
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 mb-4">
									<label class="input-label">VAT or tax ID @if($pay_methods && ($pay_methods->id == 3 || $pay_methods->id == 4))<span class="checkout-required-data">(*)</span>@endif</label>
									<input type="text" name="billafm" value="{{old('billafm',$billafm)}}" class="form-control" placeholder="" aria-describedby="inputGroupPrepend3" @if($pay_methods && ($pay_methods->id == 3 || $pay_methods->id == 4)) required="" @endif>
									<div class="invalid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 col-xl-8 mb-4 pr-xl-3">
									<label class="input-label">Street</label>
									<input type="text" class="form-control" value="{{old('billaddress',$billaddress)}}" name="billaddress" placeholder="">
									<div class="valid-feedback">
										
									</div>
								</div>
								<div class="col-md-12 col-xl-4 mb-4 pl-xl-3">
									<label class="input-label">Street number</label>
									<input type="text" class="form-control" value="{{old('billaddressnum',$billaddressnum)}}" name="billaddressnum" placeholder="">
									<div class="valid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 col-xl-8 mb-4 pr-xl-3">
									<label class="input-label">Town/city</label>
									<input type="text" name="billcity" value="{{old('billcity',$billcity)}}" class="form-control" placeholder="">
									<div class="valid-feedback">
										
									</div>
								</div>
								<div class="col-md-12 col-xl-4 mb-4 pl-xl-3">
									<label class="input-label">Postcode (ZIP)</label>
									<input type="text" name="billpostcode" value="{{old('billpostcode',$billpostcode)}}" class="form-control"  placeholder="">
									<div class="valid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 mb-4">
									<label class="input-label">State</label>
									<input type="text" class="form-control" name="billstate" value="{{old('billstate',$billstate)}}" placeholder="" aria-describedby="inputGroupPrepend3">
									<div class="invalid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 mb-4">
									<label class="input-label">Country</label>
									<input type="text" class="form-control" name="billcountry" value="{{old('billcountry',$billcountry)}}" placeholder="" aria-describedby="inputGroupPrepend3">
									<div class="invalid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-12 mb-4">
									<label class="input-label">Please send this receipt to this email address</label>
									<input type="text" name="billemail" value="{{old('billemail',$billemail)}}" class="form-control" placeholder="" aria-describedby="inputGroupPrepend3">
									<div class="invalid-feedback">
										
									</div>
								</div>
							</div>
							<div class="form-row my-5 align-items-center prev-next-wrap">
								<div class="d-flex align-items-center previous-participant-link">
									<img src="{{cdn('new_cart/images/arrow-previous-green.svg')}}" width="20px" height="12px" class="without-hover" alt="">
									<img src="{{cdn('new_cart/images/arrow-previous-green2.svg')}}" width="20px" height="12px" class="with-hover" alt="">
									<a href="/registration" class="link-color">Previous: Participant(s)</a>
								</div>	
								<button id="btn" type="submit" class="btn checkout-button-secondary">Next:Checkout <img src="{{cdn('new_cart/images/arrow-next-red.svg')}}" width="20px" height="12px" class="without-hover" alt=""> <img src="{{cdn('new_cart/images/arrow-next-red2.svg')}}" width="20px" height="12px" class="with-hover" alt=""> </button>
							</div>
						</div>
						</form>
					</div>
				</div>
				<!---------------- billing form end--------------->

				<!---------------- My Selection start--------------->
				@include('theme.cart.new_cart.selection')
				<!---------------- My Selection end--------------->
			</div>
		</div>						
	</div>
@stop


@push('scripts')

@if(isset($tigran) && !env('APP_DEBUG'))
<script type="text/javascript">

dataLayer.push({
    'event': 'begin_checkout',
    'currency': 'EUR',
	'value': "{{$tigran['Price']}}",
	'items': [{
		'item_id': "{{$tigran['Product_id']}}",
		'item_name': $.parseHTML("{{ $tigran['ProductName'] }}")[0].data,
		'item_brand': 'Knowcrunch',
		'item_category': "{{$tigran['ProductCategory']}}",
		'price': "{{$tigran['Price']}}",
		'quantity': 1
	}]
});	
	</script>
@endif
@endpush

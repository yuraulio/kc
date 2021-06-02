@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<?php $totalitems = 0; ?>
@foreach (Cart::content() as $item)
<?php $totalitems = $totalitems + $item->qty ?>

@endforeach

<div id="event-banner">
    <div class="page-helper contact-help"></div>
</div>
<div id="main-content-body" class="cart-page">

	<div class="container">
		<div class="page-header">
		    <h1>Checkout</h1>
		    @if($user = Sentinel::check())
		    <p class="lead">{{ $user->email}}</p>
		    @endif
		</div>

		<div class="auth__wall row">

			<div class="col-md-6">
				@if (!Sentinel::check())
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified nav-auth" role="tablist">
					<li class="active"><a href="#login" title="Login" role="tab" data-toggle="tab">Login</a></li>
					<li><a href="#register" title="Register" role="tab" data-toggle="tab">Register</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="login">
						@include('sentinel/login/cform')
					</div>
					<div class="tab-pane" id="register">
						@include('sentinel/register/form')
					</div>
				</div>
				@else
					<h3>Choose Payment Options</h3>
					@if (sizeof(Cart::content()) != 0)
					{!! Form::model($user, ['route' => 'userPaySbt', 'method' => 'post', 'id' => 'sbt-pay', 'class' => 'form-horizontal']) !!}
						{!! Form::label('payment_method_id', 'Μέθοδος Πληρωμής : ') !!}
                                {!! Form::select('payment_method_id', array_pluck($pay_methods, 'method_name', 'id'), null, ['class' => 'form-horizontal']) !!}

						<!--  <input type="hidden" id="payment_method_id" name="payment_method_id" value="2"> -->
						 <input type="hidden" id="logged_in_user" name="logged_in_user" value="{{ $user->id }}">
		                <div class="ckoutbx_option">
							<div class="checkbox">

							<label><input class="c-box" type="checkbox" id="need_invoice" value=""><span>I need an invoice</span></label>

                            <div id="invoice-form" class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyname" name="companyname" placeholder="Company Name" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companyname'])){{$pay_invoice_data['companyname']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyprofession" name="companyprofession" placeholder="Profession" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companyprofession'])){{$pay_invoice_data['companyprofession']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyafm" name="companyafm" placeholder="VAT id" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companyafm'])){{$pay_invoice_data['companyafm']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companydoy" name="companydoy" placeholder="VAT authority" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companydoy'])){{$pay_invoice_data['companydoy']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyaddress" name="companyaddress" placeholder="Street Name" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companyaddress'])){{$pay_invoice_data['companyaddress']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyaddressnum" name="companyaddressnum" placeholder="Street Number" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companyaddressnum'])){{$pay_invoice_data['companyaddressnum']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companypostcode" name="companypostcode" placeholder="Post Code" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companypostcode'])){{$pay_invoice_data['companypostcode']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companycity" name="companycity" placeholder="City" value="@if(isset($pay_invoice_data) && isset($pay_invoice_data['companycity'])){{$pay_invoice_data['companycity']}}@endif">
                                    </div>
                                </div>
                            </div>

							</div>

							<div class="checkbox">
							<label><input class="c-box" id="accept" type="checkbox" value="" name="accept" required=""><span><b>I agree &amp; accept the following terms &amp; conditions:</b></span></label>
							</div>

							<h6 class="checkout-terms">
								1) <b>Booking:</b> This is binding booking. By registering you agree to <b>a full ticket payment</b>.<br><br>
								2) <b>Payment:</b> Payments for all tickets can take place in <b>3 installments</b>. The 1st installment (33%) is required on registration day in order to confirm your reservation. If this payment is not received 1 day after you register, your reservation will be automatically cancelled. 2nd installment takes place 1 month after registration. 3rd 2 months after registration.<br><br>
								3) <b>Refunds:</b> Cancellations received 15 days before the course will receive a full 100% refund, cancelations received 14 - 1 days before the course will receive 50% refund and cancellations received after the start of the course will <b>not</b> be eligible for any refund and any outstanding amount due (the remaining installments) must be fully paid as far as the seat is booked.
							</h6>



							{!! Form::submit('I Want To Book My Seat Now & Pay', ['class' => 'btn btn-booknow-full']) !!}

							@if(isset($info) && isset($info['message']) && $info['message'] != '')

							<div class="pgCont">
								<h3><?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?></h3>
								<?php $res = json_decode($info['transaction']['payment_response'], true); ?>
		                        {{ $info['message'] }}<br />
                                <span><strong>Transaction: {{ $info['transaction']['id'] }}<br />Error: {{ $res['status'] }}</strong></span>
		                    </div>
		                    @endif
						</div>




		 				{{ csrf_field() }}

						{!! Form::close() !!}

					@endif
				@endif

			</div>

			<div class="col-md-6">
				<table class="table table-striped table-bordered table-hover">
		                    <thead>
		                        <tr>
		                            <td class="col-md-6">Event</td>
		                            <td class="col-md-1"><span class="pull-right">Price</span></td>
		                            <td class="col-md-1"><span class="pull-right">Total</span></td>
		                            <!-- <td class="col-md-1"></td> -->
		                            <!-- <td class="col-md-2" colspan="2">Total</td> -->
		                        </tr>
		                    </thead>
		                    <tbody>
		                        @if (sizeof(Cart::content()) == 0)
		                        <tr>
		                            <td colspan="5">Your shopping cart is empty.</td>
		                            <?php $totalitems = 0; ?>
		                        </tr>
		                        @else
		                        <?php $totalitems = 0; ?>
		                        @foreach (Cart::content() as $item)
		                        <?php $totalitems = $totalitems + $item->qty; // dd($item->model); ?>
		                        <tr>
		                            <td class="cart-event">
		                                 <div style="float:left; width: calc(100% - 30px);">{{ $item->name }} <span class="cetype">({{ $item->model->header }})</span></div>
		                                 <a data-toggle="tooltip" data-placement="left" title="Remove event" class="btn btn-danger btn-xs btn-remove pull-right" href="{{ $item->rowId }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
		                            </td>
       	                            <td><span class="pull-right">{{ $item->qty }}x &euro;{{ $item->price }}</span></td>
		                            <td><span class="pull-right">&euro;{{ $item->subtotal }}</span></td>
		                        </tr>
		                        @endforeach


		                        @endif
		                    </tbody>
		                </table>

		                <table class="table table-striped table-bordered table-hover">

		                    <tbody>
		                        @if (sizeof(Cart::content()) != 0)

		                        	<!-- <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr> -->
									<tr>
		                            <td width="90%">
		                                <span class="pull-right">Total Tickets</span>
		                            </td>
		                            <td  width="10%"><span class="pull-right">{{ $totalitems }} ({{ Cart::content()->count() }} type<?php if(Cart::content()->count() > 1) echo 's'; ?>)</span></td>
		                        </tr>
		                        <tr>
		                            <td>
		                                <span class="pull-right">Subtotal</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->subtotal() }}</span></td>
		                        </tr>
		                        <!-- <tr>
		                            <td colspan="4">
		                                <span class="pull-right">Subtotal (with discounts)</span>
		                            </td>
		                            <td></td>
		                        </tr> -->

		                        <!-- <tr>
		                            <td colspan="4">
		                                <span class="pull-right">E</span>
		                            </td>
		                            <td></td>
		                        </tr> -->
		                        <tr>
		                            <td>
		                                <span class="pull-right">Tax (23%)</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->tax() }}</span></td>
		                        </tr>

		                        <tr>
		                            <td>
		                                <span class="pull-right">Total</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->total() }}</span></td>
		                        </tr>
		                        @endif
		                    </tbody>
		                </table>



			</div>

		</div>
	</div>
</div>

<div id="footer-payments-logos">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">


                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Verified by Visa logo">
                                    <img class="img-responsive center-block" alt="Verified by Visa" title="Verified by Visa" src="theme/assets/img/payways/verified_visa.png" />
                                </a>
                            </div>
                        <div class="payment-tile">
                            <a target="_blank" href="#" title="Mastercard logo">
                                <img class="img-responsive center-block" alt="Mastercard logo" title="Mastercard logo" src="theme/assets/img/payways/mastercard.png" />
                            </a>
                        </div>
                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Safekey logo">
                                    <img class="img-responsive center-block" alt="Safekey logo" title="Safekey logo" src="theme/assets/img/payways/safekey.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="#" title="Visa logo">
                                <img class="img-responsive center-block" alt="Visa logo" title="Visa logo" src="theme/assets/img/payways/visa.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="#" title="New Mastercard logo">
                                <img class="img-responsive center-block" alt="New mastercard logo" title="New mastercard logo" src="theme/assets/img/payways/mastercardnew.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="#" title="Maestro logo">
                                <img class="img-responsive center-block" alt="Maestro logo" src="theme/assets/img/payways/maestronew.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="#" title="American express logo">
                                    <img class="img-responsive center-block" alt="American express logo" title="American express logo" src="theme/assets/img/payways/americanex.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Diners logo">
                                    <img class="img-responsive center-block" alt="Diners logo" title="Diners logo" src="theme/assets/img/payways/diners.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Masterpass logo">
                                    <img class="img-responsive center-block" alt="Masterpass logo" title="Masterpass logo" src="theme/assets/img/payways/masterpass.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Alpha Bank logo">
                                    <img class="img-responsive center-block" alt="Alpha bank logo" title="Alpha bank logo" src="theme/assets/img/payways/alpha_bank.png" />
                                </a>
                        </div>
                        <div class="payment-tile">
                                <a target="_blank" href="#" title="Alpha ecommerce logo">
                                    <img class="img-responsive center-block" alt="Alpha ecommerce logo" title="Alpha ecommerce logo" src="theme/assets/img/payways/alpha-ecommerce.png" />
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach(Cart::content() as $row) :?>

                        <tr>
                            <td>
                                <p><strong><?php echo $row->name; ?></strong></p>
                                <p><?php echo ($row->options->has('size') ? $row->options->size : ''); ?></p>
                            </td>
                            <td><input type="text" value="<?php echo $row->qty; ?>"></td>
                            <td>$<?php echo $row->price; ?></td>
                            <td>$<?php echo $row->total; ?></td>
                        </tr>

                    <?php endforeach;?>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Subtotal</td>
                        <td><?php echo Cart::subtotal(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Tax</td>
                        <td><?php echo Cart::tax(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Total</td>
                        <td><?php echo Cart::total(); ?></td>
                    </tr>
                </tfoot>
            </table> -->

@stop


@section('scripts')
<script src="theme/assets/js/cart.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '</div><div class="field"><input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button>';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);

            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });

    $("#need_invoice").on("change", function () {
        if ($(this).prop("checked") === true) {
            $('#invoice-form').show();
        } else {
            $('#invoice-form').hide();
        }
    });

    /*$("#agree_terms").on("change", function () {
		if ($(this).prop("checked") === true) {
			letsPay();
		} else {
			$('.step_area[data-dp-step="4"] .user_details').html('');
		}
	});*/


});

/*function letsPay() {
	$('.step_area[data-dp-step="4"] .user_details').html('<span class="btn btn-default-inv step_finish" data-dp-step="4">ΟΛΟΚΛΗΡΩΣΗ ΠΑΡΑΓΓΕΛΙΑΣ</span>');
}*/
</script>
<script type="text/javascript">

		/*function acceptance() {
			var accept_btn = document.getElementById('accbtn').checked;
			var form_post = document.getElementById('demo');
			if (accept_btn)
			{
				form_post.submit();
			} else {
				alert("Please accept the Terms of use (I Agree)");
			}
		}*/
		</script>
@stop
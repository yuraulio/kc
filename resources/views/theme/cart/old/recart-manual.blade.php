@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@inject('cFieldLib', 'Library\CustomFieldHelperLib')

<script src="theme/assets/js/jquery.bootstrap-touchspin.min.js"></script>
<script src="theme/assets/js/bootstrap-select.min.js"></script>
<!-- <div id="event-banner">

</div> -->
<div class="page-helper contact-help mob-help" id="cart-page"></div>
<div id="main-content-body" class="cart-page">

	<div class="container">
		<div class="page-header">
		    <h1>Manual Ticket Booking</h1>
		    @if(isset($info) && isset($info['message']) && $info['message'] != '')

			<div class="pgCont {{ $info['statusClass'] }}">
				<?php echo ((isset($info) && isset($info['title'])) ? $info['title'] : 'Info') ?>
				<?php $res = json_decode($info['transaction']['payment_response'], true); ?>
                {!! $info['message'] !!}
                <span class="hidden"><strong>Transaction: {{ $info['transaction']['id'] }}<br />Message: {{ $res['status'] }}</strong></span>
            </div>

            @endif
		    <!-- <p class="lead">All your knowledge tickets</p> -->
		</div>

		<div class="row">
		    <div class="col-lg-12">

		        <div class="table-responsive">
		        	{!! Form::open(['route' => 'adminPaySbt', 'method' => 'post', 'id' => 'sbt-pay', 'class' => 'cartForm']) !!}
		            <!-- <form role="form" method="post" action="cart/checkout" class="cartForm"> -->
		            	 @if (sizeof(Cart::content()) != 0)
			            	<!-- <div class="cart-buttons">
			            	 <a href="{{ route('cart.destroy') }}" class="btn btn-booknow andnormal">Empty Cart</a>
				                <div class="pull-right">
				                	<button class="btn btn-green update-cart">Update</button>
				                    <button type="submit" class="btn btn-green-invert do-checkout">Checkout</button>
				                </div>
			                </div> -->
		                @endif

		                {{ csrf_field() }}

		                @if (sizeof(Cart::content()) == 0)
		                	@if(!isset($info) && !isset($info['message']))
		                        <!-- <h5>Booking cart is empty.</h5> -->
		                        <div style="display: inline-block; width: 60%; ">
			                        <select id="newevent" class="selectpicker" data-width="fit" name="newevent"  data-size="10">
							            <option value="0">Select Event</option>
							            @foreach ($events as $ttype => $name)
							            <option value="{!! $ttype !!}" <?php if (isset($eventid) && $ttype == $eventid) { echo 'selected="selected"'; }?>>{{ $name }}</option>
							            @endforeach

							        </select>




    <?php //dd($linkedseats); ?>
    <div class="container">
    	<div class="row seats-bgcolor seatfullwrap">
    		@if(isset($linkedseats))
            <h2>Select Ticket Type</h2>
    		<div class="seats" id="seatscarousel">
    			@foreach($linkedseats as $key => $value)
    			<?php //dd($value->ticket); ?>
    			@if(isset($value->type) && $value->type != 0)
		        @if($value->type == 1)
                    @if(isset($value->stock))
                    	@if(isset($value->price) && $value->price != 0)

                    		<?php

                        		$tick = $value->ticket;
                        		$isonCart = Cart::search(function ($cartItem, $rowId) use ($tick) {

    								return $cartItem->id === $tick->id;
                                }); //echo count($isonCart);
                                //echo $value['c_fields']['dropdown_boolean'][0]['value'];
							?>


    						@if(isset($value->ticket['c_fields']['dropdown_boolean'][0]) && $value->ticket['c_fields']['dropdown_boolean'][0]['value'] == 1)
    							<?php $blue = 'fblue'; $wprice = ''; ?>
    						@else
    							<?php $blue = '';  $wprice = 'wprice'; ?>
    						@endif
			                <div class="seat-tile seatfull <?php echo $blue; ?>">
		                            <h3>{{ $value->ticket['header'] }}</h3>

		                            <br /><br /><br /><br /><br /><br /><br /><br />
			                    <?php

		                        if (isset($value->price)) {
		                            $price = $value->price;
		                        } else { $price = 0; }
						        ?>
						        <div class="gobottom">
						        <?php
		                        echo '<span class="seatprice '.$wprice.'">&euro;' . $price . ' </span><br />'; ?>
		                        <div class="seats-remain <?php echo $wprice; ?>">
		                        {{ $value->stock }} seats remaining</div>

		                        @if($dis > 0 && $dis != $value->ticket_id)

		                        	@if($value->stock == 0)
						    			<div class="btn btn-completed">SOLD-OUT</div>
						    		@else
						    			<div class="btn btn-completed">AVAILABLE SOON</div>
						    		@endif

		                        @else

	                        		@if(count($isonCart) == 0)

								        @if(isset($value->ticket['c_fields']['dropdown_boolean'][1]) && $value->ticket['c_fields']['dropdown_boolean'][1]['value'] == 1)

								        	<!-- if($value->stock == 0)
								    			<div class="btn btn-completed">SOLD-OUT</div>
								    	    else -->
    					    					<div class="dropdown">
    												<button class="btn btn-booknow-full dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    												    BOOK NOW
    												</button>
    												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 1 ]) }}">UNEMPLOYED</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 2 ]) }}">STUDENT</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 3 ]) }}">KNOWCRUNCH ALUMNI</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 4 ]) }}">DEREE ALUMNI</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 5 ]) }}">GROUP OF 2+</a>
    												</div>
    											</div>

											<!-- endif -->

					    				@else

					    					<!-- if($value->stock == 0)
								    			<div class="btn btn-completed">SOLD-OUT</div>
								    		else -->
								    			<a class="btn btn-booknow-full btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 0 ]) }}">BOOK NOW</a>
								    		<!-- endif -->


					    				@endif

					    			@else

					    				@if($value->stock == 0)
							    			<div class="btn btn-completed">SOLD-OUT</div>
							    		@else
							    			<a class="btn btn-booknow-full" href="/cart">BOOK NOW</a>
							    		@endif

					    			@endif

						    	@endif



								    <!-- else
								    	<div style="display:block; height: 42px; min-height:42px;"></div>
								    endif -->
						        <!-- endif -->

						        </div>
		                	</div>

                    	@endif
                    @endif


                @endif
                @endif
                @endforeach
            </div>
            @endif
    	</div>
    </div>













							    </div>
		                    @endif
		                            <?php $totalitems = 0; ?>
		                        </tr>
		                @else
                        <h3>Booking Details</h3>
		                <table class="table table-striped table-bordered table-hover">
		                    <thead>
		                        <tr>
		                            <td class="col-md-6">Event</td>
		                            <td class="col-md-1">Type</td>
		                            <td class="col-md-1"># of Seats</td>

		                            <td class="col-md-1"><span class="pull-right">Price</span></td>
		                            <td class="col-md-1"><span class="pull-right">Total</span></td>
		                            <!-- <td class="col-md-1"></td> -->
		                            <!-- <td class="col-md-2" colspan="2">Total</td> -->
		                        </tr>
		                    </thead>
		                    <tbody>

		                        <?php $totalitems = 0;




		                        ?>
		                        @foreach (Cart::content() as $item)
		                        <?php $totalitems = $totalitems + $item->qty; // dd($item->model); ?>
		                        <tr>
		                            <td class="cart-event">
		                                <!-- <div class="col-md-2">
		                                    <img src="http://placehold.it/80x80" alt="..." class="img-thumbnail">
		                                </div> -->

		                                 <span style="padding-right: 30px;">{{ $item->name }}</span>
		                                 <a data-toggle="tooltip" data-placement="left" title="Remove event" class="btn btn-danger btn-xs btn-remove pull-right" href="{{ $item->rowId }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
		                            </td>
		                            <td>{{ $item->model->header }} <?php
		                            	if($item->options->has('type')) {
		                            		$thetype = $item->options->type;
		                            		switch ($thetype) {
		                            			case 0:
		                            				echo "";
		                            				break;
		                            			case 1:
		                            				echo "<br><span class='subtickettype'>UNEMPLOYED</span>";
		                            				$placeid = 'Unemployed Id';
		                            				break;
		                            			case 2:
		                            				echo "<br><span class='subtickettype'>STUDENT</span>";
		                            				$placeid = 'Student Id';
		                            				break;
		                            			case 3:
		                            				echo "<br><span class='subtickettype'>KNOWCRUNCH ALUMNI</span>";
		                            				$placeid = 'Knowcrunch Id';
		                            				break;
		                            			case 4:
		                            				echo "<br><span class='subtickettype'>DEREE ALUMNI</span>";
		                            				$placeid = 'Deree Id';
		                            				break;
		                            			case 5:
		                            				echo "<br><span class='subtickettype'>GROUP OF 2+</span>";
		                            				$placeid = '';
		                            				break;


		                            			default:
		                            				echo "";
		                            				$placeid = '';
		                            				break;
		                            		}

		                            	}
		                            	else {
		                            		$placeid = '';
		                            	}

		                          //  echo ($item->options->has('type') ? $item->options->type : ''); ?></td>
		                            <td>
		                                <!-- <input class="form-control" type="text" name="update[{{ $item->rowId }}][quantity]" value="{{{ $item->qty }}}" /> -->

		                                <?php $mod = $item->model; $cFieldLib->contentCustomFields($mod);

		                                //dd($mod);




		                                //get stock of this ticket
		                                $curStock = 1;
		                                foreach ($eventtickets as $tkey => $tvalue) {

				                        	if ($tvalue->event_id == $item->options->event && $tvalue->ticket_id == $mod->id) {

				                        		$curStock = $tvalue->stock;
				                        	}
				                        	/*else {
				                        		$curStock = 1;
				                        	}*/
				                        }

                                		if($item->options->has('type')) {
                            				$thetype = $item->options->type;
                            				//echo $thetype;
                            				if ($thetype == 5) {
                            					$min = 2;
                            				}
                            				else {
                            					$min = 1;
                            				}
                            			}
                            			else {
                            				$min = 1;
                            			}

		                               	?>


		                                <input readonly id="item00<?php echo $item->model->id; ?>" type="hidden" value="{{ $item->qty }}" name="update[{{ $item->rowId }}][quantity]" value="{{{ $item->qty }}}" class="qtyspinner" min="{{$min}}" max="{{ $curStock }}" />

                                        1



										<script>
										/*$("input#item00<?php echo $item->model->id; ?>").TouchSpin({verticalbuttons: true, mousewheel: false, booster: false, min: <?php echo $min; ?>,
											max: {{ $curStock }}
											});*/


										</script>







		                            </td>

		                            <td><span class="pull-right">{{ $item->qty }}x &euro;{{ $item->price }}</span></td>
		                            <td><span class="pull-right">&euro;{{ $item->subtotal }}</span></td>
		                            <!-- <td align="center">

		                                <a class="btn btn-danger btn-xs btn-remove" href="{{ $item->rowId }}">Delete</a>

		                            </td> -->
		                        </tr>
		                        @endforeach

		                        <!-- <tr>
		                            <td colspan="5">
		                                <span class="pull-right">Total Tickets</span>
		                            </td>
		                            <td>{{ $totalitems }} ({{ Cart::content()->count() }} types)</td>
		                        </tr>
		                        <tr>
		                            <td colspan="5">
		                                <span class="pull-right">Subtotal</span>
		                            </td>
		                            <td>&euro;{{ Cart::instance('default')->subtotal() }}</td>
		                        </tr>

		                        <tr>
		                            <td colspan="5">
		                                <span class="pull-right">Tax {{ Cart::instance('default')->taxRate }}(23%)</span>
		                            </td>
		                            <td>&euro;{{ Cart::instance('default')->tax() }}</td>
		                        </tr>

		                        <tr>
		                            <td colspan="5">
		                                <span class="pull-right">Total</span>
		                            </td>
		                            <td>&euro;{{ Cart::instance('default')->total() }}</td>
		                        </tr> -->
		                        @endif
		                    </tbody>
		                </table>
		                @if (sizeof(Cart::content()) != 0)
		                <h3>Personal Ticket Information</h3>

		                <input type="hidden" name="count" value="1" />

			                    	<?php for ($i=0; $i < $totalitems; $i++) : ?>
			                        <div id="field<?php echo $i; ?>" class="acfield">
			                        	<h4>Seat #<?php echo $i+1; ?></h4>

				                        <div class="row">
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="name<?php echo $i; ?>" name="name[]" placeholder="Name" value="@if(isset($pay_seats_data) && isset($pay_seats_data['names'][$i])){{$pay_seats_data['names'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="surname<?php echo $i; ?>" name="surname[]" placeholder="Surname" value="@if(isset($pay_seats_data) && isset($pay_seats_data['surnames'][$i])){{$pay_seats_data['surnames'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="email<?php echo $i; ?>" name="email[]" placeholder="Email" value="@if(isset($pay_seats_data) && isset($pay_seats_data['emails'][$i])){{$pay_seats_data['emails'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

									            <div class="form-group endrow">
								            		<input type="text" class="form-control" id="mobile<?php echo $i; ?>" name="mobile[]" placeholder="Mobile" value="@if(isset($pay_seats_data) && isset($pay_seats_data['mobiles'][$i])){{$pay_seats_data['mobiles'][$i]}}@endif">
								            	</div>
								            </div>
								        </div>
								        <div class="row">
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="address<?php echo $i; ?>" name="address[]" placeholder="Street Name" value="@if(isset($pay_seats_data) && isset($pay_seats_data['addresses'][$i])){{$pay_seats_data['addresses'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="addressnum<?php echo $i; ?>" name="addressnum[]" placeholder="Street Number" value="@if(isset($pay_seats_data) && isset($pay_seats_data['addressnums'][$i])){{$pay_seats_data['addressnums'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="postcode<?php echo $i; ?>" name="postcode[]" placeholder="Post Code" value="@if(isset($pay_seats_data) && isset($pay_seats_data['postcodes'][$i])){{$pay_seats_data['postcodes'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

									            <div class="form-group endrow">
								            		<input type="text" class="form-control" id="city<?php echo $i; ?>" name="city[]" placeholder="City" value="@if(isset($pay_seats_data) && isset($pay_seats_data['cities'][$i])){{$pay_seats_data['cities'][$i]}}@endif">
								            	</div>
								            </div>

								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								            	<div class="form-group startrow">
								            		<input type="text" class="form-control" id="jobtitle<?php echo $i; ?>" name="jobtitle[]" placeholder="Title or Job Position" value="@if(isset($pay_seats_data) && isset($pay_seats_data['jobtitles'][$i])){{$pay_seats_data['jobtitles'][$i]}}@endif">
								            	</div>
								            </div>
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

									            <div class="form-group endrow">
								            		<input type="text" class="form-control" id="company<?php echo $i; ?>" name="company[]" placeholder="Company (if any)" value="@if(isset($pay_seats_data) && isset($pay_seats_data['companies'][$i])){{$pay_seats_data['companies'][$i]}}@endif">
								            	</div>
								            </div>

								            @if($thetype != 5 && $thetype != 0)
								            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

									            <div class="form-group endrow">
								            		<input type="text" class="form-control" id="student<?php echo $i; ?>" name="student[]" placeholder="<?php echo $placeid; ?>" value="@if(isset($pay_seats_data) && isset($pay_seats_data['students'][$i])){{$pay_seats_data['students'][$i]}}@endif">
								            	</div>
								            </div>
								            @endif


								        </div>
			                        </div>

			                    <?php endfor; ?>

			                   <br />

			            @endif
					@if (sizeof(Cart::content()) != 0)
					<h3>Receipt Options</h3>

					<div class="ckoutbx_option">

							<div class="btn-group" data-toggle="buttons" id="billing">
						        <label class="btn @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 0) active @elseif(!isset($pay_bill_data['billing'])) active @endif">
						          <input type="radio" name="needbilling" value="0" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 0) checked @elseif(!isset($pay_bill_data['billing'])) checked @endif><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Receipt details are the same as Seat #1 above</span>
						        </label>
						        <label class="btn @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) active @endif">
						          <input type="radio" name="needbilling" value="1" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) checked @endif ><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Receipt details are different</span>
						        </label>

						        <label class="btn @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 2) active @endif">
						          <input type="radio" name="needbilling" value="2" @if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 2) checked @endif ><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  I need an invoice</span>
						        </label>
					      	</div>
							<br />

                            <div id="billing-form" style="@if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 1) display:block @endif" class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billname" name="billname" placeholder="Name" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billname'])){{$pay_bill_data['billname']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billsurname" name="billsurname" placeholder="Surname" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billsurname'])){{$pay_bill_data['billsurname']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billemail" name="billemail" placeholder="Email" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billemail'])){{$pay_bill_data['billemail']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billmobile" name="billmobile" placeholder="Mobile" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billmobile'])){{$pay_bill_data['billmobile']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billaddress" name="billaddress" placeholder="Street Name" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billaddress'])){{$pay_bill_data['billaddress']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billaddressnum" name="billaddressnum" placeholder="Street Number" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billaddressnum'])){{$pay_bill_data['billaddressnum']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billpostcode" name="billpostcode" placeholder="Post Code" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billpostcode'])){{$pay_bill_data['billpostcode']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6  col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="billcity" name="billcity" placeholder="City" value="@if(isset($pay_bill_data) && isset($pay_bill_data['billcity'])){{$pay_bill_data['billcity']}}@endif">
                                    </div>
                                </div>
                            </div>

						</div>



						<div class="ckoutbx_option">

							<!-- <div class="custom-checkbox" id="invoice">
								<input class="c-box" type="checkbox" id="needinvoice" name="needinvoice" value="0" <?php if(isset($pay_invoice_data) && isset($pay_invoice_data["invoice"]) && $pay_invoice_data["invoice"] == 1) echo 'checked'; ?>><label>I need an invoice</label>
							</div>
							<br /> -->

                            <div id="invoice-form" class="row" style="@if(isset($pay_bill_data) && isset($pay_bill_data['billing']) && $pay_bill_data['billing'] == 2) display:block @endif">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyname" name="companyname" placeholder="ΕΠΩΝΥΜΙΑ ΕΤΑΙΡΙΑΣ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyname'])){{$pay_bill_data['companyname']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyprofession" name="companyprofession" placeholder="ΔΡΑΣΤΗΡΙΟΤΗΤΑ ΕΤΑΙΡΙΑΣ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyprofession'])){{$pay_bill_data['companyprofession']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyafm" name="companyafm" placeholder="Α.Φ.Μ." value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyafm'])){{$pay_bill_data['companyafm']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companydoy" name="companydoy" placeholder="ΔΟΥ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companydoy'])){{$pay_bill_data['companydoy']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyaddress" name="companyaddress" placeholder="ΔΙΕΥΘΥΝΣΗ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyaddress'])){{$pay_bill_data['companyaddress']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companyaddressnum" name="companyaddressnum" placeholder="ΑΡΙΘΜΟΣ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companyaddressnum'])){{$pay_bill_data['companyaddressnum']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companypostcode" name="companypostcode" placeholder="Τ.Κ." value="@if(isset($pay_bill_data) && isset($pay_bill_data['companypostcode'])){{$pay_bill_data['companypostcode']}}@endif">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="companycity" name="companycity" placeholder="ΠΟΛΗ" value="@if(isset($pay_bill_data) && isset($pay_bill_data['companycity'])){{$pay_bill_data['companycity']}}@endif">
                                    </div>
                                </div>
                            </div>
                            <br />

						</div>



					<h3>Payment Options</h3>

						<div class="btn-group" data-toggle="buttons" id="cardtype">
					        <!-- <label class="btn <?php if(isset($cardtype) && $cardtype == 1) echo 'active'; elseif(isset($cardtype) && $cardtype != 2) echo 'active'; ?>">
					          <input type="radio" name='cardtype' value='1' <?php if(isset($cardtype) && $cardtype == 1) echo 'checked';  elseif(isset($cardtype) && $cardtype != 2) echo 'checked';  ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Debit Card (no installments)</span>
					        </label>
					        <label class="btn <?php if(isset($cardtype) && $cardtype == 2) echo 'active'; ?>">
					          <input type="radio" name='cardtype' value='2' <?php if(isset($cardtype) && $cardtype == 2) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Credit Card (1-3 installments)</span>
					        </label> -->
					        <label class="btn <?php if(isset($cardtype) && $cardtype == 3) echo 'active'; elseif(isset($cardtype) && $cardtype != 4) echo 'active'; ?>">
					          <input type="radio" name='cardtype' value='3' <?php if(isset($cardtype) && $cardtype == 3) echo 'checked'; elseif(isset($cardtype) && $cardtype != 4) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Bank Transfer</span>
					        </label>
					        <label class="btn <?php if(isset($cardtype) && $cardtype == 4) echo 'active'; ?>">
					          <input type="radio" name='cardtype' value='4' <?php if(isset($cardtype) && $cardtype == 4) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  Cash</span>
					        </label>
				      	</div>
				      	<br />
				      	<?php
				      		$instOne = Cart::instance('default')->subtotal();
				      		$instTwo = round($instOne / 2, 2);
				      		$instThree = round($instOne / 3, 2);
				      	 ?>
				      	<div id="installments-form" style="<?php if(isset($cardtype) && $cardtype == 2) echo 'display:block;'; ?>" class="btn-group" data-toggle="buttons">
					        <label class="btn <?php if(isset($installments) && $installments == 1) echo 'active'; ?>">
					          <input type="radio" name='installments' value='1' <?php if(isset($installments) && $installments == 1) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i> <span>  1 installment x{{$instOne}}&euro;</span>
					        </label>
					        <label class="btn <?php if(isset($installments) && $installments == 2) echo 'active'; ?>">
					          <input type="radio" name='installments' value='2' <?php if(isset($installments) && $installments == 2) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> 2 installments x{{$instTwo}}&euro;</span>
					        </label>
					        <label class="btn <?php if(isset($installments) && $installments == 3) echo 'active'; ?>">
					          <input type="radio" name='installments' value='3' <?php if(isset($installments) && $installments == 3) echo 'checked'; ?>><i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> 3 installments x{{$instThree}}&euro;</span>
					        </label>
				      	</div>


						<br />

						<span class="hidden">
                        {!! Form::select('payment_method_id', array_pluck($pay_methods, 'method_name', 'id'), null, ['class' => 'selectpicker']) !!}
                        </span>

						<!--  <input type="hidden" id="payment_method_id" name="payment_method_id" value="2"> -->

					@endif

						<br />

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
		                        <!-- <tr>
		                            <td>
		                                <span class="pull-right">Subtotal</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->subtotal() }}</span></td>
		                        </tr> -->

		                        <!-- <tr>
		                            <td>
		                                <span class="pull-right">Tax (23%)</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->tax() }}</span></td>
		                        </tr> -->

		                        <tr>
		                            <td>
		                                <span class="pull-right">Total</span>
		                            </td>
		                            <td><span class="pull-right">&euro;{{ Cart::instance('default')->subtotal() }}</span></td>
		                        </tr>
		                        @endif
		                    </tbody>
		                </table>

		                 @if (sizeof(Cart::content()) != 0)

		                <div class="mobileFR">
		                	<div class="custom-checkbox-uni" id="terms" >

							<input class="c-box" id="accept" type="checkbox" value="0" name="accept" required="" checked="checked"><a id="toggleterms" target="_blank" title="View Terms and conditions" href="/terms-privacy">I agree &amp; accept the terms &amp; conditions</a>
							</div>

							<br />

			                <div class="mobileR">
			                    {!! Form::submit('FINISH BOOKING', ['class' => 'btn btn-booknow-full do-checkout']) !!}
			                </div>
		                </div>

		                @endif

		            </form>


		            <br />
		        </div>
		    </div>
		</div>
	</div>
</div>


<div id="footer-payments-logos">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">


                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/verified_visa.png" />
                                </a>
                            </div>
                        <div class="payment-tile">
                            <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/mastercard.png" />
                            </a>
                        </div>
                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/safekey.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/visa.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/mastercardnew.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                            <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/maestronew.png" />
                            </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/americanex.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/diners.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/masterpass.png" />
                                </a>
                        </div>

                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/alpha_bank.png" />
                                </a>
                        </div>
                        <div class="payment-tile">
                                <a target="_blank" href="javaScript:void(0);" title="Payment Logo">
                                    <img class="img-responsive center-block" alt="" src="theme/assets/img/payways/alpha-ecommerce.png" />
                                </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

<div class="loadmodal"></div>
@stop


@section('scripts')
<script src="theme/assets/js/mcart.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<div id="field' + next + '" class="acfield"><div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="name' + next + '" name="name[]" placeholder="Name"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="surname' + next + '" name="surname[]" placeholder="Surname"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="email' + next + '" name="email[]" placeholder="Email"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group endrow"><input type="text" class="form-control" id="mobile' + next + '" name="mobile[]" placeholder="Email"></div></div></div><div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="address' + next + '" name="address[]" placeholder="Street Name"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="addressnum' + next + '" name="addressnum[]" placeholder="Street Address"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group startrow"><input type="text" class="form-control" id="postcode' + next + '" name="postcode[]" placeholder="Post Code"></div></div><div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><div class="form-group endrow"><input type="text" class="form-control" id="city' + next + '" name="city[]" placeholder="City"></div></div></div></div>';



        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-booknow andnormal remove-me" >-</button>';
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
});
</script>

<script>

  $("input.qtyspinner").on("change", function() {
  	  $.ajax({ url: '/cart', type: "post",
            data: $(".cartForm").serialize(),
            success: function(data) {
            	ajaxCount('default');
				window.location.replace('admin/mcart/');
            }
        });
  });
</script>



<script type="text/javascript">
$(document).ready(function(){

	 $('.selectpicker').selectpicker({
		  style: 'form-control btn-booknow andnormal',
		  size: 'auto'
		});

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

    $("a#toggleterms:before").on("click", function () {
    	var thec = $(this).find('input[type=checkbox]');
        if (thec.prop("checked") === false) {
            thec.prop('checked', true);
            thec.val('1');
        } else {
            thec.prop('checked', false);
            thec.val('0');
        }
    });

/*    $("#invoice").on("click", function () {
    	var thec = $(this).find('input[type=checkbox]');
        if (thec.prop("checked") === false) {
            $('#invoice-form').slideDown();
            thec.prop('checked', true);
            thec.val('1');
        } else {
            $('#invoice-form').slideUp();
            thec.prop('checked', false);
            thec.val('0');
        }
    });

    	var invoice =  $("#invoice");
    	var thec = invoice.find('input[type=checkbox]');
        if (thec.prop("checked") === false) {
            $('#invoice-form').slideUp();
            thec.prop('checked', false);
            thec.val('0');
        } else {
            $('#invoice-form').slideDown();
            thec.prop('checked', true);
            thec.val('1');
        }*/




    $('#billing input[type=radio]').on('change', function() {
    	//console.log(this.value);
    	if (this.value == 1) {
    		$('#billing-form').slideDown();
    		$('#invoice-form').slideUp();
    	}
    	else if (this.value == 2) {
    		$('#billing-form').slideUp();
    		$('#invoice-form').slideDown();
    	}
    	else {
    		$('#billing-form').slideUp();
    		$('#invoice-form').slideUp();
    	}
    //console.log(this.value);
	});


	$('#cardtype input[type=radio]').on('change', function() {
    	//console.log(this.value);
    	if (this.value == 2) {
    		$('#installments-form').slideDown();
    	} else {
    		$('#installments-form').slideUp();

    	}
	});



});


</script>
<script type="text/javascript">

		function acceptance() {
			var accept_btn = document.getElementById('accbtn').checked;
			var form_post = document.getElementById('demo');
			if (accept_btn)
			{
				form_post.submit();
			} else {
				alert("Please accept the Terms of use (I Agree)");
			}
		}

$('select').on('change', function() {
  //alert( this.value );
  window.location.replace('admin/mcart/' + this.value);
  /*$.ajax({
		url: 'admin/gettickets', type: "post", data: {eventid: this.value}
	}).done(function(res) {
		//console.log(res);
		if (res.message === 'success')
		{

			window.location.replace('admin/mcart/' + res.id);
		}
		else {
			self.show();

		}
	});*/
})

</script>


@stop

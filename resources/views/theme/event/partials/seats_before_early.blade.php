<!-- SEATS -->
@if(isset($section_seats))
<div id="section-seats">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
       			<h2>{{ $section_seats->title }}</h2>
               	{!! $section_seats->body !!}
            </div>
        </div>
    </div>
    <?php //$taglessBody = strip_tags($event_blocks[3]->summary); echo " " . $taglessBody; ?>
	<?php /*$isonCart = Cart::search(function ($cartItem, $rowId) use ($content) {
		return $cartItem->id === $content->id;
	});*/ ?>
    <?php $all = count($linkedseats); ?>
    @foreach($linkedseats as $key => $value)
        @if($value->price && $value->price != 0)
        @else
          <?php $all--; ?>
        @endif
    @endforeach
    <?php //dd($linkedseats); ?>
    <div class="container hidden-xs">
    	<div class="row seats-bgcolor @if($all >= 3) seatfullwrap @endif">
    		@if(isset($linkeddata))
    		<div class="seats" id="seatscarousel">
    			@foreach($linkedseats as $key => $value)
    			<?php //dd($value->ticket); ?>
    			@if(isset($value->type) && $value->type != 0)
		        @if($value->type == 1)

                    @if(isset($value->stock) && $value->stock > 0)

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
			                <div class="seat-tile @if($all >= 3) seatfull @endif <?php echo $blue; ?>">
			                		<!-- $value['c_fields']['input_num'][0]['value'] TOTAL -->
		                            <h3>{{ $value->ticket['header'] }}</h3>
		                            <h4>{{ $value->ticket['subtitle'] }}</h4>
				                        <!-- {{ $value['header'] }} -->
		                            <div class="seat-features">
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][0]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][1]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][2]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][3]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][4]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][5]['value'] }}</div>
		                            </div>

			                    <?php

		                        if (isset($value->price)) {
		                            $price = $value->price;
		                        } else { $price = 0; }

		                        /*if ($value['short_title'] != '') {
						        	$price = (float)$value['short_title'];
						        } else { $price = 0.00; }  */


						        ?>
						        <div class="gobottom">
						        <?php
		                        echo '<span class="seatprice '.$wprice.'">&euro;' . $price . ' </span><br />'; ?>
		                        <div class="seats-remain <?php echo $wprice; ?>">
		                        <!-- $value['c_fields']['input_num'][1]['value'] TICKET PRICE-->
		                        {{ $value->stock }} seats remaining</div>

		                        <!--if(isset($tickettypes[$value->id]) && $tickettypes[$value->id] != 0)
		                        	if($tickettypes[$value->id] == 1)-->

		                        @if($dis > 0 && $dis != $value->ticket_id)

		                        	@if($value->stock == 0)
						    			<div class="btn btn-completed">SOLD-OUT</div>
						    		@else
						    			<div class="btn btn-completed">AVAILABLE SOON</div>
						    		@endif

		                        @else

	                        		@if(count($isonCart) == 0)

								        @if(isset($value->ticket['c_fields']['dropdown_boolean'][1]) && $value->ticket['c_fields']['dropdown_boolean'][1]['value'] == 1)

								        	@if($value->stock == 0)
								    			<div class="btn btn-completed">SOLD-OUT</div>
								    		@else
    					    					<div class="dropdown">
    												<button class="btn btn-booknow-full dropdown-toggle fbpix" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    												    BOOK NOW
    												</button>
    												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    												    <a class="dropdown-item btn-add" title="Unemployed" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 1 ]) }}">UNEMPLOYED</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 2 ]) }}">STUDENT</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 3 ]) }}">KNOWCRUNCH ALUMNI</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 4 ]) }}">DEREE ALUMNI</a>
    												    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 5 ]) }}">GROUP OF 2+</a>
    												</div>
    											</div>
											@endif

					    				@else

					    					@if($value->stock == 0)
								    			<div class="btn btn-completed">SOLD-OUT</div>
								    		@else
								    			<a class="btn btn-booknow-full btn-add" title="ENROLL NOW" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 0 ]) }}">BOOK NOW</a>
								    		@endif


					    				@endif

					    			@else

					    				@if($value->stock == 0)
							    			<div class="btn btn-completed">SOLD-OUT</div>
							    		@else
							    			<a class="btn btn-booknow-full" title="ENROLL NOW" href="/cart">BOOK NOW</a>
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
<div class="swiper-container hidden-lg hidden-md hidden-sm">

@if(isset($linkeddata))
<div class="swiper-wrapper">
    			@foreach($linkedseats as $key => $value)
    			@if(isset($value->type) && $value->type != 0)
		        @if($value->type == 1)
                    @if(isset($value->stock))
                    	@if(isset($value->price) && $value->price != 0)

                    		<?php $isonCart = Cart::search(function ($cartItem, $rowId) use ($value) {
								return $cartItem->id === $value->id; });
							?>

    						@if(isset($value->ticket['c_fields']['dropdown_boolean'][0]) && $value->ticket['c_fields']['dropdown_boolean'][0]['value'] == 1)
    							<?php $blue = 'fblue'; $wprice = ''; ?>
    						@else
    							<?php $blue = '';  $wprice = 'wprice'; ?>
    						@endif
			                <div class="seat-tile swiper-slide <?php echo $blue; ?>">
			                		<!-- $value['c_fields']['input_num'][0]['value'] TOTAL -->
		                            <h3>{{ $value->ticket['header'] }}</h3>
		                            <h4>{{ $value->ticket['subtitle'] }}</h4>
				                        <!-- {{ $value['header'] }} -->
		                            <div class="seat-features">
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][0]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][1]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][2]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][3]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][4]['value'] }}</div>
			                        	<div class="">{{ $value->ticket['c_fields']['simple_text'][5]['value'] }}</div>
		                            </div>

			                    <?php

		                        if (isset($value->price)) {
		                            $price = $value->price;
		                        } else { $price = 0; }

		                        /*if ($value['short_title'] != '') {
						        	$price = (float)$value['short_title'];
						        } else { $price = 0.00; }  */


						        ?>
						        <div class="gobottom">
						        <?php
		                        echo '<span class="seatprice '.$wprice.'">&euro;' . $price . ' </span><br />'; ?>
		                        <div class="seats-remain <?php echo $wprice; ?>">
		                        <!-- $value['c_fields']['input_num'][1]['value'] TICKET PRICE-->
		                        {{ $value->stock }} seats remaining</div>

		                        <!--if(isset($tickettypes[$value->id]) && $tickettypes[$value->id] != 0)
		                        	if($tickettypes[$value->id] == 1)-->

		                        @if($dis > 0 && $dis != $value->ticket_id)

		                        	@if($value->stock == 0)
						    			<div class="btn btn-completed">SOLD-OUT</div>
						    		@else
						    			<div class="btn btn-completed">AVAILABLE SOON</div>
						    		@endif

		                        @else

		                        		@if(count($isonCart) == 0)
									        @if(isset($value->ticket['c_fields']['dropdown_boolean'][1]) && $value->ticket['c_fields']['dropdown_boolean'][1]['value'] == 1)
									        	@if($value->stock == 0)
									    			<div class="btn btn-completed">SOLD-OUT</div>
									    		@else
						    					<div class="dropdown dropup">
													<button class="btn btn-booknow-full dropdown-toggle dropup fbpix" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													    BOOK NOW
													</button>

													<div class="dropdown-menu " aria-labelledby="dropdownMenuButton">
													    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 1 ]) }}">UNEMPLOYED</a>
													    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 2 ]) }}">STUDENT</a>
													    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 3 ]) }}">KNOWCRUNCH ALUMNI</a>
													    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 4 ]) }}">DEREE ALUMNI</a>
													    <a class="dropdown-item btn-add" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 5 ]) }}">GROUP OF 2+</a>
													</div>

												</div>
												@endif
						    				@else
						    					@if($value->stock == 0)
									    			<div class="btn btn-completed">SOLD-OUT</div>
									    		@else
									    			<a class="btn btn-booknow-full btn-add" title="ENROLL NOW" href="{{ route('cart.add-item', [ $content->id, $value->ticket_id, 0 ]) }}">BOOK NOW</a>
									    		@endif


						    				@endif

						    			@else
						    				@if($value->stock == 0)
								    			<div class="btn btn-completed">SOLD-OUT</div>
								    		@else
								    			<a class="btn btn-booknow-full" title="ENROLL NOW" href="/cart">BOOK NOW</a>
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
@endif
<!-- SEATS END -->

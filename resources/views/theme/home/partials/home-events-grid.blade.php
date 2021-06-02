<!-- HOME PAGE -->
<?php
if(Session::has('scopeone')){
    $fone = Session::get('scopeone');
}
else { $fone = 0; }

if(Session::has('scopetwo')){
	$ftwo = Session::get('scopetwo');
}
else { $ftwo = 0; }

if(Session::has('scopethree')){
    $fthree = Session::get('scopethree');
}
else { $fthree = 0; }

$totalfound = 0;
?>
    <div id="home-page">
        <div class="container">
        @if(isset($events))
                <!-- <div class="row"> -->
                <div id="products" class="row list-group">
                <?php $lastmonth = ''; ?>
                @foreach($events as $key => $row)
                	<?php


                        /*$isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                            return $cartItem->id === $row->id;
                        });*/

                        ?>
                        <?php
                        	$location = [];
                        	$eventtype = [];
                            $eventtopic = [];
                            $advancedtag = 0;
                            $advancedtagslug = '';
                        	if (isset($row->categories) && !empty($row->categories)) :
					            foreach ($row->categories as $category) :
				                    if ($category->depth != 0 && $category->parent_id == 9) {
				                         $location=$category;
				                    }
				                    if ($category->depth != 0 && $category->parent_id == 12) {
				                         $eventtype=$category;
				                    }
                                    if ($category->depth != 0 && $category->parent_id == 22) {
                                        $eventtopic[]=$category->id;
                                    }
                                    if ($category->id == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category->slug;
                                    }
					            endforeach;
					        endif;

						    if ($fone && $fone != 0 && $ftwo && $ftwo != 0 && $fthree && $fthree != 0) {
                                if(!empty($eventtype) && !empty($location) && !empty($eventtopic)) { //was eventtopic only
    					        	if ($location->id == $ftwo && $eventtype->id == $fone && in_array($fthree, $eventtopic)) {
    					        		//$eventtype->id == $fthree
    					        		$dont = true;
    					        	}
    					        	else {
    					        		$dont = false;
    					        	}
                                }
                                else {
                                    $dont = false;
                                }
					        }

					        elseif($fone == 0 && $fthree == 0 && $ftwo != 0) {
					        	if(!empty($location)) {
						        	if ($location->id == $ftwo) {
						        		$dont = true;
						        	}
						        	else {
						        		$dont = false;
						        	}
						        }
					        	else {
					        		$dont = false;
					        	}
					        }
					        elseif($ftwo == 0 && $fone != 0 && $fthree == 0) {
					        	if(!empty($eventtype)) {
						        	if ($eventtype->id == $fone) {
						        		$dont = true;
						        	}
						        	else {
						        		$dont = false;
						        	}
					        	}
                                else {
                                    $dont = false;
                                }

					        }
                            elseif($ftwo == 0 &&  $fone == 0 && $fthree != 0) {
                                if(!empty($eventtopic)) {
                                    if (in_array($fthree, $eventtopic)) {
                                    	//$eventtopic->id == $fthree
                                        $dont = true;
                                    }
                                    else {
                                        $dont = false;
                                    }
                                }
                                else {
                                    $dont = false;
                                }
                            }
                            elseif($fone == 0  && $fthree != 0 && $ftwo != 0) {
                                if(!empty($eventtopic)) {
                                    if ($location->id == $ftwo && in_array($fthree, $eventtopic)) {
                                    	//$eventtopic->id == $fthree
                                        $dont = true;
                                    }
                                    else {
                                        $dont = false;
                                    }
                                }
                                else {
                                    $dont = false;
                                }
                            }
                            elseif($ftwo == 0 && $fthree != 0 && $fone != 0) {
                                if(!empty($eventtopic)) {
                                    if ($eventtype->id == $fone && in_array($fthree, $eventtopic)) {
                                    	//$eventtopic->id == $fthree
                                        $dont = true;
                                    }
                                    else {
                                        $dont = false;
                                    }
                                }
                                else {

                                    $dont = false;

                                }
                            }
                            elseif($fthree == 0 && $fone != 0 && $ftwo != 0) {
                            	if(!empty($eventtype)) {
	                                if ($eventtype->id == $fone && $location->id == $ftwo) {
	                                    $dont = true;
	                                }
	                                else {
	                                    $dont = false;
	                                }
	                            }
                                else {
                                    $dont = false;
                                }
                            }
					        elseif($ftwo == 0 && $fone == 0 && $fthree == 0) {
					        	$dont = true;
					        }


					 if ($dont) {
                        $totalfound++;
                        $chmonth = date('m', strtotime($row->published_at));
	                	$month = date('F Y', strtotime($row->published_at));
	                	if($chmonth != $lastmonth) {
	                		echo '<h4 class="list-calendar">' . $month . '</h4>';
	                		$lastmonth = $chmonth;
	                	}
                        ?>
                    <div class="e-item col-lg-4 col-md-4 col-sm-6">
                        <div class="event-cell">

                            <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
background: -moz-linear-gradient(to bottom, #419cff, #65afff);;
background: -webkit-linear-gradient(to bottom, #419cff, #65afff);
background: linear-gradient(to bottom, #419cff, #65afff);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif>


                                <div class="event-info-wrap">

                                    <!-- <div class="event-info-type" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '') style="color: {{ $eventtype->primary_color }} !important;" @endif>
                                    @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                                    </div> -->
                                    @if(isset($advancedtag) && $advancedtag == 1)
                                        <span class="advancedtag">
                                        <a href="{{ $advancedtagslug }}">Advanced</a>
                                        </span>
                                    @endif


                                    <span class="location-city">
                                        @if(isset($location->name))<a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
                                    </span>

                                    <div class="event-info-title">

                                        <h2>
                                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                        <?php
                                        $string = $frontHelp->pField($row, 'title');
                                         if( strpos($string, ',') !== false ) {
                                           $until = substr($string, 0, strrpos($string, ","));
                                         }
                                         else {
                                            $until = $string;
                                            } ?>
                                        {{ $until }}
                                        </a>
                                        </h2>

                                    </div>

                                  <?php  if (isset($eventprices[$row->id])) {
                                        $price = $eventprices[$row->id];
                                  }
                                  else { $price = 0; } ?>

                                    @if(isset($row['c_fields']['dropdown_select_status']['value']))

                                        <?php $etstatus = $row['c_fields']['dropdown_select_status']['value']; ?>
                                    @endif

                                </div>

                            </div>


                                <div class="event-info-title-list" id="diploma">
                                    @if(isset($advancedtag) && $advancedtag == 1)
                                        <span class="advancedtaglist">
                                        <a href="{{ $advancedtagslug }}">Advanced</a>
                                        </span>
                                    @endif
                                    <h2>
                                    <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    <?php
                                    $string = $frontHelp->pField($row, 'title');
                                     if( strpos($string, ',') !== false ) {
                                       $until = substr($string, 0, strrpos($string, ","));
                                     }
                                     else {
                                        $until = $string;
                                        } ?>
                                    {{ $until }}
                                    </a>
                                    </h2>

                                </div>


                                <div class="event-info-line">
                                    <span class="list-only">
                                    <img src="theme/assets/img/pin.svg" alt="Location" title="Location" />
                                    @if(isset($location->name))<a href="{{ $location->slug }}" title="{{ $location->name }}">{{ $location->name }}</a> @else City @endif
                                    </span>
                                    <span class="">
                                    <img src="theme/assets/img/calendar.svg" alt="Date" title="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
                                    </span>
                                    @if (isset($row['c_fields']['simple_text'][34]) && $row['c_fields']['simple_text'][34]['value'] != '')
                                    <span class="date-with-icons">
                                    <img src="theme/assets/img/clock.svg" alt="Time" title="Time" /> {{ $row['c_fields']['simple_text'][34]['value'] }}h
                                    </span>
                                    @endif
                                    <!-- <span class="event-info-type-list" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '') style="color: {{ $eventtype->primary_color }} !important;" @endif>
                                    @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                                    </span> -->
                                </div>

	                            <div class="event-info-last @if($etstatus != 0) as-soldout @endif">
	                                @if(isset($row['c_fields']['dropdown_select_status']['value']))
	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>
                                            @if($price == 0)
                                			<span class="sprice">Free</span>
                                            @else
                                            <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                            @endif

                                			<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile fbpix">BOOK NOW</a>
                                            <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout intile">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <a href="{{ $frontHelp->pSlug($row) }}" title="View Details" class="btn btn-soldout intile">
                                                SOLD-OUT
                                                </a>
                                            <!-- <div class="btn btn-soldout intile">SOLD-OUT
			            					</div>  --><?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout intile">COMPLETED
			            					</div> <?php
	                                		break;
	                                	default:
	                                		?>

                                            @if($price == 0)
                                            <span class="sprice">Free</span>
                                            @else
                                            <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                            @endif
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile fbpix">BOOK NOW
			            					</a> <?php
	                                		break;
	                                }
	                                 ?>
								@else

                                    @if($price == 0)
                                    <span class="sprice">Free</span>
                                    @else
                                    <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                    @endif
									<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile fbpix">BOOK NOW
			            			</a>
								@endif
									<!--  <a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a> -->
	                            </div>
                        </div>
                    </div>
                    <?php } ?>
                @endforeach
                @if($totalfound == 0)
                    <p class="thankYou">No available events for your criteria</p>
                @endif
                </div>
            @endif
        </div>
    </div>



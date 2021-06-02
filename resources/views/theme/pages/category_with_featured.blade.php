@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')

<div id="main-content-body">
    <div id="event-banner" class="mob-help">
        <div class="page-helper contact-help"></div>
    </div>
<!-- category-page -->
    <div id="category-page" class="content-fix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="category-content clearfix">
                        @if($cat_dets->parent_id == 12)
                    	<h1 class="animatable fadeInDown">{{ $cat_dets->name }}</h1>
                        @endif
                        @if($cat_dets->parent_id == 9)
                        <h1 class="animatable fadeInDown">Events in {{ $cat_dets->name }}</h1>
                        @endif
                        @if($cat_dets->parent_id == 22)
                        <h1 class="animatable fadeInDown">Events for {{ $cat_dets->name }}</h1>
                        @endif
                        @if(isset($openlist) && count($openlist) > 0)
                        <p>{{ $cat_dets->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

	            @if(isset($openlist) && count($openlist) > 0)
	                @foreach($openlist as $key => $row)
	                    <?php

	                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
	                            return $cartItem->id === $row->id;
	                        });
	                        ?>
	                        <?php
	                            $location = [];
	                            $eventtype = [];
	                            if (isset($row->categories) && !empty($row->categories)) :
	                                foreach ($row->categories as $category) :
	                                    if ($category->depth != 0 && $category->parent_id == 9) {
	                                         $location=$category;
	                                    }
	                                    if ($category->depth != 0 && $category->parent_id == 12) {
	                                         $eventtype=$category;
	                                    }
	                                endforeach;
	                            endif;


	                        ?>

	                    <div class="lead-container">
	                        <div class="cleft">
	                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                @if (!empty($row['featured']) && isset($row['featured'][0]) && isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
	                                    <?php $media = $row['featured'][0]['media']; ?>

	                                        <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive" src="{{ $frontHelp->pImg($row, 'instructors-testimonials') }}" /> <!-- group list-group-image -->
	                                @else
	                                    <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive" src="theme/assets/img/noimage.jpeg" />

	                                @endif
	                            </a>

	                        </div>
	                        <div class="cright">
	                            <div class="event-info-wrap">
	                                <div class="event-info-line">
	                                    <span>
	                                    <img src="theme/assets/img/pin.svg" alt="Location" />
	                                    @if(isset($location->name)) <a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
	                                    </span>
	                                    <span class="date-with-icons">
	                                    <img src="theme/assets/img/calendar.svg" alt="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
	                                    </span>
	                                </div>
	                                <div class="event-info-type">

	                                @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
	                                </div>
	                                <div class="event-info-title">
	                                    <h2>
	                                    <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                    {!! $frontHelp->pField($row, 'title') !!}
	                                    </a>
	                                    </h2>
	                                    <p>{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'summary'), 200) }}</p>
	                                </div>





	                                <?php  if (isset($eventprices[$row->id])) {
	                                        $price = $eventprices[$row->id];
	                                  }
	                                  else { $price = 0; } ?>


	                                <div class="event-info-last">


	                                	 @if(isset($row['c_fields']['dropdown_select_status']['value']))

		                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
		                                switch ($estatus) {
		                                	case 0:
		                                		//'OPEN'
	                                			?>
	                                			<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                			<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            						</a> <?php
		                                		break;
		                                	case 1:
		                                			//'CLOSED'
		                                		?> <!-- <div class="btn btn-soldout">CLOSED
				            					</div> -->
	                                            <a href="{{ $frontHelp->pSlug($row) }}" title="View CLOSED event" class="btn btn-soldout ">
	                                            CLOSED
	                                            </a>
	                                             <?php
		                                		break;
		                                	case 2:
		                                			//'SOLD-OUT'
		                                		?> <div class="btn btn-soldout">SOLD-OUT
				            					</div> <?php
		                                		break;
		                                	case 3:
		                                			//'COMPLETED'
		                                		?> <div class="btn btn-soldout">COMPLETED
				            					</div> <?php
		                                		break;


		                                	default:
		                                		?>
		                                		<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
		                                		<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
				            					</a> <?php
		                                		break;
		                                }



		                                 ?>
									@else
										<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
										<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
				            			</a>
									@endif


	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                   
	                @endforeach
	            @else
	            	<br /><br />
	            	<br /><br />
	            	<h4 style="text-align:center;">There are no events according to your criteria. Please try something else or check again later</h4>
	            	<br /><br />
	            	<br /><br />
	            @endif

	            </div>
            </div>

            <!-- <div class="category-lead-content clearfix">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                </div>
            </div>
            </div> -->
        @if(isset($openlist) && count($openlist) > 1)
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if($cat_dets->parent_id == 12)
            <h2>Other {{ $cat_dets->name }}</h2>
            @endif
            @if($cat_dets->parent_id == 9)
            <h2>Other Events in {{ $cat_dets->name }}</h2>
            @endif

            @if($cat_dets->parent_id == 22)
            <h2>Other Events for {{ $cat_dets->name }}</h2>
            @endif



                <!-- <div class="row"> id="products" -->
            <div class="elist-cat-status">
                <div class="row list-group">
                @foreach($openlist as $key => $row)
                    @if($key != 0)
                	<?php

                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                            return $cartItem->id === $row->id;
                        });
                        ?>
                        <?php
                        	$location = [];
                        	$eventtype = [];
                        	if (isset($row->categories) && !empty($row->categories)) :
					            foreach ($row->categories as $category) :
				                    if ($category->depth != 0 && $category->parent_id == 9) {
				                         $location=$category;
				                    }
				                    if ($category->depth != 0 && $category->parent_id == 12) {
				                         $eventtype=$category;
				                    }
					            endforeach;
					        endif;


                        ?>
                    <div class="e-item list-group-item col-lg-4 col-md-4 col-sm-6">
                        <div class="event-cell">


                            <div class="event-info-image">
                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    @if (!empty($row['featured']) && isset($row['featured'][0]) && isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                                        <?php $media = $row['featured'][0]['media']; ?>

                                            <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive hvr-grow" src="{{ $frontHelp->pImg($row, 'instructors-testimonials') }}" /> <!-- group list-group-image -->
                                    @else
                                        <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive  hvr-grow" src="theme/assets/img/noimage.jpeg" />

                                    @endif
                                </a>
                            </div>



                            <div class="event-info-wrap">
                            	<div class="event-info-line">
	                            	<span>
	                                <img src="theme/assets/img/pin.svg" alt="Location" />
	                                @if(isset($location->name)) <a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif

	                            	</span>
	                                <span class="date-with-icons">
	                                <img src="theme/assets/img/calendar.svg" alt="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
	                            	</span>
	                            </div>
                            	<div class="event-info-type">

                                @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                            	</div>
	                            <div class="event-info-title">
	                                <h2>
	                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                {!! $frontHelp->pField($row, 'title') !!}
	                                </a>
	                                </h2>
                                    <span class="event-info-text">{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 200) }}</span>
	                            </div>




                                <?php  if (isset($eventprices[$row->id])) {
                                        $price = $eventprices[$row->id];
                                  }
                                  else { $price = 0; } ?>

	                            <div class="event-info-last city-fix">
	                            	@if(isset($row['c_fields']['dropdown_select_status']['value']))

	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>
                                			<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                			<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
		            						</a> <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <div class="btn btn-soldout">SOLD-OUT
			            					</div> <?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout">COMPLETED
			            					</div> <?php
	                                		break;


	                                	default:
	                                		?>
	                                		<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            					</a> <?php
	                                		break;
	                                }



	                                 ?>
								@else
									<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
									<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a>
								@endif
	                               <!--  <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                 <a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a> -->


	                            </div>
                        	</div>




                        </div>
                    </div>

                    @endif
                @endforeach
                </div>
            	</div>
            </div>
        @endif



<!-- Sold out events -->
	@if(isset($soldoutlist) && count($soldoutlist) > 0)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            <h2>Sold-out :(</h2>
            
            <div class="elist-cat-status">
                <!-- <div class="row"> id="products"-->
                <div class="row list-group">
                @foreach($soldoutlist as $key => $row)
                    
                	<?php

                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                            return $cartItem->id === $row->id;
                        });
                        ?>
                        <?php
                        	$location = [];
                        	$eventtype = [];
                        	if (isset($row->categories) && !empty($row->categories)) :
					            foreach ($row->categories as $category) :
				                    if ($category->depth != 0 && $category->parent_id == 9) {
				                         $location=$category;
				                    }
				                    if ($category->depth != 0 && $category->parent_id == 12) {
				                         $eventtype=$category;
				                    }
					            endforeach;
					        endif;


                        ?>
                    <div class="e-item list-group-item col-lg-4 col-md-4 col-sm-6">
                        <div class="event-cell">


                            <div class="event-info-image">
                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    @if (!empty($row['featured']) && isset($row['featured'][0]) && isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                                        <?php $media = $row['featured'][0]['media']; ?>

                                            <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive hvr-grow" src="{{ $frontHelp->pImg($row, 'instructors-testimonials') }}" /> <!-- group list-group-image -->
                                    @else
                                        <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive  hvr-grow" src="theme/assets/img/noimage.jpeg" />

                                    @endif
                                </a>
                            </div>



                            <div class="event-info-wrap">
                            	<div class="event-info-line">
	                            	<span>
	                                <img src="theme/assets/img/pin.svg" alt="Location" />
	                                @if(isset($location->name)) <a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif

	                            	</span>
	                                <span class="date-with-icons">
	                                <img src="theme/assets/img/calendar.svg" alt="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
	                            	</span>
	                            </div>
                            	<div class="event-info-type">

                                @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                            	</div>
	                            <div class="event-info-title">
	                                <h2>
	                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                {!! $frontHelp->pField($row, 'title') !!}
	                                </a>
	                                </h2>
                                    <span class="event-info-text">{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 200) }}</span>
	                            </div>




                                <?php  if (isset($eventprices[$row->id])) {
                                        $price = $eventprices[$row->id];
                                  }
                                  else { $price = 0; } ?>

	                            <div class="event-info-last city-fix">
	                            	@if(isset($row['c_fields']['dropdown_select_status']['value']))

	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>
                                			<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                			<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
		            						</a> <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <div class="btn btn-soldout">SOLD-OUT
			            					</div> <?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout">COMPLETED
			            					</div> <?php
	                                		break;


	                                	default:
	                                		?>
	                                		<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            					</a> <?php
	                                		break;
	                                }



	                                 ?>
								@else
									<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
									<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a>
								@endif
	                               <!--  <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                 <a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a> -->


	                            </div>
                        	</div>




                        </div>
                    </div>

                    
                @endforeach
                </div>
            	</div>
            </div>
        @endif



<!-- Completed events -->
	@if(isset($completedlist) && count($completedlist) > 0)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            @if($cat_dets->parent_id == 12)
            <h2>Past {{ $cat_dets->name }}</h2>
            @endif
            @if($cat_dets->parent_id == 9)
            <h2>Past Events in {{ $cat_dets->name }}</h2>
            @endif

            @if($cat_dets->parent_id == 22)
            <h2>Past Events for {{ $cat_dets->name }}</h2>
            @endif
            
            <div class="elist-cat-status">
                <!-- <div class="row"> id="products"-->
                <div class="row list-group">
                @foreach($soldoutlist as $key => $row)
                    
                	<?php

                        $isonCart = Cart::search(function ($cartItem, $rowId) use ($row) {
                            return $cartItem->id === $row->id;
                        });
                        ?>
                        <?php
                        	$location = [];
                        	$eventtype = [];
                        	if (isset($row->categories) && !empty($row->categories)) :
					            foreach ($row->categories as $category) :
				                    if ($category->depth != 0 && $category->parent_id == 9) {
				                         $location=$category;
				                    }
				                    if ($category->depth != 0 && $category->parent_id == 12) {
				                         $eventtype=$category;
				                    }
					            endforeach;
					        endif;


                        ?>
                    <div class="e-item list-group-item col-lg-4 col-md-4 col-sm-6">
                        <div class="event-cell">


                            <div class="event-info-image">
                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                    @if (!empty($row['featured']) && isset($row['featured'][0]) && isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                                        <?php $media = $row['featured'][0]['media']; ?>

                                            <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive hvr-grow" src="{{ $frontHelp->pImg($row, 'instructors-testimonials') }}" /> <!-- group list-group-image -->
                                    @else
                                        <img alt="{{ $frontHelp->pField($row, 'title') }}" class="img-responsive  hvr-grow" src="theme/assets/img/noimage.jpeg" />

                                    @endif
                                </a>
                            </div>



                            <div class="event-info-wrap">
                            	<div class="event-info-line">
	                            	<span>
	                                <img src="theme/assets/img/pin.svg" alt="Location" />
	                                @if(isset($location->name)) <a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif

	                            	</span>
	                                <span class="date-with-icons">
	                                <img src="theme/assets/img/calendar.svg" alt="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
	                            	</span>
	                            </div>
                            	<div class="event-info-type">

                                @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                            	</div>
	                            <div class="event-info-title">
	                                <h2>
	                                <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                {!! $frontHelp->pField($row, 'title') !!}
	                                </a>
	                                </h2>
                                    <span class="event-info-text">{{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'body'), 200) }}</span>
	                            </div>




                                <?php  if (isset($eventprices[$row->id])) {
                                        $price = $eventprices[$row->id];
                                  }
                                  else { $price = 0; } ?>

	                            <div class="event-info-last city-fix">
	                            	@if(isset($row['c_fields']['dropdown_select_status']['value']))

	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>
                                			<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
                                			<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
		            						</a> <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <div class="btn btn-soldout">SOLD-OUT
			            					</div> <?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout">COMPLETED
			            					</div> <?php
	                                		break;


	                                	default:
	                                		?>
	                                		<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            					</a> <?php
	                                		break;
	                                }



	                                 ?>
								@else
									<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
									<a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a>
								@endif
	                               <!--  <span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
	                                 <a href="{{ $frontHelp->pSlug($row) }}" title="ENROLL NOW" class="btn btn-booknow intile">BOOK NOW
			            			</a> -->


	                            </div>
                        	</div>




                        </div>
                    </div>

                    
                @endforeach
                </div>
            	</div>
            </div>
        @endif


















        </div>
    </div>
    <!-- single-post-page END -->
</div>

@endsection




























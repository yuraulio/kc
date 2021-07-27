<!-- HOME PAGE -->
<?php
if(Session::has('scopeone')){
    $fone = Session::get('scopeone');
}
else { $fone = 0; }

$totalfound = 0;
$totalcats = 0;
?>

<div id="home-page">

    @if(isset($events))
        @foreach($eventsbycategory as $bcatid => $bcateventids)
        @if(isset($eventsbycategoryHelper) && isset($eventsbycategoryDetailsHelper) && isset($eventsbycategoryHelper[$bcatid]) && 
                                                                            ($eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_event' && $eventsbycategoryDetailsHelper[$bcatid]->view_tpl !== 'elearning_greek'))

        <div class="dok-back">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="category-details">

                        <?php
                        $string = $frontHelp->pField($eventsbycategoryDetailsHelper[$bcatid], 'title');
                         if( strpos($string, ',') !== false ) {
                           $until = substr($string, 0, strrpos($string, ","));
                         }
                         else {
                            $until = $string;
                            } ?>
                        <!-- $eventsbycategoryHelper[$bcatid]->name -->
                        <h2>{{ $until }}</h2>

                        <?php
                            $location = [];
                            $eventtype = [];
                            $eventtopic = [];
                            $advancedtag = 0;
                            $advancedtagslug = '';
                            if (isset($eventsbycategoryDetailsHelper[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelper[$bcatid]->categories)) :
                                foreach ($eventsbycategoryDetailsHelper[$bcatid]->categories as $category) :
                                    /*if ($category->depth != 0 && $category->parent_id == 9) {
                                         $location=$category;
                                    }
                                    if ($category->depth != 0 && $category->parent_id == 12) {
                                         $eventtype=$category;
                                    }
                                    if ($category->depth != 0 && $category->parent_id == 22) {
                                        $eventtopic[]=$category->id;
                                    }*/

                                    if ($category->id == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category->slug;
                                    }
                                endforeach;
                            endif;
                        ?>

                        @if (isset($eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]) && $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                        <span class="date-with-icons-no">
                        <img src="theme/assets/img/new_icons/homepage/hours.svg" alt="Time" title="Time" /> {{ $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] }}h
                        </span>
                        @endif

                        @if(isset($advancedtag) && $advancedtag == 1)
                            <span class="cat-advancedtag">
                            <a href="{{ $advancedtagslug }}"><img src="theme/assets/img/adv.svg" alt="Time" title="Time" /> Advanced</a>
                            </span>
                        @endif
                        <p>{!!$eventsbycategoryHelper[$bcatid]->description!!}<p>

                     <!--   <a title="View all events on this category" class="more-cat-home" href="/{!!$eventsbycategoryHelper[$bcatid]->slug!!}">read more<i class="fa fa-angle-right" aria-hidden="true"></i></a>-->

                   
                    </div>
                </div>

                <div class="col-lg-8 col-md-8 col-sm-6">

                    <div class="swiper-container eventtype{{$bcatid}}">

                        <div class="swiper-wrapper home-s-wrap">


                        <?php $lastmonth = ''; ?>
                        @foreach($events as $key => $row)
                        <?php
                        	$location = [];
                        	$eventtype = [];
                            $eventtopic = [];
                            $advancedtag = 0;
                            $onthiscat = 0;
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
                                    if ($category->depth != 0 && $category->parent_id == 45) {
                                        $onthiscat=$category->id;
                                    }
                                    if ($category->id == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category->slug;
                                    }
					            endforeach;
					        endif;

						    $dont = true;



                            $totalfound++;
                            $chmonth = date('m', strtotime($row->published_at));
    	                	$month = date('F Y', strtotime($row->published_at));
    	                	if($chmonth != $lastmonth) {
    	                		$lastmonth = $chmonth;
    	                	}
                            ?>

                            @if($onthiscat == $bcatid)
                            <div class="event-cell swiper-slide">


                                @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                                    <div class="event-info-image-ph">
                                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                        <img src="{{ $frontHelp->pImg($row, 'event-card') }}" />
                                        </a>
                                @else


                                <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
background: -moz-linear-gradient(to bottom, #419cff, #65afff);;
background: -webkit-linear-gradient(to bottom, #419cff, #65afff);;
background: linear-gradient(to bottom, #419cff, #65afff);;
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif >


                                @endif

                                    <!-- <div class="event-info-wrap">
                                        <div class="event-info-title">

                                            <h2 class="hidden">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}"> -->
                                            <?php
                                            $string = $frontHelp->pField($row, 'title');
                                             if( strpos($string, ',') !== false ) {
                                               $until = substr($string, 0, strrpos($string, ","));
                                             }
                                             else {
                                                $until = $string;
                                                } ?>

                                           <!--  </a>
                                            </h2>

                                        </div> -->

                                        <?php
                                        if (isset($eventprices[$row->id])) {
                                                $price = $eventprices[$row->id];
                                        }
                                        else { $price = 0; } ?>

                                        @if(isset($row['c_fields']['dropdown_select_status']['value']))
                                            <?php $etstatus = $row['c_fields']['dropdown_select_status']['value']; ?>
                                        @endif

                                    <!-- </div> -->

                                </div>

                                <div class="event-info-line blue-line" id="home-page-loc">
                               <p class="event-title"> {{ $until }} </p>
                                  
                                    <p style="text-transform: uppercase; text-align: center;" class="home-page-month">
                                    {{$month}}
                                    </p>
                                    <span class="citytext">
                                    @if(isset($location->name))<a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
                                    </span>
                                </div>

	                            <div class="event-info-last @if($etstatus != 0) as-soldout @endif">
	                                @if(isset($row['c_fields']['dropdown_select_status']['value']))
	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>

                                			<a class="course-details" href="{{ $frontHelp->pSlug($row) }}" title="Course Details" >Course Details</a>
                                            <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <a href="{{ $frontHelp->pSlug($row) }}" title="View Details" class="btn btn-soldout">
                                                SOLD-OUT
                                                </a>
                                            <?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout">COMPLETED
			            					</div> <?php
	                                		break;
	                                	default:
	                                		?>
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="Course Details" class="btn btn-booknow innewtile fbpix">Course Details
			            					</a> <?php
    	                                		break;
    	                                }
    	                                 ?>
    								@else
    									<a href="{{ $frontHelp->pSlug($row) }}" title="Course Details" class="btn btn-booknow innewtile fbpix">Course Details
    			            			</a>
    								@endif

	                            </div>
                            </div>
                            @endif


                        @endforeach

                        @if($totalfound == 0)
                            <p class="thankYou">No available events for your criteria</p>
                        @endif
                        </div>
                    </div>

                    <div class="home-swiper-button-next evcn{{$bcatid}}"><img src="/theme/assets/img/new_icons/homepage/right_arrow.png" /></div>
                    <div class="home-swiper-button-prev evcp{{$bcatid}}"><img src="/theme/assets/img/new_icons/homepage/left_arrow.png" /></div>
                </div>
            </div>
        </div>
        </div>
        @endif                       
      
        @endforeach
    @endif


    @if(isset($events))
        @foreach($eventsbycategory as $bcatid => $bcateventids)
        @if(isset($eventsbycategoryHelper) && isset($eventsbycategoryDetailsHelper) && isset($eventsbycategoryHelper[$bcatid]) && 
                                                                            ($eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_event' || $eventsbycategoryDetailsHelper[$bcatid]->view_tpl === 'elearning_greek'))

        <div class="elearning-homepage">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="category-details">

                        <?php
                        $string = $frontHelp->pField($eventsbycategoryDetailsHelper[$bcatid], 'title');
                         if( strpos($string, ',') !== false ) {
                           $until = substr($string, 0, strrpos($string, ","));
                         }
                         else {
                            $until = $string;
                            } ?>
                        <!-- $eventsbycategoryHelper[$bcatid]->name -->
                        <h2>{{ $until }}</h2>

                        <?php
                            $location = [];
                            $eventtype = [];
                            $eventtopic = [];
                            $advancedtag = 0;
                            $advancedtagslug = '';
                            if (isset($eventsbycategoryDetailsHelper[$bcatid]->categories) && !empty($eventsbycategoryDetailsHelper[$bcatid]->categories)) :
                                foreach ($eventsbycategoryDetailsHelper[$bcatid]->categories as $category) :
                                    /*if ($category->depth != 0 && $category->parent_id == 9) {
                                         $location=$category;
                                    }
                                    if ($category->depth != 0 && $category->parent_id == 12) {
                                         $eventtype=$category;
                                    }
                                    if ($category->depth != 0 && $category->parent_id == 22) {
                                        $eventtopic[]=$category->id;
                                    }*/

                                    if ($category->id == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category->slug;
                                    }
                                endforeach;
                            endif;
                        ?>

                        @if (isset($eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]) && $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] != '')
                        <span class="date-with-icons-no">
                        <img src="theme/assets/img/new_icons/homepage/hours.svg" alt="Time" title="Time" /> {{ $eventsbycategoryDetailsHelper[$bcatid]['c_fields']['simple_text'][12]['value'] }}h
                        </span>
                        @endif

                        @if(isset($advancedtag) && $advancedtag == 1)
                            <span class="cat-advancedtag">
                            <a href="{{ $advancedtagslug }}"><img src="theme/assets/img/adv.svg" alt="Time" title="Time" /> Advanced</a>
                            </span>
                        @endif
                        <p>{!!$eventsbycategoryHelper[$bcatid]->description!!}<p>

                     <!--   <a title="View all events on this category" class="more-cat-home" href="/{!!$eventsbycategoryHelper[$bcatid]->slug!!}">read more<i class="fa fa-angle-right" aria-hidden="true"></i></a>-->

                   
                    </div>
                </div>

                <div class="col-lg-8 col-md-8 col-sm-6">

                    <div class="swiper-container eventtype{{$bcatid}}">

                        <div class="swiper-wrapper home-s-wrap">


                        <?php $lastmonth = ''; ?>
                        @foreach($events as $key => $row)
                        <?php
                        	$location = [];
                        	$eventtype = [];
                            $eventtopic = [];
                            $advancedtag = 0;
                            $onthiscat = 0;
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
                                    if ($category->depth != 0 && $category->parent_id == 45) {
                                        $onthiscat=$category->id;
                                    }
                                    if ($category->id == 117) {
                                        $advancedtag = 1;
                                        $advancedtagslug = $category->slug;
                                    }
					            endforeach;
					        endif;

						    $dont = true;



                            $totalfound++;
                            $chmonth = date('m', strtotime($row->published_at));
    	                	$month = date('F Y', strtotime($row->published_at));
    	                	if($chmonth != $lastmonth) {
    	                		$lastmonth = $chmonth;
    	                	}
                            ?>

                            @if($onthiscat == $bcatid)
                            <div class="event-cell swiper-slide">


                                @if (!empty($row['featured']) && isset($row['featured'][0]) &&isset($row['featured'][0]['media']) && !empty($row['featured'][0]['media']))
                                    <div class="event-info-image-ph">
                                        <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                        <img src="{{ $frontHelp->pImg($row, 'event-card') }}" />
                                        </a>
                                @else


                                <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
background: -moz-linear-gradient(to bottom, #419cff, #65afff);;
background: -webkit-linear-gradient(to bottom, #419cff, #65afff);;
background: linear-gradient(to bottom, #419cff, #65afff);;
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif >


                                @endif

                                    <!-- <div class="event-info-wrap">
                                        <div class="event-info-title">

                                            <h2 class="hidden">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}"> -->
                                            <?php
                                            $string = $frontHelp->pField($row, 'title');
                                             if( strpos($string, ',') !== false ) {
                                               $until = substr($string, 0, strrpos($string, ","));
                                             }
                                             else {
                                                $until = $string;
                                                } ?>

                                           <!--  </a>
                                            </h2>

                                        </div> -->

                                        <?php
                                        if (isset($eventprices[$row->id])) {
                                                $price = $eventprices[$row->id];
                                        }
                                        else { $price = 0; } ?>

                                        @if(isset($row['c_fields']['dropdown_select_status']['value']))
                                            <?php $etstatus = $row['c_fields']['dropdown_select_status']['value']; ?>
                                        @endif

                                    <!-- </div> -->

                                </div>

                                <div class="event-info-line orange-line" id="home-page-loc">
                               <p class="event-title"> {{ $until }} </p>
                                  
                                </div>

	                            <div class="event-info-last @if($etstatus != 0) as-soldout @endif">
	                                @if(isset($row['c_fields']['dropdown_select_status']['value']))
	                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
	                                switch ($estatus) {
	                                	case 0:
	                                		//'OPEN'
                                			?>

                                			<a class="course-details" href="{{ $frontHelp->pSlug($row) }}" title="Course Details" >Course Details</a>
                                            <?php
	                                		break;
	                                	case 1:
	                                			//'CLOSED'
	                                		?> <div class="btn btn-soldout">CLOSED
			            					</div> <?php
	                                		break;
	                                	case 2:
	                                			//'SOLD-OUT'
	                                		?> <a href="{{ $frontHelp->pSlug($row) }}" title="View Details" class="btn btn-soldout">
                                                SOLD-OUT
                                                </a>
                                            <?php
	                                		break;
	                                	case 3:
	                                			//'COMPLETED'
	                                		?> <div class="btn btn-soldout">COMPLETED
			            					</div> <?php
	                                		break;
	                                	default:
	                                		?>
	                                		<a href="{{ $frontHelp->pSlug($row) }}" title="Course Details" class="btn btn-booknow innewtile fbpix">Course Details
			            					</a> <?php
    	                                		break;
    	                                }
    	                                 ?>
    								@else
    									<a href="{{ $frontHelp->pSlug($row) }}" title="Course Details" class="btn btn-booknow innewtile fbpix">Course Details
    			            			</a>
    								@endif

	                            </div>
                            </div>
                            @endif


                        @endforeach

                        @if($totalfound == 0)
                            <p class="thankYou">No available events for your criteria</p>
                        @endif
                        </div>
                    </div>

                    <div class="home-swiper-button-next evcn{{$bcatid}}"><img src="/theme/assets/img/new_icons/homepage/right_arrow.png" /></div>
                    <div class="home-swiper-button-prev evcp{{$bcatid}}"><img src="/theme/assets/img/new_icons/homepage/left_arrow.png" /></div>
                </div>
            </div>
        </div>
        </div>
        @endif                       
      
        @endforeach
    @endif
</div>





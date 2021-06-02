@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<script type="text/javascript" src="theme/assets/js/jquery.mark.min.js"></script>
<div id="event-banner" class="hidden-xs">
    <div class="page-helper contact-help "></div>
</div>
<div id="main-content-body">
<!-- single-post-page -->
    <div id="search-results">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="search-results-head-wrap" style="padding-top: 50px;">

                        <h1 class="search-results-head">Search Results for ''{{ $search_term }}''...</h1>
                        <hr />
                        <div class="hidden" id="keyword">{{ $search_term }}</div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 sarrow">
                    <div class="search-results-arrow"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- CATEGORY SEARCH RESULTS SECTION -->
                    <div class="posts-category-sections">
                        <div class="row">
                        @if (!empty($list))
                            @foreach ($list as $key => $row)


                                <?php

                                //dd($row);
                                $location = [];
                                $eventtype = [];
                                $eventtopic = [];
                                if (isset($row->categories) && !empty($row->categories)) :
                                    foreach ($row->categories as $category) :
                                        if ($category->depth != 0 && $category->parent_id == 9) {
                                             $location=$category;
                                        }
                                        if ($category->depth != 0 && $category->parent_id == 12) {
                                             $eventtype=$category;
                                        }
                                        if ($category->depth != 0 && $category->parent_id == 22) {
                                             $eventtopic=$category;
                                        }
                                    endforeach;
                                endif;

                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 dpContentEntry">
                                    <div class="home-small-photo-tile">


                                    @if($frontHelp->pImg($row, 'instructors-testimonials') != '')
                                        <div class="home-small-photo">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="{{ $frontHelp->pImg($row, 'instructors-testimonials') }}" />
                                            </a>
                                        </div>
                                    @else
                                        <div class="home-small-photo">
                                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                <img style="width:100%;" class="img-responsive center-block" alt="{{ $frontHelp->pField($row, 'title') }}" src="http://placehold.it/200x150" />
                                            </a>
                                        </div>
                                    @endif
                                        <!-- <span class="home-small-post-date">
                                            {{ $frontHelp->contentDate($row['published_at']) }}
                                        </span> -->
                                        <!-- <h2 class="home-small-post-sub-title">
                                            <a class="section-heading-generic" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}" target="{{ $frontHelp->pTarget($row) }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'header'), 100) }}
                                            </a>
                                        </h2> -->

                                        <div class="home-small-text">
                                        <h3 class="home-small-post-title">

                                           	<a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
                                                {{ $frontHelp->truncateOnSpace($frontHelp->pField($row, 'title'), 100) }}

                                                <!-- <span class="search-small-letters">
                                                @if(isset($location->name))
                                                    {{ $location->name }} |
                                                @endif

                                                @if(isset($eventtopic->name))
                                                    {{ $eventtopic->name }} |
                                                @endif

                                                @if(isset($eventtype->name))
                                                    {{ $eventtype->name }}
                                                @endif

                                                </span> -->





                                            </a>
                                        </h3>
                                        @if($frontHelp->pField($row, 'summary') != '')
                                            <p>

                                            {!! $frontHelp->truncateOnSpace($frontHelp->pField($row, 'summary'), 320) !!}
                                            </p>

                                        @endif
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



                                        <br /><br />
                                        	<?php  if (isset($eventprices[$row->id])) {
			                                    $price = $eventprices[$row->id];
			                              }
			                              else { $price = 0; } ?>
                                         	@if(isset($row['c_fields']['dropdown_select_status']['value']))

				                                <?php $estatus = $row['c_fields']['dropdown_select_status']['value'];
				                                switch ($estatus) {
				                                	case 0:
				                                		//'OPEN'
			                                			?>
			                                			<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
			                                			<a href="{{ $frontHelp->pSlug($row) }}" title="BOOK NOW" class="btn btn-booknow intile">BOOK NOW
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
				                                		<a href="{{ $frontHelp->pSlug($row) }}" title="BOOK NOW" class="btn btn-booknow intile">BOOK NOW
						            					</a> <?php
				                                		break;
				                                }



				                                 ?>
											@else
												<span class="slabel">from</span><span class="sprice">&euro;<?php echo $price; ?></span>
												<a href="{{ $frontHelp->pSlug($row) }}" title="BOOK NOW" class="btn btn-booknow intile">BOOK NOW
						            			</a>
											@endif
                                        </div>
                                    </div>
                                    <hr />
                                </div>

                            @endforeach

                        @endif

                        @if(!empty($list) && count($list) == 0 )
                            <br /><br /><br />
                            <h1 style="text-align: center;">Nothing found containing the term '{{ $search_term }}'. Please try again!</h1>
                            <br /><br /><br />
                        @endif

                        </div>
                    </div>
                </div>

            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(function() {
	fbq('track', 'Search');

    var keyword = $('#keyword').html();
    $(".posts-category-sections").mark(keyword, {
    "element": "span",
    "className": "highlight"
});
});
</script>
@stop

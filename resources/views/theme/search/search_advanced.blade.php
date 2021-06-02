@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<!-- <script type="text/javascript" src="theme/assets/js/jquery.mark.min.js"></script> -->
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

                        <h1 class="search-results-head">
                        Events
                        {{--
                        @if(isset($city))
                        	City: {{ $city }}
                        @endif
                        @if(isset($topic))
                        	@if(isset($city))
                        	,
                        	@endif
                        	 Topic: {{ $topic }}
                        @endif
                        @if(isset($type))
                        	@if(isset($topic))
                        	,
                        	@elseif(isset($city))
                        	,
                        	@endif
                            Type: {{ $type }}
                        @endif
                        --}}
                        <span class="pull-right">
                        @if(isset($month))
                        	{{ $month }}
                        @endif
                        @if(isset($year))
	                        @if(isset($month))
	                        	/
	                        @endif
                        	{{ $year }}
                        @endif
                        </span>
                        </h1>
                        <hr />
                        <div class="hidden" id="keyword"></div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 sarrow">
                    <div class="search-results-arrow"></div>
                </div>
            </div>

            
	        <div id="products" class="row list-group">
	            
	            	@if(isset($result_html))
	                {!! $result_html !!}
	                @endif
	        </div>
                    
             

        </div><!-- ROW END -->
    </div>
</div>
    <!-- single-post-page END -->
@endsection
@section('scripts')
<script type="text/javascript">
/*$(function() {
	fbq('track', 'Search');

    var keyword = $('#keyword').html();
    $(".posts-category-sections").mark(keyword, {
    "element": "span",
    "className": "highlight"
});
});*/
</script>
@stop

@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
@include('theme.preview.preview_warning', ["id" => $content->id, "type" => "content", "status" => $content->status])

<div id="main-content-body">

<div id="event-banner">
    @if (!empty($content['featured']) && isset($content['featured'][0]) &&isset($content['featured'][0]['media']) && !empty($content['featured'][0]['media']))
        <?php $hasfeat = true; //$media = $content['featured'][0]['media'];  ?>
        {{-- <div class="fill" style="background-image:url('{{ $frontHelp->pImg($content, 'main') }}');"></div> --}}
        <img class="fullme" alt="{{ $content->title }}" src="{{ $frontHelp->pImg($content, 'header-image') }}" />
        @else
        <div class="page-helper"></div>
         <?php $hasfeat = false; ?>
        @endif
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="post-caption">
                    <h1 class="animatable fadeInDown">{{ $content->title }}</h1>
                    <h2 class="animatable fadeInUp">{!! $content->summary !!}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- single-post-page -->

    <div id="about-page" class="@if($hasfeat) content-fix @endif">
        <div class="container">


            <div class="row row-offcanvas row-offcanvas-left">
            <!--	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 side sidebar-offcanvas">
            		<div class="about-sidebardddd clearfix">
	            	<ul class="dont-boost" id="sidebar">
	            		<li><a href="/about" title="About Us"> About Us</a></li>
	                    @if (!empty($aboutSidebar))
	                        @foreach ($aboutSidebar as $key => $row)
	                        <li class="@if($url == 'about' && $key == 0) active @endif @if($url == $row->slug) active @endif">
	                            <a href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
	                                {{ $frontHelp->pField($row, 'title') }}
	                            </a>
	                        </li>
	                        @endforeach
	                    @endif
	                    <li class="@if($url == 'contact') active @endif"><a href="/contact" title="About us"> Contact us</a></li>
	                </ul>
	            	</div>
	            </div>-->

                <div class="col-lg-12">
                	<p class="visible-xs" style="margin-top:20px;">
		                <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>
				    </p>
                    <div class="post-content clearfix">
                        <div class="page-quote">{!! $content->subtitle !!}</div>
                        {!! $content->body !!}
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
$(window).load(function(){ 

var width = $(window).width();
if (width > 768) {
    var $sidebar   = $("#sidebar"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 170;
    var maxcont = $('.post-content').height() - 280;
    var ban = $('#event-banner').height();

    $window.scroll(function() {
        
        var modfix = offset.top - 60;

        if ($window.scrollTop() > modfix && $window.scrollTop() <  maxcont + ban) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - ban + 100

            });
        } else {
            $sidebar.stop().animate({
                marginTop: 0
            });
        }
    });
}

});

</script>
<script type="text/javascript">


$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });
});

</script>
@stop

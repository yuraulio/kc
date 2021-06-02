@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<link href="/theme/assets/addons/lightGallery/css/lightgallery.css" rel="stylesheet">
<div id="main-content-body">
<!-- single-post-page -->
    <div id="gallery">
        <div class="container">
            @include('theme.sections.media.partials.media_type_categories')
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="launchGallery">
                        <img class="img-responsive gallery-photo-image" alt="{{ $frontHelp->pField($content, 'title') }}" src="{{ $frontHelp->pImg($content, 'video-featured') }}">
                        <br />
                        <br />
                        @if (!empty($photoGallery['imgs']))
                        <div id="lightgallery">
                            @foreach ($photoGallery['imgs'] as $key => $media)
                            @if (isset($media['caption']) && strlen($media['caption']))
                            <a href="javascript:void(0);" rel="uploads/originals/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}" data-sub-html="{{ $media['caption'] }}">
                                <img class="img-responsive" src="portal-img/photo-gallery-thumb/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}">
                            </a>
                            @else
                            <a href="javascript:void(0);" rel="uploads/originals/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}">
                                <img class="img-responsive" src="portal-img/photo-gallery-thumb/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}">
                            </a>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="post-content">
                        {!! $photoGallery['summary'] !!}
                    </div>
                </div>
            </div><!-- ROW END -->
        </div>
    </div>
    <!-- single-post-page END -->
</div>

<script type="text/javascript">
$(function () {
    dynamicGallery();
});

$(".launchGallery").on("click", function () {
    dynamicGallery();
});

function dynamicGallery() {
    var dynamicEl = [];
    $('#lightgallery a').each(function (key, row) {
        dynamicEl[key] = { "src": $(this).attr("rel"),
            "thumb": $(this).find('img').attr("src"),
            "subHtml": $(this).attr("data-sub-html")
        }
    });

    $(this).lightGallery({
        dynamic: true,
        dynamicEl: dynamicEl,
        download: false,
        hash: false
    });
}
</script>
<script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lightgallery.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-fullscreen.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-thumbnail.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-video.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-autoplay.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-zoom.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-hash.js"></script>
<script src="/theme/assets/addons/lightGallery/js/lg-pager.js"></script>
<script src="/theme/assets/addons/lightGallery/lib/jquery.mousewheel.min.js"></script>

@include('theme.sections.media.partials.media_scripts')
@endsection

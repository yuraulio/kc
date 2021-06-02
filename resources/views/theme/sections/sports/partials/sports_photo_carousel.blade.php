<!-- PHOTO GALLERY block -->
@if (isset($photoGallery) && !empty($photoGallery))
<div class="home-category-heading-photo-gal">
    <div class="photo-gallery-head">
        <h1>Photo Gallery</h1>
    </div>
</div>
<div class="home-category-gallery-counter">
    <i class="fa fa-camera photo-counter"> 1/13</i>
</div>
<div id="categoryPhotoCarousel" class="carousel slide" data-ride="carousel" data-interval="5000">
    <div class="carousel-inner">
        @foreach ($photoGallery as $key => $media)
        <div class="item <?php if ($key == 0) { echo 'active'; } ?>">
            <img src="portal-img/photo-gallery/{{ $media['path'] }}/{{ $media['name'].$media['ext'] }}" alt="{{ $media['name'] }}">
            <div class="carousel-caption">
                {{ $media['caption'] }}
            </div>
        </div>
        @endforeach
        @if (count($photoGallery) > 1)
        <div class="home-video-controls">
            <a class="left carousel-control" href="#categoryPhotoCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
            <div id="photo-caption-holder"></div>
            <a class="right carousel-control" href="#categoryPhotoCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>
        @endif
    </div>
</div>
@endif

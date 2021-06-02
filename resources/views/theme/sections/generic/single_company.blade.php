@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')


<div id="page-banner">
    <img alt="" src="assets/img/banners/{{ $content->short_title }}.jpg" class="img-responsive center-block" />
    <div class="container" id="banner-caption">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="introtextpages-center animatable fadeInUp">{{ $content->header }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">

    <div class="container">

        <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-6">
                <div class="company-sidebar">
                    <h1 class="auto-head">{{ $content->title }}</h1>
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">
                        About</a></li>
                        <?php  if (isset($attachedPhotos) && !empty($attachedPhotos)) : ?>
                        <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">
                        Photo Gallery</a></li>
                        <?php endif; ?>
                        <li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">
                        Downloads</a></li>

                     </ul>

                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-6">

                <div class="tab-content">
                     <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                        <h2>About</h2>
                        {!! $content->body !!}
                    </div>
                    <?php  if (isset($attachedPhotos) && !empty($attachedPhotos)) : ?>
                    <div role="tabpanel" class="tab-pane fade" id="tab2">
                        <h2>Gallery</h2>
                        <div class="row">
                            <?php foreach ($attachedPhotos as $key => $value) : ?>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 gallery-tile">
                                    <a class="popup-link" href="/uploads/galleries/<?php echo $galleryPath . '/' . $value['truefilename']; ?>">
                                        <img alt="{{ $content->title }}" src="/uploads/galleries/<?php echo $galleryPath . '/' . $value['truefilename']; ?>" class="img-responsive center-block" />
                                    </a>

                                </div>

                            <?php endforeach; ?>
                        </div>

                    </div>
                    <?php endif; ?>
                    <div role="tabpanel" class="tab-pane fade" id="tab3">
                        <h2>Download list</h2>
                        <?php  if (isset($attachedFiles)) : ?>
                        <ul class="sq-list">
                        <?php foreach ($attachedFiles as $key => $value) : ?>
                        	<li>
                                <a target="_blank" href="/uploads/files/<?php echo $value['truefilename']; ?>">Download File: <?php echo $value['filename']; ?></a>
                            </li>
                       		 <?php endforeach; ?>
                        </ul>
                        <?php else :

                            echo "No documents found.";

                             endif; ?>

                    </div>

                </div>


            </div>
        </div>


    @include('theme.home.partials.home-flexifamily')


    </div>
</div>

@include('theme.home.partials.newsletter')
@endsection

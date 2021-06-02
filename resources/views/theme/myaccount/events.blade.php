@extends('theme.layouts.master')

@section('content')
@inject('frontHelp', 'Library\FrontendHelperLib')
<?php $currentuser = Sentinel::getUser(); ?>
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/css/fileicon.css') }}" />
<link type="text/css" rel="stylesheet" href="{{ cdn('theme/assets/addons/dropzone/dropzone.min.css') }}" />
<div id="main-content-body">

    <div id="event-banner">
        <div class="page-helper"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="post-caption static-pages">
                        @if($currentuser)
                        <h1 class="animatable fadeInDown"><div class="players-img">
                                    <img src="{{ cdn('theme/assets/img/myhumber.png')}}"><!-- icon_student_grey -->
                                </div>{{ $currentuser->first_name }} {{ $currentuser->last_name }}</h1>
                                 <div class="user-ids animatable fadeInUp">@if($currentuser->kc_id != '')
                                KNOWCRUNCH I.D. : {{ $currentuser->kc_id }}<br />
                                @endif
                                @if($currentuser->partner_id)
                                DEREE I.D.: {{ $currentuser->partner_id }}<br />
                                @endif</div>
                        <!-- <h2 class="fadeInUp animated">@if($currentuser->kc_id != '') {{ $currentuser->kc_id }}-{{ $currentuser->partner_id }} @endif</h2> -->

                        @else
                        <h1 class="animatable fadeInDown">Student Page</h1>
                        <h2 class="fadeInUp animated"></h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <?php $hasfeat = false; ?>
    </div>

    <div id="about-page" class="@if($hasfeat) content-fix @endif">
        <div class="container" >
            <div class="row row-offcanvas row-offcanvas-left" >
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 side sidebar-offcanvas" id="leftCol">
                    <div class="about-sidebar clearfix">

                        <span class="hidden" id="stid">{{ $currentuser->id }}</span>

                        @include('theme.myaccount.dropme')
                        @include('theme.myaccount.menu')

                    </div>
                </div>
                <div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12 col-xs-offset-0">
                     @include('theme.myaccount.menu-mobile')
                    <!-- <p class="visible-xs" style="margin-top:20px;">
                        <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>
                    </p> -->

                @if($currentuser)

                    @if(isset($inst_events1) && count($inst_events1) > 0)
                        <div class="col-lg-12">
                        <h3 id="inev">My events <span class="small">(being instructor)</span></h3>
                        <h3>My current courses</h3>
                            <div class="list-group">




                            @foreach($inst_events1 as $ik => $iev)

                                <?php
                                    $location = [];
                                    $eventtype = [];
                                    if (isset($iev->categories) && !empty($iev->categories)) :
                                        foreach ($iev->categories as $category) :
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
                                    <div class="event-cat"><!-- event-cell -->
                                        <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
                    background: -moz-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%, {{ $eventtype->seconday_color }} 100%);
                    background: -webkit-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    background: linear-gradient(135deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif>

                                            <div class="event-info-wrap">
                                                <span class="location-city">

                                                    @if(isset($location->name))<a href="{{ $location->slug }}" title="{{ $location->name }}">{{ $location->name }}</a> @else City @endif
                                                </span>

                                                <div class="event-info-title">
                                                    <h2>
                                                    <a target="_blank" href="{{ $frontHelp->pSlug($iev) }}" title="{{ $frontHelp->pField($iev, 'title') }}">
                                                    <?php
                                                    $string = $frontHelp->pField($iev, 'title');
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

                                                <?php  if (isset($eventprices[$iev->id])) {
                                                            $price = $eventprices[$iev->id];
                                                      }
                                                      else { $price = 0; } ?>

                                                @if(isset($iev['c_fields']['dropdown_select_status']['value']))
                                                    <?php $etstatus = $iev['c_fields']['dropdown_select_status']['value']; ?>
                                                @endif
                                            </div>
                                        </div>


                                            {{-- <div class="event-info-line">
                                                </div>
                                            </div> --}}

                                        <div class="event-info-title-list onmyacc">
                                            <h2>
                                            <a target="_blank" href="{{ $frontHelp->pSlug($iev) }}" title="{{ $frontHelp->pField($iev, 'title') }}">
                                            <?php
                                            $string = $frontHelp->pField($iev, 'title');
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
                                            @if(isset($location->name))<a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
                                            </span>
                                            <span class="">
                                            <img src="theme/assets/img/calendar.svg" alt="Date" title="Date" />@if (isset($iev['c_fields']['simple_text'][0]) && $iev['c_fields']['simple_text'][0]['value'] != '') {{ $iev['c_fields']['simple_text'][0]['value'] }} @else Date @endif
                                            </span>
                                            @if (isset($iev['c_fields']['simple_text'][34]) && $iev['c_fields']['simple_text'][34]['value'] != '')
                                            <span class="date-with-icons">
                                            <img src="theme/assets/img/clock.svg" alt="Time" title="Time" /> {{ $iev['c_fields']['simple_text'][34]['value'] }}h
                                            </span>
                                            @endif

                                        </div>


                                    </div>
                                </div>

                            @endforeach





                            </div>
                        </div>
                    @endif

                    @if(isset($inst_events) && count($inst_events) > 0)
                        <div class="col-lg-12">
                       
                        <h3>Upcoming events</h3>
                            <div class="list-group">




                            @foreach($inst_events as $ik => $iev)

                                <?php
                                    $location = [];
                                    $eventtype = [];
                                    if (isset($iev->categories) && !empty($iev->categories)) :
                                        foreach ($iev->categories as $category) :
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
                                    <div class="event-cat"><!-- event-cell -->
                                        <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
                    background: -moz-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%, {{ $eventtype->seconday_color }} 100%);
                    background: -webkit-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    background: linear-gradient(135deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif>

                                            <div class="event-info-wrap">
                                                <span class="location-city">

                                                    @if(isset($location->name))<a href="{{ $location->slug }}" title="{{ $location->name }}">{{ $location->name }}</a> @else City @endif
                                                </span>

                                                <div class="event-info-title">
                                                    <h2>
                                                    <a target="_blank" href="{{ $frontHelp->pSlug($iev) }}" title="{{ $frontHelp->pField($iev, 'title') }}">
                                                    <?php
                                                    $string = $frontHelp->pField($iev, 'title');
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

                                                <?php  if (isset($eventprices[$iev->id])) {
                                                            $price = $eventprices[$iev->id];
                                                      }
                                                      else { $price = 0; } ?>

                                                @if(isset($iev['c_fields']['dropdown_select_status']['value']))
                                                    <?php $etstatus = $iev['c_fields']['dropdown_select_status']['value']; ?>
                                                @endif
                                            </div>
                                        </div>


                                            {{-- <div class="event-info-line">
                                                </div>
                                            </div> --}}

                                        <div class="event-info-title-list onmyacc">
                                            <h2>
                                            <a target="_blank" href="{{ $frontHelp->pSlug($iev) }}" title="{{ $frontHelp->pField($iev, 'title') }}">
                                            <?php
                                            $string = $frontHelp->pField($iev, 'title');
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
                                            @if(isset($location->name))<a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
                                            </span>
                                            <span class="">
                                            <img src="theme/assets/img/calendar.svg" alt="Date" title="Date" />@if (isset($iev['c_fields']['simple_text'][0]) && $iev['c_fields']['simple_text'][0]['value'] != '') {{ $iev['c_fields']['simple_text'][0]['value'] }} @else Date @endif
                                            </span>
                                            @if (isset($iev['c_fields']['simple_text'][34]) && $iev['c_fields']['simple_text'][34]['value'] != '')
                                            <span class="date-with-icons">
                                            <img src="theme/assets/img/clock.svg" alt="Time" title="Time" /> {{ $iev['c_fields']['simple_text'][34]['value'] }}h
                                            </span>
                                            @endif

                                        </div>


                                    </div>
                                </div>

                            @endforeach





                            </div>
                        </div>
                    @endif

                    @if(!isset($inst_events))
                    <div class="col-lg-12">
                        <h3 id="accdownloads">My events</h3>
                        @if(isset($events) && count($events) > 0)
                        <div class="list-group">
                                    <?php
                                        $countopen = count($events);
                                        $etstatus = 0;
                                    ?>
                                    <?php $lastmonth1 = ''; ?>
                                    @foreach($events as $key => $row)
                                        <?php
                                            $chmonth = date('m', strtotime($row->published_at));
                                            $month = date('F Y', strtotime($row->published_at));
                                            if($chmonth != $lastmonth1) {
                                                echo '<h4 class="list-calendar in">' . $month . '</h4>';
                                                $lastmonth1 = $chmonth;
                                            }

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
                                            <div class="event-cat">
                                                <div class="event-info-image" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '' && isset($eventtype->seconday_color) && $eventtype->seconday_color != '') style="background: {{ $eventtype->primary_color }};
                    background: -moz-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%, {{ $eventtype->seconday_color }} 100%);
                    background: -webkit-linear-gradient(-45deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    background: linear-gradient(135deg, {{ $eventtype->primary_color }} 0%,{{ $eventtype->seconday_color }} 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{ $eventtype->primary_color }}', endColorstr='{{ $eventtype->seconday_color }}',GradientType=1 );" @endif>

                                                    <div class="event-info-wrap">
                                                        <!-- <div class="event-info-type" @if(isset($eventtype->primary_color) && $eventtype->primary_color != '') style="color: {{ $eventtype->primary_color }} !important;" @endif>
                                                        @if(isset($eventtype->name)) {{ $eventtype->name }} @else Event Type @endif
                                                        </div> -->

                                                        <span class="location-city">
                                                            <!-- <img src="theme/assets/img/pinw.svg" alt="Location" /> -->
                                                            @if(isset($location->name))<a href="{{ $location->slug }}" title="{{ $location->name }}">{{ $location->name }}</a> @else City @endif
                                                        </span>
                                                        <div class="event-info-title">
                                                            <h2>
                                                            <a target="_blank" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
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


                                                <div class="event-info-title-list onmyacc">

                                                    <h2>
                                                    <a target="_blank" href="{{ $frontHelp->pSlug($row) }}" title="{{ $frontHelp->pField($row, 'title') }}">
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
                                                    @if(isset($location->name))<a href="{{ $location->slug }}">{{ $location->name }}</a> @else City @endif
                                                    </span>
                                                    <span class="">
                                                    <img src="theme/assets/img/calendar.svg" alt="Date" title="Date" />@if (isset($row['c_fields']['simple_text'][0]) && $row['c_fields']['simple_text'][0]['value'] != '') {{ $row['c_fields']['simple_text'][0]['value'] }} @else Date @endif
                                                    </span>
                                                    @if (isset($row['c_fields']['simple_text'][34]) && $row['c_fields']['simple_text'][34]['value'] != '')
                                                    <span class="date-with-icons">
                                                    <img src="theme/assets/img/clock.svg" alt="Time" title="Time" /> {{ $row['c_fields']['simple_text'][34]['value'] }}h
                                                    </span>
                                                    @endif

                                                </div>


                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                        @else
                            <h4>No events</h4>
                        @endif



                        @endif

                    </div>
                @endif

                </div>
            </div><!-- ROW END -->
        </div>
    </div>
</div><!-- close profile-account -->

@endsection
@section('scripts')
<script src="{{ cdn('theme/assets/js/jquery.scrollto.js') }}"></script>
<script src="{{ cdn('theme/assets/addons/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">
$(window).load(function(){

var width = $(window).width();
if (width > 768) {
    var $sidebar   = $("#sidebar"),
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 170;

    var ban = $('#event-banner').height();


    $window.scroll(function() {

        var maxcont = $('#about-page').height() - 280;

        var modfix = offset.top - 60;

        //console.log(offset.top);

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

    $("body").on("click", ".delete_media", function (event) {
        if(confirm('Do you really want to remove your profile picture?')) {
            //alert('You are very brave!');
            deleteMediaPromt($(this).attr("data-dp-media-id"));
        }
        else {
            alert('Tip! Drag and Drop or click to change your profile picture');

        }
    });

    function deleteMediaPromt(media_id) {
        //alert(media_id+' deleted');
        $.ajax({ url: "student/removeavatar", type: "post",
            data: media_id,
            success: function(data) {
                if (Number(data.status) === 1) {

                    var html = '';
                    html += '<div class="featured_img_border">';
                    html += '<i class="fa fa-user"></i><br />';
                    html += '<p>Drag and Drop or<br /> click to set a profile picture</p>';
                    html += '</div>';


                    $('#logoDropzone').html(html);
                    $('.delete_media').hide();
                    //alert('Profile picture removed.');
                }
            }
        });
    }

    $.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $(document).ajaxError(function(event, jqxhr, settings, exception) {
        if (exception == 'Unauthorized') {
            window.location.href = baseUrl+'/';
        }
    });

    var settingsObj = {
        maxUploadSize: "5", // in MB
        dropzoneAcceptedFiles: "image/*",
        dropzoneAcceptImage: "image/*"
    };



    var logo_dropzone = {};
    var logo_dropzoneObj = {dragenter: 0, dragleave: 0};

    function logo_dropzoneToDropzone() {
        logo_dropzone = new Dropzone(document.getElementById("logo_dropzone"), {
        url: 'admin/media_uploader/upload',
        method: "post",
        maxFilesize: settingsObj.maxUploadSize,
        paramName: "dp_fileupload",
        uploadMultiple: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        previewsContainer: null,
        previewTemplate: '<div id="preview-template" style="display: none;"></div>',
        createImageThumbnails: false,
        maxFiles: null,
        init: function () { },
        acceptedFiles: settingsObj.dropzoneAcceptedFiles,
        autoProcessQueue: true,
        uploadprogress: function(file, progress, bytesSent) {
            //console.log(progress);
        },
        sending: function (file, xhr, formData) {
           /* NProgress.configure({ parent: '#logo_dropzone' });
            NProgress.start();*/
            formData.append("filesize", file.size);
            formData.append("student_avatar", '1');
            formData.append("stid", $('#stid').html());
            formData.append("scope", "logo_dropzone");
        },
        complete: function (file) {
        },
        success: function (file, response, load) {
            $('#logo_media').val(response.urls.large_thumb);
            $('#logo_media_id').val(response.media_id);
            renderLogoDropzone(response, true);
        }
    });

    logo_dropzone.on("dragenter", function(event) {
        logo_dropzoneObj.dragenter++;
        logo_dropzoneObj.dragleave = 0;
        $("#logoDropzone").addClass('acceptDrop');
    });

    logo_dropzone.on("dragleave", function(file) {
        logo_dropzoneObj.dragleave++;
        logo_dropzoneObj.dragenter = 0;
        if (logo_dropzoneObj.dragleave > 1) {
            $("#logoDropzone").removeClass('acceptDrop');
        }
    });
}

 logo_dropzoneToDropzone();



function renderLogoDropzone(mediaObj, overwrite) {
    //console.log(mediaObj);
    var html = '<div class="logo_dropzone_click">';
    if (mediaObj.media_id > 0) {
        html += '<div class="featured_media" data-dp-media-id="'+mediaObj.media_id+'">';
        html += '<div id="logoDropzone" class="custFieldMediaDrop dz-message">';
        /*html += '<img class="dp_featured_img dz-message" src="'+mediaObj.urls.large_thumb+'" alt="cust image" />';*/
        html += '<div class="featured_img_border">';
        html += '<img class="dp_featured_img dz-message" src="portal-img/users/'+mediaObj.media.path+'/'+mediaObj.media.name+mediaObj.media.ext+'" alt="cust image" />';
        if (Number(mediaObj.media.type) === 1) {
            html += '<p class="mediaDesc" title="">'+mediaObj.media.name+'</p>';
        }
        html += '</div>';
        html += '</div>';
        html += '<a data-dp-media-id="'+mediaObj.media_id+'" class="delete_media" title="Remove profile picture" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> remove</a>';
        html += '</div>';
    } else {
        html += '<div class="featured_media">';
        html += '<div id="logoDropzone" class="custFieldMediaDrop dz-message">';
        html += '<div class="featured_img_border">';
        html += '<i class="fa fa-user"></i><br />';
        html += '<p>Drag and Drop or<br /> click to set a profile picture</p>';
        html += '</div>';
        html += '</div>';

        html += '</div>';
    }
    html += '</div>';

    if ((typeof overwrite !== "undefined") && (overwrite === true)) {
        //console.log(html);
        $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').html(html);
        $('[data-dp-scope="logo_dropzone"] #cfMedia_logo_dropzone').find('.logo_dropzone_click').addClass('dz-clickable');
        var dropzoneFn = 'logo_dropzoneToDropzone';
        //window[dropzoneFn]();
        return true;
    } else {
        return html;
    }
}




















    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });

    $('.getdropboxlink').click(function() {

        var dir = $(this).attr('data-dirname');
        var fname = $(this).attr('data-filename');

        $.ajax({ url: '/getdropbox', type: "post",
            data: {dir: dir, fname:fname},
            success: function(data) {
              window.location.href = data;
            }
        });

    });

    /*$(".dont-boost li a").click(function(evn){
        evn.preventDefault();
        if(!$(this).parent().hasClass('disabled')) {
            $('html,body').scrollTo(this.hash, this.hash);
            $('.dont-boost li').removeClass('active');
            $(this).parent().addClass('active');
        }
    });*/

    $('.edit-mode').on('click', function() {
        $('#student-view-mode').addClass('hidden');
       // $('#student-billing-view-mode').addClass('hidden');
        $('#student-edit-mode').removeClass('hidden');
    });
    $('.cancel-edit-mode').on('click', function() {
        $('#student-edit-mode').addClass('hidden');
        $('#student-view-mode').removeClass('hidden');
        //$('#student-billing-view-mode').removeClass('hidden');
    });

});

</script>

@stop


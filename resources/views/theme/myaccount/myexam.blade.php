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

        <div class="container">

            <div class="row row-offcanvas row-offcanvas-left" >

                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 side sidebar-offcanvas" id="leftCol">

                    <div class="about-sidebar clearfix">



                        <span class="hidden" id="stid">{{ $currentuser->id }}</span>



                        @include('theme.myaccount.dropme')

                        @include('theme.myaccount.menu')



                    </div>

                </div>

                <div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-12 col-xs-offset-0">



                    <p class="visible-xs" style="margin-top:20px;">

                        <button type="button" class="btn btn-green" data-toggle="offcanvas" style="margin-left:0px;"><i class="fa fa-bars"></i> Menu</button>

                    </p>



                @if($currentuser)                                   

                    <div class="col-lg-12 ib_exam_list">

                        <h3 id="accdownloads">My Exams</h3>		
                        <!---Past Exam -->			
						@foreach($ibevents as $ibevent)
						
							@if(!empty($ibevent->id))
							<div class="list-group">
								<h4 class="list-calendar in"></h4>    
								<div class="e-item list-group-item col-lg-4 col-md-4 col-sm-6">
									<div class="event-cat">
										<div class="event-info-image">
											<div class="event-info-wrap">                                   

												<span class="location-city">
													
												</span>
												<div class="event-info-title">
													<h2>
													<a target="_blank" href="" title="">
													
													</a>
													</h2>
												</div>											  
											</div>
										</div>

										<div class="event-info-title-list onmyacc onmyexam">
											<h2>
											<a target="_blank" href="{{ url($ibevent->slug) }}" title="{{$ibevent->title }}">
											{{$ibevent->title }}
											</a>
											</h2>
										</div>
										<!--
										<div class="event-info-line">
											<span class="list-only">
												<img src="theme/assets/img/pin.svg" alt="Location" title="Location" />
												
											</span>
											<span class="">
												<img src="theme/assets/img/calendar.svg" alt="Date" title="Date" />
											
											</span>
											<span class="date-with-icons">
												<img src="theme/assets/img/clock.svg" alt="Time" title="Time" />
											
											</span>
										</div>
										-->
										<?php
                                        if(isset($newlayoutExams[$ibevent->id])) {
    										foreach($newlayoutExams[$ibevent->id] as $pe) {
    										?>										
    										<div class="exam_info"> 
    										<!--	<span>{{ $pe->exam_name }}</span> -->
    											@if($pe->exstatus == 1)
    											<span class="pull-left"><a target="_blank" href="{{ url('student-summary/' . $pe->id . '/' . $currentuser->id) }}?s=1" class="btn btn-green-invert"  style="
    background: #6cc04a;
    border: 1px solid #6cc04a;
    color: #ffffff;
">VIEW RESULT</a></span>
    											@elseif($pe->islive == 1)											
    											<span class="pull-left"><a  target="_blank" onclick="window.open('{{ route('attempt-exam', [$pe->id]) }}', 'newwindow', 'width=1400,height=650'); return false;" class="btn btn-green-invert pull-right"  style="
    background: #6cc04a;
    border: 1px solid #6cc04a;
    color: #ffffff;
">TAKE EXAM</a></span>
    											@elseif($pe->isupcom == 1)											
    											<span class="pull-left" style="margin-right:15px;">{{ date('F j, Y', strtotime($pe->publish_time)) }}</span>
    											@endif  
    										</div>
    										<?php
    										}
                                        }
                                        if(isset($oldExams[$ibevent->id])) {
                                            foreach($oldExams[$ibevent->id] as $oem){

                                            ?>

                                            <div class="exam_info"> 
                                              <!--  <span>{{ $oem->name }}</span> -->
                                                <span class="pull-left" style="padding-right: 10px;">{{ $oem->score }}</a></span>
                                            </div>

                                            <?php
                                            }
                                        }
										?>									
									</div>
								</div>
							</div>
							@endif
						@endforeach
                  

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